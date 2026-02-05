<?php

/**
 * Archive Template for Services
 *
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="rf-archive-service rf-premium-ui" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>

    <div class="rf-container" style="padding: 80px 0;">
        <!-- Header Section -->
        <header class="rf-archive-header" style="text-align: center; margin-bottom: 60px;">
            <span class="rf-badge" aria-hidden="true"><?php esc_html_e('Our Services', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="font-size: clamp(2rem, 5vw, 3.5rem); margin: 20px 0;">
                <?php post_type_archive_title(); ?>
            </h1>

            <p class="rf-subtitle" style="max-width: 600px; margin: 0 auto 40px;">
                <?php esc_html_e('Comprehensive architectural solutions and technical services.', 'rfplugin'); ?>
            </p>
        </header>

        <!-- Service Grid -->
        <section class="rf-service-grid"
            aria-label="<?php esc_attr_e('Services', 'rfplugin'); ?>"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post();
                    $service_id = get_the_ID();
                    $categories = get_the_terms($service_id, 'rf_service_category');
                    $category_names = $categories && !is_wp_error($categories)
                        ? implode(', ', wp_list_pluck($categories, 'name'))
                        : '';
                ?>
                    <article class="rf-service-card rf-glass-card rf-fade-in"
                        itemscope
                        itemtype="https://schema.org/Service"
                        style="padding: 30px; display: flex; flex-direction: column; align-items: flex-start; transition: transform 0.3s ease, box-shadow 0.3s ease;">

                        <!-- Icon -->
                        <div class="rf-card-icon"
                            style="width: 48px; height: 48px; background: rgba(var(--rf-primary-rgb), 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;"
                            aria-hidden="true">
                            <span class="dashicons dashicons-hammer"
                                style="font-size: 24px; color: var(--rf-primary);"></span>
                        </div>

                        <!-- Title -->
                        <h2 class="rf-h4" itemprop="name" style="font-size: 1.25rem; margin: 0 0 12px; color: white; line-height: 1.4;">
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
                        <?php if ($category_names) : ?>
                            <div class="rf-card-meta" style="font-size: 0.8rem; color: #64748b; margin-bottom: 20px;">
                                <span itemprop="serviceType"><?php echo esc_html($category_names); ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Action Button -->
                        <a href="<?php the_permalink(); ?>"
                            class="rf-btn-link"
                            aria-label="<?php echo esc_attr(sprintf(__('View Service: %s', 'rfplugin'), get_the_title())); ?>"
                            style="margin-top: auto; color: var(--rf-primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: gap 0.2s ease;">
                            <?php esc_html_e('Learn More', 'rfplugin'); ?>
                            <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                        </a>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="rf-empty" style="grid-column: 1/-1; text-align: center; padding: 80px 40px;">
                    <div class="rf-glass-card" style="max-width: 500px; margin: 0 auto; padding: 60px;">
                        <h2 style="color: white; margin-bottom: 16px;"><?php esc_html_e('No Services Found', 'rfplugin'); ?></h2>
                        <p style="color: #94a3b8;"><?php esc_html_e('Check back soon for updates.', 'rfplugin'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <?php if (have_posts()) : ?>
            <nav class="rf-pagination"
                aria-label="<?php esc_attr_e('Service pagination', 'rfplugin'); ?>"
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
    /* Reuse existing styles or add specific ones */
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
        background: rgba(255, 255, 255, 0.05);
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-weight: 500;
    }

    .rf-pagination .page-numbers:hover,
    .rf-pagination .page-numbers.current {
        background: var(--rf-primary);
        border-color: var(--rf-primary);
        transform: translateY(-2px);
    }

    .rf-service-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .rf-btn-link:hover {
        gap: 12px !important;
    }

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
</style>

<?php get_footer(); ?>