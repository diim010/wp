<?php

/**
 * Archive Template for Resources (Production Ready)
 *
 * Premium resource library with filtering, pagination, accessibility,
 * SEO schema markup, and security best practices.
 *
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

// Sanitize and validate filter parameters
$current_mode = isset($_GET['mode']) ? sanitize_key($_GET['mode']) : '';
$valid_modes = ['faq', 'document', 'video', '3d', 'sheet'];
$current_mode = in_array($current_mode, $valid_modes, true) ? $current_mode : '';

// Modify query if filtering
if ($current_mode && is_main_query()) {
    global $wp_query;
    $wp_query->set('meta_query', [
        [
            'key' => 'resource_mode',
            'value' => $current_mode,
            'compare' => '='
        ]
    ]);
}

// Resource type configuration
$resource_types = [
    'faq' => [
        'label' => __('FAQs', 'rfplugin'),
        'icon' => 'editor-help',
        'action' => __('Read FAQ', 'rfplugin'),
        'color' => 'hsl(142, 76%, 36%)'
    ],
    'document' => [
        'label' => __('Documents', 'rfplugin'),
        'icon' => 'media-document',
        'action' => __('View Document', 'rfplugin'),
        'color' => 'hsl(217, 91%, 60%)'
    ],
    'sheet' => [
        'label' => __('Datasheets', 'rfplugin'),
        'icon' => 'media-spreadsheet',
        'action' => __('View Sheet', 'rfplugin'),
        'color' => 'hsl(262, 83%, 58%)'
    ],
    'video' => [
        'label' => __('Videos', 'rfplugin'),
        'icon' => 'video-alt3',
        'action' => __('Watch Video', 'rfplugin'),
        'color' => 'hsl(0, 84%, 60%)'
    ],
    '3d' => [
        'label' => __('3D Models', 'rfplugin'),
        'icon' => 'visibility',
        'action' => __('View Model', 'rfplugin'),
        'color' => 'hsl(38, 92%, 50%)'
    ]
];
?>


<main id="main-content" class="flex-grow min-h-screen relative overflow-hidden bg-slate-50" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <div class="container mx-auto px-6 py-12 max-w-7xl relative z-10">
        <!-- Header Section -->
        <header class="text-center mb-16 max-w-3xl mx-auto rf-animate-up">
            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase tracking-wider mb-4"><?php esc_html_e('Support Hub', 'rfplugin'); ?></span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 font-display">
                <?php esc_html_e('Resource Library', 'rfplugin'); ?>
            </h1>

            <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto">
                <?php esc_html_e('Unified access to technical manuals, video guides, FAQs, and 3D specifications.', 'rfplugin'); ?>
            </p>

            <!-- Filter Navigation -->
            <nav class="mt-8" aria-label="<?php esc_attr_e('Resource type filter', 'rfplugin'); ?>">
                <ul class="flex flex-wrap justify-center gap-2 list-none p-0 m-0" role="tablist">
                    <li role="presentation">
                        <a href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>"
                            class="px-5 py-2.5 rounded-full font-semibold text-sm transition-all duration-200 border <?php echo empty($current_mode) ? 'bg-slate-900 text-white border-slate-900 shadow-md transform scale-105' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:shadow-sm'; ?>"
                            role="tab"
                            aria-selected="<?php echo empty($current_mode) ? 'true' : 'false'; ?>">
                            <?php esc_html_e('All', 'rfplugin'); ?>
                        </a>
                    </li>
                    <?php foreach (['faq', 'document', 'video', '3d'] as $type_key) :
                        $type = $resource_types[$type_key];
                        $url = add_query_arg('mode', $type_key, get_post_type_archive_link('rf_resource'));
                        $is_active = ($current_mode === $type_key);
                    ?>
                        <li role="presentation">
                            <a href="<?php echo esc_url($url); ?>"
                                class="px-5 py-2.5 rounded-full font-semibold text-sm transition-all duration-200 border <?php echo $is_active ? 'bg-slate-900 text-white border-slate-900 shadow-md transform scale-105' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:shadow-sm'; ?>"
                                role="tab"
                                aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>">
                                <?php echo esc_html($type['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </header>

        <!-- Resource Grid -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            aria-label="<?php esc_attr_e('Resources', 'rfplugin'); ?>">

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post();
                    $resource_id = get_the_ID();
                    $mode = get_field('resource_mode', $resource_id) ?: 'document';
                    $type_config = $resource_types[$mode] ?? $resource_types['document'];
                    $visibility = get_field('resource_visibility', $resource_id) ?: 'guest';

                    // Get categories for schema
                    $categories = get_the_terms($resource_id, 'rf_resource_category');
                    $category_names = $categories && !is_wp_error($categories)
                        ? implode(', ', wp_list_pluck($categories, 'name'))
                        : '';
                ?>
                    <article class="group relative flex flex-col h-full bg-white border border-slate-200 rounded-2xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200 rf-animate-up"
                        itemscope
                        itemtype="https://schema.org/Article">

                        <!-- Header with Icon & Badge -->
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-opacity-10 backdrop-blur-sm transition-transform group-hover:scale-110 duration-300"
                                style="background-color: <?php echo esc_attr($type_config['color']); ?>20;">
                                <span class="dashicons dashicons-<?php echo esc_attr($type_config['icon']); ?> text-xl"
                                    style="color: <?php echo esc_attr($type_config['color']); ?>"></span>
                            </div>

                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-slate-200 text-slate-500 bg-slate-50 uppercase tracking-wide">
                                <?php echo esc_html($mode); ?>
                            </span>
                        </div>

                        <!-- Visibility Indicator -->
                        <?php if ($visibility !== 'guest') : ?>
                            <div class="absolute top-6 right-16"
                                title="<?php esc_attr_e('Requires login', 'rfplugin'); ?>">
                                <span class="dashicons dashicons-lock text-slate-400 text-sm"></span>
                            </div>
                        <?php endif; ?>

                        <!-- Title -->
                        <h2 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 leading-tight" itemprop="headline">
                            <a href="<?php the_permalink(); ?>"
                                itemprop="url"
                                class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <!-- Excerpt -->
                        <div class="text-sm text-slate-500 mb-6 flex-grow line-clamp-3 leading-relaxed" itemprop="description">
                            <?php echo wp_kses_post(get_the_excerpt()); ?>
                        </div>

                        <!-- Meta -->
                        <div class="flex gap-4 text-xs text-slate-400 mb-6 font-medium">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                            <?php if ($category_names) : ?>
                                <span class="text-slate-300 px-1">&bull;</span>
                                <span itemprop="articleSection" class="text-slate-500"><?php echo esc_html($category_names); ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Action Button -->
                        <span class="inline-flex items-center text-sm font-bold text-blue-600 group-hover:text-blue-700 mt-auto transition-colors">
                            <?php echo esc_html($type_config['action']); ?>
                            <span class="dashicons dashicons-arrow-right-alt2 ml-1 transition-transform group-hover:translate-x-1" aria-hidden="true"></span>
                        </span>

                        <!-- Schema.org hidden meta -->
                        <meta itemprop="author" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
                        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>" />
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-span-full text-center py-20">
                    <div class="bg-white border border-slate-200 rounded-2xl p-12 max-w-lg mx-auto shadow-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 rounded-full mb-6">
                            <span class="dashicons dashicons-search text-3xl text-slate-400"></span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2"><?php esc_html_e('No Resources Found', 'rfplugin'); ?></h2>
                        <p class="text-slate-500 mb-8">
                            <?php esc_html_e('No resources match your current filter criteria. Try selecting a different category or viewing all resources.', 'rfplugin'); ?>
                        </p>
                        <a href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow transition-all duration-200">
                            <?php esc_html_e('View All Resources', 'rfplugin'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <?php if (have_posts()) : ?>
            <nav class="mt-16 flex justify-center"
                aria-label="<?php esc_attr_e('Resource pagination', 'rfplugin'); ?>">
                <?php
                $pagination_args = [
                    'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span><span class="sr-only">' . esc_html__('Previous page', 'rfplugin') . '</span>',
                    'next_text' => '<span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span><span class="sr-only">' . esc_html__('Next page', 'rfplugin') . '</span>',
                    'mid_size' => 2,
                    'type' => 'array',
                ];
                $pages = paginate_links($pagination_args);

                if ($pages) {
                    echo '<ul class="flex gap-2">';
                    foreach ($pages as $page) {
                        $active = strpos($page, 'current') !== false;
                        $class = $active
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:border-slate-300';

                        echo '<li>' . str_replace('page-numbers', 'page-numbers flex items-center justify-center px-4 py-2 border rounded-lg text-sm font-medium transition-all duration-200 ' . $class, $page) . '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </nav>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>