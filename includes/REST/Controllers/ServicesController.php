<?php

/**
 * Services REST API Controller
 *
 * Handles all REST endpoints for rf_service post type with
 * ACF fields, relationships, and filtering.
 *
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ServicesController class
 *
 * @since 1.0.0
 */
class ServicesController extends BaseController
{
    /**
     * Constructor
     *
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace = 'rf/v1')
    {
        parent::__construct($namespace);
        $this->restBase = 'services';
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        // GET /services - List all services
        register_rest_route($this->namespace, '/' . $this->restBase, [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'getItems'],
                'permission_callback' => [$this, 'getItemsPermissionsCheck'],
                'args' => $this->getCollectionParams(),
            ],
        ]);

        // POST /services - Create service
        register_rest_route($this->namespace, '/' . $this->restBase, [
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'createItem'],
                'permission_callback' => [$this, 'createItemPermissionsCheck'],
                'args' => $this->getItemSchema(),
            ],
        ]);

        // GET /services/{id} - Get single service
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'getItem'],
                'permission_callback' => [$this, 'getItemPermissionsCheck'],
                'args' => [
                    'id' => [
                        'validate_callback' => [$this, 'validateId'],
                        'required' => true,
                    ],
                ],
            ],
        ]);

        // PUT /services/{id} - Update service
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'updateItem'],
                'permission_callback' => [$this, 'updateItemPermissionsCheck'],
                'args' => $this->getItemSchema(),
            ],
        ]);

        // DELETE /services/{id} - Delete service
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => [$this, 'deleteItem'],
                'permission_callback' => [$this, 'deleteItemPermissionsCheck'],
            ],
        ]);

        // GET /services/{id}/case-studies - Related case studies
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)/case-studies', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'getRelatedCaseStudies'],
                'permission_callback' => [$this, 'getItemPermissionsCheck'],
            ],
        ]);

        // GET /services/{id}/products - Related products
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)/products', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'getRelatedProducts'],
                'permission_callback' => [$this, 'getItemPermissionsCheck'],
            ],
        ]);
    }

    /**
     * Get services collection
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function getItems(WP_REST_Request $request)
    {
        // Check cache
        $cacheKey = serialize($request->get_params());
        $cached = $this->getCachedResponse($cacheKey);

        if ($cached !== false) {
            return new WP_REST_Response($cached, 200);
        }

        $args = $this->buildQueryArgs($request, 'rf_service');

        // Category filter
        if ($category = $request->get_param('category')) {
            $args['tax_query'] = [[
                'taxonomy' => 'rf_service_category',
                'field' => 'slug',
                'terms' => sanitize_title($category),
            ]];
        }

        // Featured filter
        if ($request->get_param('featured') === '1' || $request->get_param('featured') === 'true') {
            $args['meta_query'] = [[
                'key' => 'featured_service',
                'value' => '1',
                'compare' => '=',
            ]];
        }

        // Visibility filter (only for authenticated users)
        if (!current_user_can('read_private_posts')) {
            $args['meta_query'][] = [[
                'key' => 'visibility',
                'value' => 'public',
                'compare' => '=',
            ]];
        }

        $query = new WP_Query($args);
        $services = [];

        foreach ($query->posts as $post) {
            $services[] = $this->prepareServiceData($post);
        }

        $response = $this->prepareCollectionResponse(
            $services,
            [
                'page' => $args['paged'],
                'per_page' => $args['posts_per_page'],
            ],
            $query->found_posts
        );

        // Cache response
        $this->setCachedResponse($cacheKey, $response->data);

        return $response;
    }

    /**
     * Get single service
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function getItem(WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_service' || $post->post_status !== 'publish') {
            return $this->prepareErrorResponse(
                __('Service not found', 'rfplugin'),
                404,
                'service_not_found'
            );
        }

        // Check visibility
        $visibility = get_field('visibility', $post->ID);
        if ($visibility !== 'public' && !current_user_can('read_private_posts')) {
            return $this->prepareErrorResponse(
                __('You do not have permission to view this service', 'rfplugin'),
                403,
                'service_forbidden'
            );
        }

        return $this->prepareSuccessResponse($this->prepareServiceData($post));
    }

    /**
     * Create service
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function createItem(WP_REST_Request $request)
    {
        $postData = [
            'post_type' => 'rf_service',
            'post_title' => sanitize_text_field($request->get_param('title')),
            'post_content' => wp_kses_post($request->get_param('content')),
            'post_excerpt' => sanitize_textarea_field($request->get_param('excerpt')),
            'post_status' => 'publish',
        ];

        $postId = wp_insert_post($postData, true);

        if (is_wp_error($postId)) {
            return $this->prepareErrorResponse(
                $postId->get_error_message(),
                500,
                'create_failed'
            );
        }

        // Update ACF fields
        $this->updateServiceFields($postId, $request);

        // Clear cache
        $this->deleteCachedResponse('*');

        return $this->prepareSuccessResponse(
            $this->prepareServiceData(get_post($postId)),
            201,
            __('Service created successfully', 'rfplugin')
        );
    }

    /**
     * Update service
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function updateItem(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        $post = get_post($postId);

        if (!$post || $post->post_type !== 'rf_service') {
            return $this->prepareErrorResponse(
                __('Service not found', 'rfplugin'),
                404,
                'service_not_found'
            );
        }

        $postData = [
            'ID' => $postId,
        ];

        if ($request->has_param('title')) {
            $postData['post_title'] = sanitize_text_field($request->get_param('title'));
        }

        if ($request->has_param('content')) {
            $postData['post_content'] = wp_kses_post($request->get_param('content'));
        }

        if ($request->has_param('excerpt')) {
            $postData['post_excerpt'] = sanitize_textarea_field($request->get_param('excerpt'));
        }

        $updated = wp_update_post($postData, true);

        if (is_wp_error($updated)) {
            return $this->prepareErrorResponse(
                $updated->get_error_message(),
                500,
                'update_failed'
            );
        }

        // Update ACF fields
        $this->updateServiceFields($postId, $request);

        // Clear cache
        $this->deleteCachedResponse('*');

        return $this->prepareSuccessResponse(
            $this->prepareServiceData(get_post($postId)),
            200,
            __('Service updated successfully', 'rfplugin')
        );
    }

    /**
     * Delete service
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function deleteItem(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        $post = get_post($postId);

        if (!$post || $post->post_type !== 'rf_service') {
            return $this->prepareErrorResponse(
                __('Service not found', 'rfplugin'),
                404,
                'service_not_found'
            );
        }

        $deleted = wp_delete_post($postId, true);

        if (!$deleted) {
            return $this->prepareErrorResponse(
                __('Failed to delete service', 'rfplugin'),
                500,
                'delete_failed'
            );
        }

        // Clear cache
        $this->deleteCachedResponse('*');

        return $this->prepareSuccessResponse(
            ['deleted' => true, 'id' => $postId],
            200,
            __('Service deleted successfully', 'rfplugin')
        );
    }

    /**
     * Get related case studies
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function getRelatedCaseStudies(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        $relatedCases = get_field('related_case_studies', $postId);

        if (empty($relatedCases)) {
            return $this->prepareSuccessResponse([]);
        }

        $caseStudies = [];
        foreach ($relatedCases as $caseId) {
            $post = get_post($caseId);
            if ($post && $post->post_status === 'publish') {
                $caseStudies[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'excerpt' => get_the_excerpt($post),
                    'client' => get_field('client_name', $post->ID),
                    'industry' => wp_get_post_terms($post->ID, 'rf_case_industry', ['fields' => 'names']),
                ];
            }
        }

        return $this->prepareSuccessResponse($caseStudies);
    }

    /**
     * Get related products
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function getRelatedProducts(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        $relatedProducts = get_field('related_products', $postId);

        if (empty($relatedProducts)) {
            return $this->prepareSuccessResponse([]);
        }

        $products = [];
        foreach ($relatedProducts as $productId) {
            $post = get_post($productId);
            if ($post && $post->post_status === 'publish') {
                $product = wc_get_product($productId);
                $products[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'price' => $product ? $product->get_price() : null,
                    'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
                ];
            }
        }

        return $this->prepareSuccessResponse($products);
    }

    /**
     * Prepare service data for response
     *
     * @param \WP_Post $post Post object
     * @return array
     */
    private function prepareServiceData(\WP_Post $post): array
    {
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => apply_filters('the_content', $post->post_content),
            'excerpt' => get_the_excerpt($post),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'large'),
            'duration' => get_field('duration', $post->ID),
            'pricing' => [
                'model' => get_field('pricing_model', $post->ID),
                'base_price' => get_field('base_price', $post->ID),
                'note' => get_field('pricing_note', $post->ID),
            ],
            'visibility' => get_field('visibility', $post->ID) ?: 'public',
            'featured' => (bool) get_field('featured_service', $post->ID),
            'categories' => wp_get_post_terms($post->ID, 'rf_service_category', ['fields' => 'names']),
            'related_products_count' => count(get_field('related_products', $post->ID) ?: []),
            'related_cases_count' => count(get_field('related_case_studies', $post->ID) ?: []),
            'permalink' => get_permalink($post->ID),
            'date' => $post->post_date,
            'modified' => $post->post_modified,
        ];
    }

    /**
     * Update service ACF fields
     *
     * @param int $postId Post ID
     * @param WP_REST_Request $request Request object
     * @return void
     */
    private function updateServiceFields(int $postId, WP_REST_Request $request): void
    {
        if ($request->has_param('duration')) {
            update_field('duration', sanitize_text_field($request->get_param('duration')), $postId);
        }

        if ($request->has_param('pricing_model')) {
            update_field('pricing_model', sanitize_text_field($request->get_param('pricing_model')), $postId);
        }

        if ($request->has_param('base_price')) {
            update_field('base_price', floatval($request->get_param('base_price')), $postId);
        }

        if ($request->has_param('pricing_note')) {
            update_field('pricing_note', sanitize_textarea_field($request->get_param('pricing_note')), $postId);
        }

        if ($request->has_param('visibility')) {
            update_field('visibility', sanitize_text_field($request->get_param('visibility')), $postId);
        }

        if ($request->has_param('featured')) {
            update_field('featured_service', (bool) $request->get_param('featured'), $postId);
        }

        if ($request->has_param('related_products')) {
            update_field('related_products', array_map('intval', (array) $request->get_param('related_products')), $postId);
        }

        if ($request->has_param('related_case_studies')) {
            update_field('related_case_studies', array_map('intval', (array) $request->get_param('related_case_studies')), $postId);
        }
    }

    /**
     * Get collection params schema
     *
     * @return array
     */
    private function getCollectionParams(): array
    {
        return [
            'page' => [
                'description' => 'Current page',
                'type' => 'integer',
                'default' => 1,
                'minimum' => 1,
            ],
            'per_page' => [
                'description' => 'Items per page',
                'type' => 'integer',
                'default' => 20,
                'minimum' => 1,
                'maximum' => 100,
            ],
            'search' => [
                'description' => 'Search term',
                'type' => 'string',
            ],
            'category' => [
                'description' => 'Filter by category slug',
                'type' => 'string',
            ],
            'featured' => [
                'description' => 'Filter featured services',
                'type' => 'boolean',
            ],
            'orderby' => [
                'description' => 'Order by field',
                'type' => 'string',
                'default' => 'date',
                'enum' => ['date', 'title', 'modified'],
            ],
            'order' => [
                'description' => 'Order direction',
                'type' => 'string',
                'default' => 'DESC',
                'enum' => ['ASC', 'DESC'],
            ],
        ];
    }

    /**
     * Get item schema
     *
     * @return array
     */
    private function getItemSchema(): array
    {
        return [
            'title' => [
                'description' => 'Service title',
                'type' => 'string',
                'required' => true,
            ],
            'content' => [
                'description' => 'Service description',
                'type' => 'string',
            ],
            'excerpt' => [
                'description' => 'Service excerpt',
                'type' => 'string',
            ],
            'duration' => [
                'description' => 'Service duration',
                'type' => 'string',
            ],
            'pricing_model' => [
                'description' => 'Pricing model',
                'type' => 'string',
                'enum' => ['fixed', 'hourly', 'monthly', 'contact'],
            ],
            'base_price' => [
                'description' => 'Base price',
                'type' => 'number',
            ],
            'visibility' => [
                'description' => 'Visibility level',
                'type' => 'string',
                'enum' => ['public', 'customer', 'partner'],
                'default' => 'public',
            ],
            'featured' => [
                'description' => 'Featured service',
                'type' => 'boolean',
                'default' => false,
            ],
        ];
    }
}
