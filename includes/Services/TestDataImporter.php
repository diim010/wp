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
                // Handle Category/Taxonomy
                if ($taxonomy && isset($item->category)) {
                    $cat_name = (string)$item->category;
                    $this->assignTerm($post_id, $cat_name, $taxonomy);
                }

                // Handle Tags (Generalized)
                $tag_taxonomy = $post_type . '_tag';
                if (isset($item->tags)) {
                    foreach ($item->tags->tag as $tag) {
                        $this->assignTerm($post_id, (string)$tag, $tag_taxonomy);
                    }
                }

                // Handle ACF Fields
                if (isset($item->fields)) {
                    foreach ($item->fields->children() as $key => $value) {
                        // Support both field names and field keys
                        update_field((string)$key, (string)$value, $post_id);
                    }
                }

                // Handle Attached Docs (Relationships by Title)
                if (isset($item->attached_docs)) {
                    $doc_ids = [];
                    foreach ($item->attached_docs->title as $doc_title) {
                        $doc = get_page_by_title((string)$doc_title, OBJECT, ['rf_techdoc', 'rf_service', 'product']);
                        if ($doc) {
                            $doc_ids[] = $doc->ID;
                        }
                    }
                    if (!empty($doc_ids)) {
                        update_field('attached_docs', $doc_ids, $post_id);
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
