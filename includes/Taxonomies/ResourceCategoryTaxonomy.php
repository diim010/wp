<?php
/**
 * Resource Category Taxonomy
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class ResourceCategoryTaxonomy extends BaseTaxonomy
{
    public function __construct()
    {
        $this->taxonomy = 'rf_resource_category';
        $this->objectTypes = ['rf_resource'];
        $this->labels = $this->generateLabels(
            __('Resource Category', 'rfplugin'),
            __('Resource Categories', 'rfplugin')
        );
        $this->args = [
            'description' => __('Global categorization for all library resources', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'resource-category', 'with_front' => false],
        ];
    }
}
