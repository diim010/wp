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

<main id="main-content" class="rf-resource-single rf-premium-ui rf-mode-<?php echo esc_attr($mode); ?>" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <article class="rf-container" 
             style="padding: 100px 0;"
             itemscope 
             itemtype="https://schema.org/<?php echo esc_attr($schema_type); ?>">
        
        <!-- Breadcrumb Navigation -->
        <nav class="rf-breadcrumb" 
             aria-label="<?php esc_attr_e('Breadcrumb', 'rfplugin'); ?>"
             style="margin-bottom: 40px;">
            <ol itemscope itemtype="https://schema.org/BreadcrumbList" style="display: flex; gap: 8px; align-items: center; list-style: none; margin: 0; padding: 0; font-size: 0.9rem;">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>" style="color: #64748b; text-decoration: none;">
                        <span itemprop="name"><?php esc_html_e('Home', 'rfplugin'); ?></span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <span aria-hidden="true" style="color: #475569;">/</span>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>" style="color: #64748b; text-decoration: none;">
                        <span itemprop="name"><?php esc_html_e('Library', 'rfplugin'); ?></span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
                <?php if ($primary_category) : ?>
                    <span aria-hidden="true" style="color: #475569;">/</span>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a itemprop="item" href="<?php echo esc_url(get_term_link($primary_category)); ?>" style="color: #64748b; text-decoration: none;">
                            <span itemprop="name"><?php echo esc_html($primary_category->name); ?></span>
                        </a>
                        <meta itemprop="position" content="3" />
                    </li>
                <?php endif; ?>
                <span aria-hidden="true" style="color: #475569;">/</span>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
                    <span itemprop="name" style="color: white; font-weight: 600;"><?php echo esc_html($config['label']); ?></span>
                    <meta itemprop="position" content="<?php echo $primary_category ? '4' : '3'; ?>" />
                </li>
            </ol>
        </nav>

        <!-- Header Section -->
        <header class="rf-resource-intro" style="margin-bottom: 60px; text-align: center;">
            <span class="rf-badge" 
                  style="background: <?php echo esc_attr($config['color']); ?>20; color: <?php echo esc_attr($config['color']); ?>; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 8px;">
                <span class="dashicons dashicons-<?php echo esc_attr($config['icon']); ?>" style="font-size: 14px; width: 14px; height: 14px;" aria-hidden="true"></span>
                <?php echo esc_html($config['label']); ?>
            </span>
            
            <h1 class="rf-title" 
                itemprop="headline"
                style="font-size: clamp(2rem, 5vw, 3.5rem); margin: 24px 0; line-height: 1.2; max-width: 900px; margin-left: auto; margin-right: auto;">
                <?php the_title(); ?>
            </h1>
            
            <?php if (has_excerpt()) : ?>
                <p class="rf-subtitle" 
                   itemprop="description"
                   style="max-width: 700px; margin: 0 auto; color: #94a3b8; font-size: 1.25rem; line-height: 1.6;">
                    <?php echo wp_kses_post(get_the_excerpt()); ?>
                </p>
            <?php endif; ?>

            <!-- Meta Info -->
            <div class="rf-resource-meta" style="margin-top: 32px; display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; color: #64748b; font-size: 0.9rem;">
                <span style="display: flex; align-items: center; gap: 6px;">
                    <span class="dashicons dashicons-calendar-alt" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
                <span style="display: flex; align-items: center; gap: 6px;">
                    <span class="dashicons dashicons-update" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                    <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
                        <?php printf(esc_html__('Updated %s', 'rfplugin'), esc_html(get_the_modified_date())); ?>
                    </time>
                </span>
                <?php if ($visibility !== 'guest') : ?>
                    <span style="display: flex; align-items: center; gap: 6px; color: #f59e0b;">
                        <span class="dashicons dashicons-lock" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                        <?php esc_html_e('Protected Resource', 'rfplugin'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="rf-resource-content rf-glass-card" 
             style="padding: clamp(30px, 5vw, 60px); position: relative; overflow: hidden; border-radius: 24px;">
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
                    echo '<div class="rf-content-body">';
                    the_content();
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Footer: Related Items & Metadata -->
        <footer class="rf-resource-footer" style="margin-top: 60px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            <!-- Related Items -->
            <section class="rf-related-items" aria-labelledby="related-heading">
                <h2 id="related-heading" class="rf-h3" style="margin-bottom: 24px;"><?php esc_html_e('Related Solutions', 'rfplugin'); ?></h2>
                <?php 
                $related = get_field('related_items', $resource_id);
                if ($related && is_array($related)) : ?>
                    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                        <?php foreach ($related as $item_id) : 
                            if (!$item_id) continue;
                            $item_title = get_the_title($item_id);
                            $item_link = get_permalink($item_id);
                            if (!$item_title || !$item_link) continue;
                        ?>
                            <a href="<?php echo esc_url($item_link); ?>" 
                               class="rf-glass-card rf-related-link"
                               style="padding: 12px 24px; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; border-radius: 12px;">
                                <span class="dashicons dashicons-share-alt2" style="font-size: 16px; color: var(--rf-primary);" aria-hidden="true"></span>
                                <span style="font-weight: 600; color: white;"><?php echo esc_html($item_title); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p style="color: #64748b;"><?php esc_html_e('This is a general resource applicable to all systems.', 'rfplugin'); ?></p>
                <?php endif; ?>
            </section>

            <!-- Metadata Sidebar -->
            <aside class="rf-meta-sidebar" aria-labelledby="info-heading">
                <div class="rf-glass-card" style="padding: 32px; border-radius: 16px;">
                    <h3 id="info-heading" style="margin-top: 0; margin-bottom: 24px; font-size: 1.1rem;"><?php esc_html_e('Resource Information', 'rfplugin'); ?></h3>
                    <dl style="margin: 0;">
                        <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <dt style="color: #64748b;"><?php esc_html_e('Type', 'rfplugin'); ?></dt>
                            <dd style="color: white; margin: 0; font-weight: 500;"><?php echo esc_html($config['label']); ?></dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <dt style="color: #64748b;"><?php esc_html_e('Published', 'rfplugin'); ?></dt>
                            <dd style="color: white; margin: 0;"><?php echo esc_html(get_the_date()); ?></dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <dt style="color: #64748b;"><?php esc_html_e('Last Updated', 'rfplugin'); ?></dt>
                            <dd style="color: white; margin: 0;"><?php echo esc_html(get_the_modified_date()); ?></dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                            <dt style="color: #64748b;"><?php esc_html_e('Reference ID', 'rfplugin'); ?></dt>
                            <dd style="color: white; margin: 0; font-family: monospace;">#<?php echo esc_html($resource_id); ?></dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </footer>

        <!-- Navigation -->
        <nav class="rf-post-navigation" 
             aria-label="<?php esc_attr_e('Resource navigation', 'rfplugin'); ?>"
             style="margin-top: 60px; padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.1);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <?php 
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <div>
                    <?php if ($prev_post) : ?>
                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" 
                           class="rf-glass-card rf-nav-link"
                           style="display: block; padding: 24px; text-decoration: none; border-radius: 12px; transition: all 0.3s ease;">
                            <span style="color: #64748b; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; margin-bottom: 8px;">
                                <span class="dashicons dashicons-arrow-left-alt2" style="font-size: 14px;" aria-hidden="true"></span>
                                <?php esc_html_e('Previous', 'rfplugin'); ?>
                            </span>
                            <span style="color: white; font-weight: 600; line-height: 1.4;"><?php echo esc_html($prev_post->post_title); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <div style="text-align: right;">
                    <?php if ($next_post) : ?>
                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" 
                           class="rf-glass-card rf-nav-link"
                           style="display: block; padding: 24px; text-decoration: none; border-radius: 12px; transition: all 0.3s ease;">
                            <span style="color: #64748b; font-size: 0.85rem; display: flex; align-items: center; justify-content: flex-end; gap: 6px; margin-bottom: 8px;">
                                <?php esc_html_e('Next', 'rfplugin'); ?>
                                <span class="dashicons dashicons-arrow-right-alt2" style="font-size: 14px;" aria-hidden="true"></span>
                            </span>
                            <span style="color: white; font-weight: 600; line-height: 1.4;"><?php echo esc_html($next_post->post_title); ?></span>
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

<style>
/* Related Link Hover */
.rf-related-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

/* Navigation Link Hover */
.rf-nav-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

/* Focus Styles */
.rf-resource-single a:focus {
    outline: 2px solid var(--rf-primary);
    outline-offset: 2px;
}

/* Responsive */
@media (max-width: 768px) {
    .rf-resource-footer {
        grid-template-columns: 1fr !important;
    }
    .rf-post-navigation > div {
        grid-template-columns: 1fr !important;
    }
    .rf-breadcrumb ol {
        flex-wrap: wrap;
    }
}
</style>

<?php get_footer(); ?>
