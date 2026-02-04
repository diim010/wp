<?php
/**
 * Modern RoyalFoam Network Settings
 */
?>
<div class="wrap rf-admin-wrap">
    <header class="rf-dashboard-header rf-fade-in">
        <div class="rf-header-content">
            <h1 class="rf-h1"><?php esc_html_e('Network Settings', 'rfplugin'); ?></h1>
            <p class="rf-p"><?php esc_html_e('Standardize enterprise configurations across all subsites.', 'rfplugin'); ?></p>
        </div>
        <div class="rf-header-actions">
            <a href="<?php echo esc_url(network_admin_url('admin.php?page=royalfoam-network')); ?>" class="rf-btn rf-btn-outline">
                <span class="dashicons dashicons-dashboard" style="margin-right: 8px;"></span>
                <?php esc_html_e('Network Dashboard', 'rfplugin'); ?>
            </a>
        </div>
    </header>

    <?php settings_errors('rfplugin_network_messages'); ?>

    <form method="post" action="" class="rf-fade-in" style="animation-delay: 0.1s;">
        <?php wp_nonce_field('rfplugin_network_settings'); ?>

        <div class="rf-glass-card" style="margin-bottom: 30px;">
            <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('Default Network Standards', 'rfplugin'); ?></h2>
            <p class="rf-p" style="margin-bottom: 32px;"><?php esc_html_e('These configurations serve as the foundation for all sites in the RoyalFoam network.', 'rfplugin'); ?></p>
            
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

            <div class="rf-form-actions" style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--rf-neutral-200);">
                <input type="submit" name="rfplugin_save_network_settings" class="rf-btn rf-btn-primary" value="<?php esc_attr_e('Synchronize Network', 'rfplugin'); ?>" />
            </div>
        </div>

        <div class="rf-glass-card rf-fade-in" style="margin-bottom: 30px; animation-delay: 0.2s;">
            <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('Administrative Utilities', 'rfplugin'); ?></h2>
            <div class="rf-utility-grid">
                <div class="utility-card">
                    <div class="utility-info">
                        <strong><?php esc_html_e('Global Configuration Push', 'rfplugin'); ?></strong>
                        <p class="description"><?php esc_html_e('Force network defaults to all active subsites immediately.', 'rfplugin'); ?></p>
                    </div>
                    <button type="button" id="rfplugin-sync-settings" class="rf-btn rf-btn-outline"><?php esc_html_e('Execute Sync', 'rfplugin'); ?></button>
                </div>
                <div class="utility-card">
                    <div class="utility-info">
                        <strong><?php esc_html_e('Analytics Recalculation', 'rfplugin'); ?></strong>
                        <p class="description"><?php esc_html_e('Refresh global counters and usage statistics.', 'rfplugin'); ?></p>
                    </div>
                    <button type="button" id="rfplugin-refresh-network-stats" class="rf-btn rf-btn-outline"><?php esc_html_e('Refresh Data', 'rfplugin'); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .rf-form-layout { display: flex; flex-direction: column; gap: 32px; }
    .rf-form-row { display: grid; grid-template-columns: 1fr 2fr; gap: 40px; }
    .rf-form-label label { display: block; font-weight: 700; color: var(--rf-neutral-800); margin-bottom: 4px; }
    
    .rf-toggle-group { display: flex; flex-direction: column; gap: 12px; }
    .rf-toggle-item { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .rf-toggle-item input { margin: 0; }
    
    .rf-utility-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .utility-card { padding: 20px; background: var(--rf-neutral-50); border-radius: 12px; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .utility-info { display: flex; flex-direction: column; gap: 4px; }
    .utility-info strong { color: var(--rf-neutral-800); }

    @media (max-width: 900px) {
        .rf-utility-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 782px) {
        .rf-form-row { grid-template-columns: 1fr; gap: 12px; }
    }
</style>

<script>
jQuery(document).ready(function($) {
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
