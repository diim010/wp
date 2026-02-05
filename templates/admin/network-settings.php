<?php

/**
 * Modern RoyalFoam Network Settings
 *
 * Optimized for Network Control Center with role-based theming.
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
                    <span class="dashicons dashicons-networking" aria-hidden="true"></span>
                    <?php esc_html_e('Network Settings', 'rfplugin'); ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php esc_html_e('Standardize enterprise configurations across all subsites.', 'rfplugin'); ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
                <a href="<?php echo esc_url(network_admin_url('admin.php?page=royalfoam-network')); ?>" class="rf-admin-btn rf-admin-btn--ghost">
                    <span class="dashicons dashicons-dashboard"></span>
                    <?php esc_html_e('Network Dashboard', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </header>

    <?php settings_errors('rfplugin_network_messages'); ?>

    <form method="post" action="" class="rf-admin-animate-in">
        <?php wp_nonce_field('rfplugin_network_settings'); ?>

        <!-- Default Network Standards -->
        <div class="rf-admin-card">
            <h3 class="rf-admin-card__title rf-admin-mb-4"><?php esc_html_e('Default Network Standards', 'rfplugin'); ?></h3>
            <p class="rf-admin-text-secondary rf-admin-mb-6"><?php esc_html_e('These configurations serve as the foundation for all sites in the RoyalFoam network.', 'rfplugin'); ?></p>

            <div class="rf-form-layout">
                <div class="rf-form-row">
                    <div class="rf-form-label">
                        <label for="rfplugin_network_invoice_prefix"><?php esc_html_e('Network Invoice Prefix', 'rfplugin'); ?></label>
                        <p class="description"><?php esc_html_e('Inherited by all new subsites by default.', 'rfplugin'); ?></p>
                    </div>
                    <div class="rf-form-control">
                        <input type="text" id="rfplugin_network_invoice_prefix" name="rfplugin_network_invoice_prefix" value="<?php echo esc_attr(get_site_option('rfplugin_network_invoice_prefix', 'RF')); ?>" class="regular-text" />
                    </div>
                </div>

                <div class="rf-form-row">
                    <div class="rf-form-label">
                        <label><?php esc_html_e('Global Module Enforcement', 'rfplugin'); ?></label>
                        <p class="description"><?php esc_html_e('Strictly enable or disable modules network-wide.', 'rfplugin'); ?></p>
                    </div>
                    <div class="rf-form-control rf-toggle-group">
                        <label class="rf-toggle-item">
                            <input type="checkbox" name="rfplugin_network_enable_pdf" value="1" <?php checked(get_site_option('rfplugin_network_enable_pdf'), 1); ?> />
                            <span><?php esc_html_e('Enable PDF Processing for All Sites', 'rfplugin'); ?></span>
                        </label>
                        <label class="rf-toggle-item">
                            <input type="checkbox" name="rfplugin_network_enable_erp" value="1" <?php checked(get_site_option('rfplugin_network_enable_erp'), 1); ?> />
                            <span><?php esc_html_e('Enable Global ERP Sync', 'rfplugin'); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="rf-admin-mt-8 rf-admin-pt-6 rf-admin-border-t">
                <input type="submit" name="rfplugin_save_network_settings" class="rf-admin-btn rf-admin-btn--primary" value="<?php esc_attr_e('Synchronize Network', 'rfplugin'); ?>" />
            </div>
        </div>

        <!-- Administrative Utilities -->
        <div class="rf-admin-card rf-admin-mt-6">
            <h3 class="rf-admin-card__title rf-admin-mb-4"><?php esc_html_e('Administrative Utilities', 'rfplugin'); ?></h3>
            <div class="rf-admin-grid rf-admin-grid-2">
                <div class="rf-admin-card rf-admin-bg-elevated rf-admin-border-0">
                    <div class="rf-admin-flex rf-admin-justify-between rf-admin-items-center">
                        <div>
                            <strong class="rf-admin-block rf-admin-mb-1"><?php esc_html_e('Global Configuration Push', 'rfplugin'); ?></strong>
                            <p class="rf-admin-text-sm rf-admin-text-muted"><?php esc_html_e('Force network defaults to all active subsites.', 'rfplugin'); ?></p>
                        </div>
                        <button type="button" id="rfplugin-sync-settings" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--outline"><?php esc_html_e('Execute Sync', 'rfplugin'); ?></button>
                    </div>
                </div>
                <div class="rf-admin-card rf-admin-bg-elevated rf-admin-border-0">
                    <div class="rf-admin-flex rf-admin-justify-between rf-admin-items-center">
                        <div>
                            <strong class="rf-admin-block rf-admin-mb-1"><?php esc_html_e('Analytics Recalculation', 'rfplugin'); ?></strong>
                            <p class="rf-admin-text-sm rf-admin-text-muted"><?php esc_html_e('Refresh global counters and usage statistics.', 'rfplugin'); ?></p>
                        </div>
                        <button type="button" id="rfplugin-refresh-network-stats" class="rf-admin-btn rf-admin-btn--sm rf-admin-btn--outline"><?php esc_html_e('Refresh Data', 'rfplugin'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .rf-form-layout {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .rf-form-row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
    }

    .rf-form-label label {
        display: block;
        font-weight: 600;
        color: var(--rf-admin-text);
        margin-bottom: 4px;
    }

    .rf-form-label .description {
        color: var(--rf-admin-text-secondary);
    }

    .rf-toggle-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .rf-toggle-item {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        color: var(--rf-admin-text);
    }

    .rf-toggle-item input {
        margin: 0;
    }

    @media (max-width: 782px) {
        .rf-form-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }
</style>

<script>
    jQuery(document).ready(function($) {
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

        $('#rfplugin-sync-settings').on('click', function() {
            if (!confirm('<?php esc_html_e("This operation will override settings on all subsites. Are you sure you want to proceed?", "rfplugin"); ?>')) {
                return;
            }
            $(this).attr('disabled', true).text('Processing...');
            $.post(ajaxurl, {
                action: 'rfplugin_sync_network_settings',
                nonce: '<?php echo wp_create_nonce("rfplugin_network_admin"); ?>'
            }, function(response) {
                alert(response.success ? 'Network synchronized successfully' : 'Handshake failed: ' + response.data);
                $('#rfplugin-sync-settings').attr('disabled', false).text('Execute Sync');
            });
        });

        $('#rfplugin-refresh-network-stats').on('click', function() {
            location.reload();
        });
    });
</script>