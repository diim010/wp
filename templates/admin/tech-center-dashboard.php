<?php

/**
 * Unified Tech Center Admin Dashboard
 *
 * Aggregates technical resources across the entire multisite network.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

use RFPlugin\Admin\SuperAdminTheme;
use RFPlugin\Services\NetworkStats;

$is_super = SuperAdminTheme::isSuperAdmin();
$saved_theme = 'dark'; // Default for super admin

// Fetch network aggregated data
$stats = NetworkStats::getAggregatedStats();
$resources = NetworkStats::getNetworkResources();
?>

<div class="rf-admin-wrap" data-rf-theme="<?php echo esc_attr($saved_theme); ?>">

    <!-- Header -->
    <header class="rf-admin-header">
        <div class="rf-admin-header__content">
            <div class="rf-admin-header__left">
                <h1 class="rf-admin-header__title">
                    <span class="dashicons dashicons-category" aria-hidden="true"></span>
                    <?php esc_html_e('Network Tech Center', 'rfplugin'); ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php esc_html_e('Unified library for technical documentation, FAQs, and interactive media across all sites.', 'rfplugin'); ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon" aria-label="<?php esc_attr_e('Toggle theme', 'rfplugin'); ?>">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=rf_resource')); ?>" class="rf-admin-btn rf-admin-btn--primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php esc_html_e('Add Resource', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </header>

    <div class="rf-admin-grid rf-admin-grid-3">
        <!-- Asset Stats -->
        <div class="rf-admin-stat-card">
            <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--primary">
                <span class="dashicons dashicons-category"></span>
            </div>
            <div class="rf-admin-stat-card__content">
                <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['total_resources'] ?? $stats['resources']); ?>">0</span>
                <span class="rf-admin-stat-card__label"><?php esc_html_e('Total Assets', 'rfplugin'); ?></span>
            </div>
        </div>

        <!-- Distribution -->
        <div class="rf-admin-card">
            <h3 class="rf-admin-card__title rf-admin-mb-4"><?php esc_html_e('Asset Categories', 'rfplugin'); ?></h3>
            <div class="rf-admin-resource-distribution">
                <?php
                $types = [
                    'faq' => ['label' => __('FAQs', 'rfplugin'), 'icon' => 'editor-help'],
                    'tech_doc' => ['label' => __('Docs', 'rfplugin'), 'icon' => 'media-document'],
                    'video' => ['label' => __('Videos', 'rfplugin'), 'icon' => 'video-alt3'],
                    '3d_model' => ['label' => __('3D Models', 'rfplugin'), 'icon' => 'visibility'],
                ];
                foreach ($types as $slug => $data) : ?>
                    <div class="rf-admin-flex rf-admin-justify-between rf-admin-items-center rf-admin-mb-2">
                        <span class="rf-admin-flex rf-admin-items-center rf-admin-gap-2">
                            <span class="dashicons dashicons-<?php echo $data['icon']; ?> rf-admin-text-muted"></span>
                            <?php echo $data['label']; ?>
                        </span>
                        <a href="<?php echo esc_url(admin_url('edit.php?post_type=rf_resource&rf_resource_type=' . $slug)); ?>" class="dashicons dashicons-arrow-right-alt2 rf-admin-text-muted"></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Management Shortcuts -->
        <div class="rf-admin-card">
            <h3 class="rf-admin-card__title rf-admin-mb-4"><?php esc_html_e('Quick Links', 'rfplugin'); ?></h3>
            <ul class="rf-admin-activity-list">
                <li class="rf-admin-activity-item rf-admin-p-0 rf-admin-border-0 rf-admin-mb-2">
                    <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=rf_resource_category&post_type=rf_resource')); ?>" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--ghost rf-admin-w-full">
                        <span class="dashicons dashicons-tag"></span> <?php esc_html_e('Manage Categories', 'rfplugin'); ?>
                    </a>
                </li>
                <li class="rf-admin-activity-item rf-admin-p-0 rf-admin-border-0">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-security')); ?>" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--ghost rf-admin-w-full">
                        <span class="dashicons dashicons-shield"></span> <?php esc_html_e('Security Dashboard', 'rfplugin'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Recent Activity -->
    <section class="rf-admin-section rf-admin-mt-8">
        <h2 class="rf-admin-section__title rf-admin-mb-4">
            <span class="dashicons dashicons-calendar" aria-hidden="true"></span>
            <?php esc_html_e('Latest Network Resources', 'rfplugin'); ?>
        </h2>

        <div class="rf-admin-card rf-admin-p-0">
            <div class="rf-admin-activity-list">
                <?php if (empty($resources)) : ?>
                    <div class="rf-admin-p-8 rf-admin-text-center rf-admin-text-muted">
                        <?php esc_html_e('No resources found in the network library.', 'rfplugin'); ?>
                    </div>
                <?php else : ?>
                    <?php foreach (array_slice($resources, 0, 15) as $res) : ?>
                        <div class="rf-admin-activity-item rf-admin-px-6">
                            <div class="rf-admin-activity-item__content">
                                <span class="rf-admin-activity-item__icon">
                                    <span class="dashicons dashicons-media-document"></span>
                                </span>
                                <div>
                                    <h4 class="rf-admin-activity-item__title rf-admin-m-0"><?php echo esc_html($res['title']); ?></h4>
                                    <div class="rf-admin-activity-item__meta">
                                        <span class="rf-admin-badge rf-admin-badge--neutral"><?php echo esc_html($res['type']); ?></span>
                                        <?php if (isset($res['site_name'])) : ?>
                                            <span><?php echo esc_html($res['site_name']); ?></span>
                                        <?php endif; ?>
                                        <time><?php echo esc_html(human_time_diff(strtotime($res['modified']), current_time('timestamp'))); ?> <?php esc_html_e('ago', 'rfplugin'); ?></time>
                                    </div>
                                </div>
                            </div>
                            <div class="rf-admin-flex rf-admin-gap-2">
                                <a href="<?php echo esc_url($res['url']); ?>" target="_blank" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--ghost">
                                    <span class="dashicons dashicons-external"></span>
                                </a>
                                <a href="<?php echo esc_url($res['edit_url']); ?>" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--primary">
                                    <?php esc_html_e('Edit', 'rfplugin'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<script>
    (function() {
        // Theme toggle
        const toggle = document.getElementById('rf-theme-toggle');
        const wrap = document.querySelector('.rf-admin-wrap');

        if (toggle && wrap) {
            toggle.addEventListener('click', function() {
                const current = wrap.dataset.rfTheme || 'dark';
                const next = current === 'dark' ? 'light' : 'dark';
                wrap.dataset.rfTheme = next;
                document.documentElement.dataset.rfTheme = next;
                localStorage.setItem('rf-admin-theme', next);
            });

            // Apply saved theme
            const saved = localStorage.getItem('rf-admin-theme') || 'dark';
            wrap.dataset.rfTheme = saved;
            document.documentElement.dataset.rfTheme = saved;
        }

        // Animated counters
        const counters = document.querySelectorAll('[data-counter]');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.counter, 10);
            if (target === 0) return;

            const duration = 800;
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const update = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.ceil(current);
                    requestAnimationFrame(update);
                } else {
                    counter.textContent = target;
                }
            };

            requestAnimationFrame(update);
        });
    })();
</script>