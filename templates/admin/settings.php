<?php
/**
 * Modern RoyalFoam Settings Template
 */
?>
<div class="wrap rf-admin-wrap">
    <header class="rf-dashboard-header rf-fade-in">
        <div class="rf-header-content">
            <h1 class="rf-h1"><?php esc_html_e('Plugin Settings', 'rfplugin'); ?></h1>
            <p class="rf-p"><?php esc_html_e('Configure your enterprise foam management environment.', 'rfplugin'); ?></p>
        </div>
        <div class="rf-header-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam')); ?>" class="rf-btn rf-btn-outline">
                <span class="dashicons dashicons-dashboard" style="margin-right: 8px;"></span>
                <?php esc_html_e('Return to Dashboard', 'rfplugin'); ?>
            </a>
        </div>
    </header>

    <?php settings_errors('rfplugin_messages'); ?>

    <nav class="rf-settings-tabs rf-fade-in">
        <a href="#general" class="rf-tab-link active" data-tab="general"><?php esc_html_e('General', 'rfplugin'); ?></a>
        <a href="#zoho" class="rf-tab-link" data-tab="zoho"><?php esc_html_e('Zoho CRM', 'rfplugin'); ?></a>
        <a href="#service" class="rf-tab-link" data-tab="service"><?php esc_html_e('Service', 'rfplugin'); ?></a>
    </nav>

    <form method="post" action="" class="rf-fade-in" style="animation-delay: 0.1s;">
        <?php wp_nonce_field('rfplugin_settings'); ?>

        <!-- General Settings Tab -->
        <div id="general" class="rf-tab-content active">
            <div class="rf-glass-card" style="margin-bottom: 30px;">
                <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('General Configurations', 'rfplugin'); ?></h2>
                
                <div class="rf-form-layout">
                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_invoice_prefix"><?php esc_html_e('Invoice Prefix', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Global prefix for billing documents.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <input type="text" id="rfplugin_invoice_prefix" name="rfplugin_invoice_prefix" value="<?php echo esc_attr(get_option('rfplugin_invoice_prefix', 'RF')); ?>" class="regular-text" />
                        </div>
                    </div>

                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label><?php esc_html_e('Feature Toggles', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Enable or disable advanced modules.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control rf-toggle-group">
                            <label class="rf-toggle-item">
                                <input type="checkbox" name="rfplugin_enable_pdf" value="1" <?php checked(get_option('rfplugin_enable_pdf'), 1); ?> />
                                <span><?php esc_html_e('PDF Export Module', 'rfplugin'); ?></span>
                            </label>
                            <label class="rf-toggle-item">
                                <input type="checkbox" name="rfplugin_enable_erp" value="1" <?php checked(get_option('rfplugin_enable_erp'), 1); ?> />
                                <span><?php esc_html_e('ERP Integration API', 'rfplugin'); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Settings Tab -->
        <div id="service" class="rf-tab-content">
            <div class="rf-glass-card" style="margin-bottom: 30px;">
                <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('Service & Maintenance Utilities', 'rfplugin'); ?></h2>
                <p class="rf-p" style="margin-bottom: 24px;"><?php esc_html_e('Perform system maintenance and data management tasks.', 'rfplugin'); ?></p>
                
                <div class="rf-form-layout">
                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label><?php esc_html_e('Batch Data Import', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Populate the system with production-ready test data from XML files.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <button type="submit" name="rfplugin_import_all" class="rf-btn" style="grid-column: 1 / -1; justify-content: center; background: var(--rf-primary); color: white; border: none; font-weight: 700;">
                                <span class="dashicons dashicons-database-import" style="margin-right: 8px;"></span>
                                <?php esc_html_e('Import All Test Data', 'rfplugin'); ?>
                            </button>
                            
                            <button type="submit" name="rfplugin_import_products" class="rf-btn rf-btn-outline" style="font-size: 13px;">
                                <span class="dashicons dashicons-cart" style="margin-right: 6px;"></span>
                                <?php esc_html_e('Import Products', 'rfplugin'); ?>
                            </button>
                            <button type="submit" name="rfplugin_import_resources" class="rf-btn rf-btn-outline" style="font-size: 13px;">
                                <span class="dashicons dashicons-category" style="margin-right: 6px;"></span>
                                <?php esc_html_e('Import Resources', 'rfplugin'); ?>
                            </button>
                            <button type="submit" name="rfplugin_import_services" class="rf-btn rf-btn-outline" style="font-size: 13px;">
                                <span class="dashicons dashicons-admin-tools" style="margin-right: 6px;"></span>
                                <?php esc_html_e('Import Services', 'rfplugin'); ?>
                            </button>
                            <button type="submit" name="rfplugin_import_cases" class="rf-btn rf-btn-outline" style="font-size: 13px;">
                                <span class="dashicons dashicons-portfolio" style="margin-right: 6px;"></span>
                                <?php esc_html_e('Import Cases', 'rfplugin'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zoho CRM Settings Tab -->
        <div id="zoho" class="rf-tab-content">
            <div class="rf-glass-card" style="margin-bottom: 30px;">
                <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('Zoho CRM Integration', 'rfplugin'); ?></h2>
                <p class="rf-p" style="margin-bottom: 24px;"><?php esc_html_e('Configure your Zoho CRM API credentials to enable lead synchronization.', 'rfplugin'); ?></p>
                
                <div class="rf-form-layout">
                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_zoho_client_id"><?php esc_html_e('Client ID', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Your Zoho API Client ID.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <input type="text" id="rfplugin_zoho_client_id" name="rfplugin_zoho_client_id" value="<?php echo esc_attr(get_option('rfplugin_zoho_client_id')); ?>" class="large-text" />
                        </div>
                    </div>

                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_zoho_client_secret"><?php esc_html_e('Client Secret', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('Your Zoho API Client Secret.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <input type="password" id="rfplugin_zoho_client_secret" name="rfplugin_zoho_client_secret" value="<?php echo esc_attr(get_option('rfplugin_zoho_client_secret')); ?>" class="large-text" />
                        </div>
                    </div>

                    <div class="rf-form-row">
                        <div class="rf-form-label">
                            <label for="rfplugin_zoho_refresh_token"><?php esc_html_e('Refresh Token', 'rfplugin'); ?></label>
                            <p class="description"><?php esc_html_e('OAuth2 Refresh Token for long-term access.', 'rfplugin'); ?></p>
                        </div>
                        <div class="rf-form-control">
                            <input type="password" id="rfplugin_zoho_refresh_token" name="rfplugin_zoho_refresh_token" value="<?php echo esc_attr(get_option('rfplugin_zoho_refresh_token')); ?>" class="large-text" />
                        </div>
                    </div>
                </div>

                <div class="rf-info-alert" style="margin-top: 24px; padding: 16px; background: #eff6ff; border-radius: 12px; border: 1px solid #dbeafe; color: #1e40af;">
                    <span class="dashicons dashicons-info-outline" style="margin-right: 8px; vertical-align: middle;"></span>
                    <?php 
                    printf(
                        esc_html__('Need help setting up? Check our %s.', 'rfplugin'),
                        '<a href="' . esc_url(admin_url('admin.php?page=royalfoam-docs&doc=zoho-crm')) . '" style="text-decoration: underline; font-weight: 600;">' . esc_html__('Zoho CRM Tutorial', 'rfplugin') . '</a>'
                    ); 
                    ?>
                </div>
            </div>
        </div>

        <div class="rf-form-actions" style="margin-top: 32px; padding-top: 24px;">
            <input type="submit" name="rfplugin_save_settings" class="rf-btn rf-btn-primary" value="<?php esc_attr_e('Apply Changes', 'rfplugin'); ?>" />
        </div>
    </form>

    <div class="rf-glass-card rf-fade-in" style="animation-delay: 0.2s; margin-top: 30px;">
        <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('System Integrity', 'rfplugin'); ?></h2>
        <div class="rf-info-grid">
            <div class="info-item">
                <span class="label"><?php esc_html_e('Plugin Version', 'rfplugin'); ?></span>
                <span class="value"><?php echo esc_html(RFPLUGIN_VERSION); ?></span>
            </div>
            <div class="info-item">
                <span class="label"><?php esc_html_e('Environment', 'rfplugin'); ?></span>
                <span class="value">PHP <?php echo esc_html(PHP_VERSION); ?></span>
            </div>
            <div class="info-item">
                <span class="label"><?php esc_html_e('ACF Engine', 'rfplugin'); ?></span>
                <span class="value"><?php echo function_exists('acf_add_local_field_group') ? '✓ Production Ready' : '✗ Needs Installation'; ?></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
            
            // Update URL hash without jumping
            history.pushState(null, null, '#' + target);
        });
    });

    // Handle hash on load
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(hash)) {
        const targetTab = document.querySelector(`[data-tab="${hash}"]`);
        if (targetTab) targetTab.click();
    }
});
</script>

