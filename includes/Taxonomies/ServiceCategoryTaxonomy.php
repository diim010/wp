<?php
/**
 * Service Category Taxonomy
 * 
 * Defines the Service Category taxonomy for categorizing
 * services.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Service Category Taxonomy class
 * 
 * @since 1.0.0
 */
class ServiceCategoryTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_service_category';
        $this->objectTypes = ['rf_service'];
        $this->labels = $this->generateLabels(
            __('Service Category', 'rfplugin'),
            __('Service Categories', 'rfplugin')
        );
        $this->args = [
            'description' => __('Category categorization for services', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'service-category', 'with_front' => false],
        ];
    }
}
