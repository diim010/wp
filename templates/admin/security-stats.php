<?php
/**
 * Modern RoyalFoam Security Dashboard
 * 
 * Optimized for Network Control Center with role-based theming.
 *
 * @var array $stats
 */

defined('ABSPATH') || exit;

use RFPlugin\Admin\SuperAdminTheme;

$saved_theme = 'dark'; // Default
?>

<div class="rf-admin-wrap" data-rf-theme="<?php echo esc_attr($saved_theme); ?>">
    <header class="rf-admin-header">
        <div class="rf-admin-header__content">
            <div class="rf-admin-header__left">
                <h1 class="rf-admin-header__title">
                    <span class="dashicons dashicons-shield-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Security & Asset Guard', 'rfplugin'); ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php esc_html_e('Monitoring asset delivery integrity and network-wide protection.', 'rfplugin'); ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
                <form method="post" action="" onsubmit="return confirm('<?php esc_attr_e('Purge all security history and active locks?', 'rfplugin'); ?>');">
                    <?php wp_nonce_field('rfplugin_clear_security'); ?>
                    <button type="submit" name="rfplugin_clear_security_data" class="rf-admin-btn rf-admin-btn--ghost" style="color: var(--rf-admin-danger);">
                        <span class="dashicons dashicons-trash"></span>
                        <?php esc_html_e('Clear History', 'rfplugin'); ?>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <?php settings_errors('rfplugin_messages'); ?>

    <!-- Control Center Stats -->
    <div class="rf-admin-stats-grid">
        <div class="rf-admin-stat-card">
            <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--primary">
                <span class="dashicons dashicons-cloud-download"></span>
            </div>
            <div class="rf-admin-stat-card__content">
                <span class="rf-admin-stat-card__value" data-counter="<?php echo (int)$stats['total_downloads']; ?>">0</span>
                <span class="rf-admin-stat-card__label"><?php esc_html_e('Total Deliveries', 'rfplugin'); ?></span>
            </div>
        </div>
        
        <div class="rf-admin-stat-card">
            <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--info">
                <span class="dashicons dashicons-lock"></span>
            </div>
            <div class="rf-admin-stat-card__content">
                <span class="rf-admin-stat-card__value" data-counter="<?php echo (int)$stats['active_locks']; ?>">0</span>
                <span class="rf-admin-stat-card__label"><?php esc_html_e('Active Locks', 'rfplugin'); ?></span>
            </div>
        </div>

        <div class="rf-admin-stat-card">
            <div class="rf-admin-stat-card__icon rf-admin-stat-card__icon--danger">
                <span class="dashicons dashicons-warning"></span>
            </div>
            <div class="rf-admin-stat-card__content">
                <span class="rf-admin-stat-card__value" data-counter="<?php echo (int)$stats['suspicious_count']; ?>">0</span>
                <span class="rf-admin-stat-card__label"><?php esc_html_e('Threats blocked', 'rfplugin'); ?></span>
            </div>
        </div>
    </div>

    <!-- Monitoring Feed -->
    <section class="rf-admin-section rf-admin-mt-8">
        <div class="rf-admin-section__header">
            <h2 class="rf-admin-section__title">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e('Real-time Delivery Monitor', 'rfplugin'); ?>
            </h2>
            
            <form method="get" action="" class="rf-admin-flex rf-admin-gap-4">
                <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
                <input type="text" name="ip_filter" value="<?php echo esc_attr($_GET['ip_filter'] ?? ''); ?>" placeholder="Filter IP..." class="rf-admin-input" style="width: 150px; height: 32px; padding: 4px 12px; font-size: 13px;">
                <button type="submit" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--secondary">
                    <?php esc_html_e('Filter', 'rfplugin'); ?>
                </button>
            </form>
        </div>

        <div class="rf-admin-card rf-admin-p-0">
            <div class="rf-admin-activity-list">
                <?php if (empty($stats['recent_history'])) : ?>
                    <div class="rf-admin-p-10 rf-admin-text-center rf-admin-text-muted">
                        <?php esc_html_e('No delivery activity logged yet.', 'rfplugin'); ?>
                    </div>
                <?php else : ?>
                    <div class="rf-admin-monitoring-table-wrap">
                        <table class="rf-admin-monitoring-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Timestamp', 'rfplugin'); ?></th>
                                    <th><?php esc_html_e('Asset', 'rfplugin'); ?></th>
                                    <th><?php esc_html_e('Client IP', 'rfplugin'); ?></th>
                                    <th><?php esc_html_e('Identity', 'rfplugin'); ?></th>
                                    <th><?php esc_html_e('Status', 'rfplugin'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_history'] as $item) : ?>
                                    <tr>
                                        <td><time class="rf-admin-text-muted"><?php echo esc_html($item->download_at); ?></time></td>
                                        <td><strong><?php echo esc_html($item->post_title ?: __('Unknown', 'rfplugin')); ?></strong></td>
                                        <td><code><?php echo esc_html($item->ip_address); ?></code></td>
                                        <td>
                                            <?php 
                                            if ($item->user_id) {
                                                $u = get_userdata($item->user_id);
                                                echo esc_html($u ? $u->display_name : __('Guest', 'rfplugin'));
                                            } else {
                                                esc_html_e('Guest', 'rfplugin');
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($item->is_suspicious) : ?>
                                                <span class="rf-admin-badge rf-admin-badge--danger">
                                                    <span class="dashicons dashicons-warning"></span>
                                                    <?php esc_html_e('Suspicious', 'rfplugin'); ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="rf-admin-badge rf-admin-badge--success">
                                                    <?php esc_html_e('Secure', 'rfplugin'); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if (!empty($stats['dangerous_users'])) : ?>
    <section class="rf-admin-section rf-admin-mt-8">
        <h2 class="rf-admin-section__title rf-admin-mb-4" style="color: var(--rf-admin-danger);">
            <span class="dashicons dashicons-shield"></span>
            <?php esc_html_e('Network Blacklist', 'rfplugin'); ?>
        </h2>
        <div class="rf-admin-site-grid">
            <?php foreach ($stats['dangerous_users'] as $user) : ?>
                <div class="rf-admin-card rf-admin-flex rf-admin-justify-between rf-admin-items-center">
                    <div>
                        <code style="font-size: 1.1rem; color: var(--rf-admin-danger);"><?php echo esc_html($user->ip_address); ?></code>
                        <p class="rf-admin-text-muted rf-admin-mt-1">
                            <?php printf(esc_html__('%d suspicious attempts detected', 'rfplugin'), $user->suspicious_hits); ?>
                        </p>
                    </div>
                    <span class="rf-admin-badge rf-admin-badge--danger"><?php esc_html_e('BLOCKED', 'rfplugin'); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle
    const toggle = document.getElementById('rf-theme-toggle');
    const wrap = document.querySelector('.rf-admin-wrap');
    if (toggle && wrap) {
        toggle.addEventListener('click', () => {
            const current = wrap.dataset.rfTheme || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            wrap.dataset.rfTheme = next;
            document.documentElement.dataset.rfTheme = next;
            localStorage.setItem('rf-admin-theme', next);
        });
        
        const saved = localStorage.getItem('rf-admin-theme') || 'dark';
        wrap.dataset.rfTheme = saved;
        document.documentElement.dataset.rfTheme = saved;
    }

    // Numbers animation
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach(c => {
        const target = parseInt(c.dataset.counter, 10);
        if (target === 0) return;
        let count = 0;
        const inc = target / 50;
        const timer = setInterval(() => {
            count += inc;
            if (count >= target) {
                c.innerText = target.toLocaleString();
                clearInterval(timer);
            } else {
                c.innerText = Math.floor(count).toLocaleString();
            }
        }, 20);
    });
});
</script>

<style>
.rf-admin-monitoring-table-wrap { overflow-x: auto; }
.rf-admin-monitoring-table { width: 100%; border-collapse: collapse; }
.rf-admin-monitoring-table th { text-align: left; padding: 16px 24px; border-bottom: 2px solid var(--rf-admin-border); color: var(--rf-admin-text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
.rf-admin-monitoring-table td { padding: 16px 24px; border-bottom: 1px solid var(--rf-admin-border); color: var(--rf-admin-text); font-size: 0.875rem; }
.rf-admin-monitoring-table tr:hover td { background: var(--rf-admin-bg-elevated); }
.rf-superadmin-theme .rf-admin-monitoring-table td code { background: rgba(0,0,0,0.3); color: var(--rf-admin-primary); }
</style>
