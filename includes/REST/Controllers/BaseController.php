<?php
/**
 * Abstract Base REST Controller
 * 
 * Provides common functionality for all REST API controllers.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Controller class
 * 
 * @since 1.0.0
 */
abstract class BaseController
{
    /**
     * API namespace
     * 
     * @var string
     */
    protected string $namespace;

    /**
     * REST base route
     * 
     * @var string
     */
    protected string $restBase;

    /**
     * Constructor
     * 
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Register routes for this controller
     * 
     * @return void
     */
    abstract public function registerRoutes(): void;

    /**
     * Get collection permission callback
     * 
     * @return bool
     */
    public function getItemsPermissionsCheck(): bool
    {
        return true;
    }

    /**
     * Get single item permission callback
     * 
     * @param \WP_REST_Request $request Request object
     * @return bool
     */
    public function getItemPermissionsCheck(\WP_REST_Request $request): bool
    {
        return \RFPlugin\Security\Permissions::canViewPost((int)$request->get_param('id'));
    }

    /**
     * Create item permission callback
     * 
     * @return bool
     */
    public function createItemPermissionsCheck(): bool
    {
        return is_user_logged_in();
    }

    /**
     * Update item permission callback
     * 
     * @return bool
     */
    public function updateItemPermissionsCheck(): bool
    {
        return current_user_can('edit_posts');
    }

    /**
     * Delete item permission callback
     * 
     * @return bool
     */
    public function deleteItemPermissionsCheck(): bool
    {
        return current_user_can('delete_posts');
    }

    /**
     * Prepare response for collection
     * 
     * @param array<mixed> $items Items to prepare
     * @return \WP_REST_Response
     */
    protected function prepareCollectionResponse(array $items): \WP_REST_Response
    {
        return new \WP_REST_Response([
            'success' => true,
            'data' => $items,
            'count' => count($items),
        ], 200);
    }

    /**
     * Prepare error response
     * 
     * @param string $message Error message
     * @param int $code HTTP status code
     * @return \WP_Error
     */
    protected function prepareErrorResponse(string $message, int $code = 400): \WP_Error
    {
        return new \WP_Error('rfplugin_error', $message, ['status' => $code]);
    }
}
