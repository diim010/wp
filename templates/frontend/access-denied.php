<?php
/**
 * Access Denied Template Part
 * 
 * Displayed when a user tries to access restricted content.
 */
?>
<div class="rf-access-denied rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-1"></div>
    
    <div class="rf-container" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">
        <div class="rf-glass-card" style="text-align: center; max-width: 500px; padding: 60px;">
            <div class="rf-icon-wrapper" style="margin-bottom: 30px; display: inline-flex; padding: 20px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; color: #ef4444;">
                <span class="dashicons dashicons-lock" style="font-size: 40px; width: 40px; height: 40px;"></span>
            </div>
            
            <h1 class="rf-h1" style="margin-bottom: 16px;"><?php esc_html_e('Restricted Access', 'rfplugin'); ?></h1>
            <p class="rf-p" style="margin-bottom: 32px; color: #94a3b8;">
                <?php esc_html_e('This content is reserved for specific user roles. Please log in with an authorized account to continue.', 'rfplugin'); ?>
            </p>

            <div class="rf-actions" style="display: flex; gap: 16px; justify-content: center;">
                <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="rf-btn rf-btn-primary">
                    <?php esc_html_e('Log In', 'rfplugin'); ?>
                </a>
                <a href="<?php echo esc_url(home_url()); ?>" class="rf-btn rf-btn-outline">
                    <?php esc_html_e('Return Home', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
