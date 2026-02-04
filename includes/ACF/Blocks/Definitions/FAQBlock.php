<?php
/**
 * FAQ Accordion Block Definition
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
 * FAQBlock class
 */
class FAQBlock extends BaseBlock
{
    /**
     * Get block name (slug)
     */
    public function getName(): string
    {
        return 'faq-accordion';
    }

    /**
     * Get block title
     */
    public function getTitle(): string
    {
        return __('FAQ Accordion', 'rfplugin');
    }

    /**
     * Register ACF fields for the block
     */
    public function registerFields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_block_faq_accordion',
                'title' => $this->getTitle(),
                'fields' => [
                    [
                        'key' => 'field_faq_block_title',
                        'label' => __('Block Title', 'rfplugin'),
                        'name' => 'block_title',
                        'type' => 'text',
                        'default_value' => __('Frequently Asked Questions', 'rfplugin'),
                    ],
                    [
                        'key' => 'field_faq_block_mode',
                        'label' => __('Selection Mode', 'rfplugin'),
                        'name' => 'selection_mode',
                        'type' => 'select',
                        'choices' => [
                            'all' => __('Show All', 'rfplugin'),
                            'category' => __('Filter by Category', 'rfplugin'),
                            'manual' => __('Manual Selection', 'rfplugin'),
                        ],
                        'default_value' => 'all',
                    ],
                    [
                        'key' => 'field_faq_block_category',
                        'label' => __('Category', 'rfplugin'),
                        'name' => 'category',
                        'type' => 'taxonomy',
                        'taxonomy' => 'rf_faq_category',
                        'field_type' => 'select',
                        'multiple' => 0,
                        'add_term' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_faq_block_mode',
                                    'operator' => '==',
                                    'value' => 'category',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_faq_block_manual',
                        'label' => __('Select FAQs', 'rfplugin'),
                        'name' => 'manual_faqs',
                        'type' => 'post_object',
                        'post_type' => ['rf_faq'],
                        'multiple' => 1,
                        'return_format' => 'id',
                        'ui' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_faq_block_mode',
                                    'operator' => '==',
                                    'value' => 'manual',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_faq_block_style',
                        'label' => __('Accordion Style', 'rfplugin'),
                        'name' => 'style',
                        'type' => 'button_group',
                        'choices' => [
                            'default' => __('Classic', 'rfplugin'),
                            'modern' => __('Modern (Glass)', 'rfplugin'),
                        ],
                        'default_value' => 'modern',
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
