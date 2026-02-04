<?php
/**
 * Container Block Definition
 */

namespace RFPlugin\ACF\Blocks\Definitions;

use RFPlugin\ACF\Blocks\BaseBlock;

if (!defined('ABSPATH')) {
    exit;
}

class ContainerBlock extends BaseBlock
{
    protected array $settings = [
        'supports' => [
            'align' => ['wide', 'full'],
            'mode'  => false,
            'jsx'   => true,
        ],
    ];

    public function getName(): string
    {
        return 'container';
    }

    public function getTitle(): string
    {
        return __('RF Section Container', 'rfplugin');
    }

    public function registerFields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_block_container',
                'title' => 'Block: Container',
                'fields' => [
                    [
                        'key' => 'field_container_padding',
                        'label' => 'Vertical Padding',
                        'name' => 'padding',
                        'type' => 'select',
                        'choices' => [
                            'none' => 'None',
                            'small' => 'Small (PY-8)',
                            'medium' => 'Medium (PY-16)',
                            'large' => 'Large (PY-24)',
                        ],
                        'default_value' => 'medium',
                    ],
                    [
                        'key' => 'field_container_bg',
                        'label' => 'Background Type',
                        'name' => 'bg_type',
                        'type' => 'select',
                        'choices' => [
                            'none' => 'Transparent',
                            'slate' => 'Light Slate (F8FAFC)',
                            'white' => 'White',
                            'glass' => 'Glassmorphism Card',
                        ],
                        'default_value' => 'none',
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/container',
                        ],
                    ],
                ],
            ]);
        }
    }
}
