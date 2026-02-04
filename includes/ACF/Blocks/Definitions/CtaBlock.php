<?php
/**
 * CTA Block Definition
 */

namespace RFPlugin\ACF\Blocks\Definitions;

use RFPlugin\ACF\Blocks\BaseBlock;

if (!defined('ABSPATH')) {
    exit;
}

class CtaBlock extends BaseBlock
{
    public function getName(): string
    {
        return 'cta';
    }

    public function getTitle(): string
    {
        return __('RF Call to Action', 'rfplugin');
    }

    public function registerFields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_block_cta',
                'title' => 'Block: CTA',
                'fields' => [
                    [
                        'key' => 'field_cta_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'default_value' => 'Ready to build something great?',
                    ],
                    [
                        'key' => 'field_cta_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'textarea',
                        'rows' => 3,
                        'default_value' => 'Connect with our engineering team to explore specification details and custom solutions.',
                    ],
                    [
                        'key' => 'field_cta_button',
                        'label' => 'Button',
                        'name' => 'cta_button',
                        'type' => 'link',
                    ],
                    [
                        'key' => 'field_cta_bg_color',
                        'label' => 'Background Color',
                        'name' => 'bg_color',
                        'type' => 'select',
                        'choices' => [
                            'primary' => 'Primary (Dark Blue)',
                            'accent'  => 'Accent (Indigo)',
                            'white'   => 'White / Light',
                        ],
                        'default_value' => 'primary',
                    ],
                    [
                        'key' => 'field_cta_bg_image',
                        'label' => 'Background Image',
                        'name' => 'bg_image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/cta',
                        ],
                    ],
                ],
            ]);
        }
    }
}
