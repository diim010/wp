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


<div class="rf-tech-center th-corp-mode" 
     data-config='<?php echo json_encode($rf_data); ?>'>
    
    <!-- Background Decoration -->
    <div class="rf-bg-blob rf-bg-blob-1"></div>
    <div class="rf-bg-blob rf-bg-blob-2"></div>

    <div class="th-container th-py-6">
        <header class="th-text-center th-mb-7">
            <span class="th-badge th-badge--primary th-mb-3">
                <span class="dashicons dashicons-shield-alt" aria-hidden="true" style="font-size: 16px; margin-right: 6px;"></span>
                <?php _e('Support Hub', 'rfplugin'); ?>
            </span>
            <h1 class="th-h1 th-mb-4"><?php _e('Resource Library', 'rfplugin'); ?></h1>
            <p class="th-lead th-text-muted th-mx-auto" style="max-width: 600px;">
                <?php _e('Unified access to technical manuals, video guides, FAQs, and 3D specifications.', 'rfplugin'); ?>
            </p>
        </header>

        <div class="th-card th-p-6 th-mb-8" role="search">
            <div class="th-grid th-grid-cols-1 md:th-grid-cols-3 th-gap-4">
                <div class="th-col-span-2 th-relative">
                    <span class="dashicons dashicons-search th-text-muted th-absolute th-top-3 th-left-3" aria-hidden="true"></span>
                    <input type="text" id="rf-unified-search" class="th-input th-pl-10" 
                           placeholder="<?php _e('Search across all resources...', 'rfplugin'); ?>"
                           style="padding-left: 2.5rem;"
                           aria-label="<?php _e('Search Technical Center', 'rfplugin'); ?>">
                </div>

                <select id="rf-resource-cat" class="th-input" aria-label="<?php _e('Filter by Category', 'rfplugin'); ?>">
                    <option value=""><?php _e('All Categories', 'rfplugin'); ?></option>
                    <?php foreach ($resource_categories as $cat): ?>
                        <option value="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <nav class="th-flex th-justify-center th-flex-wrap th-gap-2 th-mb-8" role="tablist">
            <button class="th-btn th-btn--primary active" data-target="all" role="tab" aria-selected="true"><?php _e('All Assets', 'rfplugin'); ?></button>
            <button class="th-btn th-btn--ghost" data-target="faq" role="tab" aria-selected="false"><?php _e('FAQs', 'rfplugin'); ?></button>
            <button class="th-btn th-btn--ghost" data-target="document" role="tab" aria-selected="false"><?php _e('Tech Docs', 'rfplugin'); ?></button>
            <button class="th-btn th-btn--ghost" data-target="video" role="tab" aria-selected="false"><?php _e('Video Guides', 'rfplugin'); ?></button>
            <button class="th-btn th-btn--ghost" data-target="3d" role="tab" aria-selected="false"><?php _e('3D Models', 'rfplugin'); ?></button>
        </nav>

        <section id="rf-results-area" class="rf-content-section active">
            <div class="th-grid th-grid-cols-1 md:th-grid-cols-3 th-gap-6" id="rf-resource-grid">
                <!-- Data injected via JS -->
            </div>
        </section>
    </div>
</div>

<style>
/* Active State for Tabs (Simple Override) */
.th-btn[aria-selected="true"] {
    background-color: var(--th-primary);
    color: white;
    border-color: var(--th-primary);
}
</style>

<?php 
get_footer(); ?>
