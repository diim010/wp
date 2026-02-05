<?php

/**
 * Control Center for Super Admins
 *
 * Network-wide dashboard with statistics, quick actions, and management tools.
 * Only accessible to users with super admin privileges.
 *
 * @package RFPlugin\Admin
 * @since 2.0.0
 */

namespace RFPlugin\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ControlCenter class
 */
class ControlCenter
{
    /**
     * Menu slug
     */
    private const MENU_SLUG = 'rf-control-center';

    /**
     * Initialize Control Center
     */
    public static function init(): void
    {
        // Only for super admins
        if (!SuperAdminTheme::isSuperAdmin()) {
            return;
        }

        add_action('admin_menu', [self::class, 'registerMenu']);
        add_action('admin_bar_menu', [self::class, 'addAdminBarLink'], 100);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAssets']);
    }

    /**
     * Register Control Center menu page
     */
    public static function registerMenu(): void
    {
        add_menu_page(
            __('Ground Control', 'rfplugin'),
            __('Ground Control', 'rfplugin'),
            'manage_options',
            self::MENU_SLUG,
            [self::class, 'renderDashboard'],
            'dashicons-dashboard',
            2 // Position: above RoyalFoam
        );

        // Register submenus
        self::registerSubmenus();
    }

    /**
     * Register Ground Control submenus
     */
    private static function registerSubmenus(): void
    {
        // Dashboard (rename default submenu)
        add_submenu_page(
            self::MENU_SLUG,
            __('Dashboard', 'rfplugin'),
            __('Dashboard', 'rfplugin'),
            'manage_options',
            self::MENU_SLUG,
            [self::class, 'renderDashboard']
        );

        // Settings submenu
        add_submenu_page(
            self::MENU_SLUG,
            __('Settings', 'rfplugin'),
            __('Settings', 'rfplugin'),
            'manage_options',
            'royalfoam',
            [\RFPlugin\Admin\Menu::class, 'renderSettings']
        );

        // Documentation submenu
        add_submenu_page(
            self::MENU_SLUG,
            __('Documentation', 'rfplugin'),
            __('Documentation', 'rfplugin'),
            'manage_options',
            'rf-gc-docs',
            [\RFPlugin\Admin\Menu::class, 'renderDocumentation']
        );

        // Security Stats submenu
        add_submenu_page(
            self::MENU_SLUG,
            __('Security Stats', 'rfplugin'),
            __('Security Stats', 'rfplugin'),
            'manage_options',
            'rf-gc-security',
            [\RFPlugin\Admin\Menu::class, 'renderSecurityStats']
        );

        // Technical Center submenu
        add_submenu_page(
            self::MENU_SLUG,
            __('Technical Center', 'rfplugin'),
            __('Tech Center', 'rfplugin'),
            'manage_options',
            'rf-gc-tech',
            [self::class, 'renderTechCenter']
        );

        // Services, Cases, and Invoices are automatically added by their PostType classes
    }

    /**
     * Add Control Center link to admin bar
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     */
    public static function addAdminBarLink($wp_admin_bar): void
    {
        $wp_admin_bar->add_node([
            'id' => 'rf-control-center',
            'title' => '<span class="ab-icon dashicons dashicons-dashboard"></span><span class="ab-label">' . __('Ground Control', 'rfplugin') . '</span>',
            'href' => admin_url('admin.php?page=' . self::MENU_SLUG),
            'meta' => [
                'title' => __('RoyalFoam Ground Control', 'rfplugin'),
                'class' => 'rf-control-center-link',
            ],
        ]);
    }

    /**
     * Enqueue Control Center assets
     */
    public static function enqueueAssets($hook): void
    {
        // Only on Control Center page
        if ($hook !== 'toplevel_page_' . self::MENU_SLUG) {
            return;
        }

        // Main CSS is already enqueued globally
        // Add inline styles for Control Center specific animations
        wp_add_inline_style('rfplugin-admin', self::getControlCenterStyles());

        // Add inline script for animations
        wp_add_inline_script('jquery', self::getControlCenterScript());
    }

