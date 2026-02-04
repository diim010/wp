<?php
/**
 * Archive Template for FAQ
 * 
 * @package RFPlugin
 */

get_header();

$categories = get_terms([
    'taxonomy' => 'rf_faq_category',
    'hide_empty' => true,
]);
?>

<div class="rf-faq-archive rf-premium-ui">
    <div class="rf-container">
        <!-- Decorative Elements -->
        <div class="rf-blob rf-blob-1" style="width: 500px; height: 500px; top: -100px; right: -100px; background: hsla(220, 90%, 50%, 0.05);"></div>
        
        <header class="rf-hero" style="margin-bottom: 80px; text-align: center; position: relative; z-index: 10;">
            <span class="rf-badge" style="margin-bottom: 24px;"><?php _e('Knowledge Base', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="margin-bottom: 24px; font-size: clamp(3rem, 8vw, 5rem);"><?php _e('How can we help?', 'rfplugin'); ?></h1>
            <p style="font-size: 1.25rem; color: var(--rf-text-muted); max-width: 650px; margin: 0 auto; line-height: 1.6;">
                <?php _e('Expert answers to your technical questions. For detailed specifications and manuals, visit our', 'rfplugin'); ?>
                <a href="<?php echo get_permalink(get_page_by_path('technical-center')); ?>" style="color: var(--rf-primary); font-weight: 800; text-decoration: none; border-bottom: 2px solid var(--rf-primary-light);"><?php _e('Technical Center', 'rfplugin'); ?></a>.
            </p>
        </header>

        <div class="rf-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 32px;">
            <?php 
            $i = 0;
            if (have_posts()): while (have_posts()): the_post(); 
                $item_id = get_the_ID();
                $item_cats = get_the_terms($item_id, 'rf_faq_category');
                $delay = ($i % 6) * 0.1;
            ?>
                <article class="rf-card" style="animation: rfFadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: <?php echo $delay; ?>s;">
                    <div class="rf-card-icon">
                        <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                    </div>
                    <h3 class="rf-card-title"><?php the_title(); ?></h3>
                    <div class="rf-card-excerpt">
                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                    </div>
                    <div class="rf-card-footer">
                        <div class="rf-card-meta" style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <?php if ($item_cats && !is_wp_error($item_cats)): ?>
                                <?php foreach (array_slice($item_cats, 0, 1) as $cat): ?>
                                    <span style="background: var(--rf-primary-light); color: var(--rf-primary); padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;">
                                        <?php echo esc_html($cat->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="rf-btn">
                            <?php _e('View Answer', 'rfplugin'); ?> <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true" style="margin-left: 8px;"></span>
                        </a>
                    </div>
                </article>
            <?php 
                $i++;
                endwhile; else: 
            ?>
                <div class="rf-empty-state" style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                    <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <span class="dashicons dashicons-search" style="font-size: 32px; width: 32px; height: 32px; color: #94a3b8;"></span>
                    </div>
                    <h4 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 12px;"><?php _e('No resources found', 'rfplugin'); ?></h4>
                    <p style="font-size: 1.1rem; color: #64748b; margin: 0;"><?php _e('Please check back later as we update our knowledge base.', 'rfplugin'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
