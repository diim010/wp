<?php

/**
 * Single Service Template
 *
 * Template for displaying a single service.
 *
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header();

$service_id = get_the_ID();
$categories = get_the_terms($service_id, 'rf_service_category');
$primary_category = ($categories && !is_wp_error($categories)) ? $categories[0] : null;

?>

<main id="main-content" class="rf-service-single rf-premium-ui" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <article class="rf-container"
        style="padding: 100px 0;"
        itemscope
        itemtype="https://schema.org/Service">

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
                    <a itemprop="item" href="<?php echo esc_url(get_post_type_archive_link('rf_service')); ?>" style="color: #64748b; text-decoration: none;">
                        <span itemprop="name"><?php esc_html_e('Services', 'rfplugin'); ?></span>
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
                    <span itemprop="name" style="color: white; font-weight: 600;"><?php the_title(); ?></span>
                    <meta itemprop="position" content="<?php echo $primary_category ? '4' : '3'; ?>" />
                </li>
            </ol>
        </nav>

        <!-- Header Section -->
        <header class="rf-service-intro" style="margin-bottom: 60px; text-align: center;">
            <span class="rf-badge"
                style="background: rgba(var(--rf-primary-rgb), 0.1); color: var(--rf-primary); padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 8px;">
                <span class="dashicons dashicons-hammer" style="font-size: 14px; width: 14px; height: 14px;" aria-hidden="true"></span>
                <?php esc_html_e('Service', 'rfplugin'); ?>
            </span>

            <h1 class="rf-title"
                itemprop="name"
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
        </header>

        <!-- Main Content Area -->
        <div class="rf-service-content rf-glass-card"
            style="padding: clamp(30px, 5vw, 60px); position: relative; overflow: hidden; border-radius: 24px;">
            <div class="rf-content-body">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- Footer: Related Items -->
        <footer class="rf-service-footer" style="margin-top: 60px;">
            <!-- Placeholder for related case studies or products -->
        </footer>

        <!-- Navigation -->
        <nav class="rf-post-navigation"
            aria-label="<?php esc_attr_e('Service navigation', 'rfplugin'); ?>"
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
    </article>
</main>

<style>
    /* Navigation Link Hover */
    .rf-nav-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .rf-post-navigation>div {
            grid-template-columns: 1fr !important;
        }

        .rf-breadcrumb ol {
            flex-wrap: wrap;
        }
    }
</style>

<?php get_footer(); ?>