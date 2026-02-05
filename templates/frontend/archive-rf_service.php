<?php

/**
 * Archive Services Template
 *
 * Grid layout with category filtering.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

get_header();

$categories = get_terms([
    'taxonomy' => 'rf_service_category',
    'hide_empty' => true,
]);
$current_cat = get_queried_object();
$is_category = is_tax('rf_service_category');
?>

<main id="main-content" class="flex-grow relative" role="main">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-50 to-white -z-10" aria-hidden="true"></div>

    <div class="container mx-auto px-6 py-20 max-w-7xl">

        <!-- Header -->
        <header class="text-center max-w-3xl mx-auto mb-16 rf-animate-up">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider mb-6 border border-blue-100">
                <span class="dashicons dashicons-admin-tools" aria-hidden="true"></span>
                <?php esc_html_e('Our Services', 'rfplugin'); ?>
            </span>

            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-6">
                <?php
                if ($is_category) {
                    echo esc_html($current_cat->name);
                } else {
                    esc_html_e('Professional Services', 'rfplugin');
                }
                ?>
            </h1>

            <p class="text-lg text-slate-500 leading-relaxed">
                <?php
                if ($is_category && $current_cat->description) {
                    echo esc_html($current_cat->description);
                } else {
                    esc_html_e('Comprehensive foam solutions for your architectural projects.', 'rfplugin');
                }
                ?>
            </p>
        </header>

        <!-- Category Filter -->
        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
            <nav class="flex flex-wrap justify-center gap-4 mb-16 rf-animate-up" aria-label="<?php esc_attr_e('Filter by category', 'rfplugin'); ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('rf_service')); ?>"
                    class="px-5 py-2.5 rounded-full font-semibold text-sm transition-all duration-200 border <?php echo !$is_category ? 'bg-slate-900 text-white border-slate-900 shadow-lg' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:shadow-md'; ?>">
                    <?php esc_html_e('All', 'rfplugin'); ?>
                </a>
                <?php foreach ($categories as $cat) : ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                        class="px-5 py-2.5 rounded-full font-semibold text-sm transition-all duration-200 border <?php echo ($is_category && $current_cat->term_id === $cat->term_id) ? 'bg-slate-900 text-white border-slate-900 shadow-lg' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:shadow-md'; ?>">
                        <?php echo esc_html($cat->name); ?>
                        <span class="ml-2 opacity-60 text-xs"><?php echo esc_html($cat->count); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <!-- Services Grid -->
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $args = ['post' => get_post()];
                    include RFPLUGIN_PATH . 'templates/frontend/partials/card-service.php';
                    ?>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav class="flex justify-center rf-animate-up" aria-label="<?php esc_attr_e('Pagination', 'rfplugin'); ?>">
                <?php
                $pagination_args = [
                    'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span> ' . __('Previous', 'rfplugin'),
                    'next_text' => __('Next', 'rfplugin') . ' <span class="dashicons dashicons-arrow-right-alt2"></span>',
                    'before_page_number' => '<span class="sr-only">Page </span>',
                    'type' => 'array',
                ];
                $pages = paginate_links($pagination_args);

                if ($pages) {
                    echo '<ul class="flex gap-2">';
                    foreach ($pages as $page) {
                        $active = strpos($page, 'current') !== false;
                        $class = $active
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';

                        echo '<li>' . str_replace('page-numbers', 'page-numbers flex items-center justify-center px-4 py-2 border rounded-lg text-sm font-medium transition-colors ' . $class, $page) . '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </nav>

        <?php else : ?>
            <div class="text-center py-32 rf-animate-up">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 rounded-full mb-6">
                    <span class="dashicons dashicons-admin-tools text-3xl text-slate-400" aria-hidden="true"></span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2"><?php esc_html_e('No services found', 'rfplugin'); ?></h2>
                <p class="text-slate-500"><?php esc_html_e('Check back soon for new services.', 'rfplugin'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>