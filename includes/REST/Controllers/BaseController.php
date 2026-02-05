<?php

/**
 * Base REST API Controller
 *
 * Provides common functionality for all REST controllers including
 * permissions, response formatting, caching, and error handling.
 *
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * BaseController class
 *
 * @since 1.0.0
 */
abstract class BaseController extends WP_REST_Controller
{
    /**
     * API namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * REST base route
     *
     * @var string
     */
    protected $restBase;

    /**
     * Cache TTL in seconds
     *
     * @var int
     */
    protected $cacheTTL = 3600; // 1 hour

    /**
     * Constructor
     *
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace = 'rf/v1')
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
     * Check if user can read items
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function getItemsPermissionsCheck(WP_REST_Request $request)
    {
        return true; // Public read access by default
    }

    /**
     * Check if user can read a single item
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function getItemPermissionsCheck(WP_REST_Request $request)
    {
        return true; // Public read access by default
    }

    /**
     * Check if user can create items
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function createItemPermissionsCheck(WP_REST_Request $request)
    {
        return current_user_can('edit_posts');
    }

    /**
     * Check if user can update items
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function updateItemPermissionsCheck(WP_REST_Request $request)
    {
        return current_user_can('edit_posts');
    }

    /**
     * Check if user can delete items
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function deleteItemPermissionsCheck(WP_REST_Request $request)
    {
        return current_user_can('delete_posts');
    }

    /**
     * Prepare a collection response with pagination
     *
     * @param array $items Array of items
     * @param array $args Query arguments
     * @param int $total Total number of items
     * @return WP_REST_Response
     */
    protected function prepareCollectionResponse(array $items, array $args = [], int $total = 0): WP_REST_Response
    {
        $page = $args['page'] ?? 1;
        $perPage = $args['per_page'] ?? 20;
        $totalPages = $total > 0 ? ceil($total / $perPage) : 1;

        $response = new WP_REST_Response([
            'success' => true,
            'data' => $items,
            'pagination' => [
                'total' => $total,
                'count' => count($items),
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => $totalPages,
            ],
        ], 200);

        // Add caching headers
        $response->header('X-WP-Total', $total);
        $response->header('X-WP-TotalPages', $totalPages);
        $response->header('Cache-Control', 'public, max-age=' . $this->cacheTTL);
        $response->header('ETag', md5(serialize($items)));

        return $response;
    }

    /**
     * Prepare success response
     *
     * @param mixed $data Response data
     * @param int $status HTTP status code
     * @param string $message Optional message
     * @return WP_REST_Response
     */
    protected function prepareSuccessResponse($data, int $status = 200, string $message = ''): WP_REST_Response
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return new WP_REST_Response($response, $status);
    }

    /**
     * Prepare error response
     *
     * @param string $message Error message
     * @param int $status HTTP status code
     * @param string $code Error code
     * @param array $data Additional error data
     * @return WP_Error
     */
    protected function prepareErrorResponse(
        string $message,
        int $status = 400,
        string $code = 'rest_error',
        array $data = []
    ): WP_Error {
        $data['status'] = $status;
        return new WP_Error($code, $message, $data);
    }

    /**
     * Sanitize search query
     *
     * @param string $search Search string
     * @return string
     */
    protected function sanitizeSearch(string $search): string
    {
        return sanitize_text_field(trim($search));
    }

    /**
     * Parse filter parameters
     *
     * @param WP_REST_Request $request Request object
     * @param array $allowed Allowed filter keys
     * @return array
     */
    protected function parseFilters(WP_REST_Request $request, array $allowed = []): array
    {
        $filters = [];

        foreach ($allowed as $key) {
            $value = $request->get_param($key);
            if ($value !== null) {
                $filters[$key] = sanitize_text_field($value);
            }
        }

        return $filters;
    }

    /**
     * Get cached response
     *
     * @param string $key Cache key
     * @return mixed|false
     */
    protected function getCachedResponse(string $key)
    {
        return get_transient($this->getCacheKey($key));
    }

    /**
     * Set cached response
     *
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param int $ttl Time to live (optional)
     * @return bool
     */
    protected function setCachedResponse(string $key, $data, int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->cacheTTL;
        return set_transient($this->getCacheKey($key), $data, $ttl);
    }

    /**
     * Delete cached response
     *
     * @param string $key Cache key
     * @return bool
     */
    protected function deleteCachedResponse(string $key): bool
    {
        return delete_transient($this->getCacheKey($key));
    }

    /**
     * Get full cache key with namespace
     *
     * @param string $key Cache key
     * @return string
     */
    protected function getCacheKey(string $key): string
    {
        return 'rf_api_' . $this->restBase . '_' . md5($key);
    }

    /**
     * Build query args from request
     *
     * @param WP_REST_Request $request Request object
     * @param string $postType Post type
     * @return array
     */
    protected function buildQueryArgs(WP_REST_Request $request, string $postType): array
    {
        $args = [
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => min((int) ($request->get_param('per_page') ?? 20), 100),
            'paged' => (int) ($request->get_param('page') ?? 1),
            'orderby' => $request->get_param('orderby') ?? 'date',
            'order' => strtoupper($request->get_param('order') ?? 'DESC'),
        ];

        // Search
        if ($search = $request->get_param('search')) {
            $args['s'] = $this->sanitizeSearch($search);
        }

        // Status filter
        if ($status = $request->get_param('status')) {
            $args['post_status'] = sanitize_key($status);
        }

        // Date filters
        if ($after = $request->get_param('after')) {
            $args['date_query'][] = [
                'after' => sanitize_text_field($after),
                'inclusive' => true,
            ];
        }

        if ($before = $request->get_param('before')) {
            $args['date_query'][] = [
                'before' => sanitize_text_field($before),
                'inclusive' => true,
            ];
        }

        return $args;
    }

    /**
     * Validate ID parameter
     *
     * @param mixed $param Parameter value
     * @return bool
     */
    protected function validateId($param): bool
    {
        return is_numeric($param) && $param > 0;
    }

    /**
     * Check if request has conditional header match
     *
     * @param WP_REST_Request $request Request object
     * @param string $etag ETag to check
     * @return bool
     */
    protected function hasConditionalMatch(WP_REST_Request $request, string $etag): bool
    {
        $ifNoneMatch = $request->get_header('if_none_match');
        return $ifNoneMatch === $etag;
    }

    /**
     * Log API error for debugging
     *
     * @param string $message Error message
     * @param array $context Additional context
     * @return void
     */
    protected function logError(string $message, array $context = []): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '[RF API Error] %s %s',
                $message,
                !empty($context) ? json_encode($context) : ''
            ));
        }
    }
}
