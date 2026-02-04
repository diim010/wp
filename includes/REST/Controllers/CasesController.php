<?php
/**
 * Cases REST Controller
 * 
 * Handles REST API endpoints for case studies.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cases Controller class
 * 
 * @since 1.0.0
 */
class CasesController extends BaseController
{
    /**
     * Constructor
     * 
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);
        $this->restBase = 'cases';
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
     * Get cases collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $args = [
            'post_type' => 'rf_case',
            'post_status' => 'publish',
            'posts_per_page' => $request->get_param('per_page') ?? 10,
            'paged' => $request->get_param('page') ?? 1,
        ];

        $industry = $request->get_param('industry');
        if ($industry) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'rf_case_industry',
                    'field' => 'slug',
                    'terms' => $industry,
                ],
            ];
        }

        $query = new \WP_Query($args);
        $cases = [];

        foreach ($query->posts as $post) {
            $cases[] = $this->prepareCaseData($post);
        }

        return $this->prepareCollectionResponse($cases);
    }

    /**
     * Get single case
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_case') {
            return $this->prepareErrorResponse(__('Case not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareCaseData($post),
        ], 200);
    }

    /**
     * Prepare case data for response
     * 
     * @param \WP_Post $post Post object
     * @return array<string, mixed>
     */
    private function prepareCaseData(\WP_Post $post): array
    {
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_content,
            'excerpt' => $post->post_excerpt,
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'large'),
            'industries' => wp_get_post_terms($post->ID, 'rf_case_industry', ['fields' => 'names']),
            'gallery' => get_field('case_gallery', $post->ID),
        ];
    }
}
