<?php
/**
 * Resources REST Controller
 */

namespace RFPlugin\REST\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Resources Controller class
 */
class ResourcesController extends BaseController
{
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);
        $this->restBase = 'resources';
    }

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

        register_rest_route($this->namespace, $this->restBase . '/(?P<id>[\d]+)/download', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'trackDownload'],
                'permission_callback' => [$this, 'getDownloadPermissionsCheck'],
            ],
        ]);
    }

    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $meta_query = [];
        $tax_query = [];
        
        $mode = $request->get_param('mode');
        $type = $request->get_param('type');
        $category = $request->get_param('category');
        $search = $request->get_param('search');

        if ($mode) {
            $meta_query[] = [
                'key' => 'resource_mode',
                'value' => sanitize_text_field($mode),
                'compare' => '=',
            ];
        }

        if ($type) {
            $tax_query[] = [
                'taxonomy' => 'rf_resource_type',
                'field' => 'slug',
                'terms' => sanitize_text_field($type),
            ];
        }

        if ($category) {
            $tax_query[] = [
                'taxonomy' => 'rf_resource_category',
                'field' => 'slug',
                'terms' => sanitize_text_field($category),
            ];
        }

        $args = [
            'post_type' => 'rf_resource',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
            's' => $search ? sanitize_text_field($search) : '',
        ];

        $query = new \WP_Query($args);
        $resources = [];

        foreach ($query->posts as $post) {
            $resources[] = $this->prepareResourceData($post);
        }

        return $this->prepareCollectionResponse($resources);
    }

    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_resource') {
            return $this->prepareErrorResponse(__('Resource not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareResourceData($post),
        ], 200);
    }

    public function getItemsPermissionsCheck(): bool
    {
        return true;
    }

    public function getItemPermissionsCheck(\WP_REST_Request $request): bool
    {
        return \RFPlugin\Security\Permissions::canViewPost((int)$request->get_param('id'));
    }

    public function getDownloadPermissionsCheck(\WP_REST_Request $request)
    {
        $id = (int)$request->get_param('id');
        if (!\RFPlugin\Security\Permissions::canViewPost($id)) {
            return new \WP_Error('rest_forbidden', __('Access denied.', 'rfplugin'), ['status' => 403]);
        }
        return true;
    }

    public function trackDownload(\WP_REST_Request $request)
    {
        $id = (int)$request->get_param('id');
        $post = get_post($id);

        if (!$post || $post->post_type !== 'rf_resource') {
            return $this->prepareErrorResponse(__('Resource not found', 'rfplugin'), 404);
        }

        $file_data = get_field('field_resource_file', $id);
        if (!$file_data || !isset($file_data['ID'])) {
            return $this->prepareErrorResponse(__('File reference missing.', 'rfplugin'), 404);
        }

        $file_path = get_attached_file($file_data['ID']);
        if (!$file_path || !file_exists($file_path)) {
            return $this->prepareErrorResponse(__('Physical file not found.', 'rfplugin'), 404);
        }

        // Increment count
        $count = (int)get_field('field_resource_download_count', $id);
        update_field('field_resource_download_count', $count + 1, $id);

        // Serve File
        $filename = basename($file_path);
        $mime = $file_data['mime_type'] ?? 'application/octet-stream';

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // Clean output buffer to prevent corrupted files
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        readfile($file_path);
        exit;
    }

    private function prepareResourceData(\WP_Post $post): array
    {
        $mode = get_field('field_resource_mode', $post->ID) ?: 'document';
        
        $data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => get_the_excerpt($post->ID),
            'mode' => $mode,
            'permalink' => get_permalink($post->ID),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
        ];

        // Type Specific Data
        if ($mode === 'faq') {
            $data['answer'] = wp_kses_post(get_field('field_resource_answer', $post->ID));
        } elseif ($mode === 'video') {
            $data['video_url'] = get_field('field_resource_video_url', $post->ID);
        } elseif (in_array($mode, ['document', 'sheet'])) {
            $file = get_field('field_resource_file', $post->ID);
            $data['file_url'] = $file['url'] ?? '';
            $data['file_size'] = isset($file['filesize']) ? size_format($file['filesize']) : '';
            $data['file_type'] = $file['subtype'] ?? '';
            $data['download_url'] = rest_url($this->namespace . '/' . $this->restBase . '/' . $post->ID . '/download');
        } elseif ($mode === '3d') {
            $data['model_embed'] = get_field('field_resource_3d_embed', $post->ID);
        }

        return apply_filters('rfplugin_resource_rest_response', $data, $post);
    }
}
