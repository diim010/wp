<?php

/**
 * ACF Field Groups Configuration
 *
 * Registers Advanced Custom Fields groups for products,
 * services, and other custom post types.
 *
 * @package RFPlugin\ACF
 * @since 1.0.0
 */

namespace RFPlugin\ACF;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FieldGroups class
 *
 * @since 1.0.0
 */
class FieldGroups
{
    /**
     * Register all ACF field groups
     *
     * @return void
     */
    public static function register(): void
    {
        self::registerProductFields();
        self::registerServiceFields();
        self::registerCaseStudyFields();
        self::registerResourceFields();
        self::registerInvoiceFields();
    }

    /**
     * Register product specification fields
     *
     * @return void
     */
    /**
     * Register product specification fields
     *
     * @return void
     */
    private static function registerProductFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_product_specifications',
            'title' => __('Product Specifications', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_product_specs',
                    'label' => __('Technical Data', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_technical_specifications',
                    'label' => __('Technical Specifications', 'rfplugin'),
                    'name' => 'technical_specifications',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => __('Add Specification', 'rfplugin'),
                    'sub_fields' => [
                        [
                            'key' => 'field_spec_label',
                            'label' => __('Label', 'rfplugin'),
                            'name' => 'label',
                            'type' => 'text',
                            'required' => 1,
                        ],
                        [
                            'key' => 'field_spec_value',
                            'label' => __('Value', 'rfplugin'),
                            'name' => 'value',
                            'type' => 'text',
                            'required' => 1,
                        ],
                    ],
                ],
                [
                    'key' => 'tab_product_visuals',
                    'label' => __('Badges & Visuals', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                // Related Cases field removed
                [
                    'key' => 'field_tech_files',
                    'label' => __('Technical Files', 'rfplugin'),
                    'name' => 'tech_files',
                    'type' => 'repeater',
                    'sub_fields' => [
                        [
                            'key' => 'field_product_tech_file',
                            'label' => __('File', 'rfplugin'),
                            'name' => 'file',
                            'type' => 'file',
                            'return_format' => 'array',
                        ],
                    ],
                ],
                [
                    'key' => 'field_product_badges',
                    'label' => __('Custom Badges', 'rfplugin'),
                    'name' => 'product_badges',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'sub_fields' => [
                        [
                            'key' => 'field_badge_text',
                            'label' => __('Text', 'rfplugin'),
                            'name' => 'text',
                            'type' => 'text',
                            'required' => 1,
                        ],
                        [
                            'key' => 'field_badge_color',
                            'label' => __('Color', 'rfplugin'),
                            'name' => 'color',
                            'type' => 'color_picker',
                        ],
                        [
                            'key' => 'field_badge_position',
                            'label' => __('Position', 'rfplugin'),
                            'name' => 'position',
                            'type' => 'select',
                            'choices' => [
                                'top-left' => __('Top Left', 'rfplugin'),
                                'top-right' => __('Top Right', 'rfplugin'),
                                'bottom-left' => __('Bottom Left', 'rfplugin'),
                                'bottom-right' => __('Bottom Right', 'rfplugin'),
                            ],
                            'default_value' => 'top-left',
                        ],
                    ],
                ],
                [
                    'key' => 'field_product_color_swatches',
                    'label' => __('Color Swatches', 'rfplugin'),
                    'name' => 'color_swatches',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'sub_fields' => [
                        [
                            'key' => 'field_swatch_name',
                            'label' => __('Color Name', 'rfplugin'),
                            'name' => 'name',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_swatch_color',
                            'label' => __('Color Picker', 'rfplugin'),
                            'name' => 'color',
                            'type' => 'color_picker',
                        ],
                    ],
                ],

            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'product',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Register Service field group
     *
     * @return void
     */
    private static function registerServiceFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_service_details',
            'title' => __('Service Details', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_service_general',
                    'label' => __('General', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                ],
                [
                    'key' => 'field_service_duration',
                    'label' => __('Duration', 'rfplugin'),
                    'name' => 'service_duration',
                    'type' => 'text',
                    'instructions' => __('e.g., "2-3 weeks", "1 month"', 'rfplugin'),
                ],
                [
                    'key' => 'field_service_pricing_model',
                    'label' => __('Pricing Model', 'rfplugin'),
                    'name' => 'pricing_model',
                    'type' => 'select',
                    'choices' => [
                        'fixed' => __('Fixed Price', 'rfplugin'),
                        'hourly' => __('Hourly Rate', 'rfplugin'),
                        'project' => __('Per Project', 'rfplugin'),
                        'contact' => __('Contact for Quote', 'rfplugin'),
                    ],
                    'default_value' => 'contact',
                ],
                [
                    'key' => 'field_service_base_price',
                    'label' => __('Base Price', 'rfplugin'),
                    'name' => 'base_price',
                    'type' => 'number',
                    'instructions' => __('Leave empty if Contact for Quote', 'rfplugin'),
                    'conditional_logic' => [
                        [
                            ['field' => 'field_service_pricing_model', 'operator' => '!=', 'value' => 'contact'],
                        ],
                    ],
                ],
                [
                    'key' => 'field_service_price_note',
                    'label' => __('Pricing Note', 'rfplugin'),
                    'name' => 'price_note',
                    'type' => 'textarea',
                    'rows' => 3,
                    'instructions' => __('Additional pricing details or disclaimers', 'rfplugin'),
                ],
                [
                    'key' => 'tab_service_relationships',
                    'label' => __('Relationships', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_service_related_products',
                    'label' => __('Related Products', 'rfplugin'),
                    'name' => 'related_products',
                    'type' => 'relationship',
                    'post_type' => ['product'],
                    'return_format' => 'id',
                    'filters' => ['search'],
                ],
                [
                    'key' => 'field_service_related_cases',
                    'label' => __('Related Case Studies', 'rfplugin'),
                    'name' => 'related_cases',
                    'type' => 'relationship',
                    'post_type' => ['rf_case_study'],
                    'return_format' => 'id',
                    'filters' => ['search'],
                ],
                [
                    'key' => 'tab_service_visibility',
                    'label' => __('Visibility', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_service_visibility',
                    'label' => __('Visibility Settings', 'rfplugin'),
                    'name' => 'service_visibility',
                    'type' => 'select',
                    'choices' => [
                        'public' => __('Public', 'rfplugin'),
                        'customer' => __('Customers Only', 'rfplugin'),
                        'partner' => __('Partners Only', 'rfplugin'),
                    ],
                    'default_value' => 'public',
                ],
                [
                    'key' => 'field_service_featured',
                    'label' => __('Featured Service', 'rfplugin'),
                    'name' => 'featured_service',
                    'type' => 'true_false',
                    'message' => __('Mark as featured service', 'rfplugin'),
                    'default_value' => 0,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_service',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Register Case Study field group
     *
     * @return void
     */
    private static function registerCaseStudyFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_case_study_details',
            'title' => __('Case Study Details', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_case_client',
                    'label' => __('Client', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                ],
                [
                    'key' => 'field_case_client_name',
                    'label' => __('Client Name', 'rfplugin'),
                    'name' => 'client_name',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_case_client_logo',
                    'label' => __('Client Logo', 'rfplugin'),
                    'name' => 'client_logo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ],
                [
                    'key' => 'field_case_project_date',
                    'label' => __('Project Date', 'rfplugin'),
                    'name' => 'project_date',
                    'type' => 'date_picker',
                    'display_format' => 'F Y',
                    'return_format' => 'Y-m-d',
                ],
                [
                    'key' => 'tab_case_project',
                    'label' => __('Project', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_case_challenge',
                    'label' => __('Challenge', 'rfplugin'),
                    'name' => 'challenge',
                    'type' => 'wysiwyg',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ],
                [
                    'key' => 'field_case_solution',
                    'label' => __('Solution', 'rfplugin'),
                    'name' => 'solution',
                    'type' => 'wysiwyg',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ],
                [
                    'key' => 'field_case_results',
                    'label' => __('Results & Metrics', 'rfplugin'),
                    'name' => 'results',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => __('Add Metric', 'rfplugin'),
                    'sub_fields' => [
                        [
                            'key' => 'field_result_label',
                            'label' => __('Metric', 'rfplugin'),
                            'name' => 'metric_label',
                            'type' => 'text',
                            'required' => 1,
                        ],
                        [
                            'key' => 'field_result_value',
                            'label' => __('Value', 'rfplugin'),
                            'name' => 'metric_value',
                            'type' => 'text',
                            'required' => 1,
                        ],
                    ],
                ],
                [
                    'key' => 'field_case_duration',
                    'label' => __('Project Duration', 'rfplugin'),
                    'name' => 'project_duration',
                    'type' => 'text',
                    'instructions' => __('e.g., "3 months"', 'rfplugin'),
                ],
                [
                    'key' => 'tab_case_media',
                    'label' => __('Media', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_case_gallery',
                    'label' => __('Before/After Gallery', 'rfplugin'),
                    'name' => 'project_gallery',
                    'type' => 'gallery',
                    'return_format' => 'array',
                ],
                [
                    'key' => 'field_case_video',
                    'label' => __('Featured Video URL', 'rfplugin'),
                    'name' => 'video_url',
                    'type' => 'url',
                    'instructions' => __('YouTube or Vimeo URL', 'rfplugin'),
                ],
                [
                    'key' => 'tab_case_relationships',
                    'label' => __('Relationships', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_case_related_products',
                    'label' => __('Related Products', 'rfplugin'),
                    'name' => 'related_products',
                    'type' => 'relationship',
                    'post_type' => ['product'],
                    'return_format' => 'id',
                    'filters' => ['search'],
                ],
                [
                    'key' => 'field_case_related_services',
                    'label' => __('Related Services', 'rfplugin'),
                    'name' => 'related_services',
                    'type' => 'relationship',
                    'post_type' => ['rf_service'],
                    'return_format' => 'id',
                    'filters' => ['search'],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_case_study',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Register unified resource library fields
     */
    private static function registerResourceFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_resource_library',
            'title' => __('Resource Management', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'field_resource_mode',
                    'label' => __('Resource Mode', 'rfplugin'),
                    'name' => 'resource_mode',
                    'type' => 'select',
                    'required' => 1,
                    'choices' => [
                        'faq' => __('FAQ', 'rfplugin'),
                        'document' => __('Technical Document', 'rfplugin'),
                        'video' => __('Video Presentation', 'rfplugin'),
                        'sheet' => __('Data Sheet', 'rfplugin'),
                        '3d' => __('3D Model / Viewer', 'rfplugin'),
                    ],
                    'default_value' => 'faq',
                ],
                [
                    'key' => 'tab_res_general',
                    'label' => __('General', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_resource_visibility',
                    'label' => __('Visibility', 'rfplugin'),
                    'name' => 'resource_visibility',
                    'type' => 'select',
                    'choices' => [
                        'guest' => __('Public', 'rfplugin'),
                        'subscriber' => __('Customers only', 'rfplugin'),
                        'partner' => __('Partners only', 'rfplugin'),
                        'administrator' => __('Staff only', 'rfplugin'),
                    ],
                    'default_value' => 'guest',
                ],
                [
                    'key' => 'field_resource_related_items',
                    'label' => __('Related Products', 'rfplugin'),
                    'name' => 'related_items',
                    'type' => 'relationship',
                    'post_type' => ['product'],
                    'return_format' => 'id',
                ],

                // FAQ Specific
                [
                    'key' => 'tab_res_faq',
                    'label' => __('FAQ Content', 'rfplugin'),
                    'type' => 'tab',
                    'conditional_logic' => [
                        [['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'faq']]
                    ],
                ],
                [
                    'key' => 'field_resource_answer',
                    'label' => __('Answer Content', 'rfplugin'),
                    'name' => 'resource_answer',
                    'type' => 'wysiwyg',
                    'conditional_logic' => [
                        [['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'faq']]
                    ],
                ],

                // File Specific (Document/Sheet)
                [
                    'key' => 'tab_res_file',
                    'label' => __('File Asset', 'rfplugin'),
                    'type' => 'tab',
                    'conditional_logic' => [
                        [
                            ['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'document'],
                        ],
                        [
                            ['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'sheet'],
                        ]
                    ],
                ],
                [
                    'key' => 'field_resource_file',
                    'label' => __('Attachment File', 'rfplugin'),
                    'name' => 'resource_file',
                    'type' => 'file',
                    'return_format' => 'array',
                    'conditional_logic' => [
                        [
                            ['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'document'],
                        ],
                        [
                            ['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'sheet'],
                        ]
                    ],
                ],

                // Video Specific
                [
                    'key' => 'tab_res_video',
                    'label' => __('Video Details', 'rfplugin'),
                    'type' => 'tab',
                    'conditional_logic' => [
                        [['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'video']]
                    ],
                ],
                [
                    'key' => 'field_resource_video_url',
                    'label' => __('Video URL (YouTube/Vimeo)', 'rfplugin'),
                    'name' => 'resource_video_url',
                    'type' => 'url',
                    'conditional_logic' => [
                        [['field' => 'field_resource_mode', 'operator' => '==', 'value' => 'video']]
                    ],
                ],

                // 3D Specific
                [
                    'key' => 'tab_res_3d',
                    'label' => __('3D Model', 'rfplugin'),
                    'type' => 'tab',
                    'conditional_logic' => [
                        [['field' => 'field_resource_mode', 'operator' => '==', 'value' => '3d']]
                    ],
                ],
                [
                    'key' => 'field_resource_3d_embed',
                    'label' => __('3D Viewer Embed Code', 'rfplugin'),
                    'name' => 'resource_3d_embed',
                    'type' => 'textarea',
                    'conditional_logic' => [
                        [['field' => 'field_resource_mode', 'operator' => '==', 'value' => '3d']]
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_resource',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Register Invoice fields for form submissions
     *
     * @return void
     */
    private static function registerInvoiceFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_invoice_details',
            'title' => __('Form Submission Details', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_invoice_customer',
                    'label' => __('Customer Information', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_invoice_name',
                    'label' => __('Full Name', 'rfplugin'),
                    'name' => 'customer_name',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_invoice_email',
                    'label' => __('Email Address', 'rfplugin'),
                    'name' => 'customer_email',
                    'type' => 'email',
                    'required' => 1,
                ],
                [
                    'key' => 'field_invoice_phone',
                    'label' => __('Phone Number', 'rfplugin'),
                    'name' => 'customer_phone',
                    'type' => 'text',
                ],
                [
                    'key' => 'tab_invoice_submission',
                    'label' => __('Submission Content', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_invoice_message',
                    'label' => __('Message', 'rfplugin'),
                    'name' => 'form_message',
                    'type' => 'textarea',
                ],
                [
                    'key' => 'field_invoice_source_url',
                    'label' => __('Source Page URL', 'rfplugin'),
                    'name' => 'source_url',
                    'type' => 'url',
                    'readonly' => 1,
                ],
                [
                    'key' => 'field_invoice_form_id',
                    'label' => __('Form ID', 'rfplugin'),
                    'name' => 'form_id',
                    'type' => 'text',
                    'readonly' => 1,
                ],
                [
                    'key' => 'field_invoice_product',
                    'label' => __('Selected Product', 'rfplugin'),
                    'name' => 'selected_product',
                    'type' => 'post_object',
                    'post_type' => ['product'],
                    'return_format' => 'id',
                    'ui' => 1,
                ],
                [
                    'key' => 'field_invoice_options',
                    'label' => __('Selected Options', 'rfplugin'),
                    'name' => 'selected_options',
                    'type' => 'textarea',
                    'rows' => 4,
                ],
                [
                    'key' => 'tab_invoice_zoho',
                    'label' => __('Zoho CRM Sync', 'rfplugin'),
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_invoice_zoho_id',
                    'label' => __('Zoho Record ID', 'rfplugin'),
                    'name' => 'zoho_id',
                    'type' => 'text',
                    'readonly' => 1,
                ],
                [
                    'key' => 'field_invoice_sync_status',
                    'label' => __('Sync Status', 'rfplugin'),
                    'name' => 'sync_status',
                    'type' => 'select',
                    'choices' => [
                        'pending' => __('Pending', 'rfplugin'),
                        'synced' => __('Synced', 'rfplugin'),
                        'failed' => __('Failed', 'rfplugin'),
                    ],
                    'default_value' => 'pending',
                ],
                [
                    'key' => 'field_invoice_sync_error',
                    'label' => __('Sync Error', 'rfplugin'),
                    'name' => 'sync_error',
                    'type' => 'text',
                    'readonly' => 1,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_invoice',
                    ],
                ],
            ],
        ]);
    }
}
