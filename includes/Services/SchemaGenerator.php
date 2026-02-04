<?php
/**
 * Schema Generator Service
 * 
 * Generates JSON-LD structured data for SEO and AI Search optimization.
 * 
 * @package RFPlugin\Services
 * @since 1.0.0
 */

namespace RFPlugin\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SchemaGenerator class
 */
class SchemaGenerator
{
    /**
     * Initialize the generator hooks
     */
    public function register(): void
    {
        add_action('wp_head', [$this, 'outputSchema'], 20);
    }

    /**
     * Output appropriate schema in the head
     */
    public function outputSchema(): void
    {
        $schema = [];

        if (is_singular('rf_resource')) {
            $schema = $this->generateResourceSchema();
        } elseif (is_singular('product')) {
            $schema = $this->generateProductSchema();
        }

        // Add Breadcrumbs if not home/front
        if (!is_front_page() && !is_home()) {
            $breadcrumbs = $this->generateBreadcrumbSchema();
            if ($breadcrumbs) {
                $this->renderJsonLd($breadcrumbs, 'breadcrumbs');
            }
        }

        if (!empty($schema)) {
            $this->renderJsonLd($schema, 'main-item');
        }
    }

    /**
     * Generate Schema for Resource Post Type (FAQ, Doc)
     */
    private function generateResourceSchema(): array
    {
        global $post;
        $mode = get_field('field_resource_mode', $post->ID) ?: 'document';

        if ($mode === 'faq') {
            return [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name' => get_the_title(),
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => wp_strip_all_tags(get_field('field_resource_answer', $post->ID))
                        ]
                    ]
                ]
            ];
        }

        // Default TechnicalArticle for documentation
        return [
            '@context' => 'https://schema.org',
            '@type' => 'TechnicalArticle',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'author' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name')
            ],
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c')
        ];
    }

    /**
     * Generate Schema for WooCommerce Product
     */
    private function generateProductSchema(): array
    {
        if (!function_exists('wc_get_product')) return [];
        
        $product = wc_get_product(get_the_ID());
        if (!$product) return [];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($post->post_excerpt ?: $post->post_content),
            'sku' => $product->get_sku(),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => 'https://schema.org/' . ($product->is_in_stock() ? 'InStock' : 'OutOfStock')
            ]
        ];
    }

    /**
     * Generate Breadcrumb Schema
     */
    private function generateBreadcrumbSchema(): array
    {
        $items = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => __('Home', 'rfplugin'),
                'item' => home_url('/')
            ]
        ];

        if (is_singular()) {
            $post_type = get_post_type();
            $post_type_obj = get_post_type_object($post_type);
            
            if ($post_type_obj && $post_type_obj->has_archive) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $post_type_obj->labels->name,
                    'item' => get_post_type_archive_link($post_type)
                ];
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => get_the_title(),
                    'item' => get_permalink()
                ];
            } else {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => get_the_title(),
                    'item' => get_permalink()
                ];
            }
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }

    /**
     * Render data as JSON-LD script tag
     */
    private function renderJsonLd(array $data, string $id): void
    {
        echo "\n<!-- RoyalFoam SEO Structured Data ($id) -->\n";
        echo '<script type="application/ld+json" class="rf-schema-' . esc_attr($id) . '">' . "\n";
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo "\n</script>\n";
    }
}
