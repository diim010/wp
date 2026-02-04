<?php
/**
 * Tech Docs REST Controller
 * 
 * Handles REST API endpoints for technical documentation.
 * 
 * @package RFPlugin\REST\Controllers
 * @since 1.0.0
 */

namespace RFPlugin\REST\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Tech Docs Controller class
 * 
 * @since 1.0.0
 */
class TechDocsController extends BaseController
{
    /**
     * Constructor
     * 
     * @param string $namespace API namespace
     */
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);
        $this->restBase = 'techdocs';
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

        register_rest_route($this->namespace, $this->restBase . '/(?P<id>[\d]+)/download', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'trackDownload'],
                'permission_callback' => [$this, 'getDownloadPermissionsCheck'],
            ],
        ]);
    }

    /**
     * Get tech docs collection
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request): \WP_REST_Response
    {
        $meta_query = [];
        $tax_query = [];
        $file_type = $request->get_param('file_type');
        $tag = $request->get_param('tag');

        if ($file_type) {
            $meta_query[] = [
                'key' => 'file_type',
                'value' => sanitize_text_field($file_type),
                'compare' => '=',
            ];
        }

        if ($tag) {
            $tax_query[] = [
                'taxonomy' => 'rf_techdoc_tag',
                'field' => 'slug',
                'terms' => sanitize_text_field($tag),
            ];
        }

        $args = [
            'post_type' => 'rf_techdoc',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
        ];

        $search = $request->get_param('search');
        if ($search) {
            $args['s'] = sanitize_text_field($search);
        }

        $query = new \WP_Query($args);
        $docs = [];

        foreach ($query->posts as $post) {
            $docs[] = $this->prepareTechDocData($post);
        }

        return $this->prepareCollectionResponse($docs);
    }

    /**
     * Get single tech doc
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getItem(\WP_REST_Request $request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post || $post->post_type !== 'rf_techdoc') {
            return $this->prepareErrorResponse(__('Tech doc not found', 'rfplugin'), 404);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => $this->prepareTechDocData($post),
        ], 200);
    }

    /**
     * Check permissions for collection
     * 
     * @return bool
     */
    public function getItemsPermissionsCheck(): bool
    {
        return true;
    }

    /**
     * Check permissions for single tech doc
     * 
     * @param \WP_REST_Request $request Request object
     * @return bool
     */
    public function getItemPermissionsCheck(\WP_REST_Request $request): bool
    {
        return \RFPlugin\Security\Permissions::canViewTechDoc((int)$request->get_param('id'));
    }

    /**
     * Check permissions for downloading tech doc
     * 
     * @param \WP_REST_Request $request Request object
     * @return bool|\WP_Error
     */
    public function getDownloadPermissionsCheck(\WP_REST_Request $request)
    {
        $id = (int)$request->get_param('id');
        
        if (!\RFPlugin\Security\Permissions::canViewTechDoc($id)) {
            return new \WP_Error('rest_forbidden', __('You do not have permission to access this document.', 'rfplugin'), ['status' => 403]);
        }

        $nonce = $request->get_param('_wpnonce');
        if (!\RFPlugin\Security\Permissions::verifyRestNonce($nonce)) {
            return new \WP_Error('rest_forbidden', __('Security session expired. Please refresh the page.', 'rfplugin'), ['status' => 403]);
        }

        return true;
    }

    /**
     * Track download and stream file
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function trackDownload(\WP_REST_Request $request)
    {
        $id = (int)$request->get_param('id');
        $post = get_post($id);

        if (!$post || $post->post_type !== 'rf_techdoc') {
            return $this->prepareErrorResponse(__('Tech doc not found', 'rfplugin'), 404);
        }

        $ip = \RFPlugin\Security\Permissions::getRealIP();

        // 1. IP Throttling Check
        if (\RFPlugin\Security\DownloadProtector::hasActiveLock($ip)) {
            return $this->prepareErrorResponse(__('Download already in progress. Please wait.', 'rfplugin'), 429);
        }

        // 2. Dangerous Activity Check
        if (\RFPlugin\Security\DownloadProtector::isDangerous($ip)) {
            return $this->prepareErrorResponse(__('Access denied due to suspicious activity. Contact support.', 'rfplugin'), 403);
        }

        // 3. Resolve File Path
        $file_data = get_field('field_tech_doc_file', $id) ?: get_field('tech_file', $id);
        $file_url = is_array($file_data) ? ($file_data['url'] ?? '') : (string)$file_data;

        if (!$file_url) {
            return $this->prepareErrorResponse(__('Download reference missing.', 'rfplugin'), 404);
        }

        $file_path = '';
        if (is_array($file_data) && !empty($file_data['ID'])) {
            $file_path = get_attached_file($file_data['ID']);
        } else {
            $file_id = attachment_url_to_postid($file_url);
            if ($file_id) {
                $file_path = get_attached_file($file_id);
            } else {
                // Fallback for secure dir
                $upload_dir = wp_upload_dir();
                $filename = basename($file_url);
                $secure_path = $upload_dir['basedir'] . '/rfplugin-docs/' . $filename;
                if (file_exists($secure_path)) {
                    $file_path = $secure_path;
                } else {
                    $file_path = str_replace(site_url('/'), ABSPATH, $file_url);
                }
            }
        }

        if (!$file_path || !file_exists($file_path)) {
             return $this->prepareErrorResponse(__('Physical file not found.', 'rfplugin'), 404);
        }

        // 4. Create Lock and Log History
        $version_timestamp = (string)get_field('field_last_file_update', $id);
        \RFPlugin\Security\DownloadProtector::createLock($ip, $id);
        \RFPlugin\Security\DownloadProtector::logDownload($id, $ip, $version_timestamp);

        // 5. Update Statistics
        $count = (int)get_field('field_download_count', $id) ?: (int)get_field('download_count', $id);
        update_field('field_download_count', $count + 1, $id);
        update_field('download_count', $count + 1, $id); // Sync both for safety

        // 6. Stream File
        $mime_type = wp_check_filetype($file_path)['type'] ?: 'application/octet-stream';
        $filename = basename($file_path);

        // Clean output buffer to prevent corruption
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Security headers
        header('X-Robots-Tag: noindex, nofollow', true);
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: private, must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        // Use readfile for the stream
        register_shutdown_function([\RFPlugin\Security\DownloadProtector::class, 'releaseLock'], $ip);
        
        readfile($file_path);
        exit;
    }

    /**
     * Prepare tech doc data for response
     * 
     * @param \WP_Post $post Post object
     * @return array<string, mixed>
     */
    private function prepareTechDocData(\WP_Post $post): array
    {
        $file_data = get_field('field_tech_doc_file', $post->ID) ?: get_field('tech_file', $post->ID);
        $file_url = is_array($file_data) ? ($file_data['url'] ?? '') : (string)$file_data;
        $file_size = '';

        if (!empty($file_data['ID'])) {
            $file_path = get_attached_file($file_data['ID']);
            if ($file_path && file_exists($file_path)) {
                $file_size = size_format(filesize($file_path));
            }
        }

        $data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_content,
            'excerpt' => wp_trim_words($post->post_content, 20),
            'file_url' => $file_url,
            'file_type' => get_field('file_type', $post->ID),
            'file_size' => $file_size,
            'download_url' => rest_url($this->namespace . '/' . $this->restBase . '/' . $post->ID . '/download'),
            'download_count' => (int)get_field('download_count', $post->ID),
            'tags' => (array)get_the_terms($post->ID, 'rf_techdoc_tag') ? wp_list_pluck(get_the_terms($post->ID, 'rf_techdoc_tag'), 'name') : [],
            'permalink' => get_permalink($post->ID),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium') ?: RFPLUGIN_URL . 'assets/images/doc-placeholder.png',
        ];

        return apply_filters('rfplugin_techdoc_rest_response', $data, $post);
    }
}
