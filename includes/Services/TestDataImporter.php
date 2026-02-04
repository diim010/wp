<?php
/**
 * Test Data Importer Service
 * 
 * Generates and inserts sample data for the plugin.
 * 
 * @package RFPlugin\Services
 * @since 1.0.0
 */

namespace RFPlugin\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TestDataImporter class
 */
class TestDataImporter
{
    /**
     * Data directory path
     * 
     * @var string
     */
    private string $dataDir;

    public function __construct()
    {
        $this->dataDir = RFPLUGIN_PATH . 'data/';
    }

    /**
     * Import test data from XML for a specific post type
     * 
     * @param string $post_type
     * @param string $taxonomy Optional taxonomy for categories
     * @return array<string, mixed> Result of the import
     */
    public function importFromXML(string $post_type, string $taxonomy = ''): array
    {
        $filename = $this->getFilenameForPostType($post_type);
        $file_path = $this->dataDir . $filename;

        if (!file_exists($file_path)) {
            return [
                'success' => false,
                'message' => sprintf(__('Data file %s not found.', 'rfplugin'), $filename),
            ];
        }

        $xml = simplexml_load_file($file_path);
        if (!$xml) {
            return [
                'success' => false,
                'message' => __('Failed to parse XML file.', 'rfplugin'),
            ];
        }

        $count = 0;
        foreach ($xml->item as $item) {
            $post_id = wp_insert_post([
                'post_title'   => (string)$item->title,
                'post_content' => (string)$item->content,
                'post_status'  => 'publish',
                'post_type'    => $post_type,
            ]);

            if ($post_id && !is_wp_error($post_id)) {
                // Handle Resource Mode (rf_resource only)
                if ($post_type === 'rf_resource' && isset($item->mode)) {
                    update_field('field_resource_mode', (string)$item->mode, $post_id);
                }

                // Handle Generic Category Taxonomy
                $cat_tax = $taxonomy;
                if ($post_type === 'rf_resource') {
                    $cat_tax = 'rf_resource_category';
                }

                if ($cat_tax && isset($item->category)) {
                    $this->assignTerm($post_id, (string)$item->category, $cat_tax);
                }

                // Handle Resource Type Taxonomy (rf_resource only)
                if ($post_type === 'rf_resource' && isset($item->type)) {
                    $this->assignTerm($post_id, (string)$item->type, 'rf_resource_type');
                }

                // Handle Tags (Generalized)
                $tag_taxonomy = $post_type . '_tag';
                if ($post_type === 'rf_resource') {
                    // Resources don't have separate tags yet, but we could add them if needed
                }

                if (isset($item->tags)) {
                    foreach ($item->tags->tag as $tag) {
                        $this->assignTerm($post_id, (string)$tag, $tag_taxonomy);
                    }
                }

                // Handle ACF & Meta Fields
                if (isset($item->fields)) {
                    foreach ($item->fields->children() as $key => $value) {
                        $meta_key = (string)$key;
                        $meta_value = (string)$value;
                        
                        // Support standard WP meta for underscored keys (like _price)
                        if (str_starts_with($meta_key, '_')) {
                            update_post_meta($post_id, $meta_key, $meta_value);
                            // Ensure regular price is set for WooCommerce
                            if ($meta_key === '_price' && $post_type === 'product') {
                                update_post_meta($post_id, '_regular_price', $meta_value);
                            }
                        } else {
                            update_field($meta_key, $meta_value, $post_id);
                        }
                    }
                }

                // Handle Relationships (Unified)
                if (isset($item->attached_docs) || isset($item->related_items)) {
                    $rel_ids = [];
                    $rel_source = $item->attached_docs ?? $item->related_items;
                    foreach ($rel_source->title as $title) {
                        $p = get_page_by_title((string)$title, OBJECT, ['rf_resource', 'product']);
                        if ($p) {
                            $rel_ids[] = $p->ID;
                        }
                    }
                    if (!empty($rel_ids)) {
                        $field_name = ($post_type === 'rf_resource') ? 'related_items' : 'attached_docs';
                        update_field($field_name, $rel_ids, $post_id);
                    }
                }
                
                $count++;
            }
        }

        return [
            'success' => true,
            'count'   => $count,
            'message' => sprintf(__('Imported %d items for %s.', 'rfplugin'), $count, $post_type),
        ];
    }

    /**
     * Helper to assign a term to a post, creating it if it doesn't exist
     */
    private function assignTerm(int $post_id, string $term_name, string $taxonomy): void
    {
        $term = get_term_by('name', $term_name, $taxonomy);
        if (!$term) {
            $term = wp_insert_term($term_name, $taxonomy);
            $term_id = is_array($term) ? ($term['term_id'] ?? 0) : $term;
        } else {
            $term_id = $term->term_id;
        }

        if ($term_id && !is_wp_error($term_id)) {
            wp_set_post_terms($post_id, [$term_id], $taxonomy, true);
        }
    }

    /**
     * Helper to get filename for post type
     */
    private function getFilenameForPostType(string $post_type): string
    {
        return str_replace('rf_', '', $post_type) . 's.xml';
    }

    /**
     * Legacy wrapper for FAQs
     */
    public function importFAQs(): array
    {
        return $this->importFromXML('rf_faq', 'rf_faq_category');
    }
}
