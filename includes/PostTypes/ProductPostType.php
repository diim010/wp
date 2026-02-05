<?php

/**
 * Product Post Type
 *
 * Custom post type for configurable products designed for Next.js constructor.
 * Supports dimensions, materials, colors, finishes, and dynamic pricing.
 *
 * @package RFPlugin\PostTypes
 * @since 2.0.0
 */

namespace RFPlugin\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Post Type class
 *
 * @since 2.0.0
 */
class ProductPostType extends BasePostType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postType = 'rf_product';
        $this->labels = $this->generateLabels(
            __('Product', 'rfplugin'),
            __('Products', 'rfplugin')
        );
        $this->args = [
            'description' => __('Configurable products for Next.js constructor', 'rfplugin'),
            'menu_icon' => 'dashicons-products',
            'show_in_menu' => 'rf-control-center',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'products', 'with_front' => false],
            'show_in_rest' => true,
            'has_archive' => 'products',
            'taxonomies' => ['rf_product_category', 'rf_product_material'],
        ];
    }

    /**
     * Define custom admin columns
     */
    public function defineColumns(array $columns): array
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['thumbnail'] = __('Image', 'rfplugin');
        $new_columns['title'] = $columns['title'];
        $new_columns['sku'] = __('SKU', 'rfplugin');
        $new_columns['base_price'] = __('Base Price', 'rfplugin');
        $new_columns['category'] = __('Category', 'rfplugin');
        $new_columns['configurable'] = __('Configurable', 'rfplugin');
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    /**
     * Render custom admin columns
     */
    public function renderColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, [50, 50]);
                } else {
                    echo '<span class="dashicons dashicons-format-image" style="font-size:50px;color:#ddd;"></span>';
                }
                break;

            case 'sku':
                $sku = get_post_meta($post_id, 'product_sku', true);
                echo $sku ? esc_html($sku) : '—';
                break;

            case 'base_price':
                $price = get_post_meta($post_id, 'product_base_price', true);
                if ($price) {
                    echo '$' . number_format((float)$price, 2);
                } else {
                    echo '—';
                }
                break;

            case 'category':
                $terms = wp_get_post_terms($post_id, 'rf_product_category', ['fields' => 'names']);
                echo $terms ? esc_html(implode(', ', $terms)) : '—';
                break;

            case 'configurable':
                $configurable = get_post_meta($post_id, 'product_configurable', true);
                if ($configurable) {
                    echo '<span class="dashicons dashicons-yes-alt" style="color:#46b450;"></span>';
                } else {
                    echo '<span class="dashicons dashicons-minus" style="color:#ddd;"></span>';
                }
                break;
        }
    }

    /**
     * Register meta fields for REST API
     */
    public function registerMetaFields(): void
    {
        // SKU
        register_post_meta('rf_product', 'product_sku', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        // Base Price
        register_post_meta('rf_product', 'product_base_price', [
            'type' => 'number',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'floatval',
        ]);

        // Configurable flag
        register_post_meta('rf_product', 'product_configurable', [
            'type' => 'boolean',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Calculation method
        register_post_meta('rf_product', 'product_calculation_method', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        // Minimum order quantity
        register_post_meta('rf_product', 'product_minimum_order', [
            'type' => 'number',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'absint',
        ]);

        // Configuration JSON (for complex ACF data)
        register_post_meta('rf_product', 'product_config_json', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'wp_kses_post',
        ]);
    }

    /**
     * Additional hooks
     */
    protected function additionalHooks(): void
    {
        add_action('init', [$this, 'registerMetaFields']);
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('save_post_rf_product', [$this, 'saveProductMeta'], 10, 2);
    }

    /**
     * Add meta boxes
     */
    public function addMetaBoxes(): void
    {
        add_meta_box(
            'rf_product_quick_config',
            __('Quick Configuration', 'rfplugin'),
            [$this, 'renderQuickConfigMetaBox'],
            'rf_product',
            'side',
            'high'
        );
    }

    /**
     * Render quick config meta box
     */
    public function renderQuickConfigMetaBox($post): void
    {
        wp_nonce_field('rf_product_meta', 'rf_product_meta_nonce');

        $sku = get_post_meta($post->ID, 'product_sku', true);
        $price = get_post_meta($post->ID, 'product_base_price', true);
        $configurable = get_post_meta($post->ID, 'product_configurable', true);
        $calc_method = get_post_meta($post->ID, 'product_calculation_method', true);
        $min_order = get_post_meta($post->ID, 'product_minimum_order', true);

        ?>
        <div class="rf-product-meta">
            <p>
                <label for="product_sku"><strong><?php _e('SKU', 'rfplugin'); ?></strong></label><br>
                <input type="text" id="product_sku" name="product_sku" value="<?php echo esc_attr($sku); ?>" class="widefat">
            </p>
            <p>
                <label for="product_base_price"><strong><?php _e('Base Price ($)', 'rfplugin'); ?></strong></label><br>
                <input type="number" id="product_base_price" name="product_base_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0" class="widefat">
            </p>
            <p>
                <label>
                    <input type="checkbox" name="product_configurable" value="1" <?php checked($configurable, 1); ?>>
                    <?php _e('Configurable Product', 'rfplugin'); ?>
                </label>
            </p>
            <p>
                <label for="product_calculation_method"><strong><?php _e('Calculation Method', 'rfplugin'); ?></strong></label><br>
                <select id="product_calculation_method" name="product_calculation_method" class="widefat">
                    <option value="fixed" <?php selected($calc_method, 'fixed'); ?>><?php _e('Fixed Price', 'rfplugin'); ?></option>
                    <option value="per_sqm" <?php selected($calc_method, 'per_sqm'); ?>><?php _e('Per Square Meter', 'rfplugin'); ?></option>
                    <option value="per_linear_m" <?php selected($calc_method, 'per_linear_m'); ?>><?php _e('Per Linear Meter', 'rfplugin'); ?></option>
                </select>
            </p>
            <p>
                <label for="product_minimum_order"><strong><?php _e('Minimum Order', 'rfplugin'); ?></strong></label><br>
                <input type="number" id="product_minimum_order" name="product_minimum_order" value="<?php echo esc_attr($min_order); ?>" min="1" class="widefat">
            </p>
        </div>
        <?php
    }

    /**
     * Save product meta
     */
    public function saveProductMeta(int $post_id, $post): void
    {
        // Verify nonce
        if (!isset($_POST['rf_product_meta_nonce']) || !wp_verify_nonce($_POST['rf_product_meta_nonce'], 'rf_product_meta')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save SKU
        if (isset($_POST['product_sku'])) {
            update_post_meta($post_id, 'product_sku', sanitize_text_field($_POST['product_sku']));
        }

        // Save base price
        if (isset($_POST['product_base_price'])) {
            update_post_meta($post_id, 'product_base_price', floatval($_POST['product_base_price']));
        }

        // Save configurable flag
        update_post_meta($post_id, 'product_configurable', isset($_POST['product_configurable']) ? 1 : 0);

        // Save calculation method
        if (isset($_POST['product_calculation_method'])) {
            update_post_meta($post_id, 'product_calculation_method', sanitize_text_field($_POST['product_calculation_method']));
        }

        // Save minimum order
        if (isset($_POST['product_minimum_order'])) {
            update_post_meta($post_id, 'product_minimum_order', absint($_POST['product_minimum_order']));
        }
    }
}
