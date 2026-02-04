<?php
/**
 * Case Industry Taxonomy
 * 
 * Defines the Case Industry taxonomy for categorizing
 * case studies by industry vertical.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Case Industry Taxonomy class
 * 
 * @since 1.0.0
 */
class CaseIndustryTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_case_industry';
        $this->objectTypes = ['rf_case'];
        $this->labels = $this->generateLabels(
            __('Industry', 'rfplugin'),
            __('Industries', 'rfplugin')
        );
        $this->args = [
            'description' => __('Industry categorization for case studies', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'industry', 'with_front' => false],
        ];
    }
}
