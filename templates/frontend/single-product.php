<?php
/**
 * Single Product Template (Premium Edition)
 * 
 * Custom premium view for WooCommerce products using glassmorphism
 * and consistent design with the RFPlugin ecosystem.
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="rf-single-product rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-1"></div>
    <div class="rf-bg-blob rf-bg-blob-2"></div>

    <div class="rf-container" style="padding: 100px 0;">
        <?php while (have_posts()) : the_post(); 
            global $product;
            $product_id = get_the_ID();
            $specs = get_field('technical_specifications', $product_id);
            $tech_files = get_field('tech_files', $product_id);
            $badges = get_field('product_badges', $product_id);
        ?>
            <nav class="rf-breadcrumb" style="margin-bottom: 40px;">
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><?php _e('Solutions', 'rfplugin'); ?></a>
                <span class="sep">/</span>
                <span class="current"><?php the_title(); ?></span>
            </nav>

            <div class="rf-product-grid" style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 60px; align-items: start;">
                <!-- Left: Visuals & Description -->
                <div class="rf-product-main">
                    <div class="rf-visual-container rf-glass-card" style="padding: 40px; margin-bottom: 40px; position: relative; border-radius: 32px;">
                        <?php if ($badges): foreach ($badges as $badge): ?>
                            <span class="rf-custom-badge" style="position: absolute; top: 20px; left: 20px; background: <?php echo $badge['color'] ?: 'var(--rf-primary)'; ?>; padding: 6px 16px; border-radius: 99px; font-weight: 700; font-size: 11px; color: white;">
                                <?php echo esc_html($badge['text']); ?>
                            </span>
                        <?php endforeach; endif; ?>
                        
                        <div class="rf-main-image" style="border-radius: 20px; overflow: hidden; box-shadow: var(--rf-shadow-premium);">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', ['style' => 'width: 100%; height: auto; display: block;']); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="rf-product-content rf-glass-card" style="padding: 60px; border-radius: 32px;">
                        <h1 class="rf-title" style="margin-bottom: 32px;"><?php the_title(); ?></h1>
                        <div class="rf-description rf-p" style="font-size: 1.15rem; line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>

                <!-- Right: Specs & Actions -->
                <aside class="rf-product-sidebar">
                    <div class="rf-glass-card rf-cta-card" style="padding: 40px; margin-bottom: 40px; border: 1px solid rgba(255,255,255,0.2); background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));">
                        <div class="rf-price-box" style="margin-bottom: 32px;">
                            <span style="display: block; font-size: 0.9rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px;"><?php _e('Starting from', 'rfplugin'); ?></span>
                            <div class="rf-price" style="font-size: 3rem; font-weight: 800; color: white; line-height: 1;"><?php echo $product->get_price_html(); ?></div>
                        </div>

                        <div class="rf-actions">
                            <?php woocommerce_template_single_add_to_cart(); ?>
                        </div>
                        
                        <div class="rf-meta-footer" style="margin-top: 40px; padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; gap: 20px; justify-content: center;">
                             <span style="display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: 0.85rem;">
                                <span class="dashicons dashicons-shield"></span> <?php _e('Verified Compliance', 'rfplugin'); ?>
                             </span>
                             <span style="display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: 0.85rem;">
                                <span class="dashicons dashicons-external"></span> <?php _e('Global Shipping', 'rfplugin'); ?>
                             </span>
                        </div>
                    </div>

                    <?php if ($specs): ?>
                        <div class="rf-glass-card" style="padding: 40px; margin-bottom: 40px;">
                            <h3 class="rf-h4" style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                                <span class="dashicons dashicons-list-view" style="color: var(--rf-primary);"></span>
                                <?php _e('Technical Data', 'rfplugin'); ?>
                            </h3>
                            <div class="rf-specs-list" style="display: flex; flex-direction: column; gap: 16px;">
                                <?php foreach ($specs as $spec): ?>
                                    <div class="rf-spec-item" style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><?php echo esc_html($spec['label']); ?></span>
                                        <span style="color: white; font-weight: 700; font-size: 0.9rem;"><?php echo esc_html($spec['value']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($tech_files): ?>
                        <div class="rf-glass-card" style="padding: 40px;">
                            <h3 class="rf-h4" style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                                <span class="dashicons dashicons-download" style="color: var(--rf-primary);"></span>
                                <?php _e('Engineering Assets', 'rfplugin'); ?>
                            </h3>
                            <div class="rf-files-list" style="display: flex; flex-direction: column; gap: 12px;">
                                <?php foreach ($tech_files as $file_data): $file = $file_data['file']; ?>
                                    <?php if ($file): ?>
                                        <a href="<?php echo esc_url($file['url']); ?>" class="rf-file-link" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: rgba(255,255,255,0.05); border-radius: 12px; text-decoration: none; transition: 0.3s; color: white;">
                                            <span class="dashicons dashicons-pdf" style="color: #ef4444;"></span>
                                            <div style="display: flex; flex-direction: column;">
                                                <span style="font-weight: 700; font-size: 0.85rem; line-height: 1;"><?php echo esc_html($file['filename']); ?></span>
                                                <span style="font-size: 10px; color: #64748b; margin-top: 4px;"><?php echo strtoupper($file['subtype']); ?> • <?php echo size_format($file['filesize']); ?></span>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<style>
    .rf-file-link:hover { background: rgba(255,255,255,0.1) !important; transform: translateX(5px); }
    .rf-product-sidebar .rf-glass-card { border-radius: 24px; }
    
    /* Product Custom Styles Override */
    .rf-single-product .quantity { display: none !important; }
    .rf-single-product .button.alt { 
        width: 100%; justify-content: center; padding: 18px !important; border-radius: 16px !important;
        background: var(--rf-primary) !important; font-weight: 700 !important; font-size: 1.1rem !important;
        box-shadow: 0 20px 40px rgba(37, 99, 235, 0.3) !important; border: none !important;
    }
</style>

<?php get_footer(); ?>
