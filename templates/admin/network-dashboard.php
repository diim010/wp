<?php
/**
 * Modern RoyalFoam Network Dashboard
 */
?>
<div class="wrap rf-admin-wrap">
    <header class="rf-dashboard-header rf-fade-in">
        <div class="rf-header-content">
            <h1 class="rf-h1"><?php esc_html_e('RoyalFoam Network Dashboard', 'rfplugin'); ?></h1>
            <p class="rf-p"><?php esc_html_e('Enterprise-wide control and multisite analytics.', 'rfplugin'); ?></p>
        </div>
        <div class="rf-header-actions">
            <a href="<?php echo esc_url(network_admin_url('admin.php?page=royalfoam-network-settings')); ?>" class="rf-btn rf-btn-primary">
                <span class="dashicons dashicons-networking" style="margin-right: 8px;"></span>
                <?php esc_html_e('Network Settings', 'rfplugin'); ?>
            </a>
        </div>
    </header>

    <div class="rf-dashboard-grid rf-grid rf-fade-in" style="animation-delay: 0.1s;">
        <!-- Total Sites -->
        <div class="rf-glass-card stat-card site-count-card">
            <div class="card-icon" style="background: var(--rf-gradient-primary);"><span class="dashicons dashicons-admin-site"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Total Sites', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($networkStats['total_sites']); ?></p>
            </div>
        </div>

        <!-- Network Products -->
        <div class="rf-glass-card stat-card network-products-card">
            <div class="card-icon" style="background: var(--rf-gradient-primary);"><span class="dashicons dashicons-products"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Global Products', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($networkStats['total_products']); ?></p>
            </div>
        </div>

        <!-- Network Invoices -->
        <div class="rf-glass-card stat-card network-invoices-card">
            <div class="card-icon" style="background: var(--rf-gradient-primary);"><span class="dashicons dashicons-media-spreadsheet"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Global Invoices', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($networkStats['total_invoices']); ?></p>
            </div>
        </div>

        <!-- Network Services -->
        <div class="rf-glass-card stat-card network-services-card">
            <div class="card-icon" style="background: var(--rf-gradient-primary);"><span class="dashicons dashicons-admin-tools"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Global Services', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($networkStats['total_services']); ?></p>
            </div>
        </div>
    </div>

    <div class="rf-glass-card sites-status-table rf-fade-in" style="margin-top: 40px; animation-delay: 0.2s;">
        <h2 class="rf-h2" style="margin-bottom: 24px;"><?php esc_html_e('Sites Status', 'rfplugin'); ?></h2>
        
        <div class="rf-table-responsive">
            <table class="wp-list-table widefat fixed striped" style="border: none; background: transparent;">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Site Name', 'rfplugin'); ?></th>
                        <th><?php esc_html_e('Products', 'rfplugin'); ?></th>
                        <th><?php esc_html_e('Invoices', 'rfplugin'); ?></th>
                        <th><?php esc_html_e('Plugin Status', 'rfplugin'); ?></th>
                        <th><?php esc_html_e('Actions', 'rfplugin'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sites = get_sites(['number' => 100]);
                    foreach ($sites as $site):
                        switch_to_blog($site->blog_id);
                        $productCount = wp_count_posts('product')->publish ?? 0;
                        $invoiceCount = wp_count_posts('rf_invoice')->publish ?? 0;
                        $isActive = is_plugin_active(RFPLUGIN_BASENAME);
                        $siteName = get_bloginfo('name');
                        restore_current_blog();
                    ?>
                    <tr>
                        <td class="column-primary">
                            <strong><?php echo esc_html($siteName); ?></strong>
                            <br><small><?php echo esc_html($site->domain . $site->path); ?></small>
                        </td>
                        <td><span class="rf-badge-count"><?php echo esc_html($productCount); ?></span></td>
                        <td><span class="rf-badge-count"><?php echo esc_html($invoiceCount); ?></span></td>
                        <td>
                            <?php if ($isActive): ?>
                                <span class="badge online">Active</span>
                            <?php else: ?>
                                <span class="badge offline">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url(get_admin_url($site->blog_id)); ?>" class="rf-btn rf-btn-outline" style="padding: 4px 12px; font-size: 11px;">
                                <?php esc_html_e('Dashboard', 'rfplugin'); ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .rf-table-responsive { overflow-x: auto; }
    .rf-badge-count {
        background: var(--rf-neutral-100);
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
    }
    .badge.offline { background: #fee2e2; color: #991b1b; }
    
    /* Table Styling */
    .widefat { border-radius: 12px; overflow: hidden; }
    .widefat thead th { 
        background: var(--rf-neutral-50); 
        padding: 16px !important;
        font-weight: 700;
        color: var(--rf-neutral-700);
    }
    .widefat tbody td { padding: 16px !important; vertical-align: middle; }
</style>
