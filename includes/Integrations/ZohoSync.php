<?php
/**
 * Zoho CRM Integration Service
 * 
 * Handles OAuth2 authentication and data synchronization with Zoho CRM.
 * 
 * @package RFPlugin\Integrations
 * @since 1.0.0
 */

namespace RFPlugin\Integrations;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ZohoSync class
 * 
 * @since 1.0.0
 */
class ZohoSync
{
    /**
     * Zoho API Base URL
     * 
     * @var string
     */
    private string $apiBase = 'https://www.zohoapis.com/crm/v2';

    /**
     * Zoho Accounts URL (OAuth)
     * 
     * @var string
     */
    private string $accountsUrl = 'https://accounts.zoho.com/oauth/v2/token';

    /**
     * Create a new Lead or Inquiry in Zoho CRM
     * 
     * @param int $invoice_id The WP Post ID of the rf_invoice
     * @return bool|string True on success, error message on failure
     */
    public function syncInvoice(int $invoice_id)
    {
        $name = get_field('field_invoice_name', $invoice_id);
        $email = get_field('field_invoice_email', $invoice_id);
        $phone = get_field('field_invoice_phone', $invoice_id);
        $message = get_field('field_invoice_message', $invoice_id);
        $source = get_field('field_invoice_source_url', $invoice_id);
        $product_id = get_field('field_invoice_product', $invoice_id);
        $options = get_field('field_invoice_options', $invoice_id);

        $product_title = $product_id ? get_the_title($product_id) : 'N/A';
        $full_description = sprintf(
            "Product: %s\nOptions:\n%s\n---\nMessage:\n%s",
            $product_title,
            $options ?: 'None',
            $message
        );

        $data = [
            'data' => [
                [
                    'Last_Name' => $name,
                    'Email' => $email,
                    'Phone' => $phone,
                    'Description' => $full_description,
                    'Lead_Source' => 'Website Form',
                    'Website' => $source,
                ]
            ]
        ];

        $token = $this->getAccessToken();
        if (!$token) {
            return __('Failed to obtain Zoho access token.', 'rfplugin');
        }

        $response = wp_remote_post($this->apiBase . '/Leads', [
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken ' . $token,
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['data'][0]['code']) && $body['data'][0]['code'] === 'SUCCESS') {
            $zoho_id = $body['data'][0]['details']['id'];
            update_field('field_invoice_zoho_id', $zoho_id, $invoice_id);
            update_field('field_invoice_sync_status', 'synced', $invoice_id);
            return true;
        }

        $error = $body['data'][0]['message'] ?? __('Unknown Zoho API error.', 'rfplugin');
        update_field('field_invoice_sync_status', 'failed', $invoice_id);
        update_field('field_invoice_sync_error', $error, $invoice_id);
        
        return $error;
    }

    /**
     * Get OAuth2 Access Token
     * 
     * @return string|null
     */
    private function getAccessToken(): ?string
    {
        $client_id = get_option('rfplugin_zoho_client_id');
        $client_secret = get_option('rfplugin_zoho_client_secret');
        $refresh_token = get_option('rfplugin_zoho_refresh_token');

        if (!$client_id || !$client_secret || !$refresh_token) {
            return null;
        }

        $response = wp_remote_post($this->accountsUrl, [
            'body' => [
                'refresh_token' => $refresh_token,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'refresh_token',
            ],
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['access_token'] ?? null;
    }
}
