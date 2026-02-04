<?php
/**
 * FAQ Category Taxonomy
 * 
 * Defines the FAQ Category taxonomy for categorizing
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
 * FAQ Category Taxonomy class
 * 
 * @since 1.0.0
 */
class FAQCategoryTaxonomy extends BaseTaxonomy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomy = 'rf_faq_category';
        $this->objectTypes = ['rf_faq'];
        $this->labels = $this->generateLabels(
            __('FAQ Category', 'rfplugin'),
            __('FAQ Categories', 'rfplugin')
        );
        $this->args = [
            'description' => __('Category categorization for FAQs', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'faq-category', 'with_front' => false],
        ];
    }
}
