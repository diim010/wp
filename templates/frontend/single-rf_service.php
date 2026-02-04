<?php
/**
 * Single Service Template
 */

get_header(); 

$price = get_field('field_service_price');
$model = get_field('field_service_pricing_model');
?>

<div class="rf-single-service rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-1"></div>

    <div class="rf-container" style="padding: 100px 0;">
        <nav class="rf-breadcrumb" style="margin-bottom: 40px;">
            <a href="<?php echo get_post_type_archive_link('rf_service'); ?>"><?php _e('Services', 'rfplugin'); ?></a>
            <span class="sep">/</span>
            <span class="current"><?php the_title(); ?></span>
        </nav>

        <div class="rf-service-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 60px;">
            <main class="rf-main-content">
                <h1 class="rf-title" style="font-size: 3rem; margin-bottom: 24px;"><?php the_title(); ?></h1>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="rf-hero-image" style="border-radius: 24px; overflow: hidden; margin-bottom: 40px; box-shadow: var(--rf-shadow-premium);">
                        <?php the_post_thumbnail('full', ['style' => 'width: 100%; display: block;']); ?>
                    </div>
                <?php endif; ?>

                <div class="rf-content-body rf-glass-card" style="padding: 40px;">
                    <?php the_content(); ?>
                </div>
            </main>

            <aside class="rf-sidebar">
                <div class="rf-glass-card rf-sticky-widget" style="padding: 32px; position: sticky; top: 120px;">
                    <h3 class="rf-h3" style="margin-bottom: 24px;"><?php _e('Service Details', 'rfplugin'); ?></h3>
                    
                    <div class="rf-price-box" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; margin-bottom: 30px; text-align: center;">
                        <span style="display: block; font-size: 0.9rem; color: #94a3b8; margin-bottom: 8px;"><?php _e('Starting From', 'rfplugin'); ?></span>
                        <?php if ($price) : ?>
                            <span style="font-size: 2.5rem; font-weight: 800; color: white; line-height: 1;">€<?php echo number_format((float)$price, 2); ?></span>
                            <?php if ($model) : ?>
                                <span style="display: block; font-size: 0.9rem; color: var(--rf-primary); margin-top: 8px; text-transform: capitalize;"><?php echo esc_html($model); ?></span>
                            <?php endif; ?>
                        <?php else : ?>
                            <span style="font-size: 1.5rem; font-weight: 700; color: white;"><?php _e('Custom Quote', 'rfplugin'); ?></span>
                        <?php endif; ?>
                    </div>

                    <a href="#quote-form" class="rf-btn rf-btn-primary rf-btn-block" style="width: 100%; justify-content: center; margin-bottom: 16px;">
                        <?php _e('Request Service', 'rfplugin'); ?>
                    </a>
                    
                    <a href="<?php echo get_post_type_archive_link('rf_case'); ?>" class="rf-btn rf-btn-outline rf-btn-block" style="width: 100%; justify-content: center;">
                        <?php _e('View Related Work', 'rfplugin'); ?>
                    </a>

                    <div class="rf-features-list" style="margin-top: 30px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <span class="dashicons dashicons-yes" style="color: var(--rf-primary);"></span>
                            <span style="color: #cbd5e1;"><?php _e('Professional Consultation', 'rfplugin'); ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <span class="dashicons dashicons-yes" style="color: var(--rf-primary);"></span>
                            <span style="color: #cbd5e1;"><?php _e('Quality Assurance', 'rfplugin'); ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="dashicons dashicons-yes" style="color: var(--rf-primary);"></span>
                            <span style="color: #cbd5e1;"><?php _e('Full Support', 'rfplugin'); ?></span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>
