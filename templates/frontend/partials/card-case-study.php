<?php

/**
 * Card Component - Case Study
 *
 * Reusable case study card for archive and related sections.
 *
 * @package RFPlugin
 * @since 2.0.0
 *
 * @param WP_Post $post (passed via $args)
 */

defined('ABSPATH') || exit;

$post = $args['post'] ?? get_post();
$post_id = $post->ID;
$industries = get_the_terms($post_id, 'rf_case_industry');
$primary_industry = ($industries && !is_wp_error($industries)) ? $industries[0] : null;
$excerpt = get_the_excerpt($post);
?>

<article class="th-card th-card--hoverable th-flex th-flex-col th-h-full th-animate-up"
    itemscope
    itemtype="https://schema.org/Article">

    <?php if (has_post_thumbnail($post)) : ?>
        <div class="th-relative th-overflow-hidden th-bg-gray-100" style="aspect-ratio: 16/9;">
            <?php echo get_the_post_thumbnail($post, 'large', [
                'class' => 'th-w-full th-h-full th-object-cover',
                'loading' => 'lazy',
                'itemprop' => 'image',
                'style' => 'object-fit: cover; width: 100%; height: 100%; display: block;'
            ]); ?>
            <?php if ($primary_industry) : ?>
                <span class="th-absolute th-top-4 th-right-4 th-badge th-badge--primary">
                    <?php echo esc_html($primary_industry->name); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="th-p-6 th-flex th-flex-col th-flex-grow">
        <h3 class="th-h4 th-mb-2" itemprop="headline">
            <a href="<?php echo esc_url(get_permalink($post)); ?>" class="th-no-underline th-text-inherit hover:th-text-primary th-transition">
                <?php echo esc_html(get_the_title($post)); ?>
            </a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="th-text-sm th-text-muted th-mb-4 th-line-clamp-3" itemprop="description">
                <?php echo esc_html(wp_trim_words($excerpt, 25)); ?>
            </p>
        <?php endif; ?>

        <div class="th-mt-auto">
            <a href="<?php echo esc_url(get_permalink($post)); ?>"
                class="th-btn th-btn--primary th-btn--sm">
                <?php esc_html_e('View Case Study', 'rfplugin'); ?>
            </a>
        </div>
    </div>
</article>