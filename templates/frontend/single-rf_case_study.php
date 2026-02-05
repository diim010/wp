<?php

/**
 * Single Case Study Template
 *
 * Optimized template using corporate design system.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

$post_id = get_the_ID();
$industries = get_the_terms($post_id, 'rf_case_industry');
$primary_industry = ($industries && !is_wp_error($industries)) ? $industries[0] : null;

// ACF fields (if available)
$client = function_exists('get_field') ? get_field('client_name') : '';
$results = function_exists('get_field') ? get_field('key_results') : '';

// Breadcrumb
$breadcrumb_items = [
    ['label' => __('Home', 'rfplugin'), 'url' => home_url('/')],
    ['label' => __('Case Studies', 'rfplugin'), 'url' => get_post_type_archive_link('rf_case_study')],
];
if ($primary_industry) {
    $breadcrumb_items[] = ['label' => $primary_industry->name, 'url' => get_term_link($primary_industry)];
}
$breadcrumb_items[] = ['label' => get_the_title(), 'url' => null];
?>

<main id="main-content" class="th-mode-corp" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <article class="th-container th-py-8"
        itemscope
        itemtype="https://schema.org/Article">

        <?php
        $args = ['items' => $breadcrumb_items];
        include RFPLUGIN_PATH . 'templates/frontend/partials/breadcrumb.php';
        ?>

        <!-- Header -->
        <header class="th-text-center th-mb-8 th-animate-up">
            <?php if ($primary_industry) : ?>
                <span class="th-badge th-badge--primary th-mb-4">
                    <?php echo esc_html($primary_industry->name); ?>
                </span>
            <?php endif; ?>

            <h1 class="th-h1 th-mb-4" itemprop="headline">
                <?php the_title(); ?>
            </h1>

            <?php if (has_excerpt()) : ?>
                <p class="th-lead th-text-muted th-mx-auto" itemprop="description" style="max-width: 800px;">
                    <?php echo wp_kses_post(get_the_excerpt()); ?>
                </p>
            <?php endif; ?>

            <!-- Meta -->
            <div class="th-flex th-justify-center th-gap-6 th-text-sm th-text-muted th-mt-6">
                <?php if ($client) : ?>
                    <span class="th-flex th-items-center th-gap-2">
                        <span class="dashicons dashicons-building" aria-hidden="true"></span>
                        <?php echo esc_html($client); ?>
                    </span>
                <?php endif; ?>
                <span class="th-flex th-items-center th-gap-2">
                    <span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
            <figure class="th-mb-8 th-rounded th-overflow-hidden th-shadow-lg th-animate-up">
                <?php the_post_thumbnail('full', [
                    'class' => 'th-w-full th-h-auto th-block',
                    'itemprop' => 'image',
                    'loading' => 'eager'
                ]); ?>
            </figure>
        <?php endif; ?>

        <!-- Key Results (if available) -->
        <?php if ($results && is_array($results)) : ?>
            <section class="th-grid th-grid-cols-1 md:th-grid-cols-3 th-gap-6 th-mb-8 th-animate-up" aria-label="<?php esc_attr_e('Key Results', 'rfplugin'); ?>">
                <?php foreach ($results as $result) : ?>
                    <div class="th-card th-p-6 th-text-center">
                        <span class="th-block th-text-3xl th-font-bold th-text-primary th-mb-2">
                            <?php echo esc_html($result['value'] ?? ''); ?>
                        </span>
                        <span class="th-text-sm th-text-muted th-uppercase th-tracking-wide">
                            <?php echo esc_html($result['label'] ?? ''); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Content -->
        <div class="th-card th-p-8 th-mb-8 th-animate-up">
            <div class="th-prose th-max-w-none" itemprop="articleBody">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- Related Case Studies -->
        <?php
        $related = new WP_Query([
            'post_type' => 'rf_case_study',
            'posts_per_page' => 3,
            'post__not_in' => [$post_id],
            'tax_query' => $primary_industry ? [
                ['taxonomy' => 'rf_case_industry', 'terms' => $primary_industry->term_id]
            ] : [],
        ]);

        if ($related->have_posts()) : ?>
            <section class="th-mt-8 th-pt-8 th-border-t th-border-color th-animate-up" aria-labelledby="related-cases-title">
                <h2 id="related-cases-title" class="th-h3 th-mb-6">
                    <?php esc_html_e('More Case Studies', 'rfplugin'); ?>
                </h2>
                <div class="th-grid th-grid-cols-1 md:th-grid-cols-3 th-gap-6">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <?php include RFPLUGIN_PATH . 'templates/frontend/partials/card-case-study.php'; ?>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Navigation -->
        <?php
        $args = ['post_type_label' => __('Case Study', 'rfplugin')];
        include RFPLUGIN_PATH . 'templates/frontend/partials/post-navigation.php';
        ?>

        <meta itemprop="author" itemscope itemtype="https://schema.org/Organization" content="">
    </article>
</main>

<?php get_footer(); ?>