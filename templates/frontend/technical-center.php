<?php
/**
 * Template Name: Technical Center
 * 
 * Unified search and filter hub for FAQs and Technical Documentation.
 * 
 * @package RFPlugin
 */

get_header();

// Fetch categories and tags for filters
$faq_categories = get_terms(['taxonomy' => 'rf_faq_category', 'hide_empty' => true]);
$techdoc_tags = get_terms(['taxonomy' => 'rf_techdoc_tag', 'hide_empty' => true]);
$techdoc_types = [
    'manual' => __('Manuals', 'rfplugin'),
    'datasheet' => __('Data Sheets', 'rfplugin'),
    'guide' => __('Installation Guides', 'rfplugin'),
    'drawing' => __('Technical Drawings', 'rfplugin'),
    'certificate' => __('Certification', 'rfplugin'),
];

$rf_data = [
    'restUrl' => rest_url('rfplugin/v1'),
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
            <h1 class="rf-title"><?php _e('Technical Center', 'rfplugin'); ?></h1>
            <p class="rf-subtitle"><?php _e('The ultimate resource for RoyalFoam technical specifications, manuals, and expert guidance.', 'rfplugin'); ?></p>
        </header>

        <div class="rf-search-bar" role="search">
            <div class="rf-input-wrapper">
                <span class="dashicons dashicons-search" aria-hidden="true"></span>
                <input type="text" id="rf-unified-search" class="rf-input" 
                       placeholder="<?php _e('Search for answers or documents...', 'rfplugin'); ?>"
                       aria-label="<?php _e('Search Technical Center', 'rfplugin'); ?>">
            </div>

            <!-- FAQ Specific Filter -->
            <select id="rf-faq-cat" class="rf-filter-dropdown rf-faq-only" aria-label="<?php _e('Filter by Topic', 'rfplugin'); ?>">
                <option value=""><?php _e('All FAQ Topics', 'rfplugin'); ?></option>
                <?php foreach ($faq_categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></option>
                <?php endforeach; ?>
            </select>

            <!-- TechDoc Specific Filter -->
            <select id="rf-doc-type" class="rf-filter-dropdown rf-doc-only" style="display:none;" aria-label="<?php _e('Filter by Document Type', 'rfplugin'); ?>">
                <option value=""><?php _e('All Document Types', 'rfplugin'); ?></option>
                <?php foreach ($techdoc_types as $val => $label): ?>
                    <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <nav class="rf-tabs" role="tablist">
            <button class="rf-tab active" data-target="faq" role="tab" aria-selected="true" aria-controls="rf-section-faq">
                <?php _e('FAQs & Support', 'rfplugin'); ?>
            </button>
            <button class="rf-tab" data-target="docs" role="tab" aria-selected="false" aria-controls="rf-section-docs">
                <?php _e('Technical Library', 'rfplugin'); ?>
            </button>
        </nav>

        <section id="rf-section-faq" class="rf-content-section active" role="tabpanel" aria-labelledby="faq-tab">
            <div class="rf-results-grid" id="rf-results-faq">
                <!-- Initial Skeletons -->
                <?php for($i=0; $i<6; $i++): ?>
                    <div class="rf-card rf-skeleton-card">
                        <div class="rf-card-icon rf-skeleton" style="width: 56px; height: 56px; border-radius: 12px; margin-bottom: 24px;"></div>
                        <div class="rf-skeleton" style="height: 24px; width: 80%; margin-bottom: 16px;"></div>
                        <div class="rf-skeleton" style="height: 16px; width: 100%; margin-bottom: 8px;"></div>
                        <div class="rf-skeleton" style="height: 16px; width: 90%; margin-bottom: 24px;"></div>
                        <div class="rf-card-footer">
                            <div class="rf-skeleton" style="height: 48px; width: 120px; border-radius: 12px;"></div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

        <section id="rf-section-docs" class="rf-content-section" role="tabpanel" aria-labelledby="docs-tab">
            <div class="rf-results-grid" id="rf-results-docs">
                <!-- Data injected via JS -->
            </div>
        </section>
    </div>
</div>

<?php 
get_footer(); ?>
