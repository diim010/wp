<?php

/**
 * Product REST Controller
 *
 * RESTful API endpoints for Product post type with configuration and pricing.
 *
 * @package RFPlugin\API\Controllers
 * @since 2.0.0
 */

namespace RFPlugin\API\Controllers;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Controller class
 *
 * @since 2.0.0
 */
class ProductController extends BaseController
{
    /**
     * Post type
     *
     * @var string
     */
    protected $post_type = 'rf_product';

    /**
     * Register routes
     */
    public function register_routes()
    {
        // List products
        register_rest_route($this->namespace, '/products', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getProducts'],
                'permission_callback' => '__return_true', // Public endpoint
                'args' => $this->getCollectionParams(),
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'createProduct'],
                'permission_callback' => [$this, 'checkCreatePermission'],
                'args' => $this->getProductSchema(),
            ],
        ]);

        // Single product
        register_rest_route($this->namespace, '/products/(?P<id>[\d]+)', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getProduct'],
                'permission_callback' => '__return_true',
                'args' => [
                    'id' => [
                        'required' => true,
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                    ],
                ],
            ],
            [
                'methods' => 'PUT',
                'callback' => [$this, 'updateProduct'],
                'permission_callback' => [$this, 'checkUpdatePermission'],
                'args' => $this->getProductSchema(),
            ],
            [
                'methods' => 'DELETE',
                'callback' => [$this, 'deleteProduct'],
                'permission_callback' => [$this, 'checkDeletePermission'],
            ],
        ]);

        // Price calculation
        register_rest_route($this->namespace, '/products/(?P<id>[\d]+)/calculate', [
            'methods' => 'POST',
            'callback' => [$this, 'calculatePrice'],
            'permission_callback' => '__return_true',
            'args' => [
                'id' => [
                    'required' => true,
                    'type' => 'integer',
                ],
                'width' => [
                    'type' => 'number',
                ],
                'height' => [
                    'type' => 'number',
                ],
                'quantity' => [
                    'type' => 'integer',
                    'default' => 1,
                ],
                'material' => [
                    'type' => 'string',
                ],
                'color' => [
                    'type' => 'string',
                ],
                'finish' => [
                    'type' => 'string',
                ],
            ],
        ]);
    }

    /**
     * Get products list
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function getProducts(WP_REST_Request $request)
    {
        // Check rate limit
        $rate_check = $this->checkRateLimit($request);
        if (is_wp_error($rate_check)) {
            return $rate_check;
        }

        // Build cache key
        $cache_key = 'products_' . md5(serialize($request->get_params()));

        // Check cache
        $cached = $this->getCachedResponse($cache_key);
        if ($cached !== false) {
            return $this->prepareResponse($cached);
        }

        // Query parameters
        $args = [
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => $request->get_param('per_page') ?? 10,
            'paged' => $request->get_param('page') ?? 1,
            'orderby' => $request->get_param('orderby') ?? 'date',
            'order' => $request->get_param('order') ?? 'DESC',
        ];

        // Category filter
        if ($category = $request->get_param('category')) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'rf_product_category',
                    'field' => 'slug',
                    'terms' => $category,
                ],
            ];
        }

        // Material filter
        if ($material = $request->get_param('material')) {
            $args['tax_query'] = $args['tax_query'] ?? [];
            $args['tax_query'][] = [
                'taxonomy' => 'rf_product_material',
                'field' => 'slug',
                'terms' => $material,
            ];
        }

        // Price range filter
        if ($min_price = $request->get_param('min_price')) {
            $args['meta_query'] = $args['meta_query'] ?? [];
            $args['meta_query'][] = [
                'key' => 'product_base_price',
                'value' => floatval($min_price),
                'compare' => '>=',
                'type' => 'NUMERIC',
            ];
        }

        if ($max_price = $request->get_param('max_price')) {
            $args['meta_query'] = $args['meta_query'] ?? [];
            $args['meta_query'][] = [
                'key' => 'product_base_price',
                'value' => floatval($max_price),
                'compare' => '<=',
                'type' => 'NUMERIC',
            ];
        }

        // Search
        if ($search = $request->get_param('search')) {
            $args['s'] = sanitize_text_field($search);
        }

        $query = new WP_Query($args);

        $products = [];
        foreach ($query->posts as $post) {
            $products[] = $this->prepareProductData($post);
        }

        $response_data = [
            'products' => $products,
            'total' => $query->found_posts,
            'pages' => $query->max_num_pages,
            'page' => (int)$args['paged'],
        ];

        // Cache response
        $this->setCachedResponse($cache_key, $response_data);

        return $this->prepareResponse($response_data);
    }

    /**
     * Get single product
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function getProduct(WP_REST_Request $request)
    {
        $product_id = $request->get_param('id');
        $post = get_post($product_id);

        if (!$post || $post->post_type !== $this->post_type) {
            return new WP_Error(
                'rest_product_not_found',
                __('Product not found.', 'rfplugin'),
                ['status' => 404]
            );
        }

        $cache_key = "product_{$product_id}";
        $cached = $this->getCachedResponse($cache_key);

        if ($cached !== false) {
            return $this->prepareResponse($cached);
        }

        $data = $this->prepareProductData($post, true);
        $this->setCachedResponse($cache_key, $data);

        return $this->prepareResponse($data);
    }

    /**
     * Create product
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function createProduct(WP_REST_Request $request)
    {
        $auth_check = $this->checkAuthentication($request);
        if (is_wp_error($auth_check)) {
            return $auth_check;
        }

        $params = $this->validateParams($request, $this->getProductSchema());
        if (is_wp_error($params)) {
            return $params;
        }

        $post_data = [
            'post_type' => $this->post_type,
            'post_title' => $params['title'],
            'post_content' => $params['description'] ?? '',
            'post_status' => $params['status'] ?? 'draft',
        ];

        $product_id = wp_insert_post($post_data);

        if (is_wp_error($product_id)) {
            return $product_id;
        }

        // Update meta fields
        if (isset($params['sku'])) {
            update_post_meta($product_id, 'product_sku', $params['sku']);
        }
        if (isset($params['base_price'])) {
            update_post_meta($product_id, 'product_base_price', $params['base_price']);
        }

        return $this->prepareResponse([
            'id' => $product_id,
            'message' => __('Product created successfully.', 'rfplugin'),
        ], 201, false);
    }

    /**
     * Update product
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function updateProduct(WP_REST_Request $request)
    {
        $product_id = $request->get_param('id');
        $post = get_post($product_id);

        if (!$post || $post->post_type !== $this->post_type) {
            return new WP_Error('rest_product_not_found', __('Product not found.', 'rfplugin'), ['status' => 404]);
        }

        $params = $this->validateParams($request, $this->getProductSchema());
        if (is_wp_error($params)) {
            return $params;
        }

        // Update post
        $post_data = ['ID' => $product_id];
        if (isset($params['title'])) {
            $post_data['post_title'] = $params['title'];
        }
        if (isset($params['description'])) {
            $post_data['post_content'] = $params['description'];
        }

        wp_update_post($post_data);

        // Update meta
        if (isset($params['sku'])) {
            update_post_meta($product_id, 'product_sku', $params['sku']);
        }
        if (isset($params['base_price'])) {
            update_post_meta($product_id, 'product_base_price', $params['base_price']);
        }

        // Invalidate cache
        $this->invalidateCache("product_{$product_id}");

        return $this->prepareResponse([
            'id' => $product_id,
            'message' => __('Product updated successfully.', 'rfplugin'),
        ], 200, false);
    }

    /**
     * Delete product
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function deleteProduct(WP_REST_Request $request)
    {
        $product_id = $request->get_param('id');
        $post = get_post($product_id);

        if (!$post || $post->post_type !== $this->post_type) {
            return new WP_Error('rest_product_not_found', __('Product not found.', 'rfplugin'), ['status' => 404]);
        }

        $result = wp_delete_post($product_id, true);

        if (!$result) {
            return new WP_Error('rest_cannot_delete', __('Could not delete product.', 'rfplugin'), ['status' => 500]);
        }

        $this->invalidateCache("product_{$product_id}");

        return $this->prepareResponse([
            'deleted' => true,
            'message' => __('Product deleted successfully.', 'rfplugin'),
        ], 200, false);
    }

    /**
     * Calculate product price
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function calculatePrice(WP_REST_Request $request)
    {
        $product_id = $request->get_param('id');
        $post = get_post($product_id);

        if (!$post || $post->post_type !== $this->post_type) {
            return new WP_Error('rest_product_not_found', __('Product not found.', 'rfplugin'), ['status' => 404]);
        }

        $base_price = (float)get_post_meta($product_id, 'product_base_price', true);
        $calc_method = get_post_meta($product_id, 'product_calculation_method', true);
        $quantity = (int)$request->get_param('quantity') ?: 1;

        $price = $base_price;

        // Calculate based on method
        if ($calc_method === 'per_sqm') {
            $width = (float)$request->get_param('width') / 1000; // Convert mm to m
            $height = (float)$request->get_param('height') / 1000;
            $area = $width * $height;
            $price = $base_price * $area;
        } elseif ($calc_method === 'per_linear_m') {
            $width = (float)$request->get_param('width') / 1000;
            $price = $base_price * $width;
        }

        // Apply quantity
        $total = $price * $quantity;

        return $this->prepareResponse([
            'base_price' => $base_price,
            'unit_price' => round($price, 2),
            'quantity' => $quantity,
            'total' => round($total, 2),
            'currency' => 'USD',
            'calculation_method' => $calc_method,
        ], 200, false);
    }

    /**
     * Prepare product data for response
     *
     * @param \WP_Post $post
     * @param bool $full
     * @return array
     */
    protected function prepareProductData($post, bool $full = false): array
    {
        $data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'sku' => get_post_meta($post->ID, 'product_sku', true),
            'base_price' => (float)get_post_meta($post->ID, 'product_base_price', true),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
        ];

        if ($full) {
            $data['description'] = $post->post_content;
            $data['configurable'] = (bool)get_post_meta($post->ID, 'product_configurable', true);
            $data['calculation_method'] = get_post_meta($post->ID, 'product_calculation_method', true);
            $data['minimum_order'] = (int)get_post_meta($post->ID, 'product_minimum_order', true);

            // Categories
            $categories = wp_get_post_terms($post->ID, 'rf_product_category');
            $data['categories'] = array_map(function($term) {
                return ['id' => $term->term_id, 'name' => $term->name, 'slug' => $term->slug];
            }, $categories);

            // Materials
            $materials = wp_get_post_terms($post->ID, 'rf_product_material');
            $data['materials'] = array_map(function($term) {
                return ['id' => $term->term_id, 'name' => $term->name, 'slug' => $term->slug];
            }, $materials);
        }

        return $data;
    }

    /**
     * Get collection parameters
     */
    protected function getCollectionParams(): array
    {
        return [
            'page' => [
                'type' => 'integer',
                'default' => 1,
                'minimum' => 1,
            ],
            'per_page' => [
                'type' => 'integer',
                'default' => 10,
                'minimum' => 1,
                'maximum' => 100,
            ],
            'search' => [
                'type' => 'string',
            ],
            'category' => [
                'type' => 'string',
            ],
            'material' => [
                'type' => 'string',
            ],
            'min_price' => [
                'type' => 'number',
            ],
            'max_price' => [
                'type' => 'number',
            ],
            'orderby' => [
                'type' => 'string',
                'enum' => ['date', 'title', 'price'],
                'default' => 'date',
            ],
            'order' => [
                'type' => 'string',
                'enum' => ['ASC', 'DESC'],
                'default' => 'DESC',
            ],
        ];
    }

    /**
     * Get product schema
     */
    protected function getProductSchema(): array
    {
        return [
            'title' => [
                'type' => 'string',
                'required' => true,
            ],
            'description' => [
                'type' => 'string',
            ],
            'sku' => [
                'type' => 'string',
            ],
            'base_price' => [
                'type' => 'number',
            ],
            'status' => [
                'type' => 'string',
                'enum' => ['publish', 'draft', 'private'],
            ],
        ];
    }

    /**
     * Check create permission
     */
    public function checkCreatePermission(): bool
    {
        return current_user_can('edit_posts');
    }

    /**
     * Check update permission
     */
    public function checkUpdatePermission(WP_REST_Request $request): bool
    {
        $product_id = $request->get_param('id');
        return current_user_can('edit_post', $product_id);
    }

    /**
     * Check delete permission
     */
    public function checkDeletePermission(WP_REST_Request $request): bool
    {
        $product_id = $request->get_param('id');
        return current_user_can('delete_post', $product_id);
    }
}
