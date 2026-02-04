<?php
/**
 * Service Post Type
 * 
 * Defines the Service custom post type for managing
 * additional services that can be added to products.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Service Post Type class
 * 
 * @since 1.0.0
 */
class ServicePostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_service';
        $this->labels = $this->generateLabels(
            __('Service', 'rfplugin'),
            __('Services', 'rfplugin')
        );
        $this->args = [
            'description' => __('Additional services for products', 'rfplugin'),
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'services', 'with_front' => false],
        ];
    }

    /**
     * Define custom admin columns
     */
    public function defineColumns(array $columns): array
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['price'] = __('Price (EUR)', 'rfplugin');
        $new_columns['pricing_model'] = __('Pricing Model', 'rfplugin');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'price':
                $price = get_field('field_service_price', $post_id);
                echo $price ? '€' . number_format((float)$price, 2) : '—';
                break;
            case 'pricing_model':
                $model = get_field('field_service_pricing_model', $post_id);
                echo esc_html(ucfirst($model ?: '—'));
                break;
        }
    }

    /**
     * Setup custom admin filters
     */
    public function setupFilters(): void
    {
        global $typenow;
        if ($typenow !== $this->postType) {
            return;
        }

        $current_model = $_GET['pricing_model'] ?? '';
        $options = [
            'fixed' => __('Fixed Price', 'rfplugin'),
            'hourly' => __('Hourly Rate', 'rfplugin'),
            'project' => __('Project Based', 'rfplugin'),
            'subscription' => __('Subscription', 'rfplugin'),
        ];

        echo '<select name="pricing_model">';
        echo '<option value="">' . __('All Pricing Models', 'rfplugin') . '</option>';
        foreach ($options as $value => $label) {
            printf('<option value="%s" %s>%s</option>', $value, selected($current_model, $value, false), $label);
        }
        echo '</select>';
    }

    /**
     * Apply custom admin filters
     */
    public function applyFilters($query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== $this->postType) {
            return;
        }

        if (!empty($_GET['pricing_model'])) {
            $query->set('meta_query', [
                [
                    'key' => 'pricing_model',
                    'value' => sanitize_text_field($_GET['pricing_model']),
                    'compare' => '=',
                ]
            ]);
        }
    }
}
