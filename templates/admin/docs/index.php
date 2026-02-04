<?php
/**
 * Documentation Overview
 */
?>
<h2 class="rf-h2"><?php esc_html_e('Plugin Documentation', 'rfplugin'); ?></h2>
<p class="rf-p"><?php esc_html_e('Welcome to the RoyalFoam enterprise management system. This portal contains all the information you need to configure and optimize your workflow.', 'rfplugin'); ?></p>

<div class="rf-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 40px;">
    <div class="rf-glass-card" style="border: 1px solid var(--rf-neutral-100); padding: 32px;">
        <div class="card-icon" style="margin-bottom: 20px; background: #e0f2fe; color: #0369a1;">
            <span class="dashicons dashicons-admin-settings"></span>
        </div>
        <h3 style="margin: 0 0 12px 0;">Configuration</h3>
        <p style="font-size: 14px; margin-bottom: 20px;">Learn how to set up core plugin features, PDF generation, and global billing settings.</p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-settings')); ?>" class="rf-btn rf-btn-outline" style="min-width: unset; width: 100%;">
            <?php esc_html_e('Go to Settings', 'rfplugin'); ?>
        </a>
    </div>

    <div class="rf-glass-card" style="border: 1px solid var(--rf-neutral-100); padding: 32px;">
        <div class="card-icon" style="margin-bottom: 20px; background: #fef2f2; color: #b91c1c;">
            <span class="dashicons dashicons-cloud"></span>
        </div>
        <h3 style="margin: 0 0 12px 0;">Zoho CRM</h3>
        <p style="font-size: 14px; margin-bottom: 20px;">Step-by-step guide to connecting your Zoho CRM account and synchronizing website leads.</p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs&doc=zoho-crm')); ?>" class="rf-btn rf-btn-outline" style="min-width: unset; width: 100%;">
            <?php esc_html_e('Read Tutorial', 'rfplugin'); ?>
        </a>
    </div>
</div>
