<?php
/**
 * FAQ REST Controller
 * 
 * Handles REST API endpoints for FAQs.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FAQ Controller class
 * 
 * @since 1.0.0
 */
class FAQController extends BaseController
{
    /**
     * Constructor
     * 
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);
        $this->restBase = 'faq';
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
     * Get FAQs collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $tax_query = [];
        $category = $request->get_param('category');
        $tag = $request->get_param('tag');

        if ($category) {
            $tax_query[] = [
                'taxonomy' => 'rf_faq_category',
                'field' => 'slug',
                'terms' => sanitize_text_field($category),
            ];
        }

        if ($tag) {
            $tax_query[] = [
                'taxonomy' => 'rf_faq_tag',
                'field' => 'slug',
                'terms' => sanitize_text_field($tag),
            ];
        }

        $args = [
            'post_type' => 'rf_faq',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => $tax_query,
        ];

        $search = $request->get_param('search');
        if ($search) {
            $args['s'] = sanitize_text_field($search);
        }

        $query = new \WP_Query($args);
        $faqs = [];

        foreach ($query->posts as $post) {
            $faqs[] = $this->prepareFAQData($post);
        }

        return $this->prepareCollectionResponse($faqs);
    }

    /**
     * Get single FAQ
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_faq') {
            return $this->prepareErrorResponse(__('FAQ not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareFAQData($post),
        ], 200);
    }

    /**
     * Prepare FAQ data for response
     * 
     * @param \WP_Post $post Post object
     * @return array<string, mixed>
     */
    private function prepareFAQData(\WP_Post $post): array
    {
        $data = [
            'id' => $post->ID,
            'question' => $post->post_title,
            'answer' => $post->post_content,
            'excerpt' => wp_trim_words($post->post_content, 30),
            'order' => $post->menu_order,
            'categories' => wp_get_post_terms($post->ID, 'rf_faq_category', ['fields' => 'names']),
            'tags' => wp_get_post_terms($post->ID, 'rf_faq_tag', ['fields' => 'names']),
            'permalink' => get_permalink($post->ID),
        ];

        return apply_filters('rfplugin_faq_rest_response', $data, $post);
    }
}
