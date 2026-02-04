<?php
/**
 * FAQ Tag Taxonomy
 * 
 * Defines the FAQ Tag taxonomy for tagging
 * frequently asked questions.
 * 
 * @package RFPlugin\Taxonomies
 * @since 1.0.0
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FAQ Tag Taxonomy class
 * 
 * @since 1.0.0
 */
class FAQTagTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_faq_tag';
        $this->objectTypes = ['rf_faq'];
        $this->labels = $this->generateLabels(
            __('FAQ Tag', 'rfplugin'),
            __('FAQ Tags', 'rfplugin')
        );
        $this->args = [
            'description' => __('Tag categorization for FAQs', 'rfplugin'),
            'hierarchical' => false,
            'rewrite' => ['slug' => 'faq-tag', 'with_front' => false],
            'show_admin_column' => true,
        ];
    }
}
