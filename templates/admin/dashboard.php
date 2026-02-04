<?php
/**
 * Modern RoyalFoam Admin Dashboard
 * Premium glassmorphism design with optimized presentation
 */
?>
<div class="wrap rf-admin-wrap">
    <header class="rf-dashboard-header rf-fade-in">
        <div class="rf-header-content">
            <h1 class="rf-h1"><?php esc_html_e('RoyalFoam Dashboard', 'rfplugin'); ?></h1>
            <p class="rf-p"><?php esc_html_e('Enterprise foam solutions management center.', 'rfplugin'); ?></p>
        </div>
        <div class="rf-header-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-settings')); ?>" class="rf-btn rf-btn-primary">
                <span class="dashicons dashicons-admin-settings" style="margin-right: 8px;"></span>
                <?php esc_html_e('Global Settings', 'rfplugin'); ?>
            </a>
        </div>
    </header>

    <div class="rf-dashboard-top rf-grid rf-grid-cols-1 rf-gap-6 rf-fade-in rf-mb-8">
        <div class="rf-glass-card rf-p-6">
            <h3 class="rf-text-xs rf-font-bold rf-uppercase rf-tracking-wider rf-text-slate-500 rf-mb-4"><?php esc_html_e('Quick Actions', 'rfplugin'); ?></h3>
            <div class="rf-flex rf-gap-4 rf-flex-wrap">
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>" class="rf-btn rf-btn-primary">
                    <span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add New Product', 'rfplugin'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=rf_resource')); ?>" class="rf-btn rf-btn-outline">
                    <span class="dashicons dashicons-plus"></span> <?php esc_html_e('New Resource', 'rfplugin'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=rf_invoice')); ?>" class="rf-btn rf-btn-outline">
                    <span class="dashicons dashicons-plus"></span> <?php esc_html_e('Create Invoice', 'rfplugin'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=rf-tech-center')); ?>" class="rf-btn rf-btn-outline">
                    <span class="dashicons dashicons-category"></span> <?php esc_html_e('Tech Center', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="rf-dashboard-grid rf-grid rf-grid-cols-1 md:rf-grid-cols-2 lg:rf-grid-cols-4 rf-gap-6 rf-fade-in">
        <!-- Products -->
        <div class="rf-glass-card stat-card product-card">
            <div class="card-icon"><span class="dashicons dashicons-products"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Active Products', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($stats['products']); ?></p>
            </div>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>" class="rf-btn rf-btn-outline">
                <?php esc_html_e('Manage Catalog', 'rfplugin'); ?>
            </a>
        </div>

        <!-- Tech Center - Resources -->
        <div class="rf-glass-card stat-card tech-center-card">
            <div class="card-icon" style="background: var(--rf-gradient-primary);"><span class="dashicons dashicons-category"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Tech Center', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($stats['resources']); ?></p>
                <p style="font-size: 11px; opacity: 0.7; margin: -5px 0 0;"><?php esc_html_e('FAQs, Docs & Resources', 'rfplugin'); ?></p>
            </div>
            <a href="<?php echo esc_url(admin_url('admin.php?page=rf-tech-center')); ?>" class="rf-btn rf-btn-primary">
                <?php esc_html_e('Enter Hub', 'rfplugin'); ?>
            </a>
        </div>

        <!-- Invoices -->
        <div class="rf-glass-card stat-card invoice-card">
            <div class="card-icon"><span class="dashicons dashicons-media-spreadsheet"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('Generated Invoices', 'rfplugin'); ?></h3>
                <p class="stat-number"><?php echo esc_html($stats['invoices']); ?></p>
            </div>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=rf_invoice')); ?>" class="rf-btn rf-btn-outline">
                <?php esc_html_e('View Billing', 'rfplugin'); ?>
            </a>
        </div>

        <!-- System Health -->
        <div class="rf-glass-card stat-card health-card">
            <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);"><span class="dashicons dashicons-shield"></span></div>
            <div class="card-info">
                <h3><?php esc_html_e('System Health', 'rfplugin'); ?></h3>
                <div class="health-indicators" style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px;">
                    <div class="rf-health-item rf-flex rf-items-center rf-gap-3">
                        <span class="<?php echo function_exists('acf_add_local_field_group') ? 'rf-badge-online' : 'rf-badge-offline'; ?>">
                            <?php echo function_exists('acf_add_local_field_group') ? 'Online' : 'Offline'; ?>
                        </span>
                        <span class="rf-text-sm rf-font-medium"><?php esc_html_e('ACF Pro', 'rfplugin'); ?></span>
                    </div>
                    <div class="rf-health-item rf-flex rf-items-center rf-gap-3">
                        <span class="<?php echo class_exists('WooCommerce') ? 'rf-badge-online' : 'rf-badge-offline'; ?>">
                            <?php echo class_exists('WooCommerce') ? 'Online' : 'Offline'; ?>
                        </span>
                        <span class="rf-text-sm rf-font-medium"><?php esc_html_e('WooCommerce', 'rfplugin'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rf-dashboard-footer rf-grid rf-grid-cols-1 lg:rf-grid-cols-2 rf-gap-6 rf-fade-in rf-mt-10" style="animation-delay: 0.2s;">
        <div class="rf-glass-card recent-activity">
            <h2 class="rf-h2"><?php esc_html_e('Recent Activity', 'rfplugin'); ?></h2>
            <div class="activity-list" style="margin-top: 20px;">
                <?php if (!empty($stats['recent_activity'])) : ?>
                    <?php foreach ($stats['recent_activity'] as $post) : ?>
                        <div class="activity-item" style="padding: 12px; border-bottom: 1px solid var(--rf-neutral-100); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="color: var(--rf-primary);"><?php echo esc_html(get_post_type_object($post->post_type)->labels->singular_name); ?>:</strong>
                                <span style="margin-left: 8px;"><?php echo esc_html($post->post_title); ?></span>
                            </div>
                            <span style="font-size: 11px; color: var(--rf-neutral-400);"><?php echo esc_html(human_time_diff(get_the_time('U', $post), current_time('timestamp'))); ?> <?php esc_html_e('ago', 'rfplugin'); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="rf-p"><?php esc_html_e('No recent activity found.', 'rfplugin'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="rf-glass-card api-monitor">
            <h2 class="rf-h2"><?php esc_html_e('REST API Monitor', 'rfplugin'); ?></h2>
            <div class="api-endpoints" style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                <div class="endpoint-item"><code>GET /v1/products</code> <span class="badge online">Active</span></div>
                <div class="endpoint-item"><code>POST /v1/invoices</code> <span class="badge online">Active</span></div>
                <div class="endpoint-item"><code>GET /v1/resources</code> <span class="badge online">Active</span></div>
            </div>
        </div>
    </div>

    <div class="rf-dashboard-docs rf-fade-in" style="animation-delay: 0.3s; margin-top: 32px;">
        <div class="rf-glass-card" style="display: flex; justify-content: space-between; align-items: center; padding: 32px; background: var(--rf-gradient-primary); color: white; border: none;">
            <div style="max-width: 60%;">
                <h2 class="rf-h2" style="color: white; margin-bottom: 12px;"><?php esc_html_e('Need Help or Documentation?', 'rfplugin'); ?></h2>
                <p style="opacity: 0.9; font-size: 1.1rem;"><?php esc_html_e('Access our comprehensive guides, Zoho CRM tutorials, and technical documentations to maximize your enterprise efficiency.', 'rfplugin'); ?></p>
            </div>
            <div class="rf-flex rf-gap-4">
                <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs&doc=zoho-crm')); ?>" class="rf-btn rf-bg-white rf-text-primary-800 hover:rf-bg-gray-50">
                    <span class="dashicons dashicons-welcome-learn-more"></span>
                    <?php esc_html_e('Zoho Setup Guide', 'rfplugin'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs')); ?>" class="rf-btn rf-btn-outline rf-border-white/30 rf-text-white hover:rf-bg-white/10">
                    <?php esc_html_e('Full Documentation', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.health-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.health-item .badge {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
}
.health-item .badge.online {
    background: #10b981;
    color: white;
}
.health-item .badge.offline {
    background: #ef4444;
    color: white;
}
</style>
