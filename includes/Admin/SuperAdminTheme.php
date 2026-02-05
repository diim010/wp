<?php

/**
 * Super Admin Theme Manager
 *
 * Applies dark theme CSS only for users with super_admin capability.
 * Regular site admins and other roles see classic WP admin.
 *
 * @package RFPlugin\Admin
 * @since 2.0.0
 */

namespace RFPlugin\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SuperAdminTheme class
 */
class SuperAdminTheme
{
    /**
     * Body class for super admin theme
     */
    private const BODY_CLASS = 'rf-superadmin-theme';

    /**
     * Initialize the theme manager
     */
    public static function init(): void
    {
        // Only run in admin
        if (!is_admin()) {
            return;
        }

        add_filter('admin_body_class', [self::class, 'addBodyClass']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueStyles'], 99);
        add_action('admin_head', [self::class, 'injectThemeScript']);
    }

    /**
     * Check if current user is super admin
     *
     * @return bool
     */
    public static function isSuperAdmin(): bool
    {
        // For multisite: check super_admin
        if (is_multisite()) {
            return is_super_admin();
        }

        // For single site: check manage_options + is network admin equivalent
        return current_user_can('manage_options');
    }

    /**
     * Add body class for super admins
     *
     * @param string $classes
     * @return string
     */
    public static function addBodyClass(string $classes): string
    {
        if (self::isSuperAdmin()) {
            $classes .= ' ' . self::BODY_CLASS;
        }

        return $classes;
    }

    /**
     * Enqueue dark theme styles for super admins
     *
     * @return void
     */
    public static function enqueueStyles(): void
    {
        if (!self::isSuperAdmin()) {
            return;
        }

        // Dark theme is already in admin.css, but scoped to .rf-superadmin-theme
        // This ensures it loads with higher priority
        wp_add_inline_style('rfplugin-admin', self::getThemeOverrides());
    }

    /**
     * Inject theme toggle script and persistence
     *
     * @return void
     */
    public static function injectThemeScript(): void
    {
        if (!self::isSuperAdmin()) {
            return;
        }

?>
        <script>
            (function() {
                // Apply saved theme preference immediately to prevent flash
                const savedTheme = localStorage.getItem('rf-admin-theme') || 'dark';
                document.documentElement.dataset.rfTheme = savedTheme;

                // Also set on body when ready
                document.addEventListener('DOMContentLoaded', function() {
                    const wrap = document.querySelector('.rf-admin-wrap');
                    if (wrap) {
                        wrap.dataset.rfTheme = savedTheme;
                    }
                });
            })();
        </script>
        <style>
            /* Hide WordPress color scheme for super admins */
            .rf-superadmin-theme #adminmenu,
            .rf-superadmin-theme #adminmenuback,
            .rf-superadmin-theme #adminmenuwrap {
                background: var(--rf-admin-bg-elevated, #111827) !important;
            }

            .rf-superadmin-theme #adminmenu a {
                color: var(--rf-admin-text-secondary, #94a3b8) !important;
            }

            .rf-superadmin-theme #adminmenu .wp-has-current-submenu .wp-submenu,
            .rf-superadmin-theme #adminmenu .wp-has-current-submenu.opensub .wp-submenu {
                background: var(--rf-admin-bg-card, #1a2332) !important;
            }

            .rf-superadmin-theme #adminmenu li.current a.menu-top,
            .rf-superadmin-theme #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu {
                background: var(--rf-admin-primary, #3b82f6) !important;
                color: white !important;
            }

            .rf-superadmin-theme #wpadminbar {
                background: var(--rf-admin-bg, #0a0e17) !important;
            }

            .rf-superadmin-theme #wpbody {
                background: var(--rf-admin-bg, #0a0e17);
            }

            .rf-superadmin-theme #wpfooter {
                color: var(--rf-admin-text-muted, #64748b);
            }

            /* Light theme override when selected */
            [data-rf-theme="light"] .rf-superadmin-theme #adminmenu,
            [data-rf-theme="light"] .rf-superadmin-theme #adminmenuback,
            [data-rf-theme="light"] .rf-superadmin-theme #adminmenuwrap {
                background: #1d2327 !important;
            }

            [data-rf-theme="light"] .rf-superadmin-theme #wpadminbar {
                background: #1d2327 !important;
            }

            [data-rf-theme="light"] .rf-superadmin-theme #wpbody {
                background: #f0f0f1;
            }
        </style>
<?php
    }

    /**
     * Get CSS overrides for super admin theme
     *
     * @return string
     */
    private static function getThemeOverrides(): string
    {
        return <<<CSS
        /* Super Admin Theme Active Indicator */
        .rf-superadmin-theme .rf-admin-wrap::before {
            content: '';
            position: fixed;
            top: 32px;
            left: 160px;
            right: 0;
            bottom: 0;
            background: var(--rf-admin-bg);
            z-index: -1;
            pointer-events: none;
        }
CSS;
    }
}
