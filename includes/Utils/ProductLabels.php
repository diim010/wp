<?php
/**
 * Product Label Utility
 * 
 * Handles generation of badges and labels for products
 * based on their status and custom meta fields.
 * 
 * @package RFPlugin\Utils
 * @since 1.0.0
 */

namespace RFPlugin\Utils;

if (!defined('ABSPATH')) {
    exit;
}

class ProductLabels
{
    /**
     * Get product labels (badges)
     * 
     * @param int|\WC_Product $product Product ID or object
     * @return array<array<string, string>> Array of label data
     */
    public static function getLabels($product): array
    {
        if (is_numeric($product)) {
            $product = wc_get_product($product);
        }

        if (!$product) {
            return [];
        }

        $labels = [];

        // Sale label
        if ($product->is_on_sale()) {
            $labels[] = [
                'type' => 'sale',
                'text' => __('Sale!', 'rfplugin'),
                'class' => 'rf-badge-sale',
            ];
        }

        // New label (based on date)
        $date_created = $product->get_date_created();
        if ($date_created && (time() - $date_created->getTimestamp()) < (30 * DAY_IN_SECONDS)) {
            $labels[] = [
                'type' => 'new',
                'text' => __('New', 'rfplugin'),
                'class' => 'rf-badge-new',
            ];
        }

        // Featured label
        if ($product->is_featured()) {
            $labels[] = [
                'type' => 'featured',
                'text' => __('Featured', 'rfplugin'),
                'class' => 'rf-badge-featured',
            ];
        }

        // Custom labels from ACF
        $custom_badges = get_field('product_badges', $product->get_id());
        if ($custom_badges && is_array($custom_badges)) {
            foreach ($custom_badges as $badge) {
                $labels[] = [
                    'type' => 'custom',
                    'text' => $badge['text'],
                    'class' => 'rf-badge-custom',
                    'color' => $badge['color'] ?? '',
                    'position' => $badge['position'] ?? 'top-left',
                ];
            }
        }

        return $labels;
    }

    /**
     * Render product labels HTML
     * 
     * @param int|\WC_Product $product Product ID or object
     * @return string
     */
    public static function render($product): string
    {
        $labels = self::getLabels($product);
        if (empty($labels)) {
            return '';
        }

        $html = '<div class="rf-product-labels">';
        foreach ($labels as $label) {
            $style = !empty($label['color']) ? ' style="background-color: ' . esc_attr($label['color']) . '; color: white;"' : '';
            $html .= sprintf(
                '<span class="rf-badge %s"%s>%s</span>',
                esc_attr($label['class']),
                $style,
                esc_html($label['text'])
            );
        }
        $html .= '</div>';

        return $html;
    }
}
