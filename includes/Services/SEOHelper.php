<?php

/**
 * SEO Helper Class
 *
 * Provides JSON-LD schema markup, meta tags, and SEO optimizations
 * for all custom post types
 *
 * @package RFPlugin\Services
 * @since 1.0.0
 */

namespace RFPlugin\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEOHelper class
 *
 * @since 1.0.0
 */
class SEOHelper
{
    /**
     * Generate Service schema markup
     *
     * @param int $post_id Post ID
     * @return string JSON-LD script tag
     */
    public static function generateServiceSchema(int $post_id): string
    {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'rf_service') {
            return '';
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => get_the_title($post_id),
            'description' => get_the_excerpt($post_id) ?: wp_trim_words(get_the_content(null, false, $post), 55),
            'provider' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
            ],
            'url' => get_permalink($post_id),
        ];

        // Add pricing if available
        $pricing_model = get_field('pricing_model', $post_id);
        $base_price = get_field('base_price', $post_id);

        if ($pricing_model && $pricing_model !== 'contact' && $base_price) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $base_price,
                'priceCurrency' => get_option('woocommerce_currency', 'USD'),
            ];
        }

        // Add categories
        $categories = wp_get_post_terms($post_id, 'rf_service_category', ['fields' => 'names']);
        if (!empty($categories) && !is_wp_error($categories)) {
            $schema['serviceType'] = implode(', ', $categories);
        }

        return self::wrapSchema($schema);
    }

    /**
     * Generate Case Study schema markup
     *
     * @param int $post_id Post ID
     * @return string JSON-LD script tag
     */
    public static function generateCaseStudySchema(int $post_id): string
    {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'rf_case_study') {
            return '';
        }

        $client_name = get_field('client_name', $post_id);
        $project_date = get_field('project_date', $post_id);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => get_the_title($post_id),
            'description' => get_the_excerpt($post_id) ?: wp_trim_words(get_the_content(null, false, $post), 55),
            'author' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
            ],
            'url' => get_permalink($post_id),
        ];

        if ($client_name) {
            $schema['about'] = [
                '@type' => 'Organization',
                'name' => $client_name,
            ];
        }

        if ($project_date) {
            $schema['datePublished'] = $project_date;
        }

        // Add industry
        $industries = wp_get_post_terms($post_id, 'rf_case_industry', ['fields' => 'names']);
        if (!empty($industries) && !is_wp_error($industries)) {
            $schema['genre'] = implode(', ', $industries);
        }

        return self::wrapSchema($schema);
    }

    /**
     * Generate breadcrumb schema
     *
     * @param array $items Breadcrumb items [['name' => '...', 'url' => '...']]
     * @return string JSON-LD script tag
     */
    public static function generateBreadcrumbSchema(array $items): string
    {
        $listItems = [];
        foreach ($items as $position => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];

        return self::wrapSchema($schema);
    }

    /**
     * Inject Open Graph and Twitter Card meta tags
     *
     * @param int $post_id Post ID
     * @return void
     */
    public static function injectMetaTags(int $post_id): void
    {
        $post = get_post($post_id);
        if (!$post) {
            return;
        }

        $title = get_the_title($post_id);
        $description = get_the_excerpt($post_id) ?: wp_trim_words(get_the_content(null, false, $post), 55);
        $url = get_permalink($post_id);
        $image = get_the_post_thumbnail_url($post_id, 'large') ?: '';

        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
        echo '<met property="og:type" content="website">' . "\n";

        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        }

        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
        }
    }

    /**
     * Wrap schema array in JSON-LD script tag
     *
     * @param array $schema Schema data
     * @return string Script tag
     */
    private static function wrapSchema(array $schema): string
    {
        return sprintf(
            '<script type="application/ld+json">%s</script>',
            wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );
    }
}
