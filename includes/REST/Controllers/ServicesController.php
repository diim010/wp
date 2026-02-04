<?php
/**
 * Services REST Controller
 * 
 * Handles REST API endpoints for services.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Services Controller class
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
    public function __construct(string $namespace)
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
            ],
        ]);
    }

    /**
     * Get services collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $args = [
            'post_type' => 'rf_service',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ];

        $query = new \WP_Query($args);
        $services = [];

        foreach ($query->posts as $post) {
            $services[] = $this->prepareServiceData($post);
        }

        return $this->prepareCollectionResponse($services);
    }

    /**
     * Get single service
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_service') {
            return $this->prepareErrorResponse(__('Service not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareServiceData($post),
        ], 200);
    }

    /**
     * Prepare service data for response
     * 
     * @param \WP_Post $post Post object
     * @return array<string, mixed>
     */
    private function prepareServiceData(\WP_Post $post): array
    {
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_content,
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
            'price' => get_field('service_price', $post->ID),
        ];
    }
}
