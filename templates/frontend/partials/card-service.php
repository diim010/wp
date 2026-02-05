<?php

/**
 * Card Component - Service
 *
 * Reusable service card for archive and related sections.
 * Uses corporate design system th-* classes.
 *
 * @package RFPlugin
 * @since 2.0.0
 *
 * @param WP_Post $post (passed via $args)
 */

defined('ABSPATH') || exit;

$post = $args['post'] ?? get_post();
$post_id = $post->ID;
$categories = get_the_terms($post_id, 'rf_service_category');
$primary_category = ($categories && !is_wp_error($categories)) ? $categories[0] : null;
$excerpt = get_the_excerpt($post);
?>

<article class="group relative flex flex-col h-full bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200 rf-animate-up"
    itemscope
    itemtype="https://schema.org/Service">

    <?php if (has_post_thumbnail($post)) : ?>
        <div class="relative w-full aspect-video overflow-hidden bg-slate-100">
            <?php echo get_the_post_thumbnail($post, 'medium_large', [
                'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
                'loading' => 'lazy',
                'itemprop' => 'image',
            ]); ?>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>
    <?php endif; ?>

    <div class="flex flex-col flex-grow p-6">
        <?php if ($primary_category) : ?>
            <div class="mb-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    <?php echo esc_html($primary_category->name); ?>
                </span>
            </div>
        <?php endif; ?>

        <h3 class="text-xl font-bold text-slate-900 mb-2 line-clamp-2" itemprop="name">
            <a href="<?php echo esc_url(get_permalink($post)); ?>" class="focus:outline-none">
                <span class="absolute inset-0" aria-hidden="true"></span>
                <?php echo esc_html(get_the_title($post)); ?>
            </a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="text-sm text-slate-500 leading-relaxed mb-4 line-clamp-3" itemprop="description">
                <?php echo esc_html(wp_trim_words($excerpt, 20)); ?>
            </p>
        <?php endif; ?>

        <div class="mt-auto pt-4 border-t border-slate-50">
            <span class="inline-flex items-center text-sm font-semibold text-blue-600 group-hover:text-blue-700 transition-colors">
                <?php esc_html_e('Learn More', 'rfplugin'); ?>
                <span class="dashicons dashicons-arrow-right-alt2 ml-1 transition-transform group-hover:translate-x-1" aria-hidden="true"></span>
            </span>
        </div>
    </div>

    <meta itemprop="url" content="<?php echo esc_url(get_permalink($post)); ?>">
</article>