<?php
/**
 * Product Type Taxonomy
 * 
 * Defines the Product Type taxonomy for categorizing products
 * with a dynamic type system.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Type Taxonomy class
 * 
 * @since 1.0.0
 */
class ProductTypeTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_product_type';
        $this->objectTypes = ['product'];
        $this->labels = $this->generateLabels(
            __('Product Type', 'rfplugin'),
            __('Product Types', 'rfplugin')
        );
        $this->args = [
            'description' => __('Product categorization system', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'product-type', 'with_front' => false],
        ];
    }
}
