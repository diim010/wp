<?php
/**
 * Products REST Controller
 * 
 * Handles REST API endpoints for products with
 * specifications and constructor data.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

use RFPlugin\Services\ProductConstructor;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Products Controller class
 * 
 * @since 1.0.0
 */
class ProductsController extends BaseController
{
    /**
     * Constructor
     * 
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);
        $this->restBase = 'products';
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
                'permission_callback' => [$this, 'getItemsPermissionsCheck'],
            ],
        ]);

        register_rest_route($this->namespace, $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getItem'],
                'permission_callback' => [$this, 'getItemPermissionsCheck'],
                'args' => [
                    'id' => [
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        },
                    ],
                ],
            ],
        ]);

        register_rest_route($this->namespace, $this->restBase . '/(?P<id>[\d]+)/construct', [
            [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'constructProduct'],
                'permission_callback' => [$this, 'getItemPermissionsCheck'],
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
     * Get products collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    /**
     * Get products collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $request->get_param('per_page') ?? 10,
            'paged' => $request->get_param('page') ?? 1,
        ];

        $query = new \WP_Query($args);
        $products = [];

        foreach ($query->posts as $post) {
            $products[] = $this->prepareProductData($post);
        }

        return $this->prepareCollectionResponse($products);
    }

    /**
     * Get single product
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'product') {
            return $this->prepareErrorResponse(__('Product not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareProductData($post),
        ], 200);
    }

    /**
     * Construct product with specifications
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function constructProduct(\WP_REST_Request $request)
    {
        $productId = $request->get_param('id');
        $specifications = $request->get_json_params();

        $constructor = new ProductConstructor();
        $result = $constructor->construct($productId, $specifications);

        if (is_wp_error($result)) {
            return $result;
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $result,
        ], 200);
    }

    /**
     * Prepare product data for response
     * 
     * @param \WP_Post $post Post object
     * @return array<string, mixed>
     */
    private function prepareProductData(\WP_Post $post): array
    {
        $product = wc_get_product($post->ID);
        
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_content,
            'excerpt' => $post->post_excerpt,
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'large'),
            'sku' => $product ? $product->get_sku() : '',
            'price' => $product ? $product->get_price() : 0,
            'specifications' => [
                'density' => get_field('density', $post->ID),
                'color' => get_field('color', $post->ID),
            ],
            'materials' => wp_get_post_terms($post->ID, 'rf_material', ['fields' => 'names']),
            'product_types' => wp_get_post_terms($post->ID, 'rf_product_type', ['fields' => 'names']),
            'related_cases' => get_field('related_cases', $post->ID),
            'tech_files' => get_field('tech_files', $post->ID),
        ];
    }
}
