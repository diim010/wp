<?php
/**
 * Resource Type Taxonomy
 */

namespace RFPlugin\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class ResourceTypeTaxonomy extends BaseTaxonomy
{
    public function __construct()
    {
        $this->taxonomy = 'rf_resource_type';
        $this->objectTypes = ['rf_resource'];
        $this->labels = $this->generateLabels(
            __('Resource Type', 'rfplugin'),
            __('Resource Types', 'rfplugin')
        );
        $this->args = [
            'description' => __('Biological type of the resource (FAQ, Video, Doc, etc)', 'rfplugin'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'resource-type', 'with_front' => false],
        ];
    }
}
