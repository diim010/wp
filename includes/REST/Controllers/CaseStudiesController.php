<?php

/**
 * Case Studies REST API Controller
 *
 * Handles all REST endpoints for rf_case_study post type with
 * ACF fields, client data, results, and industry filtering.
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
 * CaseStudiesController class
 *
 * @since 1.0.0
 */
class CaseStudiesController extends BaseController
{
    /**
     * Constructor
     *
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace = 'rf/v1')
    {
        parent::__construct($namespace);
        $this->restBase = 'case-studies';
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        // GET /case-studies - List all case studies
        register_rest_route($this->namespace, '/' . $this->restBase, [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'getItems'],
                'permission_callback' => [$this, 'getItemsPermissionsCheck'],
                'args' => $this->getCollectionParams(),
            ],
        ]);

        // POST /case-studies - Create case study
        register_rest_route($this->namespace, '/' . $this->restBase, [
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'createItem'],
                'permission_callback' => [$this, 'createItemPermissionsCheck'],
            ],
        ]);

        // GET /case-studies/{id} - Get single case study
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

        // PUT /case-studies/{id} - Update case study
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'updateItem'],
                'permission_callback' => [$this, 'updateItemPermissionsCheck'],
            ],
        ]);

        // DELETE /case-studies/{id} - Delete case study
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => [$this, 'deleteItem'],
                'permission_callback' => [$this, 'deleteItemPermissionsCheck'],
            ],
        ]);

        // GET /case-studies/{id}/related - Related case studies
        register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)/related', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'getRelatedCaseStudies'],
                'permission_callback' => [$this, 'getItemPermissionsCheck'],
            ],
        ]);
    }

    /**
     * Get case studies collection
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

        $args = $this->buildQueryArgs($request, 'rf_case_study');

        // Industry filter
        if ($industry = $request->get_param('industry')) {
            $args['tax_query'] = [[
                'taxonomy' => 'rf_case_industry',
                'field' => 'slug',
                'terms' => sanitize_title($industry),
            ]];
        }

        // Featured filter
        if ($request->get_param('featured') === '1' || $request->get_param('featured') === 'true') {
            $args['meta_query'] = [[
                'key' => 'featured_case',
                'value' => '1',
                'compare' => '=',
            ]];
        }

        $query = new WP_Query($args);
        $caseStudies = [];

        foreach ($query->posts as $post) {
            $caseStudies[] = $this->prepareCaseStudyData($post);
        }

        $response = $this->prepareCollectionResponse(
            $caseStudies,
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
     * Get single case study
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function getItem(WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_case_study' || $post->post_status !== 'publish') {
            return $this->prepareErrorResponse(
                __('Case study not found', 'rfplugin'),
                404,
                'case_study_not_found'
            );
        }

        return $this->prepareSuccessResponse($this->prepareCaseStudyData($post, true));
    }

    /**
     * Create case study
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function createItem(WP_REST_Request $request)
    {
        $postData = [
            'post_type' => 'rf_case_study',
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
        $this->updateCaseStudyFields($postId, $request);

        // Clear cache
        $this->deleteCachedResponse('*');

        return $this->prepareSuccessResponse(
            $this->prepareCaseStudyData(get_post($postId)),
            201,
            __('Case study created successfully', 'rfplugin')
        );
    }

    /**
     * Update case study
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function updateItem(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        $post = get_post($postId);

        if (!$post || $post->post_type !== 'rf_case_study') {
            return $this->prepareErrorResponse(
                __('Case study not found', 'rfplugin'),
                404,
                'case_study_not_found'
            );
        }

        $postData = ['ID' => $postId];

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

        $this->updateCaseStudyFields($postId, $request);
        $this->deleteCachedResponse('*');

        return $this->prepareSuccessResponse(
            $this->prepareCaseStudyData(get_post($postId)),
            200,
            __('Case study updated successfully', 'rfplugin')
        );
    }

    /**
     * Delete case study
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error
     */
    public function deleteItem(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        $post = get_post($postId);

        if (!$post || $post->post_type !== 'rf_case_study') {
            return $this->prepareErrorResponse(
                __('Case study not found', 'rfplugin'),
                404,
                'case_study_not_found'
            );
        }

        $deleted = wp_delete_post($postId, true);

        if (!$deleted) {
            return $this->prepareErrorResponse(
                __('Failed to delete case study', 'rfplugin'),
                500,
                'delete_failed'
            );
        }

        $this->deleteCachedResponse('*');

        return $this->prepareSuccessResponse(
            ['deleted' => true, 'id' => $postId],
            200,
            __('Case study deleted successfully', 'rfplugin')
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
        $post = get_post($postId);

        if (!$post) {
            return $this->prepareErrorResponse(
                __('Case study not found', 'rfplugin'),
                404,
                'case_study_not_found'
            );
        }

        // Get case studies from same industry
        $industries = wp_get_post_terms($postId, 'rf_case_industry', ['fields' => 'ids']);

        if (empty($industries)) {
            return $this->prepareSuccessResponse([]);
        }

        $args = [
            'post_type' => 'rf_case_study',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'post__not_in' => [$postId],
            'tax_query' => [[
                'taxonomy' => 'rf_case_industry',
                'field' => 'term_id',
                'terms' => $industries,
            ]],
        ];

        $query = new WP_Query($args);
        $related = [];

        foreach ($query->posts as $relatedPost) {
            $related[] = $this->prepareCaseStudyData($relatedPost, false);
        }

        return $this->prepareSuccessResponse($related);
    }

    /**
     * Prepare case study data for response
     *
     * @param \WP_Post $post Post object
     * @param bool $full Include full details
     * @return array
     */
    private function prepareCaseStudyData(\WP_Post $post, bool $full = false): array
    {
        $data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => get_the_excerpt($post),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'large'),
            'client' => [
                'name' => get_field('client_name', $post->ID),
                'industry' => wp_get_post_terms($post->ID, 'rf_case_industry', ['fields' => 'names']),
            ],
            'featured' => (bool) get_field('featured_case', $post->ID),
            'permalink' => get_permalink($post->ID),
            'date' => $post->post_date,
        ];

