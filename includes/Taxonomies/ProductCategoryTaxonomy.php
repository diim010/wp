<?php

/**
 * Product Category Taxonomy
 *
 * Hierarchical taxonomy for product categories.
 *
 * @package RFPlugin\Taxonomies
 * @since 2.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Category Taxonomy class
 */
class ProductCategoryTaxonomy
{
    /**
     * Taxonomy slug
     */
    private string $taxonomy = 'rf_product_category';

    /**
     * Register taxonomy
     */
    public function register(): void
    {
        $labels = [
            'name' => __('Product Categories', 'rfplugin'),
            'singular_name' => __('Product Category', 'rfplugin'),
            'search_items' => __('Search Categories', 'rfplugin'),
            'all_items' => __('All Categories', 'rfplugin'),
            'parent_item' => __('Parent Category', 'rfplugin'),
            'parent_item_colon' => __('Parent Category:', 'rfplugin'),
            'edit_item' => __('Edit Category', 'rfplugin'),
            'update_item' => __('Update Category', 'rfplugin'),
            'add_new_item' => __('Add New Category', 'rfplugin'),
            'new_item_name' => __('New Category Name', 'rfplugin'),
            'menu_name' => __('Categories', 'rfplugin'),
        ];

        $args = [
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'product-category'],
            'show_in_rest' => true,
        ];

        register_taxonomy($this->taxonomy, ['rf_product'], $args);
    }
}
