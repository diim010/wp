<?php
/**
 * Plugin Name: RFplugin
 * Plugin URI: https://royalfoam.com
 * Description: Enterprise-grade product constructor and invoice system for RoyalFoam. Built without WooCommerce, featuring specification-based products, React frontend, and secure REST API.
 * Version: 1.0.0
 * Author: RoyalFoam
 * Author URI: https://royalfoam.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rfplugin
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.4
 * Network: true
 *
 * @package RFPlugin
 */

namespace RFPlugin;

if (!defined("ABSPATH")) {
    exit();
}

define("RFPLUGIN_VERSION", "1.0.0");
define("RFPLUGIN_FILE", __FILE__);
define("RFPLUGIN_PATH", plugin_dir_path(__FILE__));
define("RFPLUGIN_URL", plugin_dir_url(__FILE__));
define("RFPLUGIN_BASENAME", plugin_basename(__FILE__));

/**
 * PSR-4 Autoloader
 *
 * @param string $class The fully-qualified class name
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefix = "RFPlugin\\";
    $baseDir = __DIR__ . "/includes/";

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace("\\", "/", $relativeClass) . ".php";

    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Plugin activation hook
 *
 * @param bool $networkWide Whether this is a network-wide activation
 * @return void
 */
function rfplugin_activate($networkWide = false)
{
    if (version_compare(PHP_VERSION, "8.4", "<")) {
        deactivate_plugins(RFPLUGIN_BASENAME);
        wp_die(
            esc_html__("RFplugin requires PHP 8.4 or higher.", "rfplugin"),
            esc_html__("Plugin Activation Error", "rfplugin"),
            ["back_link" => true],
        );
    }

    require_once RFPLUGIN_PATH . "includes/Core/Activator.php";
    Core\Activator::activate($networkWide);
}

/**
 * Plugin deactivation hook
 *
 * @param bool $networkWide Whether this is a network-wide deactivation
 * @return void
 */
function rfplugin_deactivate($networkWide = false)
{
    require_once RFPLUGIN_PATH . "includes/Core/Deactivator.php";
    Core\Deactivator::deactivate($networkWide);
}

register_activation_hook(__FILE__, 'RFPlugin\rfplugin_activate');
register_deactivation_hook(__FILE__, 'RFPlugin\rfplugin_deactivate');

/**
 * Initialize the plugin
 *
 * @return void
 */
function rfplugin_init()
{
    Core\Plugin::getInstance();

    // Handle multisite blog management
    if (is_multisite()) {
        add_action("wp_initialize_site", 'RFPlugin\rfplugin_new_blog', 10, 2);
        add_action("wp_delete_site", 'RFPlugin\rfplugin_delete_blog');
    }
}

/**
 * Handle new blog creation in multisite
 */
function rfplugin_new_blog($new_site, $args)
{
    Core\Activator::onNewBlog($new_site->blog_id);
}

/**
 * Handle blog deletion in multisite
 */
function rfplugin_delete_blog($old_site)
{
    Core\Activator::onDeleteBlog($old_site->blog_id);
}

add_action("plugins_loaded", 'RFPlugin\rfplugin_init');
