<?php
/**
 * Tech Doc List Block Definition
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
 * TechDocListBlock class
 */
class TechDocListBlock extends BaseBlock
{
    /**
     * Get block name (slug)
     */
    public function getName(): string
    {
        return 'techdoc-list';
    }

    /**
     * Get block title
     */
    public function getTitle(): string
    {
        return __('Tech Docs List', 'rfplugin');
    }

    /**
     * Register ACF fields for the block
     */
    public function registerFields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_block_techdoc_list',
                'title' => $this->getTitle(),
                'fields' => [
                    [
                        'key' => 'field_techdoc_list_title',
                        'label' => __('Section Title', 'rfplugin'),
                        'name' => 'section_title',
                        'type' => 'text',
                        'default_value' => __('Technical Documentation', 'rfplugin'),
                    ],
                    [
                        'key' => 'field_techdoc_list_mode',
                        'label' => __('Selection Mode', 'rfplugin'),
                        'name' => 'selection_mode',
                        'type' => 'select',
                        'choices' => [
                            'latest' => __('Latest Documents', 'rfplugin'),
                            'category' => __('Filter by Category', 'rfplugin'),
                            'manual' => __('Manual Selection', 'rfplugin'),
                        ],
                        'default_value' => 'latest',
                    ],
                    [
                        'key' => 'field_techdoc_list_category',
                        'label' => __('Category', 'rfplugin'),
                        'name' => 'category',
                        'type' => 'taxonomy',
                        'taxonomy' => 'product_type', // Reusing product type for classification
                        'field_type' => 'select',
                        'add_term' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_techdoc_list_mode',
                                    'operator' => '==',
                                    'value' => 'category',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_techdoc_list_manual',
                        'label' => __('Select Documents', 'rfplugin'),
                        'name' => 'manual_docs',
                        'type' => 'post_object',
                        'post_type' => ['rf_techdoc'],
                        'multiple' => 1,
                        'return_format' => 'id',
                        'ui' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_techdoc_list_mode',
                                    'operator' => '==',
                                    'value' => 'manual',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_techdoc_list_count',
                        'label' => __('Number of items', 'rfplugin'),
                        'name' => 'item_count',
                        'type' => 'number',
                        'default_value' => 6,
                        'min' => 1,
                        'max' => 20,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_techdoc_list_mode',
                                    'operator' => '!=',
                                    'value' => 'manual',
                                ],
                            ],
                        ],
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
