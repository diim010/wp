<?php

/**
 * Case Industry Taxonomy
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class CaseIndustryTaxonomy extends BaseTaxonomy
{
    public function __construct()
    {
        $this->taxonomy = 'rf_case_industry';
        $this->objectTypes = ['rf_case_study'];
        $this->labels = $this->generateLabels(
            __('Industry', 'rfplugin'),
            __('Industries', 'rfplugin')
        );
        $this->args = [
            'description' => __('Industries for case studies', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'case-industry', 'with_front' => false],
        ];
    }
}
