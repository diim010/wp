<?php
/**
 * FAQ Post Type
 * 
 * Defines the FAQ custom post type for managing
 * frequently asked questions.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FAQ Post Type class
 * 
 * @since 1.0.0
 */
class FAQPostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_faq';
        $this->labels = $this->generateLabels(
            __('FAQ', 'rfplugin'),
            __('FAQs', 'rfplugin')
        );
        $this->args = [
            'description' => __('Frequently asked questions', 'rfplugin'),
            'menu_icon' => 'dashicons-editor-help',
            'supports' => ['title', 'editor'],
            'rewrite' => ['slug' => 'faq', 'with_front' => false],
            'show_in_rest' => false, // Disable Gutenberg
            'taxonomies' => ['rf_faq_category', 'rf_faq_tag'],
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
        $new_columns['priority'] = __('Priority', 'rfplugin');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'priority':
                echo esc_html(get_field('field_faq_priority', $post_id) ?: '0');
                break;
        }
    }
}
