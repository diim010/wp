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
     * Clear all existing posts for a specific post type before import
     */
    public function clearExistingData(string $post_type): void
    {
        $posts = get_posts([
            'post_type'      => $post_type,
            'post_status'    => ['any', 'trash'],
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ($posts as $post_id) {
            wp_delete_post($post_id, true);
        }
    }

    /**
     * Import test data from XML for a specific post type
     *
     * @param string $post_type
     * @param string $taxonomy Optional taxonomy for categories
     * @param bool $clear_existing Whether to clear existing data first
     * @return array<string, mixed> Result of the import
     */
    public function importFromXML(string $post_type, string $taxonomy = '', bool $clear_existing = true): array
    {
        if ($clear_existing) {
            $this->clearExistingData($post_type);
        }

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

        // Find items: support both <items><item> and <rss><channel><item>
        $items = $xml->xpath('//item');
        if (empty($items)) {
            return [
                'success' => false,
                'message' => __('No items found in XML.', 'rfplugin'),
            ];
        }

        $count = 0;
        foreach ($items as $item) {
            // Handle content namespaces for RSS-style WXR
            $namespaces = $item->getNamespaces(true);
            $content_encoded = '';
            if (isset($namespaces['content'])) {
                $content = $item->children($namespaces['content']);
                $content_encoded = (string)$content->encoded;
            }

            $excerpt_encoded = '';
            if (isset($namespaces['excerpt'])) {
                $excerpt = $item->children($namespaces['excerpt']);
                $excerpt_encoded = (string)$excerpt->encoded;
            }

            $post_data = [
                'post_title'   => (string)$item->title,
                'post_content' => $content_encoded ?: (string)$item->content,
                'post_excerpt' => $excerpt_encoded ?: (string)$item->excerpt,
                'post_status'  => 'publish',
                'post_type'    => $post_type,
            ];

            // Map standard WP fields if present in XML (mostly for WXR-lite)
            if (isset($item->status)) $post_data['post_status'] = (string)$item->status;
            if (isset($item->post_name)) $post_data['post_name'] = (string)$item->post_name;

            $post_id = wp_insert_post($post_data);

            if ($post_id && !is_wp_error($post_id)) {
                // Handle Resource Mode (rf_resource only)
                if ($post_type === 'rf_resource' && isset($item->fields->field_resource_mode)) {
                    update_field('field_resource_mode', (string)$item->fields->field_resource_mode, $post_id);
                }

                // Handle Taxonomy Categories
                $cat_tax = $taxonomy;
                if (!$cat_tax) {
                    if ($post_type === 'rf_resource') $cat_tax = 'rf_resource_category';
                    if ($post_type === 'product') $cat_tax = 'product_cat';
                    if ($post_type === 'rf_service') $cat_tax = 'rf_service_category';
                    if ($post_type === 'rf_case_study') $cat_tax = 'rf_case_industry';
                }

                // Handle RSS/WXR style categories
                if (isset($item->category)) {
                    foreach ($item->category as $cat) {
                        $domain = (string)$cat['domain'];
                        $name = (string)$cat;
                        if ($domain) {
                            $this->assignTerm($post_id, $name, $domain);
                        } elseif ($cat_tax) {
                            $this->assignTerm($post_id, $name, $cat_tax);
                        }
                    }
                }

                // Handle direct category (simple XML)
                if (isset($item->category) && count($item->category) == 0 && $cat_tax) {
                    $this->assignTerm($post_id, (string)$item->category, $cat_tax);
                }

                // Handle ACF & Meta Fields
                if (isset($item->fields)) {
                    foreach ($item->fields->children() as $key => $value) {
                        $meta_key = (string)$key;
                        $meta_value = (string)$value;

                        // Support standard WP meta for underscored keys
                        if (str_starts_with($meta_key, '_')) {
                            update_post_meta($post_id, $meta_key, $meta_value);
                            // Special handling for product prices
                            if ($meta_key === '_price' && $post_type === 'product') {
                                update_post_meta($post_id, '_regular_price', $meta_value);
                            }
                        } else {
                            // Check if it's a repeater or group (simplified)
                            if ($value->children()) {
                                // For test data we keep it simple, but we could handle complex arrays here
                                $meta_value = $this->recursive_xml_to_array($value);
                            }
                            update_field($meta_key, $meta_value, $post_id);
                        }
                    }
                }

                // Handle Relationships
                if (isset($item->attached_docs) || isset($item->related_items)) {
                    $rel_ids = [];
                    $rel_source = $item->attached_docs ?? $item->related_items;
                    foreach ($rel_source->title as $title) {
                        // Look for resources or products by title
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
            'message' => sprintf(__('Renewed %d items for %s.', 'rfplugin'), $count, $post_type),
        ];
    }

    /**
     * Helper to convert XML node to array recursively (for ACF repeaters)
     */
    private function recursive_xml_to_array(\SimpleXMLElement $node): array
    {
        $array = [];
        foreach ($node->children() as $child) {
            if ($child->children()) {
                $array[] = $this->recursive_xml_to_array($child);
            } else {
                $array[$child->getName()] = (string)$child;
            }
        }
        return $array;
    }

    /**
     * Helper to assign a term to a post, creating it if it doesn't exist
     */
    private function assignTerm(int $post_id, string $term_name, string $taxonomy): void
    {
        if (empty($term_name) || empty($taxonomy)) return;

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
        if ($post_type === 'rf_case_study') return 'cases.xml';
        return str_replace('rf_', '', $post_type) . 's.xml';
    }

    /**
     * Legacy wrapper for FAQs (now maps to unified Resource)
     */
    public function importFAQs(): array
    {
        return $this->importFromXML('rf_resource');
    }
}
