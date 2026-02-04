<?php
/**
 * Resource Post Type
 * 
 * Unified post type for all technical assets including FAQs,
 * Documents, Videos, Sheets, and 3D Models.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Resource Post Type class
 * 
 * @since 1.0.0
 */
class ResourcePostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_resource';
        $this->labels = $this->generateLabels(
            __('Resource', 'rfplugin'),
            __('Resources', 'rfplugin')
        );
        $this->args = [
            'description' => __('Unified library for technical assets', 'rfplugin'),
            'menu_icon' => 'dashicons-category', // Unified icon
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite' => ['slug' => 'resources', 'with_front' => false],
            'show_in_rest' => false, // Use Classic Editor for precise ACF layout
            'has_archive' => 'resources',
            'taxonomies' => ['rf_resource_type', 'rf_resource_category'],
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
        $new_columns['resource_type'] = __('Type', 'rfplugin');
        $new_columns['resource_category'] = __('Category', 'rfplugin');
        $new_columns['visibility'] = __('Visibility', 'rfplugin');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'resource_type':
                $terms = wp_get_post_terms($post_id, 'rf_resource_type', ['fields' => 'names']);
                echo $terms ? esc_html(implode(', ', $terms)) : '—';
                break;
            case 'resource_category':
                $terms = wp_get_post_terms($post_id, 'rf_resource_category', ['fields' => 'names']);
                echo $terms ? esc_html(implode(', ', $terms)) : '—';
                break;
            case 'visibility':
                $role = get_field('field_resource_visibility', $post_id);
                echo esc_html(ucfirst($role ?: 'Everyone'));
                break;
        }
    }
}
