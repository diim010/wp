<?php
/**
 * Abstract Base Post Type
 * 
 * Provides common functionality for all custom post types.
 * Child classes extend this to define specific post types.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Post Type class
 * 
 * @since 1.0.0
 */
abstract class BasePostType
{
    /**
     * Post type key (max 20 chars)
     * 
     * @var string
     */
    protected string $postType;

    /**
     * Post type labels
     * 
     * @var array<string, string>
     */
    protected array $labels = [];

    /**
     * Post type arguments
     * 
     * @var array<string, mixed>
     */
    protected array $args = [];

    /**
     * Register the post type
     * 
     * @return void
     */
    public function register(): void
    {
        register_post_type($this->postType, array_merge(
            $this->getDefaultArgs(),
            $this->args
        ));

        // Register Admin Columns
        add_filter("manage_{$this->postType}_posts_columns", [$this, 'defineColumns']);
        add_action("manage_{$this->postType}_posts_custom_column", [$this, 'renderColumns'], 10, 2);
        
        // Register Filters
        add_action('restrict_manage_posts', [$this, 'setupFilters']);
        add_action('pre_get_posts', [$this, 'applyFilters']);
    }

    /**
     * Define custom admin columns (to be overridden)
     * 
     * @param array $columns
     * @return array
     */
    public function defineColumns(array $columns): array
    {
        return $columns;
    }

    /**
     * Render custom admin columns (to be overridden)
     * 
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function renderColumns(string $column, int $post_id): void
    {
        // To be implemented by child classes
    }

    /**
     * Setup custom admin filters (to be overridden)
     * 
     * @return void
     */
    public function setupFilters(): void
    {
        global $typenow;
        if ($typenow !== $this->postType) {
            return;
        }
    }

    /**
     * Apply custom admin filters (to be overridden)
     * 
     * @param \WP_Query $query
     * @return void
     */
    public function applyFilters($query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== $this->postType) {
            return;
        }
    }

    /**
     * Get default post type arguments
     * 
     * @return array<string, mixed>
     */
    protected function getDefaultArgs(): array
    {
        return [
            'labels' => $this->labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_rest' => true,
            'rest_base' => $this->postType,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_position' => null,
        ];
    }

    /**
     * Generate labels for post type
     * 
     * @param string $singular Singular name
     * @param string $plural Plural name
     * @return array<string, string>
     */
    protected function generateLabels(string $singular, string $plural): array
    {
        return [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'name_admin_bar' => $singular,
            'add_new' => sprintf(__('Add New %s', 'rfplugin'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'rfplugin'), $singular),
            'new_item' => sprintf(__('New %s', 'rfplugin'), $singular),
            'edit_item' => sprintf(__('Edit %s', 'rfplugin'), $singular),
            'view_item' => sprintf(__('View %s', 'rfplugin'), $singular),
            'all_items' => sprintf(__('All %s', 'rfplugin'), $plural),
            'search_items' => sprintf(__('Search %s', 'rfplugin'), $plural),
            'parent_item_colon' => sprintf(__('Parent %s:', 'rfplugin'), $plural),
            'not_found' => sprintf(__('No %s found.', 'rfplugin'), strtolower($plural)),
            'not_found_in_trash' => sprintf(__('No %s found in Trash.', 'rfplugin'), strtolower($plural)),
        ];
    }
}
