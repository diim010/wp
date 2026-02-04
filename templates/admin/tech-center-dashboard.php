<?php
/**
 * Unified Tech Center Admin Dashboard
 */
?>
<div class="wrap rf-admin-wrap">
    <header class="rf-dashboard-header rf-fade-in">
        <div class="rf-header-content">
            <h1 class="rf-h1"><?php esc_html_e('Resource Library Hub', 'rfplugin'); ?></h1>
            <p class="rf-p"><?php esc_html_e('Unified management for technical documentation, FAQs, and interactive media.', 'rfplugin'); ?></p>
        </div>
        <div class="rf-header-actions">
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=rf_resource')); ?>" class="rf-btn rf-btn-primary">
                <span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add New Resource', 'rfplugin'); ?>
            </a>
            <a href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>" target="_blank" class="rf-btn rf-btn-outline">
                <span class="dashicons dashicons-external"></span> <?php esc_html_e('View Public Library', 'rfplugin'); ?>
            </a>
        </div>
    </header>

    <div class="rf-dashboard-grid rf-grid rf-fade-in" style="margin-top: 32px;">
        <!-- Total Assets -->
        <div class="rf-glass-card stat-card asset-card">
            <div class="card-icon" style="background: rgba(37, 99, 235, 0.1); color: var(--rf-primary);">
                <span class="dashicons dashicons-category"></span>
            </div>
            <div class="card-info">
                <h3 style="margin-bottom: 4px;"><?php esc_html_e('Total Library Assets', 'rfplugin'); ?></h3>
                <p class="stat-number" style="font-size: 32px; font-weight: 800; color: var(--rf-neutral-900);"><?php echo esc_html($stats['resources']); ?></p>
            </div>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=rf_resource')); ?>" class="rf-btn rf-btn-outline" style="margin-top: 16px;">
                <?php esc_html_e('Manage Library', 'rfplugin'); ?>
            </a>
        </div>

        <!-- Mode Breakdown -->
        <div class="rf-glass-card" style="grid-column: span 1;">
            <h3 class="rf-h3" style="margin-bottom: 20px;"><?php esc_html_e('Asset Distribution', 'rfplugin'); ?></h3>
            <ul class="rf-admin-list" style="list-style: none; padding: 0;">
                <?php 
                $modes = [
                    'faq' => ['label' => __('FAQs', 'rfplugin'), 'icon' => 'editor-help'],
                    'document' => ['label' => __('Documents', 'rfplugin'), 'icon' => 'media-document'],
                    'video' => ['label' => __('Videos', 'rfplugin'), 'icon' => 'video-alt3'],
                    'sheet' => ['label' => __('Data Sheets', 'rfplugin'), 'icon' => 'media-spreadsheet'],
                    '3d' => ['label' => __('3D Models', 'rfplugin'), 'icon' => 'visibility'],
                ];
                foreach ($modes as $mode_slug => $mode_data) : 
                    $count = wp_count_posts('rf_resource')->publish ?? 0; // Simplified, or use meta query if needed
                    // For a real dashboard we should query these counts properly
                ?>
                <li style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=rf_resource&resource_mode=' . $mode_slug)); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-<?php echo $mode_data['icon']; ?>"></span> <?php echo $mode_data['label']; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Taxonomies & Tools -->
        <div class="rf-glass-card" style="grid-column: span 1;">
            <h3 class="rf-h3" style="margin-bottom: 20px;"><?php esc_html_e('Organization & Tools', 'rfplugin'); ?></h3>
            <ul class="rf-admin-list" style="list-style: none; padding: 0;">
                <li style="margin-bottom: 12px;">
                    <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=rf_resource_category&post_type=rf_resource')); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-tag"></span> <?php esc_html_e('Global Categories', 'rfplugin'); ?>
                    </a>
                </li>
                <li style="margin-bottom: 12px;">
                    <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=rf_resource_type&post_type=rf_resource')); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-filter"></span> <?php esc_html_e('Resource Types', 'rfplugin'); ?>
                    </a>
                </li>
                <li style="margin-bottom: 12px;">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-security')); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-shield"></span> <?php esc_html_e('Download Security Stats', 'rfplugin'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="rf-dashboard-footer rf-grid rf-fade-in" style="margin-top: 40px;">
        <div class="rf-glass-card recent-activity" style="grid-column: 1 / -1;">
            <h2 class="rf-h2"><?php esc_html_e('Recent Library Activity', 'rfplugin'); ?></h2>
            <div class="activity-list" style="margin-top: 20px;">
                <?php 
                $recent_resources = get_posts([
                    'post_type' => 'rf_resource',
                    'posts_per_page' => 10,
                    'status' => 'publish',
                ]);
                if (!empty($recent_resources)) : ?>
                    <?php foreach ($recent_resources as $post) : 
                        $mode = get_field('field_resource_mode', $post->ID);
                        $mode_label = ucfirst($mode);
                    ?>
                        <div class="activity-item" style="padding: 12px; border-bottom: 1px solid var(--rf-neutral-100); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <span class="rf-badge-outline" style="font-size: 9px; margin-right: 12px; text-transform: uppercase;"><?php echo esc_html($mode_label); ?></span>
                                <span style="font-weight: 500;"><?php echo esc_html($post->post_title); ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <span style="font-size: 11px; color: var(--rf-neutral-400);"><?php echo esc_html(human_time_diff(get_the_time('U', $post), current_time('timestamp'))); ?> <?php esc_html_e('ago', 'rfplugin'); ?></span>
                                <a href="<?php echo get_edit_post_link($post->ID); ?>" class="rf-btn rf-btn-sm rf-btn-outline" style="padding: 4px 8px; font-size: 11px;">
                                    <?php esc_html_e('Edit Asset', 'rfplugin'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="rf-p"><?php esc_html_e('No technical resources found.', 'rfplugin'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
