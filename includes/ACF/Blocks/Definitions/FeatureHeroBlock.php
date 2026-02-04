<?php
/**
 * Feature Hero Block Definition
 * 
 * @package RFPlugin\ACF\Blocks\Definitions
 * @since 1.0.0
 */

namespace RFPlugin\ACF\Blocks\Definitions;

use RFPlugin\ACF\Blocks\BaseBlock;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FeatureHeroBlock class
 */
class FeatureHeroBlock extends BaseBlock
{
    /**
     * Get block name (slug)
     */
    public function getName(): string
    {
        return 'feature-hero';
    }

    /**
     * Get block title
     */
    public function getTitle(): string
    {
        return __('Feature Hero', 'rfplugin');
    }

    /**
     * Register ACF fields for the block
     */
    public function registerFields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_block_feature_hero',
                'title' => $this->getTitle(),
                'fields' => [
                    [
                        'key' => 'field_feature_hero_title',
                        'label' => __('Title', 'rfplugin'),
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_feature_hero_subtitle',
                        'label' => __('Subtitle', 'rfplugin'),
                        'name' => 'subtitle',
                        'type' => 'textarea',
                        'rows' => 2,
                    ],
                    [
                        'key' => 'field_feature_hero_image',
                        'label' => __('Background Image', 'rfplugin'),
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ],
                    [
                        'key' => 'field_feature_hero_cta',
                        'label' => __('CTA Button', 'rfplugin'),
                        'name' => 'cta',
                        'type' => 'link',
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/' . $this->getName(),
                        ],
                    ],
                ],
            ]);
        }
    }
}
