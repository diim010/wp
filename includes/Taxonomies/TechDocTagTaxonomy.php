<?php
/**
 * Tech Doc Tag Taxonomy
 * 
 * Defines the Tech Doc Tag taxonomy for granular tagging
 * and search optimization of technical documents.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TechDocTagTaxonomy class
 */
class TechDocTagTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_techdoc_tag';
        $this->objectTypes = ['rf_techdoc'];
        $this->labels = $this->generateLabels(
            __('Tech Doc Tag', 'rfplugin'),
            __('Tech Doc Tags', 'rfplugin')
        );
        $this->args = [
            'description' => __('Granular tags for technical documents', 'rfplugin'),
            'hierarchical' => false,
            'rewrite' => ['slug' => 'tech-doc-tag', 'with_front' => false],
            'show_in_rest' => true,
        ];
    }
}
