<?php

/**
 * Single Resource Template (Production Ready)
 *
 * Hybrid template for all resource types with accessibility,
 * SEO schema markup, security, and modern UX.
 *
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

$resource_id = get_the_ID();
$mode = get_field('resource_mode', $resource_id) ?: 'document';
$visibility = get_field('resource_visibility', $resource_id) ?: 'guest';

// Security check based on visibility
$can_view = true;
if ($visibility !== 'guest') {
    if (!is_user_logged_in()) {
        $can_view = false;
    } elseif ($visibility !== 'subscriber' && !current_user_can($visibility) && !current_user_can('administrator')) {
        $can_view = false;
    }
}

if (!$can_view) {
    include RFPLUGIN_PATH . 'templates/frontend/access-denied.php';
    get_footer();
    exit;
}

// Resource type configuration
$type_config = [
    'faq' => ['label' => __('FAQ', 'rfplugin'), 'icon' => 'editor-help', 'color' => 'hsl(142, 76%, 36%)'],
    'document' => ['label' => __('Document', 'rfplugin'), 'icon' => 'media-document', 'color' => 'hsl(217, 91%, 60%)'],
    'sheet' => ['label' => __('Datasheet', 'rfplugin'), 'icon' => 'media-spreadsheet', 'color' => 'hsl(262, 83%, 58%)'],
    'video' => ['label' => __('Video', 'rfplugin'), 'icon' => 'video-alt3', 'color' => 'hsl(0, 84%, 60%)'],
    '3d' => ['label' => __('3D Model', 'rfplugin'), 'icon' => 'visibility', 'color' => 'hsl(38, 92%, 50%)']
];
$config = $type_config[$mode] ?? $type_config['document'];

// Get categories for breadcrumb and schema
$categories = get_the_terms($resource_id, 'rf_resource_category');
$primary_category = ($categories && !is_wp_error($categories)) ? $categories[0] : null;

// Schema type based on mode
$schema_types = [
    'faq' => 'FAQPage',
    'video' => 'VideoObject',
    'document' => 'TechArticle',
    'sheet' => 'TechArticle',
    '3d' => 'CreativeWork'
];
$schema_type = $schema_types[$mode] ?? 'Article';
?>


<main id="main-content" class="rf-resource-single th-mode-<?php echo esc_attr($mode); ?>" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <article class="th-container th-py-6"
        itemscope
        itemtype="https://schema.org/<?php echo esc_attr($schema_type); ?>">

        <!-- Breadcrumb Navigation -->
        <nav class="th-breadcrumb th-mb-5" aria-label="<?php esc_attr_e('Breadcrumb', 'rfplugin'); ?>">
            <ol itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>">
                        <span itemprop="name"><?php esc_html_e('Home', 'rfplugin'); ?></span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>">
                        <span itemprop="name"><?php esc_html_e('Library', 'rfplugin'); ?></span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
                <?php if ($primary_category) : ?>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a itemprop="item" href="<?php echo esc_url(get_term_link($primary_category)); ?>">
                            <span itemprop="name"><?php echo esc_html($primary_category->name); ?></span>
                        </a>
                        <meta itemprop="position" content="3" />
                    </li>
                <?php endif; ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
                    <span itemprop="name" class="current"><?php echo esc_html($config['label']); ?></span>
                    <meta itemprop="position" content="<?php echo $primary_category ? '4' : '3'; ?>" />
                </li>
            </ol>
        </nav>

        <!-- Header Section -->
        <header class="rf-resource-intro th-text-center th-mb-7">
            <span class="th-badge th-badge--<?php echo esc_attr($mode); ?> th-mb-3">
                <span class="dashicons dashicons-<?php echo esc_attr($config['icon']); ?>" aria-hidden="true"></span>
                <?php echo esc_html($config['label']); ?>
            </span>

            <h1 class="th-h1 th-mb-4" itemprop="headline">
                <?php the_title(); ?>
            </h1>

            <?php if (has_excerpt()) : ?>
                <p class="th-lead th-text-muted th-mx-auto"
                    itemprop="description"
                    style="max-width: 700px;">
                    <?php echo wp_kses_post(get_the_excerpt()); ?>
                </p>
            <?php endif; ?>

            <!-- Meta Info -->
            <div class="th-flex th-justify-center th-gap-4 th-text-sm th-text-muted th-mt-5 th-flex-wrap">
                <span class="th-flex th-items-center th-gap-1">
                    <span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
                <span class="th-flex th-items-center th-gap-1">
                    <span class="dashicons dashicons-update" aria-hidden="true"></span>
                    <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
                        <?php printf(esc_html__('Updated %s', 'rfplugin'), esc_html(get_the_modified_date())); ?>
                    </time>
                </span>
                <?php if ($visibility !== 'guest') : ?>
                    <span class="th-flex th-items-center th-gap-1 th-text-warning">
                        <span class="dashicons dashicons-lock" aria-hidden="true"></span>
                        <?php esc_html_e('Protected Resource', 'rfplugin'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="rf-resource-content th-card th-p-6 th-relative th-overflow-hidden">
            <?php
            // Map modes to template files
            $template_map = [
                'sheet' => 'document',
                'faq' => 'faq',
                'video' => 'video',
                '3d' => '3d',
                'document' => 'document'
            ];
            $template_name = $template_map[$mode] ?? 'document';

            // Look for template in theme first, then plugin
            $theme_template = locate_template('partials/content-resource-' . $template_name . '.php');
            if ($theme_template) {
                include $theme_template;
            } else {
                $plugin_template = RFPLUGIN_PATH . 'templates/frontend/partials/content-resource-' . $template_name . '.php';
                if (file_exists($plugin_template)) {
                    include $plugin_template;
                } else {
                    // Fallback to content
                    echo '<div class="th-prose">';
                    the_content();
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Footer: Related Items & Metadata -->
        <footer class="rf-resource-footer th-grid th-grid-cols-1 md:th-grid-cols-3 th-gap-6 th-mt-7">
            <!-- Related Items -->
            <section class="rf-related-items th-col-span-2" aria-labelledby="related-heading">
                <h2 id="related-heading" class="th-h3 th-mb-4"><?php esc_html_e('Related Solutions', 'rfplugin'); ?></h2>
                <?php
                $related = get_field('related_items', $resource_id);
                if ($related && is_array($related)) : ?>
                    <div class="th-flex th-gap-3 th-flex-wrap">
                        <?php foreach ($related as $item_id) :
                            if (!$item_id) continue;
                            $item_title = get_the_title($item_id);
                            $item_link = get_permalink($item_id);
                            if (!$item_title || !$item_link) continue;
                        ?>
                            <a href="<?php echo esc_url($item_link); ?>"
                                class="th-btn th-btn--ghost th-btn--sm">
                                <span class="dashicons dashicons-share-alt2" aria-hidden="true"></span>
                                <?php echo esc_html($item_title); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="th-text-muted"><?php esc_html_e('This is a general resource applicable to all systems.', 'rfplugin'); ?></p>
                <?php endif; ?>
            </section>

            <!-- Metadata Sidebar -->
            <aside class="rf-meta-sidebar" aria-labelledby="info-heading">
                <div class="th-card th-p-5">
                    <h3 id="info-heading" class="th-h4 th-mb-4"><?php esc_html_e('Resource Information', 'rfplugin'); ?></h3>
                    <dl class="th-dl">
                        <div class="th-flex th-justify-between th-py-2 th-border-b th-border-color">
                            <dt class="th-text-muted"><?php esc_html_e('Type', 'rfplugin'); ?></dt>
                            <dd class="th-font-medium"><?php echo esc_html($config['label']); ?></dd>
                        </div>
                        <div class="th-flex th-justify-between th-py-2 th-border-b th-border-color">
                            <dt class="th-text-muted"><?php esc_html_e('Published', 'rfplugin'); ?></dt>
                            <dd><?php echo esc_html(get_the_date()); ?></dd>
                        </div>
                        <div class="th-flex th-justify-between th-py-2 th-border-b th-border-color">
                            <dt class="th-text-muted"><?php esc_html_e('Last Updated', 'rfplugin'); ?></dt>
                            <dd><?php echo esc_html(get_the_modified_date()); ?></dd>
                        </div>
                        <div class="th-flex th-justify-between th-py-2">
                            <dt class="th-text-muted"><?php esc_html_e('Reference ID', 'rfplugin'); ?></dt>
                            <dd class="th-font-mono">#<?php echo esc_html($resource_id); ?></dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </footer>

        <!-- Navigation -->
        <nav class="rf-post-navigation th-mt-7 th-pt-6 th-border-t th-border-color"
            aria-label="<?php esc_attr_e('Resource navigation', 'rfplugin'); ?>">
            <div class="th-grid th-grid-cols-2 th-gap-6">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <div>
                    <?php if ($prev_post) : ?>
                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>"
                            class="th-card th-card--hoverable th-p-4 th-block th-no-underline">
                            <span class="th-text-sm th-text-muted th-flex th-items-center th-gap-1 th-mb-1">
                                <span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span>
                                <?php esc_html_e('Previous', 'rfplugin'); ?>
                            </span>
                            <span class="th-block th-font-semibold"><?php echo esc_html($prev_post->post_title); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="th-text-right">
                    <?php if ($next_post) : ?>
                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>"
                            class="th-card th-card--hoverable th-p-4 th-block th-no-underline">
                            <span class="th-text-sm th-text-muted th-flex th-items-center th-justify-end th-gap-1 th-mb-1">
                                <?php esc_html_e('Next', 'rfplugin'); ?>
                                <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                            </span>
                            <span class="th-block th-font-semibold"><?php echo esc_html($next_post->post_title); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Hidden Schema Meta -->
        <meta itemprop="author" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
        <meta itemprop="publisher" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
    </article>
</main>

<?php get_footer(); ?>