<?php
/**
 * Abstract Base Taxonomy
 * 
 * Provides common functionality for all custom taxonomies.
 * Child classes extend this to define specific taxonomies.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Taxonomy class
 * 
 * @since 1.0.0
 */
abstract class BaseTaxonomy
{
    /**
     * Taxonomy key (max 32 chars)
     * 
     * @var string
     */
    protected string $taxonomy;

    /**
     * Object types (post types) to attach taxonomy to
     * 
     * @var array<string>
     */
    protected array $objectTypes = [];

    /**
     * Taxonomy labels
     * 
     * @var array<string, string>
     */
    protected array $labels = [];

    /**
     * Taxonomy arguments
     * 
     * @var array<string, mixed>
     */
    protected array $args = [];

    /**
     * Register the taxonomy
     * 
     * @return void
     */
    public function register(): void
    {
        register_taxonomy(
            $this->taxonomy,
            $this->objectTypes,
            array_merge($this->getDefaultArgs(), $this->args)
        );
    }

    /**
     * Get default taxonomy arguments
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
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_tagcloud' => false,
            'show_in_quick_edit' => true,
            'show_admin_column' => true,
            'hierarchical' => true,
        ];
    }

    /**
     * Generate labels for taxonomy
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
            'all_items' => sprintf(__('All %s', 'rfplugin'), $plural),
            'edit_item' => sprintf(__('Edit %s', 'rfplugin'), $singular),
            'view_item' => sprintf(__('View %s', 'rfplugin'), $singular),
            'update_item' => sprintf(__('Update %s', 'rfplugin'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'rfplugin'), $singular),
            'new_item_name' => sprintf(__('New %s Name', 'rfplugin'), $singular),
            'parent_item' => sprintf(__('Parent %s', 'rfplugin'), $singular),
            'parent_item_colon' => sprintf(__('Parent %s:', 'rfplugin'), $singular),
            'search_items' => sprintf(__('Search %s', 'rfplugin'), $plural),
            'not_found' => sprintf(__('No %s found.', 'rfplugin'), strtolower($plural)),
        ];
    }
}
