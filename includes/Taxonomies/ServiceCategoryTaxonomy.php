<?php

/**
 * Service Category Taxonomy
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class ServiceCategoryTaxonomy extends BaseTaxonomy
{
    public function __construct()
    {
        $this->taxonomy = 'rf_service_category';
        $this->objectTypes = ['rf_service'];
        $this->labels = $this->generateLabels(
            __('Service Category', 'rfplugin'),
            __('Service Categories', 'rfplugin')
        );
        $this->args = [
            'description' => __('Categories for services', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'service-category', 'with_front' => false],
        ];
    }
}
