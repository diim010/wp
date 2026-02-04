<?php
/**
 * WooCommerce Hooks Integration
 * 
 * Handles frontend integrations with WooCommerce templates
 * including technical specifications and product badges.
 * 
 * @package RFPlugin\Core
 * @since 1.0.0
 */

namespace RFPlugin\Core;

use RFPlugin\Utils\ProductLabels;

if (!defined('ABSPATH')) {
    exit;
}

class WoocommerceHooks
{
    /**
     * Initialize the hooks
     */
    public static function init(): void
    {
        // Add badges to product loops
        add_action('woocommerce_before_shop_loop_item_title', [__CLASS__, 'renderBadges'], 15);
        
        // Add badges to single product
        add_action('woocommerce_before_single_product_summary', [__CLASS__, 'renderBadges'], 10);

        // Add technical specifications to single product
        add_action('woocommerce_product_additional_information', [__CLASS__, 'renderTechnicalSpecs'], 20);
        
        // Alternative: Add to a custom tab
        add_filter('woocommerce_product_tabs', [__CLASS__, 'addTechnicalSpecsTab']);
    }

    /**
     * Render product badges
     */
    public static function renderBadges(): void
    {
        global $product;
        if ($product) {
            echo ProductLabels::render($product);
        }
    }

    /**
     * Render technical specifications
     */
    public static function renderTechnicalSpecs(): void
    {
        global $product;
        $specs = get_field('technical_specifications', $product->get_id());

        if ($specs && is_array($specs)) {
            echo '<div class="rf-technical-specs" style="margin-top: 2rem;">';
            echo '<h3 class="rf-h2">' . __('Technical Specifications', 'rfplugin') . '</h3>';
            echo '<table class="rf-specs-table" style="width: 100%; border-collapse: collapse; margin-top: 1rem;">';
            echo '<tbody>';
            foreach ($specs as $spec) {
                echo '<tr style="border-bottom: 1px solid var(--rf-neutral-200);">';
                echo '<td style="padding: 12px 0; font-weight: 600; color: var(--rf-neutral-800); width: 40%;">' . esc_html($spec['label']) . '</td>';
                echo '<td style="padding: 12px 0; color: var(--rf-neutral-600);">' . esc_html($spec['value']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
        }
    }

    /**
     * Add Technical Specs as a separate tab
     */
    public static function addTechnicalSpecsTab($tabs): array
    {
        global $product;
        $specs = get_field('technical_specifications', $product->get_id());

        if ($specs) {
            $tabs['rf_technical_specs'] = [
                'title'    => __('Technical Specifications', 'rfplugin'),
                'priority' => 50,
                'callback' => [__CLASS__, 'renderTechnicalSpecsContent']
            ];
        }

        return $tabs;
    }

    /**
     * Callback for the Technical Specs tab
     */
    public static function renderTechnicalSpecsContent(): void
    {
        self::renderTechnicalSpecs();
    }
}
