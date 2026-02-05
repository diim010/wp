<?php

/**
 * Access Denied Template
 *
 * Shown when user lacks permission to view content.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="rf-corp-main" role="main">
    <div class="rf-corp-bg-gradient" aria-hidden="true"></div>

    <div class="rf-corp-container rf-corp-py-xl">
        <div class="rf-corp-card rf-corp-card--glass rf-corp-error-state rf-animate-up">

            <div class="rf-corp-error-state__icon rf-corp-error-state__icon--danger">
                <span class="dashicons dashicons-lock" aria-hidden="true"></span>
            </div>

            <h1 class="rf-corp-title rf-corp-title--lg">
                <?php esc_html_e('Access Denied', 'rfplugin'); ?>
            </h1>

            <p class="rf-corp-subtitle">
                <?php esc_html_e('You do not have permission to view this content. Please contact your administrator if you believe this is an error.', 'rfplugin'); ?>
            </p>

            <div class="rf-corp-error-state__actions">
                <?php if (!is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="rf-corp-btn rf-corp-btn--primary">
                        <span class="dashicons dashicons-admin-users" aria-hidden="true"></span>
                        <?php esc_html_e('Log In', 'rfplugin'); ?>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url(home_url('/')); ?>" class="rf-corp-btn rf-corp-btn--ghost">
                    <span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span>
                    <?php esc_html_e('Back to Home', 'rfplugin'); ?>
                </a>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>