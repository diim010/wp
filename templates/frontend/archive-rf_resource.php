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

<main id="main-content" class="rf-archive-resource rf-premium-ui" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <div class="rf-container" style="padding: 80px 0;">
        <!-- Header Section -->
        <header class="rf-archive-header" style="text-align: center; margin-bottom: 60px;">
            <span class="rf-badge" aria-hidden="true"><?php esc_html_e('Support Hub', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="font-size: clamp(2rem, 5vw, 3.5rem); margin: 20px 0;">
                <?php esc_html_e('Resource Library', 'rfplugin'); ?>
            </h1>
            
            <p class="rf-subtitle" style="max-width: 600px; margin: 0 auto 40px;">
                <?php esc_html_e('Unified access to technical manuals, video guides, FAQs, and 3D specifications.', 'rfplugin'); ?>
            </p>

            <!-- Filter Navigation -->
            <nav class="rf-filter-nav" aria-label="<?php esc_attr_e('Resource type filter', 'rfplugin'); ?>">
                <ul class="rf-pill-nav" role="tablist" style="display: inline-flex; flex-wrap: wrap; gap: 8px; background: rgba(255,255,255,0.05); padding: 6px; border-radius: 99px; border: 1px solid rgba(255,255,255,0.1); list-style: none; margin: 0;">
                    <li role="presentation">
                        <a href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>" 
                           class="rf-pill <?php echo empty($current_mode) ? 'active' : ''; ?>"
                           role="tab"
                           aria-selected="<?php echo empty($current_mode) ? 'true' : 'false'; ?>"
                           style="padding: 10px 24px; border-radius: 99px; text-decoration: none; <?php echo empty($current_mode) ? 'background: var(--rf-primary); color: white;' : 'color: #cbd5e1;'; ?> font-weight: 600; display: inline-block; transition: all 0.3s ease;">
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
                               class="rf-pill <?php echo $is_active ? 'active' : ''; ?>"
                               role="tab"
                               aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                               style="padding: 10px 24px; border-radius: 99px; text-decoration: none; <?php echo $is_active ? 'background: var(--rf-primary); color: white;' : 'color: #cbd5e1;'; ?> transition: all 0.3s ease; display: inline-block;">
                                <?php echo esc_html($type['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </header>

        <!-- Resource Grid -->
        <section class="rf-resource-grid" 
                 aria-label="<?php esc_attr_e('Resources', 'rfplugin'); ?>"
                 style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
            
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
                    <article class="rf-resource-card rf-glass-card rf-fade-in" 
                             itemscope 
                             itemtype="https://schema.org/Article"
                             style="padding: 30px; display: flex; flex-direction: column; align-items: flex-start; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        
                        <!-- Icon -->
                        <div class="rf-card-icon" 
                             style="width: 48px; height: 48px; background: <?php echo esc_attr($type_config['color']); ?>20; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;"
                             aria-hidden="true">
                            <span class="dashicons dashicons-<?php echo esc_attr($type_config['icon']); ?>" 
                                  style="font-size: 24px; color: <?php echo esc_attr($type_config['color']); ?>;"></span>
                        </div>

                        <!-- Type Badge -->
                        <span class="rf-badge-outline" 
                              style="font-size: 10px; margin-bottom: 12px; padding: 4px 10px; border: 1px solid <?php echo esc_attr($type_config['color']); ?>40; color: <?php echo esc_attr($type_config['color']); ?>; border-radius: 4px; text-transform: uppercase; font-weight: 600;">
                            <?php echo esc_html(strtoupper($mode)); ?>
                        </span>

                        <!-- Visibility Indicator -->
                        <?php if ($visibility !== 'guest') : ?>
                            <span class="rf-visibility-badge" 
                                  style="position: absolute; top: 16px; right: 16px; font-size: 10px; padding: 4px 8px; background: rgba(255,255,255,0.1); color: #94a3b8; border-radius: 4px;"
                                  title="<?php esc_attr_e('Requires login', 'rfplugin'); ?>">
                                <span class="dashicons dashicons-lock" style="font-size: 12px; width: 12px; height: 12px;"></span>
                            </span>
                        <?php endif; ?>

                        <!-- Title -->
                        <h2 class="rf-h4" itemprop="headline" style="font-size: 1.25rem; margin: 0 0 12px; color: white; line-height: 1.4;">
                            <a href="<?php the_permalink(); ?>" 
                               itemprop="url"
                               style="text-decoration: none; color: inherit; transition: color 0.2s ease;"
                               onmouseover="this.style.color='var(--rf-primary)'"
                               onmouseout="this.style.color='white'">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <!-- Excerpt -->
                        <div class="rf-excerpt" 
                             itemprop="description"
                             style="font-size: 0.95rem; color: #94a3b8; line-height: 1.6; margin-bottom: 24px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex-grow: 1;">
                            <?php echo wp_kses_post(get_the_excerpt()); ?>
                        </div>

                        <!-- Meta -->
                        <div class="rf-card-meta" style="display: flex; gap: 16px; font-size: 0.8rem; color: #64748b; margin-bottom: 20px; width: 100%;">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                            <?php if ($category_names) : ?>
                                <span itemprop="articleSection"><?php echo esc_html($category_names); ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Action Button -->
                        <a href="<?php the_permalink(); ?>" 
                           class="rf-btn-link" 
                           aria-label="<?php echo esc_attr(sprintf(__('%s: %s', 'rfplugin'), $type_config['action'], get_the_title())); ?>"
                           style="margin-top: auto; color: var(--rf-primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: gap 0.2s ease;">
                            <?php echo esc_html($type_config['action']); ?> 
                            <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                        </a>

                        <!-- Schema.org hidden meta -->
                        <meta itemprop="author" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
                        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>" />
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="rf-empty" style="grid-column: 1/-1; text-align: center; padding: 80px 40px;">
                    <div class="rf-glass-card" style="max-width: 500px; margin: 0 auto; padding: 60px;">
                        <span class="dashicons dashicons-search" style="font-size: 48px; width: 48px; height: 48px; color: #64748b; margin-bottom: 24px;"></span>
                        <h2 style="color: white; margin-bottom: 16px;"><?php esc_html_e('No Resources Found', 'rfplugin'); ?></h2>
                        <p style="color: #94a3b8; margin-bottom: 24px;">
                            <?php esc_html_e('No resources match your current filter criteria. Try selecting a different category or viewing all resources.', 'rfplugin'); ?>
                        </p>
                        <a href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>" class="rf-btn rf-btn-primary">
                            <?php esc_html_e('View All Resources', 'rfplugin'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <?php if (have_posts()) : ?>
            <nav class="rf-pagination" 
                 aria-label="<?php esc_attr_e('Resource pagination', 'rfplugin'); ?>"
                 style="margin-top: 60px; display: flex; justify-content: center; gap: 8px;">
                <?php
                echo paginate_links([
                    'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__('Previous page', 'rfplugin') . '</span>',
                    'next_text' => '<span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__('Next page', 'rfplugin') . '</span>',
                    'mid_size' => 2,
                    'type' => 'list',
                ]);
                ?>
            </nav>
        <?php endif; ?>
    </div>
</main>

<style>
/* Pagination Styles */
.rf-pagination ul {
    display: flex;
    gap: 8px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.rf-pagination .page-numbers {
    display: flex;
    width: 44px;
    height: 44px;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: rgba(255,255,255,0.05);
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.1);
    font-weight: 500;
}
.rf-pagination .page-numbers:hover,
.rf-pagination .page-numbers.current {
    background: var(--rf-primary);
    border-color: var(--rf-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}
.rf-pagination .dots {
    background: transparent;
    border: none;
}

/* Card Hover Effects */
.rf-resource-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}
.rf-resource-card:focus-within {
    outline: 2px solid var(--rf-primary);
    outline-offset: 4px;
}

/* Button Link Hover */
.rf-btn-link:hover {
    gap: 12px !important;
}

/* Pill Hover */
.rf-pill:hover:not(.active) {
    background: rgba(255,255,255,0.1);
    color: white;
}

/* Screen Reader Only */
.screen-reader-text {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .rf-resource-grid {
        grid-template-columns: 1fr !important;
    }
    .rf-pill-nav {
        border-radius: 16px !important;
    }
    .rf-pill {
        padding: 8px 16px !important;
        font-size: 0.9rem;
    }
}
</style>

<?php get_footer(); ?>
