<?php
/**
 * Forms REST Controller
 * 
 * Handles form submissions for quotes and inquiries.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use RFPlugin\Integrations\ZohoSync;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FormsController class
 * 
 * @since 1.0.0
 */
class FormsController
{
    /**
     * API namespace
     * 
     * @var string
     */
    private string $namespace;

    /**
     * Constructor
     * 
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Register REST routes
     * 
     * @return void
     */
    public function registerRoutes(): void
    {
        register_rest_route($this->namespace, 'forms/submit', [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'handleSubmit'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Handle form submission
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handleSubmit(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_params();
        
        // Basic validation
        if (empty($params['customer_name']) || empty($params['customer_email'])) {
            return new WP_REST_Response(['message' => __('Missing required fields.', 'rfplugin')], 400);
        }

        // Create rf_invoice post
        $post_id = wp_insert_post([
            'post_type' => 'rf_invoice',
            'post_status' => 'publish',
            'post_title' => sprintf(__('Inquiry from %s', 'rfplugin'), sanitize_text_field($params['customer_name'])),
        ]);

        if (is_wp_error($post_id)) {
            return new WP_REST_Response(['message' => $post_id->get_error_message()], 500);
        }

        // Update ACF fields
        update_field('field_invoice_name', sanitize_text_field($params['customer_name']), $post_id);
        update_field('field_invoice_email', sanitize_email($params['customer_email']), $post_id);
        update_field('field_invoice_phone', sanitize_text_field($params['customer_phone'] ?? ''), $post_id);
        update_field('field_invoice_message', sanitize_textarea_field($params['form_message'] ?? ''), $post_id);
        update_field('field_invoice_source_url', esc_url_raw($params['source_url'] ?? ''), $post_id);
        update_field('field_invoice_form_id', sanitize_text_field($params['form_id'] ?? 'default'), $post_id);
        update_field('field_invoice_product', absint($params['product_id'] ?? 0), $post_id);
        update_field('field_invoice_options', sanitize_textarea_field($params['product_options'] ?? ''), $post_id);

        // Trigger Zoho Sync
        $zoho = new ZohoSync();
        $sync_result = $zoho->syncInvoice($post_id);

        return new WP_REST_Response([
            'message' => __('Form submitted successfully.', 'rfplugin'),
            'id' => $post_id,
            'sync' => $sync_result === true ? 'success' : 'failed',
            'sync_error' => $sync_result !== true ? $sync_result : null,
        ], 200);
    }
}