<style>
    .rf-settings-tabs { display: flex; gap: 24px; margin-bottom: 32px; border-bottom: 1px solid var(--rf-neutral-200); }
    .rf-tab-link { 
        padding: 12px 0; 
        color: var(--rf-neutral-500); 
        text-decoration: none; 
        font-weight: 600; 
        font-size: 15px;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
    }
    .rf-tab-link:hover { color: var(--rf-primary); }
    .rf-tab-link.active { color: var(--rf-primary); border-bottom-color: var(--rf-primary); }

    .rf-tab-content { display: none; }
    .rf-tab-content.active { display: block; animation: rfFadeIn 0.3s ease-out; }

    @keyframes rfFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .rf-form-layout { display: flex; flex-direction: column; gap: 32px; }
    .rf-form-row { display: grid; grid-template-columns: 1fr 2fr; gap: 40px; }
    .rf-form-label label { display: block; font-weight: 700; color: var(--rf-neutral-800); margin-bottom: 4px; }
    
    .rf-toggle-group { display: flex; flex-direction: column; gap: 12px; }
    .rf-toggle-item { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .rf-toggle-item input { margin: 0; }
    
    .rf-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
    .info-item { display: flex; flex-direction: column; gap: 4px; padding: 16px; background: var(--rf-neutral-50); border-radius: 12px; }
    .info-item .label { font-size: 11px; text-transform: uppercase; color: var(--rf-neutral-500); font-weight: 700; }
    .info-item .value { font-weight: 600; color: var(--rf-neutral-800); }

    @media (max-width: 782px) {
        .rf-form-row { grid-template-columns: 1fr; gap: 12px; }
    }
</style>
