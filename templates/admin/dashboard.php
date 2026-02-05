<?php

/**
 * Network Control Center & Site Dashboard
 *
 * Main dashboard for super admins (Network Center) and site admins (Site Dashboard).
 * Data is passed from Menu.php controller.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

use RFPlugin\Services\NetworkStats;
use RFPlugin\Admin\SuperAdminTheme;

// Data should be passed from Menu.php, but provide fallbacks just in case
if (!isset($stats)) {
    $stats = NetworkStats::getAggregatedStats();
}
if (!isset($activity)) {
    $activity = NetworkStats::getNetworkActivity(10);
}

// Ensure is_super is set
if (!isset($is_super)) {
    $is_super = SuperAdminTheme::isSuperAdmin();
}

// Default theme for JS initialization check
$saved_theme = 'dark';
?>

<div class="rf-admin-wrap" data-rf-theme="<?php echo esc_attr($saved_theme); ?>">

    <!-- Header -->
    <header class="rf-admin-header">
        <div class="rf-admin-header__content">
            <div class="rf-admin-header__left">
                <h1 class="rf-admin-header__title">
                    <span class="dashicons <?php echo $is_super ? 'dashicons-networking' : 'dashicons-dashboard'; ?>" aria-hidden="true"></span>
                    <?php
                    if ($is_super) {
                        esc_html_e('Network Control Center', 'rfplugin');
                    } else {
                        echo esc_html(get_bloginfo('name'));
                    }
                    ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php
                    if ($is_super) {
                        printf(
                            esc_html__('Managing %d sites across your network', 'rfplugin'),
                            $stats['total_sites']
                        );
                    } else {
                        esc_html_e('Site Overview & Management', 'rfplugin');
                    }
                    ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <!-- Theme Toggle -->
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon"
                    aria-label="<?php esc_attr_e('Toggle theme', 'rfplugin'); ?>">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>

                <?php if ($is_super && is_multisite()) : ?>
                    <a href="<?php echo esc_url(network_admin_url('settings.php')); ?>"
                        class="rf-admin-btn rf-admin-btn--primary">
                        <span class="dashicons dashicons-admin-settings" aria-hidden="true"></span>
                        <?php esc_html_e('Network Settings', 'rfplugin'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-settings')); ?>"
                        class="rf-admin-btn rf-admin-btn--primary">
                        <span class="dashicons dashicons-admin-settings" aria-hidden="true"></span>
                        <?php esc_html_e('Settings', 'rfplugin'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Stats Grid -->
    <section class="rf-admin-section">
        <div class="rf-admin-stats-grid">
            <?php if ($is_super) : ?>
                <div class="rf-admin-stat-card">
                    <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--primary">
                        <span class="dashicons dashicons-admin-multisite"></span>
                    </div>
                    <div class="rf-admin-stat-card__content">
                        <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['total_sites']); ?>">0</span>
                        <span class="rf-admin-stat-card__label"><?php esc_html_e('Total Sites', 'rfplugin'); ?></span>
                    </div>
                </div>

                <div class="rf-admin-stat-card">
                    <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--success">
                        <span class="dashicons dashicons-yes-alt"></span>
                    </div>
                    <div class="rf-admin-stat-card__content">
                        <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['active_sites']); ?>">0</span>
                        <span class="rf-admin-stat-card__label"><?php esc_html_e('Active Sites', 'rfplugin'); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="rf-admin-stat-card">
                <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--accent">
                    <span class="dashicons dashicons-products"></span>
                </div>
                <div class="rf-admin-stat-card__content">
                    <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['total_products']); ?>">0</span>
                    <span class="rf-admin-stat-card__label"><?php esc_html_e('Products', 'rfplugin'); ?></span>
                </div>
            </div>

            <div class="rf-admin-stat-card">
                <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--warning">
                    <span class="dashicons dashicons-media-spreadsheet"></span>
                </div>
                <div class="rf-admin-stat-card__content">
                    <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['total_invoices']); ?>">0</span>
                    <span class="rf-admin-stat-card__label"><?php esc_html_e('Invoices', 'rfplugin'); ?></span>
                </div>
            </div>

            <div class="rf-admin-stat-card">
                <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--info">
                    <span class="dashicons dashicons-admin-tools"></span>
                </div>
                <div class="rf-admin-stat-card__content">
                    <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['total_services']); ?>">0</span>
                    <span class="rf-admin-stat-card__label"><?php esc_html_e('Services', 'rfplugin'); ?></span>
                </div>
            </div>

            <div class="rf-admin-stat-card">
                <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--neutral">
                    <span class="dashicons dashicons-media-document"></span>
                </div>
                <div class="rf-admin-stat-card__content">
                    <span class="rf-admin-stat-card__value" data-counter="<?php echo esc_attr($stats['total_resources']); ?>">0</span>
                    <span class="rf-admin-stat-card__label"><?php esc_html_e('Resources', 'rfplugin'); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Site Cards (Super Admin Only) -->
    <?php if ($is_super && !empty($stats['sites'])) : ?>
        <section class="rf-admin-section">
            <div class="rf-admin-section__header">
                <h2 class="rf-admin-section__title">
                    <span class="dashicons dashicons-admin-site" aria-hidden="true"></span>
                    <?php esc_html_e('Network Sites', 'rfplugin'); ?>
                </h2>
                <a href="<?php echo esc_url(network_admin_url('site-new.php')); ?>" class="rf-admin-btn rf-admin-btn--sm">
                    <span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
                    <?php esc_html_e('Add Site', 'rfplugin'); ?>
                </a>
            </div>

            <div class="rf-admin-site-grid">
                <?php foreach ($stats['sites'] as $site) : ?>
                    <article class="rf-admin-site-card <?php echo $site['plugin_active'] ? '' : 'rf-admin-site-card--inactive'; ?>">
                        <header class="rf-admin-site-card__header">
                            <h3 class="rf-admin-site-card__title">
                                <?php echo esc_html($site['name'] ?: __('Unnamed Site', 'rfplugin')); ?>
                            </h3>
                            <span class="rf-admin-badge <?php echo $site['plugin_active'] ? 'rf-admin-badge--success' : 'rf-admin-badge--danger'; ?>">
                                <?php echo $site['plugin_active'] ? esc_html__('Active', 'rfplugin') : esc_html__('Inactive', 'rfplugin'); ?>
                            </span>
                        </header>

                        <div class="rf-admin-site-card__url">
                            <span class="dashicons dashicons-admin-links" aria-hidden="true"></span>
                            <a href="<?php echo esc_url($site['url']); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html($site['domain'] . $site['path']); ?>
                            </a>
                        </div>

                        <div class="rf-admin-site-card__stats">
                            <div class="rf-admin-site-card__stat">
                                <span class="rf-admin-site-card__stat-value"><?php echo esc_html($site['products']); ?></span>
                                <span class="rf-admin-site-card__stat-label"><?php esc_html_e('Prod', 'rfplugin'); ?></span>
                            </div>
                            <div class="rf-admin-site-card__stat">
                                <span class="rf-admin-site-card__stat-value"><?php echo esc_html($site['services']); ?></span>
                                <span class="rf-admin-site-card__stat-label"><?php esc_html_e('Serv', 'rfplugin'); ?></span>
                            </div>
                            <div class="rf-admin-site-card__stat">
                                <span class="rf-admin-site-card__stat-value"><?php echo esc_html($site['invoices']); ?></span>
                                <span class="rf-admin-site-card__stat-label"><?php esc_html_e('Inv', 'rfplugin'); ?></span>
                            </div>
                            <div class="rf-admin-site-card__stat">
                                <span class="rf-admin-site-card__stat-value"><?php echo esc_html($site['resources']); ?></span>
                                <span class="rf-admin-site-card__stat-label"><?php esc_html_e('Res', 'rfplugin'); ?></span>
                            </div>
                        </div>

                        <footer class="rf-admin-site-card__actions">
                            <a href="<?php echo esc_url($site['admin_url']); ?>" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--ghost">
                                <span class="dashicons dashicons-dashboard" aria-hidden="true"></span>
                                <?php esc_html_e('Dashboard', 'rfplugin'); ?>
                            </a>
                            <a href="<?php echo esc_url(network_admin_url('site-info.php?id=' . $site['id'])); ?>" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--ghost">
                                <span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>
                                <?php esc_html_e('Manage', 'rfplugin'); ?>
                            </a>
                        </footer>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Activity Feed -->
    <section class="rf-admin-section">
        <div class="rf-admin-section__header">
            <h2 class="rf-admin-section__title">
                <span class="dashicons dashicons-clock" aria-hidden="true"></span>
                <?php esc_html_e('Recent Activity', 'rfplugin'); ?>
                <?php if (!$is_super) echo ' <small class="rf-admin-text-muted" style="margin-left: 10px; font-weight: 400; font-size: 0.8em">' . esc_html__('on this site', 'rfplugin') . '</small>'; ?>
            </h2>
        </div>

        <div class="rf-admin-card">
            <div class="rf-admin-activity-list">
                <?php if (empty($activity)) : ?>
                    <p class="rf-admin-empty-state"><?php esc_html_e('No recent activity found.', 'rfplugin'); ?></p>
                <?php else : ?>
                    <?php foreach ($activity as $item) : ?>
                        <div class="rf-admin-activity-item">
                            <div class="rf-admin-activity-item__icon">
                                <span class="dashicons dashicons-edit"></span>
                            </div>
                            <div class="rf-admin-activity-item__content">
                                <span class="rf-admin-activity-item__title">
                                    <?php echo esc_html($item['post_title']); ?>
                                </span>
                                <span class="rf-admin-activity-item__meta">
                                    <span class="rf-admin-badge rf-admin-badge--neutral"><?php echo esc_html($item['post_type_label']); ?></span>
                                    <span><?php echo esc_html($item['site_name']); ?></span>
                                    <time datetime="<?php echo esc_attr(date('c', $item['modified'])); ?>">
                                        <?php echo esc_html(human_time_diff($item['modified'], time()) . ' ' . __('ago', 'rfplugin')); ?>
                                    </time>
                                </span>
                            </div>
                            <?php if ($item['edit_url']) : ?>
                                <a href="<?php echo esc_url($item['edit_url']); ?>" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--ghost">
                                    <?php esc_html_e('Edit', 'rfplugin'); ?>
                                </a>
                            <?php endif; ?>
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
            const duration = 1000;
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