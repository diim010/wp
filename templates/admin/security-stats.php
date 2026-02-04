<?php
/**
 * Admin Security Stats Template
 * 
 * @var array $stats
 */
?>
<div class="wrap rf-admin-wrap" style="background: radial-gradient(circle at top right, #f8fafc, #fff); min-height: 90vh; padding: 30px; border-radius: 12px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-weight: 800; font-size: 2.2rem; color: #0f172a; margin: 0;"><?php _e('Security & Asset Guard', 'rfplugin'); ?></h1>
        <form method="post" action="" onsubmit="return confirm('<?php _e('Are you sure you want to clear all history and active locks?', 'rfplugin'); ?>');">
            <?php wp_nonce_field('rfplugin_clear_security'); ?>
            <button type="submit" name="rfplugin_clear_security_data" class="button button-link-delete" style="color: #d63638; text-decoration: none; font-weight: 600;">
                <span class="dashicons dashicons-trash" style="vertical-align: middle; margin-right: 4px;"></span>
                <?php _e('Clear Assets History', 'rfplugin'); ?>
            </button>
        </form>
    </div>

    <?php settings_errors('rfplugin_messages'); ?>

    <!-- Filter Bar -->
    <div class="rf-filter-bar" style="background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; margin-bottom: 30px;">
        <form method="get" action="" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
            
            <div class="filter-group">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; margin-bottom: 5px;"><?php _e('IP Address', 'rfplugin'); ?></label>
                <input type="text" name="ip_filter" value="<?php echo esc_attr($_GET['ip_filter'] ?? ''); ?>" placeholder="192.168..." style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px 12px;">
            </div>

            <div class="filter-group">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; margin-bottom: 5px;"><?php _e('Threat Status', 'rfplugin'); ?></label>
                <select name="status_filter" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 5px 12px;">
                    <option value=""><?php _e('All Statuses', 'rfplugin'); ?></option>
                    <option value="1" <?php selected($_GET['status_filter'] ?? '', '1'); ?>><?php _e('Suspicious Only', 'rfplugin'); ?></option>
                    <option value="0" <?php selected($_GET['status_filter'] ?? '', '0'); ?>><?php _e('Clean Only', 'rfplugin'); ?></option>
                </select>
            </div>

            <div class="filter-group">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; margin-bottom: 5px;"><?php _e('From', 'rfplugin'); ?></label>
                <input type="date" name="date_from" value="<?php echo esc_attr($_GET['date_from'] ?? ''); ?>" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 5px 12px;">
            </div>

            <div class="filter-group">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; margin-bottom: 5px;"><?php _e('To', 'rfplugin'); ?></label>
                <input type="date" name="date_to" value="<?php echo esc_attr($_GET['date_to'] ?? ''); ?>" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 5px 12px;">
            </div>

            <div class="filter-actions" style="display: flex; gap: 10px;">
                <button type="submit" class="button button-primary" style="height: 38px; border-radius: 8px; padding: 0 20px;"><?php _e('Apply Filters', 'rfplugin'); ?></button>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $_GET['page'])); ?>" class="button" style="height: 38px; border-radius: 8px; line-height: 36px;"><?php _e('Reset', 'rfplugin'); ?></a>
            </div>
        </form>
    </div>

    <div class="rf-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin: 30px 0;">
        <div class="rf-stat-card" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(8px); padding: 30px; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 5px solid #2271b1;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 0.95rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;"><?php _e('Total Assets Delivered', 'rfplugin'); ?></h3>
                <span class="dashicons dashicons-cloud-download" style="color: #2271b1;"></span>
            </div>
            <p style="font-size: 3rem; font-weight: 800; margin: 15px 0 0; color: #1e293b;"><?php echo number_format((int)$stats['total_downloads']); ?></p>
        </div>
        
        <div class="rf-stat-card" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(8px); padding: 30px; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 5px solid #72aee6;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 0.95rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;"><?php _e('Concurrent Locked Streams', 'rfplugin'); ?></h3>
                <span class="dashicons dashicons-lock" style="color: #72aee6;"></span>
            </div>
            <p style="font-size: 3rem; font-weight: 800; margin: 15px 0 0; color: #1e293b;"><?php echo number_format((int)$stats['active_locks']); ?></p>
        </div>

        <div class="rf-stat-card" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(8px); padding: 30px; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; border-top: 5px solid #d63638;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 0.95rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;"><?php _e('Threats Mitigated', 'rfplugin'); ?></h3>
                <span class="dashicons dashicons-shield" style="color: #d63638;"></span>
            </div>
            <p style="font-size: 3rem; font-weight: 800; margin: 15px 0 0; color: #d63638;"><?php echo number_format((int)$stats['suspicious_count']); ?></p>
        </div>
    </div>

    <?php if (!empty($stats['dangerous_users'])): ?>
        <div class="rf-dangerous-users" style="margin-top: 40px;">
            <h2 style="color: #d63638;"><?php _e('Potential Dangerous Users (Blocked IPs)', 'rfplugin'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('IP Address', 'rfplugin'); ?></th>
                        <th><?php _e('Suspicious Hits', 'rfplugin'); ?></th>
                        <th><?php _e('Status', 'rfplugin'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['dangerous_users'] as $user): ?>
                        <tr>
                            <td><strong><?php echo esc_html($user->ip_address); ?></strong></td>
                            <td><?php echo (int)$user->suspicious_hits; ?></td>
                            <td><span class="status-tag" style="background: #fdf2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem;"><?php _e('BLOCKED', 'rfplugin'); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="rf-recent-history" style="margin-top: 40px;">
        <h2><?php _e('Recent Download Activity', 'rfplugin'); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Time', 'rfplugin'); ?></th>
                    <th><?php _e('User / IP', 'rfplugin'); ?></th>
                    <th><?php _e('Document', 'rfplugin'); ?></th>
                    <th><?php _e('Flags', 'rfplugin'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($stats['recent_history']): ?>
                    <?php foreach ($stats['recent_history'] as $item): ?>
                        <tr>
                            <td><?php echo esc_html($item->download_at); ?></td>
                            <td>
                                <?php if ($item->user_id): $user = get_userdata($item->user_id); echo esc_html($user->display_name); else: _e('Guest', 'rfplugin'); endif; ?>
                                <br><small><?php echo esc_html($item->ip_address); ?></small>
                            </td>
                            <td><strong><?php echo esc_html($item->post_title ?: __('Deleted', 'rfplugin')); ?></strong></td>
                            <td>
                                <?php if ($item->is_suspicious): ?>
                                    <span class="dashicons dashicons-warning" style="color: #d63638;" title="<?php _e('Suspicious Activity', 'rfplugin'); ?>"></span>
                                <?php else: ?>
                                    <span class="dashicons dashicons-yes-alt" style="color: #67c23a;" title="<?php _e('Normal', 'rfplugin'); ?>"></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4"><?php _e('No download activity yet.', 'rfplugin'); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
