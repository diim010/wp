§§<?php

/**
 * Archive Case Studies Template
 *
 * Featured grid layout with industry filtering.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

$industries = get_terms([
    'taxonomy' => 'rf_case_industry',
    'hide_empty' => true,
]);
$current_industry = get_queried_object();
$is_industry = is_tax('rf_case_industry');
?>


<main id="main-content" class="th-mode-corp" role="main">
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <div class="th-container th-py-8">

        <!-- Header -->
        <header class="th-text-center th-mb-8 th-animate-up">
            <span class="th-badge th-badge--accent th-mb-4">
                <span class="dashicons dashicons-portfolio" aria-hidden="true"></span>
                <?php esc_html_e('Portfolio', 'rfplugin'); ?>
            </span>

            <h1 class="th-h1 th-mb-4">
                <?php
                if ($is_industry) {
                    echo esc_html($current_industry->name);
                } else {
                    esc_html_e('Case Studies', 'rfplugin');
                }
                ?>
            </h1>

            <p class="th-lead th-text-muted th-mx-auto" style="max-width: 600px;">
                <?php
                if ($is_industry && $current_industry->description) {
                    echo esc_html($current_industry->description);
                } else {
                    esc_html_e('Real-world success stories showcasing our expertise.', 'rfplugin');
                }
                ?>
            </p>
        </header>

        <!-- Industry Filter -->
        <?php if (!empty($industries) && !is_wp_error($industries)) : ?>
            <nav class="th-flex th-justify-center th-flex-wrap th-gap-3 th-mb-8 th-animate-up" aria-label="<?php esc_attr_e('Filter by industry', 'rfplugin'); ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('rf_case_study')); ?>"
                    class="th-btn th-btn--pill <?php echo !$is_industry ? 'th-btn--primary' : 'th-btn--ghost'; ?>">
                    <?php esc_html_e('All Industries', 'rfplugin'); ?>
                </a>
                <?php foreach ($industries as $industry) : ?>
                    <a href="<?php echo esc_url(get_term_link($industry)); ?>"
                        class="th-btn th-btn--pill <?php echo ($is_industry && $current_industry->term_id === $industry->term_id) ? 'th-btn--primary' : 'th-btn--ghost'; ?>">
                        <?php echo esc_html($industry->name); ?>
                        <span class="th-text-xs th-opacity-70 th-ml-2"><?php echo esc_html($industry->count); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <!-- Case Studies Grid -->
        <?php if (have_posts()) : ?>
            <div class="th-grid th-grid-cols-1 md:th-grid-cols-2 th-gap-8">
                <?php
                $index = 0;
                while (have_posts()) : the_post();
                    $args = ['post' => get_post()];
                    include RFPLUGIN_PATH . 'templates/frontend/partials/card-case-study.php';
                    $index++;
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <!-- Pagination -->
            <nav class="th-pagination th-mt-8 th-flex th-justify-center th-animate-up" aria-label="<?php esc_attr_e('Pagination', 'rfplugin'); ?>">
                <?php
                echo paginate_links([
                    'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span> ' . __('Previous', 'rfplugin'),
                    'next_text' => __('Next', 'rfplugin') . ' <span class="dashicons dashicons-arrow-right-alt2"></span>',
                ]);
                ?>
            </nav>

        <?php else : ?>
            <div class="th-text-center th-py-12 th-animate-up">
                <span class="dashicons dashicons-portfolio th-text-muted" style="font-size: 48px; width: 48px; height: 48px;" aria-hidden="true"></span>
                <h2 class="th-h3 th-mt-4 th-mb-2"><?php esc_html_e('No case studies found', 'rfplugin'); ?></h2>
                <p class="th-text-muted"><?php esc_html_e('We are working on documenting our success stories.', 'rfplugin'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>