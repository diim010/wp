<?php
/**
 * Invoices REST Controller
 * 
 * Handles invoice creation and management via REST API.
 * Requires authentication for invoice creation.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

use RFPlugin\Services\InvoiceManager;
use RFPlugin\Security\Permissions;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Invoices Controller class
 * 
 * @since 1.0.0
 */
class InvoicesController extends BaseController
{
    /**
     * Constructor
     * 
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);
        $this->restBase = 'invoices';
    }

    /**
     * Register routes
     * 
     * @return void
     */
    public function registerRoutes(): void
    {
        register_rest_route($this->namespace, $this->restBase, [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getItems'],
                'permission_callback' => [$this, 'getUserInvoicesPermissionsCheck'],
            ],
            [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'createItem'],
                'permission_callback' => [$this, 'createInvoicePermissionsCheck'],
            ],
        ]);

        register_rest_route($this->namespace, $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getItem'],
                'permission_callback' => [$this, 'getInvoicePermissionsCheck'],
                'args' => [
                    'id' => [
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        },
                    ],
                ],
            ],
        ]);
    }

    /**
     * Permission check for creating invoices
     * 
     * @return bool
     */
    public function createInvoicePermissionsCheck(): bool
    {
        return Permissions::canCreateInvoice();
    }

    /**
     * Permission check for viewing user's invoices
     * 
     * @return bool
     */
    public function getUserInvoicesPermissionsCheck(): bool
    {
        return is_user_logged_in() || current_user_can('view_rfplugin_invoices');
    }

    /**
     * Permission check for viewing single invoice
     * 
     * @param \WP_REST_Request $request Request object
     * @return bool
     */
    public function getInvoicePermissionsCheck(\WP_REST_Request $request): bool
    {
        return Permissions::canViewInvoice(0, $request->get_param('id'));
    }

    /**
     * Get invoices collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $args = [
            'post_type' => 'rf_invoice',
            'post_status' => 'publish',
            'posts_per_page' => $request->get_param('per_page') ?? 10,
            'paged' => $request->get_param('page') ?? 1,
        ];

        if (!current_user_can('view_rfplugin_invoices')) {
            $args['author'] = get_current_user_id();
        }

        $query = new \WP_Query($args);
        $invoices = [];

        foreach ($query->posts as $post) {
            $invoices[] = $this->prepareInvoiceData($post);
        }

        return $this->prepareCollectionResponse($invoices);
    }

    /**
     * Get single invoice
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_invoice') {
            return $this->prepareErrorResponse(__('Invoice not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareInvoiceData($post),
        ], 200);
    }

    /**
     * Create new invoice
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function createItem(\WP_REST_Request $request)
    {
        $invoiceData = $request->get_json_params();
        
        $sanitized = Permissions::sanitizeInvoiceData($invoiceData);

        $manager = new InvoiceManager();
        $result = $manager->createInvoice($sanitized);

        if (is_wp_error($result)) {
            return $result;
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $result,
            'message' => __('Invoice created successfully', 'rfplugin'),
        ], 201);
    }

    /**
     * Prepare invoice data for response
     * 
     * @param \WP_Post $post Post object
     * @return array<string, mixed>
     */
    private function prepareInvoiceData(\WP_Post $post): array
    {
        $invoiceData = get_post_meta($post->ID, '_invoice_data', true);
        
        return [
            'id' => $post->ID,
            'invoice_number' => get_post_meta($post->ID, '_invoice_number', true),
            'created_at' => $post->post_date,
            'status' => $post->post_status,
            'data' => $invoiceData ? json_decode($invoiceData, true) : [],
        ];
    }
}
