<?php

/**
 * Service Post Type
 *
 * Post type for add-on services.
 *
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Service Post Type class
 *
 * @since 1.0.0
 */
class ServicePostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_service';
        $this->labels = $this->generateLabels(
            __('Service', 'rfplugin'),
            __('Services', 'rfplugin')
        );
        $this->args = [
            'description' => __('Add-on services offered', 'rfplugin'),
            'menu_icon' => 'dashicons-hammer',
            'show_in_menu' => 'rf-control-center',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'services', 'with_front' => false],
            'show_in_rest' => true,
            'has_archive' => 'services',
            'taxonomies' => ['rf_service_category'],
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
        $new_columns['service_category'] = __('Category', 'rfplugin');
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'service_category':
                $terms = wp_get_post_terms($post_id, 'rf_service_category', ['fields' => 'names']);
                echo $terms ? esc_html(implode(', ', $terms)) : '—';
                break;
        }
    }
}
