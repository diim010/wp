<?php

/**
 * Product Material Taxonomy
 *
 * Flat taxonomy for product materials.
 *
 * @package RFPlugin\Taxonomies
 * @since 2.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Material Taxonomy class
 */
class ProductMaterialTaxonomy
{
    /**
     * Taxonomy slug
     */
    private string $taxonomy = 'rf_product_material';

    /**
     * Register taxonomy
     */
    public function register(): void
    {
        $labels = [
            'name' => __('Materials', 'rfplugin'),
            'singular_name' => __('Material', 'rfplugin'),
            'search_items' => __('Search Materials', 'rfplugin'),
            'all_items' => __('All Materials', 'rfplugin'),
            'edit_item' => __('Edit Material', 'rfplugin'),
            'update_item' => __('Update Material', 'rfplugin'),
            'add_new_item' => __('Add New Material', 'rfplugin'),
            'new_item_name' => __('New Material Name', 'rfplugin'),
            'menu_name' => __('Materials', 'rfplugin'),
        ];

        $args = [
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'material'],
            'show_in_rest' => true,
        ];

        register_taxonomy($this->taxonomy, ['rf_product'], $args);
    }
}
