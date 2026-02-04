<?php
/**
 * Template Name: Technical Center
 * 
 * Unified search and filter hub for FAQs and Technical Documentation.
 * 
 * @package RFPlugin
 */

get_header();

// Fetch all resource types for tabs/filters
$resource_types = get_terms(['taxonomy' => 'rf_resource_type', 'hide_empty' => false]);
$resource_categories = get_terms(['taxonomy' => 'rf_resource_category', 'hide_empty' => true]);

$rf_data = [
    'restUrl' => rest_url('rfplugin/v1/resources'),
    'nonce' => wp_create_nonce('wp_rest'),
    'placeholderImg' => RFPLUGIN_URL . 'assets/images/doc-placeholder.png',
];
?>

<div class="rf-tech-center rf-premium-ui" 
     data-config='<?php echo json_encode($rf_data); ?>'>
    
    <!-- Background Decoration -->
    <div class="rf-bg-blob rf-bg-blob-1"></div>
    <div class="rf-bg-blob rf-bg-blob-2"></div>

    <div class="rf-container">
        <header class="rf-header">
            <span class="rf-badge">
                <span class="dashicons dashicons-shield-alt" aria-hidden="true" style="font-size: 16px; margin-right: 6px;"></span>
                <?php _e('Support Hub', 'rfplugin'); ?>
            </span>
            <h1 class="rf-title"><?php _e('Resource Library', 'rfplugin'); ?></h1>
            <p class="rf-subtitle"><?php _e('Unified access to technical manuals, video guides, FAQs, and 3D specifications.', 'rfplugin'); ?></p>
        </header>

        <div class="rf-search-bar" role="search">
            <div class="rf-input-wrapper">
                <span class="dashicons dashicons-search" aria-hidden="true"></span>
                <input type="text" id="rf-unified-search" class="rf-input" 
                       placeholder="<?php _e('Search across all resources...', 'rfplugin'); ?>"
                       aria-label="<?php _e('Search Technical Center', 'rfplugin'); ?>">
            </div>

            <select id="rf-resource-cat" class="rf-filter-dropdown" aria-label="<?php _e('Filter by Category', 'rfplugin'); ?>">
                <option value=""><?php _e('All Categories', 'rfplugin'); ?></option>
                <?php foreach ($resource_categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <nav class="rf-tabs" role="tablist">
            <button class="rf-tab active" data-target="all" role="tab" aria-selected="true"><?php _e('All Assets', 'rfplugin'); ?></button>
            <button class="rf-tab" data-target="faq" role="tab" aria-selected="false"><?php _e('FAQs', 'rfplugin'); ?></button>
            <button class="rf-tab" data-target="document" role="tab" aria-selected="false"><?php _e('Tech Docs', 'rfplugin'); ?></button>
            <button class="rf-tab" data-target="video" role="tab" aria-selected="false"><?php _e('Video Guides', 'rfplugin'); ?></button>
            <button class="rf-tab" data-target="3d" role="tab" aria-selected="false"><?php _e('3D Models', 'rfplugin'); ?></button>
        </nav>

        <section id="rf-results-area" class="rf-content-section active">
            <div class="rf-results-grid" id="rf-resource-grid">
                <!-- Data injected via JS -->
            </div>
        </section>
    </div>
</div>

<?php 
get_footer(); ?>
