<?php
/**
 * Material Taxonomy
 * 
 * Defines the Material taxonomy for categorizing products
 * by material composition and patterns.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Material Taxonomy class
 * 
 * @since 1.0.0
 */
class MaterialTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_material';
        $this->objectTypes = ['product'];
        $this->labels = $this->generateLabels(
            __('Material', 'rfplugin'),
            __('Materials', 'rfplugin')
        );
        $this->args = [
            'description' => __('Material types and patterns for products', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'material', 'with_front' => false],
        ];
    }
}
