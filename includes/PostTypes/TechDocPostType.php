<?php
/**
 * Technical Documentation Post Type
 * 
 * Defines the Tech Doc custom post type for managing
 * technical documentation and PDF files.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Tech Doc Post Type class
 * 
 * @since 1.0.0
 */
class TechDocPostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_techdoc';
        $this->labels = $this->generateLabels(
            __('Tech Doc', 'rfplugin'),
            __('Tech Docs', 'rfplugin')
        );
        $this->args = [
            'description' => __('Technical documentation and files', 'rfplugin'),
            'menu_icon' => 'dashicons-media-document',
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'tech-docs', 'with_front' => false],
            'show_in_rest' => false, // Disable Gutenberg
        ];
    }

    /**
     * Define custom admin columns
     */
    public function defineColumns(array $columns): array
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['file_type'] = __('File Type', 'rfplugin');
        $new_columns['tags'] = __('Tags', 'rfplugin');
        $new_columns['visibility'] = __('Visibility', 'rfplugin');
        $new_columns['downloads'] = __('Downloads', 'rfplugin');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'file_type':
                $type = get_field('field_file_type', $post_id);
                echo esc_html(ucfirst($type ?: '—'));
                break;
            case 'tags':
                $tags = wp_get_post_terms($post_id, 'rf_techdoc_tag', ['fields' => 'names']);
                echo $tags ? esc_html(implode(', ', $tags)) : '—';
                break;
            case 'visibility':
                $role = get_field('field_tech_visibility', $post_id);
                echo esc_html(ucfirst($role ?: 'Everyone'));
                break;
            case 'downloads':
                echo (int)get_field('field_download_count', $post_id);
                break;
        }
    }

    /**
     * Setup custom admin filters
     */
    public function setupFilters(): void
    {
        global $typenow;
        if ($typenow !== $this->postType) {
            return;
        }

        $current_type = $_GET['file_type'] ?? '';
        $options = [
            'datasheet' => __('Datasheet', 'rfplugin'),
            'manual' => __('Manual', 'rfplugin'),
            'certificate' => __('Certificate', 'rfplugin'),
            'specification' => __('Specification', 'rfplugin'),
        ];

        echo '<select name="file_type">';
        echo '<option value="">' . __('All File Types', 'rfplugin') . '</option>';
        foreach ($options as $value => $label) {
            printf('<option value="%s" %s>%s</option>', $value, selected($current_type, $value, false), $label);
        }
        echo '</select>';
    }

    /**
     * Apply custom admin filters
     */
    public function applyFilters($query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== $this->postType) {
            return;
        }

        if (!empty($_GET['file_type'])) {
            $query->set('meta_query', [
                [
                    'key' => 'file_type',
                    'value' => sanitize_text_field($_GET['file_type']),
                    'compare' => '=',
                ]
            ]);
        }
    }
}
