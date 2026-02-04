<?php
/**
 * Case Post Type
 * 
 * Defines the Case custom post type for managing
 * case studies and portfolio examples.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Case Post Type class
 * 
 * @since 1.0.0
 */
class CasePostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_case';
        $this->labels = $this->generateLabels(
            __('Case', 'rfplugin'),
            __('Cases', 'rfplugin')
        );
        $this->args = [
            'description' => __('Portfolio cases and examples', 'rfplugin'),
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite' => ['slug' => 'cases', 'with_front' => false],
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
        $new_columns['client_name'] = __('Client', 'rfplugin');
        $new_columns['client_industry'] = __('Industry', 'rfplugin');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'client_name':
                echo esc_html(get_field('field_case_client', $post_id) ?: '—');
                break;
            case 'client_industry':
                echo esc_html(get_field('field_case_industry_text', $post_id) ?: '—');
                break;
        }
    }
}