        if ($full) {
            $data['content'] = apply_filters('the_content', $post->post_content);
            $data['project'] = [
                'challenge' => get_field('project_challenge', $post->ID),
                'solution' => get_field('project_solution', $post->ID),
                'results' => get_field('project_results', $post->ID),
            ];
            $data['client']['website'] = get_field('client_website', $post->ID);
            $data['client']['testimonial'] = get_field('client_testimonial', $post->ID);
            $data['media'] = [
                'gallery' => get_field('case_gallery', $post->ID),
                'video_url' => get_field('case_video', $post->ID),
            ];
            $data['related_services'] = $this->getRelatedServices($post->ID);
            $data['related_products'] = $this->getRelatedProducts($post->ID);
        }

        return $data;
    }

    /**
     * Get related services
     *
     * @param int $postId Post ID
     * @return array
     */
    private function getRelatedServices(int $postId): array
    {
        $services = get_field('related_services', $postId);
        if (empty($services)) {
            return [];
        }

        $result = [];
        foreach ($services as $serviceId) {
            $post = get_post($serviceId);
            if ($post && $post->post_status === 'publish') {
                $result[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'permalink' => get_permalink($post->ID),
                ];
            }
        }

        return $result;
    }

    /**
     * Get related products
     *
     * @param int $postId Post ID
     * @return array
     */
    private function getRelatedProducts(int $postId): array
    {
        $products = get_field('related_products', $postId);
        if (empty($products)) {
            return [];
        }

        $result = [];
        foreach ($products as $productId) {
            $post = get_post($productId);
            if ($post && $post->post_status === 'publish') {
                $result[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
                ];
            }
        }

        return $result;
    }

    /**
     * Update case study ACF fields
     *
     * @param int $postId Post ID
     * @param WP_REST_Request $request Request object
     * @return void
     */
    private function updateCaseStudyFields(int $postId, WP_REST_Request $request): void
    {
        $fields = [
            'client_name',
            'client_website',
            'client_testimonial',
            'project_challenge',
            'project_solution',
            'case_video',
            'featured_case'
        ];

        foreach ($fields as $field) {
            if ($request->has_param($field)) {
                $value = $request->get_param($field);
                if ($field === 'featured_case') {
                    $value = (bool) $value;
                } else {
                    $value = sanitize_text_field($value);
                }
                update_field($field, $value, $postId);
            }
        }

        // Handle arrays
        if ($request->has_param('project_results')) {
            update_field('project_results', (array) $request->get_param('project_results'), $postId);
        }
        if ($request->has_param('related_services')) {
            update_field('related_services', array_map('intval', (array) $request->get_param('related_services')), $postId);
        }
        if ($request->has_param('related_products')) {
            update_field('related_products', array_map('intval', (array) $request->get_param('related_products')), $postId);
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
            'industry' => [
                'description' => 'Filter by industry slug',
                'type' => 'string',
            ],
            'featured' => [
                'description' => 'Filter featured case studies',
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
}
