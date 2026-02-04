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
        self::registerCaseFields();
        self::registerTechDocFields();
        self::registerFAQFields();
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
                [
                    'key' => 'field_related_cases',
                    'label' => __('Related Cases', 'rfplugin'),
                    'name' => 'related_cases',
                    'type' => 'relationship',
                    'post_type' => ['rf_case'],
                    'return_format' => 'id',
                ],
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
     * Register service fields
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
                    'key' => 'tab_service_details',
                    'label' => __('Pricing & Details', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_service_price',
                    'label' => __('Price (EUR)', 'rfplugin'),
                    'name' => 'service_price',
                    'type' => 'number',
                    'required' => 1,
                    'min' => 0,
                    'step' => 0.01,
                ],
                [
                    'key' => 'field_service_duration',
                    'label' => __('Duration (days)', 'rfplugin'),
                    'name' => 'service_duration',
                    'type' => 'number',
                    'min' => 0,
                ],
                [
                    'key' => 'field_service_pricing_model',
                    'label' => __('Pricing Model', 'rfplugin'),
                    'name' => 'pricing_model',
                    'type' => 'select',
                    'choices' => [
                        'fixed' => __('Fixed Price', 'rfplugin'),
                        'hourly' => __('Hourly Rate', 'rfplugin'),
                        'project' => __('Project Based', 'rfplugin'),
                        'subscription' => __('Subscription', 'rfplugin'),
                    ],
                    'default_value' => 'fixed',
                ],
                [
                    'key' => 'tab_service_relationships',
                    'label' => __('Relationships', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_service_related_products',
                    'label' => __('Related Products', 'rfplugin'),
                    'name' => 'related_products',
                    'type' => 'relationship',
                    'post_type' => ['product'],
                    'filters' => ['search', 'taxonomy'],
                    'return_format' => 'id',
                ],
                
                [
                    'key' => 'field_service_booking_link',
                    'label' => __('Booking/Contact Link', 'rfplugin'),
                    'name' => 'booking_link',
                    'type' => 'url',
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
     * Register case study fields
     * 
     * @return void
     */
    private static function registerCaseFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_case_details',
            'title' => __('Case Details', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_case_general',
                    'label' => __('General Info', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_case_client',
                    'label' => __('Client Name', 'rfplugin'),
                    'name' => 'case_client',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_case_industry_text',
                    'label' => __('Client Industry', 'rfplugin'),
                    'name' => 'case_industry_text',
                    'type' => 'text',
                ],
                [
                    'key' => 'tab_case_media',
                    'label' => __('Media Gallery', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_case_gallery',
                    'label' => __('Gallery', 'rfplugin'),
                    'name' => 'case_gallery',
                    'type' => 'gallery',
                    'return_format' => 'array',
                ],
                [
                    'key' => 'field_case_before_after',
                    'label' => __('Media Showcase (Before/After)', 'rfplugin'),
                    'name' => 'before_after',
                    'type' => 'group',
                    'sub_fields' => [
                        [
                            'key' => 'field_case_before_img',
                            'label' => __('Before Image', 'rfplugin'),
                            'name' => 'before_img',
                            'type' => 'image',
                        ],
                        [
                            'key' => 'field_case_after_img',
                            'label' => __('After Image', 'rfplugin'),
                            'name' => 'after_img',
                            'type' => 'image',
                        ],
                    ],
                ],
                [
                    'key' => 'tab_case_content',
                    'label' => __('Story Content', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_case_challenge',
                    'label' => __('The Challenge', 'rfplugin'),
                    'name' => 'case_challenge',
                    'type' => 'wysiwyg',
                ],
                [
                    'key' => 'field_case_solution',
                    'label' => __('The Solution', 'rfplugin'),
                    'name' => 'case_solution',
                    'type' => 'wysiwyg',
                ],
                [
                    'key' => 'field_case_results',
                    'label' => __('The Results', 'rfplugin'),
                    'name' => 'case_results',
                    'type' => 'wysiwyg',
                ],
                [
                    'key' => 'field_case_client_logo',
                    'label' => __('Client Logo', 'rfplugin'),
                    'name' => 'case_client_logo',
                    'type' => 'image',
                    'return_format' => 'id',
                ],
                [
                    'key' => 'tab_case_relationships',
                    'label' => __('Relationships', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_case_related_products',
                    'label' => __('Related Products/Services', 'rfplugin'),
                    'name' => 'related_items',
                    'type' => 'relationship',
                    'post_type' => ['product', 'rf_service'],
                    'return_format' => 'id',
                ],
                
              
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_case',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Register tech doc fields
     * 
     * @return void
     */
    private static function registerTechDocFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_techdoc_details',
            'title' => __('Tech Doc Details', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_techdoc_file',
                    'label' => __('File & Classification', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_tech_doc_file',
                    'label' => __('Technical File', 'rfplugin'),
                    'name' => 'tech_file',
                    'type' => 'file',
                    'required' => 1,
                    'return_format' => 'array',
                ],
                [
                    'key' => 'tab_techdoc_access',
                    'label' => __('Access Control', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_tech_visibility',
                    'label' => __('User Role Visibility', 'rfplugin'),
                    'name' => 'user_role_visibility',
                    'type' => 'select',
                    'choices' => [
                        'administrator' => __('Administrator', 'rfplugin'),
                        'editor' => __('Editor', 'rfplugin'),
                        'author' => __('Author', 'rfplugin'),
                        'contributor' => __('Contributor', 'rfplugin'),
                        'subscriber' => __('Subscriber', 'rfplugin'),
                        'guest' => __('Guest', 'rfplugin'),
                    ],
                ],
                [
                    'key' => 'field_file_type',
                    'label' => __('File Type', 'rfplugin'),
                    'name' => 'file_type',
                    'type' => 'select',
                    'choices' => [
                        'datasheet' => __('Datasheet', 'rfplugin'),
                        'manual' => __('Manual', 'rfplugin'),
                        'certificate' => __('Certificate', 'rfplugin'),
                        'specification' => __('Specification', 'rfplugin'),
                        'guide' => __('Installation Guide', 'rfplugin'),
                        'drawing' => __('Technical Drawing', 'rfplugin'),
                        'security' => __('Security Data Sheet', 'rfplugin'),
                    ],
                ],
                [
                    'key' => 'field_download_count',
                    'label' => __('Download Count', 'rfplugin'),
                    'name' => 'download_count',
                    'type' => 'number',
                    'default_value' => 0,
                    'readonly' => 1,
                ],
                [
                    'key' => 'field_periodically_updated',
                    'label' => __('Periodically Updated', 'rfplugin'),
                    'instructions' => __('If enabled, users who downloaded this doc will be notified when a new version is published.', 'rfplugin'),
                    'name' => 'periodically_updated',
                    'type' => 'true_false',
                    'default_value' => 0,
                    'ui' => 1,
                ],
                [
                    'key' => 'field_last_file_update',
                    'label' => __('Last File Update', 'rfplugin'),
                    'name' => 'last_file_update',
                    'type' => 'text',
                    'readonly' => 1,
                    'wrapper' => [
                        'width' => '',
                        'class' => 'acf-hidden',
                        'id' => '',
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_techdoc',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Register FAQ fields
     * 
     * @return void
     */
    public static function registerFAQFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_faq_details',
            'title' => __('FAQ Details', 'rfplugin'),
            'fields' => [
                [
                    'key' => 'tab_faq_content',
                    'label' => __('Content', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_faq_answer',
                    'label' => __('Answer', 'rfplugin'),
                    'name' => 'faq_answer',
                    'type' => 'wysiwyg',
                    'required' => 1,
                ],
                [
                    'key' => 'field_faq_priority',
                    'label' => __('Priority/Order', 'rfplugin'),
                    'name' => 'faq_priority',
                    'type' => 'number',
                    'default_value' => 0,
                ],
                [
                    'key' => 'field_faq_type',
                    'label' => __('FAQ Type', 'rfplugin'),
                    'name' => 'faq_type',
                    'type' => 'select',
                    'choices' => [
                        'general' => __('General', 'rfplugin'),
                        'technical' => __('Technical', 'rfplugin'),
                        'shipping' => __('Shipping', 'rfplugin'),
                        'warranty' => __('Warranty', 'rfplugin'),
                    ],
                    'default_value' => 'general',
                ],
                [
                    'key' => 'tab_faq_relationships',
                    'label' => __('Relationships & Tech', 'rfplugin'),
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                ],
                [
                    'key' => 'field_faq_related_items',
                    'label' => __('Related Products/Services', 'rfplugin'),
                    'name' => 'related_items',
                    'type' => 'relationship',
                    'post_type' => ['product', 'rf_service'],
                    'return_format' => 'id',
                ],
                [
                    'key' => 'field_faq-attach-doc',
                    'label' => __('Tech Docs', 'rfplugin'),
                    'name' => 'attached_docs',
                    'type' => 'relationship',
                    'post_type' => ['rf_techdoc', 'rf_service'],
                    'return_format' => 'id',
                ],
               
                
               
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'rf_faq',
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
