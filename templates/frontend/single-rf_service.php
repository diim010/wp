<?php

/**
 * Single Service Template
 *
 * Optimized template using corporate design system.
 * No inline styles - all classes from rf-components.css.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

$service_id = get_the_ID();
$categories = get_the_terms($service_id, 'rf_service_category');
$primary_category = ($categories && !is_wp_error($categories)) ? $categories[0] : null;

// Build breadcrumb items
$breadcrumb_items = [
    ['label' => __('Home', 'rfplugin'), 'url' => home_url('/')],
    ['label' => __('Services', 'rfplugin'), 'url' => get_post_type_archive_link('rf_service')],
];
if ($primary_category) {
    $breadcrumb_items[] = ['label' => $primary_category->name, 'url' => get_term_link($primary_category)];
}
$breadcrumb_items[] = ['label' => get_the_title(), 'url' => null];
?>

<main id="main-content" class="th-mode-corp" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <article class="th-container th-py-8"
        itemscope
        itemtype="https://schema.org/Service">

        <?php
        // Breadcrumb
        get_template_part('partials/breadcrumb', null, ['items' => $breadcrumb_items]);

        // Fallback if theme doesn't have partial
        if (!locate_template('partials/breadcrumb.php')) {
            include RFPLUGIN_PATH . 'templates/frontend/partials/breadcrumb.php';
        }
        ?>

        <!-- Header -->
        <header class="th-text-center th-mb-8 th-animate-up">
            <span class="th-badge th-badge--primary th-mb-4">
                <span class="dashicons dashicons-hammer" aria-hidden="true"></span>
                <?php esc_html_e('Service', 'rfplugin'); ?>
            </span>

            <h1 class="th-h1 th-mb-4" itemprop="name">
                <?php the_title(); ?>
            </h1>

            <?php if (has_excerpt()) : ?>
                <p class="th-lead th-text-muted th-mx-auto" itemprop="description" style="max-width: 800px;">
                    <?php echo wp_kses_post(get_the_excerpt()); ?>
                </p>
            <?php endif; ?>
        </header>

        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
            <figure class="th-mb-8 th-rounded th-overflow-hidden th-shadow-lg th-animate-up">
                <?php the_post_thumbnail('large', [
                    'class' => 'th-w-full th-h-auto th-block',
                    'itemprop' => 'image',
                    'loading' => 'eager'
                ]); ?>
            </figure>
        <?php endif; ?>

        <!-- Content -->
        <div class="th-card th-p-8 th-mb-8 th-animate-up">
            <div class="th-prose th-max-w-none" itemprop="description">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- Related Services -->
        <?php
        $related = new WP_Query([
            'post_type' => 'rf_service',
            'posts_per_page' => 3,
            'post__not_in' => [$service_id],
            'tax_query' => $primary_category ? [
                [
                    'taxonomy' => 'rf_service_category',
                    'terms' => $primary_category->term_id,
                ]
            ] : [],
        ]);

        if ($related->have_posts()) : ?>
            <section class="th-mt-8 th-pt-8 th-border-t th-border-color th-animate-up" aria-labelledby="related-title">
                <h2 id="related-title" class="th-h3 th-mb-6">
                    <?php esc_html_e('Related Services', 'rfplugin'); ?>
                </h2>
                <div class="th-grid th-grid-cols-1 md:th-grid-cols-3 th-gap-6">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <?php include RFPLUGIN_PATH . 'templates/frontend/partials/card-service.php'; ?>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Navigation -->
        <?php
        $args = ['post_type_label' => __('Service', 'rfplugin')];
        include RFPLUGIN_PATH . 'templates/frontend/partials/post-navigation.php';
        ?>

        <meta itemprop="url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta itemprop="provider" itemscope itemtype="https://schema.org/Organization" content="">
    </article>
</main>

<?php get_footer(); ?>