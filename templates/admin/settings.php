<?php
/**
 * Modern RoyalFoam Settings Template
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
                    <span class="dashicons dashicons-admin-settings" aria-hidden="true"></span>
                    <?php esc_html_e('Plugin Settings', 'rfplugin'); ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php esc_html_e('Configure your enterprise foam management environment.', 'rfplugin'); ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
                <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam')); ?>" class="rf-admin-btn rf-admin-btn--ghost">
                    <span class="dashicons dashicons-dashboard"></span>
                    <?php esc_html_e('Dashboard', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </header>

    <?php settings_errors('rfplugin_messages'); ?>

    <nav class="rf-settings-tabs">
        <a href="#general" class="rf-tab-link active" data-tab="general"><?php esc_html_e('General', 'rfplugin'); ?></a>
        <a href="#zoho" class="rf-tab-link" data-tab="zoho"><?php esc_html_e('Zoho CRM', 'rfplugin'); ?></a>
        <a href="#maintenance" class="rf-tab-link" data-tab="maintenance"><?php esc_html_e('Maintenance', 'rfplugin'); ?></a>
    </nav>

    <form method="post" action="">
        <?php wp_nonce_field('rfplugin_settings'); ?>

        <!-- General Settings -->
        <div id="general" class="rf-tab-content active">
            <div class="rf-admin-card">
                <h3 class="rf-admin-card__title rf-admin-mb-6"><?php esc_html_e('Core Configurations', 'rfplugin'); ?></h3>
                
                <div class="rf-form-layout">
                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_invoice_prefix"><?php esc_html_e('Invoice Prefix', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Prefix for system-generated documents.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <input type="text" id="rfplugin_invoice_prefix" name="rfplugin_invoice_prefix" value="<?php echo esc_attr(get_option('rfplugin_invoice_prefix', 'RF')); ?>" />
                        </div>
                    </div>

                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label><?php esc_html_e('Modules', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Toggle advanced system modules.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <div class="rf-admin-flex rf-admin-flex-wrap rf-admin-gap-4">
                                <label class="rf-toggle-item">
                                    <input type="checkbox" name="rfplugin_enable_pdf" value="1" <?php checked(get_option('rfplugin_enable_pdf'), 1); ?> />
                                    <span><?php esc_html_e('PDF Generation', 'rfplugin'); ?></span>
                                </label>
                                <label class="rf-toggle-item">
                                    <input type="checkbox" name="rfplugin_enable_erp" value="1" <?php checked(get_option('rfplugin_enable_erp'), 1); ?> />
                                    <span><?php esc_html_e('ERP Sync API', 'rfplugin'); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Settings -->
        <div id="maintenance" class="rf-tab-content">
            <div class="rf-admin-card">
                <h3 class="rf-admin-card__title rf-admin-mb-6"><?php esc_html_e('System Maintenance', 'rfplugin'); ?></h3>
                
                <div class="rf-form-layout">
                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label><?php esc_html_e('Import Services', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Populate system with production categories and data.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control rf-admin-grid rf-admin-grid-2">
                            <button type="submit" name="rfplugin_import_all" class="rf-admin-btn rf-admin-btn--primary" style="grid-column: 1/-1">
                                <span class="dashicons dashicons-database-import"></span> <?php esc_html_e('Full System Import', 'rfplugin'); ?>
                            </button>
                            <button type="submit" name="rfplugin_import_products" class="rf-admin-btn rf-admin-btn--secondary">
                                <?php esc_html_e('Products', 'rfplugin'); ?>
                            </button>
                            <button type="submit" name="rfplugin_import_resources" class="rf-admin-btn rf-admin-btn--secondary">
                                <?php esc_html_e('Resources', 'rfplugin'); ?>
                            </button>
                        </div>
                    </div>

                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label><?php esc_html_e('Cache & Rewrites', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Fix permalink issues and clear system state.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <button type="submit" name="rfplugin_flush_rules" class="rf-admin-btn rf-admin-btn--secondary">
                                <span class="dashicons dashicons-update"></span> <?php esc_html_e('Flush Rewrite Rules', 'rfplugin'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zoho CRM Settings -->
        <div id="zoho" class="rf-tab-content">
            <div class="rf-admin-card">
                <h3 class="rf-admin-card__title rf-admin-mb-6"><?php esc_html_e('Zoho CRM Integration', 'rfplugin'); ?></h3>
                
                <div class="rf-form-layout">
                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_zoho_client_id"><?php esc_html_e('Client ID', 'rfplugin'); ?></label>
                        </div>
                        <div class="rf-form-control">
                            <input type="text" id="rfplugin_zoho_client_id" name="rfplugin_zoho_client_id" value="<?php echo esc_attr(get_option('rfplugin_zoho_client_id')); ?>" />
                        </div>
                    </div>

                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_zoho_client_secret"><?php esc_html_e('Client Secret', 'rfplugin'); ?></label>
                        </div>
                        <div class="rf-form-control">
                            <input type="password" id="rfplugin_zoho_client_secret" name="rfplugin_zoho_client_secret" value="<?php echo esc_attr(get_option('rfplugin_zoho_client_secret')); ?>" />
                        </div>
                    </div>
                </div>

                <div class="rf-admin-alert rf-admin-alert--info rf-admin-mt-6">
                    <span class="dashicons dashicons-info"></span>
                    <?php printf(esc_html__('Consult the %s for API setup guide.', 'rfplugin'), '<a href="' . esc_url(admin_url('admin.php?page=royalfoam-docs&doc=zoho-crm')) . '">' . esc_html__('technical docs', 'rfplugin') . '</a>'); ?>
                </div>
            </div>
        </div>

        <div class="rf-admin-mt-8">
            <input type="submit" name="rfplugin_save_settings" class="rf-admin-btn rf-admin-btn--primary" value="<?php esc_attr_e('Save Configuration', 'rfplugin'); ?>" />
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs logic
    const tabs = document.querySelectorAll('.rf-tab-link');
    const contents = document.querySelectorAll('.rf-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-tab');

            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(target).classList.add('active');
            history.pushState(null, null, '#' + target);
        });
    });

    // Handle initial hash
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(hash)) {
        document.querySelector(`[data-tab="${hash}"]`).click();
    }

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
    }
});
</script>