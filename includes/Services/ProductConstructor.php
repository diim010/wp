<?php
/**
 * Product Constructor Service
 * 
 * Aggregates product data with specifications, materials,
 * services, and related cases to create invoice-ready JSON.
 * 
 * @package RFPlugin\Services
 * @since 1.0.0
 */

namespace RFPlugin\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ProductConstructor class
 * 
 * @since 1.0.0
 */
class ProductConstructor
{
    /**
     * Construct product with specifications
     * 
     * @param int $productId Product post ID
     * @param array<string, mixed> $specifications User-defined specifications
     * @return array<string, mixed>|\WP_Error
     */
    public function construct(int $productId, array $specifications = [])
    {
        $product = wc_get_product($productId);

        if (!$product || $product->get_type() !== 'simple' && $product->get_type() !== 'variable') {
            return new \WP_Error(
                'invalid_product',
                __('Product not found or invalid type', 'rfplugin'),
                ['status' => 404]
            );
        }

        $constructedData = [
            'product' => $this->getProductBaseData($product),
            'specifications' => $this->processSpecifications($productId, $specifications),
            'materials' => $this->getMaterials($productId),
            'calculated' => $this->calculateDimensions($specifications),
            'related_cases' => $this->getRelatedCases($productId),
            'available_services' => $this->getAvailableServices(),
            'tech_files' => $this->getTechFiles($productId),
            'metadata' => [
                'constructed_at' => current_time('mysql'),
                'version' => '1.0',
            ],
        ];

        return $constructedData;
    }

    /**
     * Get product base data
     * 
     * @param \WC_Product $product WooCommerce Product object
     * @return array<string, mixed>
     */
    private function getProductBaseData(\WC_Product $product): array
    {
        return [
            'id' => $product->get_id(),
            'title' => $product->get_name(),
            'description' => $product->get_description(),
            'excerpt' => $product->get_short_description(),
            'thumbnail' => get_the_post_thumbnail_url($product->get_id(), 'large'),
            'sku' => $product->get_sku(),
            'price' => $product->get_price(),
        ];
    }

    /**
     * Process and validate specifications
     * 
     * @param int $productId Product ID
     * @param array<string, mixed> $userSpecs User-provided specifications
     * @return array<string, mixed>
     */
    private function processSpecifications(int $productId, array $userSpecs): array
    {
        $product = wc_get_product($productId);
        $defaultSpecs = [
            'height' => $product->get_height(),
            'width' => $product->get_width(),
            'length' => $product->get_length(),
            'density' => get_field('density', $productId),
            'color' => get_field('color', $productId),
        ];
        
        $processed = [
            'height' => $this->sanitizeDimension($userSpecs['height'] ?? $defaultSpecs['height'] ?? 0),
            'width' => $this->sanitizeDimension($userSpecs['width'] ?? $defaultSpecs['width'] ?? 0),
            'length' => $this->sanitizeDimension($userSpecs['length'] ?? $defaultSpecs['length'] ?? 0),
            'density' => sanitize_text_field($userSpecs['density'] ?? $defaultSpecs['density'] ?? ''),
            'color' => sanitize_text_field($userSpecs['color'] ?? $defaultSpecs['color'] ?? ''),
            'custom_notes' => sanitize_textarea_field($userSpecs['custom_notes'] ?? ''),
        ];

        return $processed;
    }

    /**
     * Sanitize dimension value
     * 
     * @param mixed $value Dimension value
     * @return float
     */
    private function sanitizeDimension($value): float
    {
        return max(0, floatval($value));
    }

    /**
     * Get product materials
     * 
     * @param int $productId Product ID
     * @return array<int, array<string, mixed>>
     */
    private function getMaterials(int $productId): array
    {
        $terms = wp_get_post_terms($productId, 'rf_material');
        $materials = [];

        foreach ($terms as $term) {
            $materials[] = [
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
                'pattern_image' => get_field('material_pattern', 'rf_material_' . $term->term_id),
            ];
        }

        return $materials;
    }

    /**
     * Calculate dimensions and volume
     * 
     * @param array<string, mixed> $specs Specifications
     * @return array<string, mixed>
     */
    private function calculateDimensions(array $specs): array
    {
        $height = floatval($specs['height'] ?? 0);
        $width = floatval($specs['width'] ?? 0);
        $length = floatval($specs['length'] ?? 0);

        $volume = $height * $width * $length;
        $surfaceArea = 2 * (($height * $width) + ($height * $length) + ($width * $length));

        return [
            'volume' => round($volume, 2),
            'surface_area' => round($surfaceArea, 2),
            'unit' => 'cm',
            'volume_unit' => 'cm³',
        ];
    }

    /**
     * Get related case studies
     * 
     * @param int $productId Product ID
     * @return array<int, array<string, mixed>>
     */
    private function getRelatedCases(int $productId): array
    {
        $relatedCaseIds = get_field('related_cases', $productId);
        
        if (!$relatedCaseIds || !is_array($relatedCaseIds)) {
            return [];
        }

        $cases = [];
        foreach ($relatedCaseIds as $caseId) {
            $case = get_post($caseId);
            if ($case) {
                $cases[] = [
                    'id' => $case->ID,
                    'title' => $case->post_title,
                    'excerpt' => $case->post_excerpt,
                    'thumbnail' => get_the_post_thumbnail_url($case->ID, 'medium'),
                    'url' => get_permalink($case->ID),
                ];
            }
        }

        return $cases;
    }

    /**
     * Get available services
     * 
     * @return array<int, array<string, mixed>>
     */
    private function getAvailableServices(): array
    {
        $query = new \WP_Query([
            'post_type' => 'rf_service',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        $services = [];
        foreach ($query->posts as $service) {
            $services[] = [
                'id' => $service->ID,
                'title' => $service->post_title,
                'description' => $service->post_excerpt,
                'price' => get_field('service_price', $service->ID),
            ];
        }

        return $services;
    }

    /**
     * Get technical files
     * 
     * @param int $productId Product ID
     * @return array<int, array<string, mixed>>
     */
    private function getTechFiles(int $productId): array
    {
        $files = get_field('tech_files', $productId);
        
        if (!$files || !is_array($files)) {
            return [];
        }

        $techFiles = [];
        foreach ($files as $file) {
            if (is_array($file)) {
                $techFiles[] = [
                    'url' => $file['url'] ?? '',
                    'filename' => $file['filename'] ?? '',
                    'type' => $file['mime_type'] ?? '',
                    'size' => $file['filesize'] ?? 0,
                ];
            }
        }

        return $techFiles;
    }
}
