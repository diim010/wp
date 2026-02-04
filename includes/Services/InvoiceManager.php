<?php
/**
 * Invoice Manager Service
 * 
 * Manages invoice creation, storage as JSON, and retrieval.
 * Prepared for future PDF and ERP integration.
 * 
 * @package RFPlugin\Services
 * @since 1.0.0
 */

namespace RFPlugin\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * InvoiceManager class
 * 
 * @since 1.0.0
 */
class InvoiceManager
{
    /**
     * Invoice storage directory
     * 
     * @var string
     */
    private string $invoiceDir;

    /**
     * Constructor
     */
    public function __construct()
    {
        $uploadDir = wp_upload_dir();
        $this->invoiceDir = $uploadDir['basedir'] . '/rfplugin-invoices';
    }

    /**
     * Create new invoice
     * 
     * @param array<string, mixed> $data Invoice data
     * @return array<string, mixed>|\WP_Error
     */
    public function createInvoice(array $data)
    {
        $validated = $this->validateInvoiceData($data);
        
        if (is_wp_error($validated)) {
            return $validated;
        }

        $invoiceNumber = $this->generateInvoiceNumber();
        
        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'created_at' => current_time('mysql'),
            'customer' => [
                'name' => $data['customer_name'] ?? '',
                'email' => $data['customer_email'] ?? '',
                'phone' => $data['customer_phone'] ?? '',
            ],
            'products' => $data['products'] ?? [],
            'services' => $data['services'] ?? [],
            'notes' => $data['notes'] ?? '',
            'status' => 'pending',
            'totals' => $this->calculateTotals($data),
        ];

        $postId = wp_insert_post([
            'post_type' => 'rf_invoice',
            'post_title' => sprintf(__('Invoice #%s', 'rfplugin'), $invoiceNumber),
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ]);

        if (is_wp_error($postId)) {
            return $postId;
        }

        update_post_meta($postId, '_invoice_number', $invoiceNumber);
        update_post_meta($postId, '_invoice_data', wp_json_encode($invoiceData));

        // Populate descriptive ACF fields for Admin UI and Sync
        update_field('field_invoice_name', $data['customer_name'] ?? '', $postId);
        update_field('field_invoice_email', $data['customer_email'] ?? '', $postId);
        update_field('field_invoice_phone', $data['customer_phone'] ?? '', $postId);
        update_field('field_invoice_message', $data['notes'] ?? '', $postId);
        update_field('field_invoice_form_id', 'react_creator', $postId);

        // Link first product if available
        if (!empty($data['products']) && isset($data['products'][0]['id'])) {
            $firstProduct = $data['products'][0];
            update_field('field_invoice_product', $firstProduct['id'], $postId);
            
            // Format specifications for the options textarea
            if (!empty($firstProduct['specifications'])) {
                $optionsStr = '';
                foreach ($firstProduct['specifications'] as $key => $val) {
                    $optionsStr .= ucfirst($key) . ': ' . $val . "\n";
                }
                update_field('field_invoice_options', trim($optionsStr), $postId);
            }
        }

        $this->saveInvoiceJSON($invoiceNumber, $invoiceData);

        // Trigger Zoho Sync
        $zoho = new \RFPlugin\Integrations\ZohoSync();
        $zoho->syncInvoice($postId);

        do_action('rfplugin_invoice_created', $postId, $invoiceData);

        return [
            'id' => $postId,
            'invoice_number' => $invoiceNumber,
            'data' => $invoiceData,
        ];
    }

    /**
     * Validate invoice data
     * 
     * @param array<string, mixed> $data Invoice data
     * @return true|\WP_Error
     */
    private function validateInvoiceData(array $data)
    {
        if (empty($data['customer_name'])) {
            return new \WP_Error(
                'missing_customer_name',
                __('Customer name is required', 'rfplugin'),
                ['status' => 400]
            );
        }

        if (empty($data['customer_email']) || !is_email($data['customer_email'])) {
            return new \WP_Error(
                'invalid_email',
                __('Valid customer email is required', 'rfplugin'),
                ['status' => 400]
            );
        }

        if (empty($data['products']) || !is_array($data['products'])) {
            return new \WP_Error(
                'missing_products',
                __('At least one product is required', 'rfplugin'),
                ['status' => 400]
            );
        }

        return true;
    }

    /**
     * Generate unique invoice number
     * 
     * @return string
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = get_option('rfplugin_invoice_prefix', 'RF');
        $timestamp = current_time('timestamp');
        $random = wp_generate_password(6, false, false);
        
        return strtoupper($prefix . '-' . date('Ymd', $timestamp) . '-' . $random);
    }

    /**
     * Calculate invoice totals
     * 
     * @param array<string, mixed> $data Invoice data
     * @return array<string, mixed>
     */
    private function calculateTotals(array $data): array
    {
        $subtotal = 0;
        $productsTotal = 0;
        $servicesTotal = 0;

        if (!empty($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $product) {
                $price = floatval(get_field('product_price', $product['id'] ?? 0));
                $quantity = intval($product['quantity'] ?? 1);
                $productsTotal += $price * $quantity;
            }
        }

        if (!empty($data['services']) && is_array($data['services'])) {
            foreach ($data['services'] as $serviceId) {
                $servicesTotal += floatval(get_field('service_price', $serviceId));
            }
        }

        $subtotal = $productsTotal + $servicesTotal;
        $tax = $subtotal * 0.20;
        $total = $subtotal + $tax;

        return [
            'products_total' => round($productsTotal, 2),
            'services_total' => round($servicesTotal, 2),
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'tax_rate' => 0.20,
            'total' => round($total, 2),
            'currency' => 'EUR',
        ];
    }

    /**
     * Save invoice data as JSON file
     * 
     * @param string $invoiceNumber Invoice number
     * @param array<string, mixed> $data Invoice data
     * @return bool
     */
    private function saveInvoiceJSON(string $invoiceNumber, array $data): bool
    {
        if (!file_exists($this->invoiceDir)) {
            wp_mkdir_p($this->invoiceDir);
        }

        $filename = $this->invoiceDir . '/' . $invoiceNumber . '.json';
        $json = wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return (bool) file_put_contents($filename, $json);
    }

    /**
     * Get invoice by number
     * 
     * @param string $invoiceNumber Invoice number
     * @return array<string, mixed>|null
     */
    public function getInvoiceByNumber(string $invoiceNumber): ?array
    {
        $filename = $this->invoiceDir . '/' . $invoiceNumber . '.json';
        
        if (!file_exists($filename)) {
            return null;
        }

        $json = file_get_contents($filename);
        return json_decode($json, true);
    }

    /**
     * Export invoice to PDF (placeholder for future implementation)
     * 
     * @param int $invoiceId Invoice post ID
     * @return string|\WP_Error PDF file path or error
     */
    public function exportToPDF(int $invoiceId)
    {
        return new \WP_Error(
            'not_implemented',
            __('PDF export is not yet implemented', 'rfplugin'),
            ['status' => 501]
        );
    }

    /**
     * Send invoice to ERP system (placeholder for future implementation)
     * 
     * @param int $invoiceId Invoice post ID
     * @return bool|\WP_Error Success or error
     */
    public function sendToERP(int $invoiceId)
    {
        return new \WP_Error(
            'not_implemented',
            __('ERP integration is not yet implemented', 'rfplugin'),
            ['status' => 501]
        );
    }
}