    /**
     * Render Control Center dashboard
     */
    public static function renderDashboard(): void
    {
        // Get network statistics
        $stats = \RFPlugin\Services\NetworkStats::getAggregatedStats();
        $activity = \RFPlugin\Services\NetworkStats::getNetworkActivity(15);

        // Include template
        include RFPLUGIN_PATH . 'templates/admin/control-center.php';
    }

    /**
     * Render Technical Center dashboard
     */
    public static function renderTechCenter(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'rfplugin'));
        }

        // Gather system information
        global $wpdb;

        $system_info = [
            'php_version' => PHP_VERSION,
            'mysql_version' => $wpdb->db_version(),
            'wp_version' => get_bloginfo('version'),
            'wp_memory_limit' => WP_MEMORY_LIMIT,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_input_vars' => ini_get('max_input_vars'),
            'php_extensions' => get_loaded_extensions(),
            'active_plugins' => count(get_option('active_plugins', [])),
            'active_theme' => wp_get_theme()->get('Name'),
            'multisite' => is_multisite(),
            'debug_mode' => WP_DEBUG,
            'cache_enabled' => wp_using_ext_object_cache(),
        ];

        include RFPLUGIN_PATH . 'templates/admin/tech-center.php';
    }

    /**
     * Get Control Center specific styles
     *
     * @return string
     */
    private static function getControlCenterStyles(): string
    {
        return '
        <style>
            /* iOS-Style Glassmorphism & Animations */
            .rf-glass-card {
                position: relative;
                overflow: hidden;
            }

            .rf-glass-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .rf-glass-card:hover::before {
                opacity: 1;
            }

            /* Smooth iOS-style transitions */
            .rf-stat-card {
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
            }

            /* Enhanced shadow on hover */
            .rf-stat-card:hover .rf-glass-card {
                box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15),
                           0 8px 16px -8px rgba(0, 0, 0, 0.1);
            }

            .dark .rf-stat-card:hover .rf-glass-card {
                box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.4),
                           0 8px 16px -8px rgba(0, 0, 0, 0.3);
            }

            /* Gradient text shimmer effect */
            @keyframes shimmer {
                0% { background-position: -200% center; }
                100% { background-position: 200% center; }
            }

            .rf-cc-stat-value {
                background-size: 200% auto;
                animation: shimmer 3s linear infinite;
            }

            /* Smooth entrance animations */
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .rf-cc-animate-in {
                animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
            }

            /* Stagger animation delays */
            .rf-cc-animate-in:nth-child(1) { animation-delay: 0.05s; }
            .rf-cc-animate-in:nth-child(2) { animation-delay: 0.1s; }
            .rf-cc-animate-in:nth-child(3) { animation-delay: 0.15s; }
            .rf-cc-animate-in:nth-child(4) { animation-delay: 0.2s; }
            .rf-cc-animate-in:nth-child(5) { animation-delay: 0.25s; }
            .rf-cc-animate-in:nth-child(6) { animation-delay: 0.3s; }

            /* iOS-style active state */
            .rf-stat-card:active .rf-glass-card {
                transform: scale(0.97);
                transition: transform 0.1s ease;
            }

            /* Refined backdrop blur for dark mode */
            .dark .rf-glass-card {
                backdrop-filter: blur(20px) saturate(180%);
                -webkit-backdrop-filter: blur(20px) saturate(180%);
            }

            .rf-glass-card {
                backdrop-filter: blur(20px) saturate(180%);
                -webkit-backdrop-filter: blur(20px) saturate(180%);
            }

            /* Icon glow effect */
            .rf-glass-card:hover .w-14 {
                filter: drop-shadow(0 4px 12px currentColor);
            }

            /* Smooth color transitions */
            * {
                transition-property: background-color, border-color, color, fill, stroke;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 200ms;
            }

            /* Mobile touch optimization */
            @media (hover: none) and (pointer: coarse) {
                .rf-stat-card:hover .rf-glass-card {
                    transform: none;
                }

                .rf-stat-card:active .rf-glass-card {
                    transform: scale(0.95);
                    transition: transform 0.1s ease;
                }
            }

            /* Refined spacing for mobile */
            @media (max-width: 768px) {
                .rf-glass-card {
                    padding: 1.25rem !important;
                }

                .rf-cc-stat-value {
                    font-size: 2rem !important;
                }
            }

            /* Quick action links - enhanced glass effect */
            .rf-quick-action {
                backdrop-filter: blur(12px) saturate(150%);
                -webkit-backdrop-filter: blur(12px) saturate(150%);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .rf-quick-action:hover {
                transform: translateX(4px);
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
            }

            .rf-quick-action:active {
                transform: translateX(2px) scale(0.98);
            }

            /* Theme toggle button enhancement */
            #rf-theme-toggle,
            #rf-tech-theme-toggle {
                backdrop-filter: blur(12px) saturate(150%);
                -webkit-backdrop-filter: blur(12px) saturate(150%);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            #rf-theme-toggle:hover,
            #rf-tech-theme-toggle:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
            }

            .dark #rf-theme-toggle:hover,
            .dark #rf-tech-theme-toggle:hover {
                box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.3);
            }

            #rf-theme-toggle:active,
            #rf-tech-theme-toggle:active {
                transform: scale(0.95);
            }

            /* Refined tag/badge styling */
            .rf-glass-card .rounded-full {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            /* Hero section gradient overlay */
            .rf-admin-wrap {
                position: relative;
            }

            .rf-admin-wrap::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 300px;
                background: linear-gradient(180deg,
                    rgba(59, 130, 246, 0.05) 0%,
                    transparent 100%);
                pointer-events: none;
                z-index: 0;
            }

            .dark .rf-admin-wrap::before {
                background: linear-gradient(180deg,
                    rgba(59, 130, 246, 0.08) 0%,
                    transparent 100%);
            }

            .rf-admin-wrap > * {
                position: relative;
                z-index: 1;
            }

            /* Refined border colors for dark mode */
            .dark .border-slate-200\/50 {
                border-color: rgba(148, 163, 184, 0.1) !important;
            }

            .dark .border-slate-700\/50 {
                border-color: rgba(51, 65, 85, 0.3) !important;
            }

            /* Enhanced focus states for accessibility */
            .rf-stat-card:focus-visible .rf-glass-card,
            .rf-quick-action:focus-visible {
                outline: 2px solid rgb(59, 130, 246);
                outline-offset: 2px;
            }

            .dark .rf-stat-card:focus-visible .rf-glass-card,
            .dark .rf-quick-action:focus-visible {
                outline-color: rgb(96, 165, 250);
            }
        </style>
        ';
    }

    /**
     * Get Control Center JavaScript
     *
     * @return string
     */
    private static function getControlCenterScript(): string
    {
        return <<<JS
        jQuery(document).ready(function($) {
            // Theme toggle functionality
            const wrapper = document.getElementById('rf-gc-wrapper');
            const toggleBtn = document.getElementById('rf-gc-theme-toggle');
            const savedTheme = localStorage.getItem('rf-gc-theme') || 'light';

            // Apply saved theme
            if (savedTheme === 'dark') {
                wrapper.classList.add('dark');
            }

            // Toggle theme
            toggleBtn.addEventListener('click', function() {
                wrapper.classList.toggle('dark');
                const newTheme = wrapper.classList.contains('dark') ? 'dark' : 'light';
                localStorage.setItem('rf-gc-theme', newTheme);
            });

            // Counter animation for stats
            $('.rf-cc-stat-value').each(function() {
                const target = parseInt($(this).text().replace(/,/g, ''));
                if (isNaN(target)) return;

                const duration = 1500;
                const increment = target / (duration / 16);
                let current = 0;
                const element = $(this);

                const timer = setInterval(function() {
                    current += increment;
                    if (current >= target) {
                        element.text(target.toLocaleString());
                        clearInterval(timer);
                    } else {
                        element.text(Math.floor(current).toLocaleString());
                    }
                }, 16);
            });

            // Refresh stats button
            $('.rf-cc-refresh').on('click', function(e) {
                e.preventDefault();
                location.reload();
            });
        });
JS;
    }
}
