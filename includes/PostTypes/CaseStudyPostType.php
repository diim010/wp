<?php

/**
 * Case Study Post Type
 *
 * Post type for portfolio examples/case studies.
 *
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Case Study Post Type class
 *
 * @since 1.0.0
 */
class CaseStudyPostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_case_study';
        $this->labels = $this->generateLabels(
            __('Case Study', 'rfplugin'),
            __('Case Studies', 'rfplugin')
        );
        $this->args = [
            'description' => __('Portfolio case studies', 'rfplugin'),
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'cases', 'with_front' => false],
            'show_in_rest' => true,
            'has_archive' => 'cases',
            'taxonomies' => ['rf_case_industry'],
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
        $new_columns['case_industry'] = __('Industry', 'rfplugin');
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'case_industry':
                $terms = wp_get_post_terms($post_id, 'rf_case_industry', ['fields' => 'names']);
                echo $terms ? esc_html(implode(', ', $terms)) : '—';
                break;
        }
    }
}
