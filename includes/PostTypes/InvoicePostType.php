<?php
/**
 * Invoice Post Type
 * 
 * Defines the Invoice custom post type for managing
 * customer invoices with JSON data storage.
 * 
 * @package RFPlugin\PostTypes
 * @since 1.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Invoice Post Type class
 * 
 * @since 1.0.0
 */
class InvoicePostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_invoice';
        $this->labels = $this->generateLabels(
            __('Invoice', 'rfplugin'),
            __('Invoices', 'rfplugin')
        );
        $this->args = [
            'description' => __('Customer invoices and quotes', 'rfplugin'),
            'menu_icon' => 'dashicons-media-spreadsheet',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'supports' => ['title'],
            'capability_type' => ['rf_invoice', 'rf_invoices'],
            'map_meta_cap' => true,
        ];
    }

    /**
     * Define custom admin columns
     * 
     * @param array $columns
     * @return array
     */
    public function defineColumns(array $columns): array
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['customer_name'] = __('Customer', 'rfplugin');
        $new_columns['customer_email'] = __('Email', 'rfplugin');
        $new_columns['selected_product'] = __('Product', 'rfplugin');
        $new_columns['sync_status'] = __('Zoho Sync', 'rfplugin');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Render custom admin columns
     * 
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'customer_name':
                echo esc_html(get_field('field_invoice_name', $post_id) ?: '—');
                break;
            case 'customer_email':
                echo esc_html(get_field('field_invoice_email', $post_id) ?: '—');
                break;
            case 'selected_product':
                $product_id = get_field('field_invoice_product', $post_id);
                if ($product_id) {
                    echo '<a href="' . get_edit_post_link($product_id) . '">' . esc_html(get_the_title($product_id)) . '</a>';
                } else {
                    echo '—';
                }
                break;
            case 'sync_status':
                $status = get_field('field_invoice_sync_status', $post_id) ?: 'pending';
                $class = 'rf-badge ';
                switch ($status) {
                    case 'synced': $class .= 'online'; break;
                    case 'failed': $class .= 'failed'; break;
                    default: $class .= 'pending'; break;
                }
                echo '<span class="' . esc_attr($class) . '" style="padding: 2px 8px; border-radius: 4px; font-size: 11px; text-transform: uppercase; font-weight: bold; background: #eee;">' . esc_html(ucfirst($status)) . '</span>';
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

        $current_status = $_GET['sync_status'] ?? '';
        $options = [
            'pending' => __('Pending', 'rfplugin'),
            'synced' => __('Synced', 'rfplugin'),
            'failed' => __('Failed', 'rfplugin'),
        ];

        echo '<select name="sync_status">';
        echo '<option value="">' . __('All Sync Statuses', 'rfplugin') . '</option>';
        foreach ($options as $value => $label) {
            printf('<option value="%s" %s>%s</option>', $value, selected($current_status, $value, false), $label);
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

        if (!empty($_GET['sync_status'])) {
            $query->set('meta_query', [
                [
                    'key' => 'sync_status',
                    'value' => sanitize_text_field($_GET['sync_status']),
                    'compare' => '=',
                ]
            ]);
        }
    }
}
