<?php
/**
 * WordPress Branding Removal
 * 
 * Removes WordPress branding from admin panel and login screen,
 * replacing it with RoyalFoam branding.
 * 
 * @package RFPlugin\Admin
 * @since 1.0.0
 */

namespace RFPlugin\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Branding class
 * 
 * @since 1.0.0
 */
class Branding
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initHooks();
    }

    /**
     * Initialize hooks
     * 
     * @return void
     */
    private function initHooks(): void
    {
        add_filter('login_headerurl', [$this, 'customLoginUrl']);
        add_filter('login_headertext', [$this, 'customLoginTitle']);
        add_action('login_enqueue_scripts', [$this, 'customLoginStyles']);
        add_filter('admin_footer_text', [$this, 'customAdminFooter']);
        add_filter('update_footer', '__return_empty_string', 999);
        add_action('wp_before_admin_bar_render', [$this, 'removeWPLogo']);
        add_action('admin_head', [$this, 'customAdminStyles']);
    }

    /**
     * Customize login page URL
     * 
     * @return string
     */
    public function customLoginUrl(): string
    {
        return home_url();
    }

    /**
     * Customize login page title
     * 
     * @return string
     */
    public function customLoginTitle(): string
    {
        return __('RoyalFoam', 'rfplugin');
    }

    /**
     * Add custom styles to login page
     * 
     * @return void
     */
    public function customLoginStyles(): void
    {
        ?>
        <style>
            #login h1 a,
            .login h1 a {
                background-image: none;
                background-size: contain;
                width: 100%;
                height: 80px;
                text-indent: 0;
                font-size: 32px;
                font-weight: bold;
                color: #1e3a8a;
                line-height: 80px;
            }
            
            #login h1 a::before,
            .login h1 a::before {
                content: 'RoyalFoam';
            }
            
            .login #backtoblog a,
            .login #nav a {
                color: #1e3a8a !important;
            }
            
            .login #backtoblog a:hover,
            .login #nav a:hover {
                color: #2563eb !important;
            }
        </style>
        <?php
    }

    /**
     * Customize admin footer text
     * 
     * @return string
     */
    public function customAdminFooter(): string
    {
        return sprintf(
            __('Powered by %s', 'rfplugin'),
            '<strong>RoyalFoam</strong>'
        );
    }

    /**
     * Remove WordPress logo from admin bar
     * 
     * @return void
     */
    public function removeWPLogo(): void
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
    }

    /**
     * Add custom admin styles
     * 
     * @return void
     */
    public function customAdminStyles(): void
    {
        ?>
        <style>
            #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                content: '🏠' !important;
            }
            
            #wpadminbar #wp-admin-bar-wp-logo > .ab-item {
                pointer-events: none;
            }
        </style>
        <?php
    }
}
