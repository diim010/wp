<?php
/**
 * Access Denied Template (Production Ready)
 * 
 * Displayed when a user tries to access restricted content.
 * Provides helpful actions and maintains brand consistency.
 * 
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Determine current resource for redirect after login
$redirect_url = is_singular() ? get_permalink() : home_url();
$login_url = wp_login_url($redirect_url);

// Check if user is logged in but lacks permissions
$is_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
?>

<div class="rf-access-denied rf-premium-ui" role="main">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1" aria-hidden="true"></div>
    <div class="rf-bg-blob rf-bg-blob-2" aria-hidden="true"></div>
    
    <div class="rf-container" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
        <div class="rf-glass-card" 
             style="text-align: center; max-width: 520px; padding: clamp(40px, 8vw, 60px); border-radius: 24px;"
             role="alert"
             aria-live="polite">
            
            <!-- Icon -->
            <div class="rf-icon-wrapper" 
                 aria-hidden="true"
                 style="margin-bottom: 32px; display: inline-flex; padding: 24px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.05)); border-radius: 50%; color: #ef4444;">
                <span class="dashicons dashicons-lock" style="font-size: 48px; width: 48px; height: 48px;"></span>
            </div>
            
            <!-- Title -->
            <h1 class="rf-h1" style="margin-bottom: 16px; font-size: clamp(1.75rem, 4vw, 2.25rem);">
                <?php esc_html_e('Restricted Access', 'rfplugin'); ?>
            </h1>
            
            <!-- Message -->
            <p class="rf-p" style="margin-bottom: 32px; color: #94a3b8; font-size: 1.1rem; line-height: 1.7;">
                <?php if ($is_logged_in) : ?>
                    <?php printf(
                        esc_html__('Hello %s, this content requires elevated permissions. Please contact your administrator for access.', 'rfplugin'),
                        '<strong style="color: white;">' . esc_html($current_user->display_name) . '</strong>'
                    ); ?>
                <?php else : ?>
                    <?php esc_html_e('This content is reserved for authorized users. Please log in with your account to continue.', 'rfplugin'); ?>
                <?php endif; ?>
            </p>

            <!-- Actions -->
            <div class="rf-actions" style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <?php if (!$is_logged_in) : ?>
                    <a href="<?php echo esc_url($login_url); ?>" 
                       class="rf-btn rf-btn-primary"
                       style="padding: 14px 32px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-admin-users" style="font-size: 18px; width: 18px; height: 18px;" aria-hidden="true"></span>
                        <?php esc_html_e('Log In', 'rfplugin'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                       class="rf-btn rf-btn-primary"
                       style="padding: 14px 32px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-email" style="font-size: 18px; width: 18px; height: 18px;" aria-hidden="true"></span>
                        <?php esc_html_e('Request Access', 'rfplugin'); ?>
                    </a>
                <?php endif; ?>
                
                <a href="<?php echo esc_url(get_post_type_archive_link('rf_resource')); ?>" 
                   class="rf-btn rf-btn-outline"
                   style="padding: 14px 32px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px;">
                    <span class="dashicons dashicons-arrow-left-alt" style="font-size: 18px; width: 18px; height: 18px;" aria-hidden="true"></span>
                    <?php esc_html_e('Browse Library', 'rfplugin'); ?>
                </a>
            </div>

            <!-- Help Text -->
            <p style="margin-top: 32px; font-size: 0.85rem; color: #64748b;">
                <?php esc_html_e('Need help?', 'rfplugin'); ?>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" style="color: var(--rf-primary); text-decoration: underline;">
                    <?php esc_html_e('Contact Support', 'rfplugin'); ?>
                </a>
            </p>
        </div>
    </div>
</div>

<style>
/* Button Hover Effects */
.rf-access-denied .rf-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

/* Focus Styles */
.rf-access-denied .rf-btn:focus {
    outline: 2px solid var(--rf-primary);
    outline-offset: 2px;
}

/* Link Hover */
.rf-access-denied a[style*="text-decoration: underline"]:hover {
    text-decoration: none !important;
}
</style>
