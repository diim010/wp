<?php
/**
 * Plugin Activation Handler
 *
 * Handles plugin activation tasks including database setup,
 * capability registration, and initial configuration.
 * Now with full multisite support.
 *
 * @package RFPlugin\Core
 * @since 1.0.0
 */

namespace RFPlugin\Core;

if (!defined("ABSPATH")) {
    exit();
}

/**
 * Activator class
 *
 * @since 1.0.0
 */
class Activator
{
    /**
     * Execute activation tasks
     *
     * @param bool $networkWide Whether this is a network-wide activation
     * @return void
     */
    public static function activate(bool $networkWide = false): void
    {
        if (is_multisite() && $networkWide) {
            self::networkActivate();
        } else {
            self::singleActivate();
        }

        flush_rewrite_rules();
    }

    /**
     * Network-wide activation
     *
     * @return void
     */
    private static function networkActivate(): void
    {
        // Set network-wide flag
        update_site_option("rfplugin_network_activated", true);
        update_site_option("rfplugin_network_version", RFPLUGIN_VERSION);

        // Activate on all existing sites
        $sites = get_sites(["number" => 0]);
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            self::singleActivate();
            restore_current_blog();
        }
    }

    /**
     * Single site activation
     *
     * @return void
     */
    private static function singleActivate(): void
    {
        self::createInvoiceDirectory();
        self::createTechDocDirectory();
        self::addCapabilities();
        self::setDefaultOptions();
        self::createTechnicalCenterPage();
    }

    /**
     * Handle new blog creation in multisite
     *
     * @param int $blogId Blog ID
     * @return void
     */
    public static function onNewBlog(int $blogId): void
    {
        if (!get_site_option("rfplugin_network_activated")) {
            return;
        }

        switch_to_blog($blogId);
        self::singleActivate();
        restore_current_blog();
    }

    /**
     * Handle blog deletion in multisite
     *
     * @param int $blogId Blog ID
     * @return void
     */
    public static function onDeleteBlog(int $blogId): void
    {
        switch_to_blog($blogId);

        // Clean up invoice directory
        $uploadDir = wp_upload_dir();
        $invoiceDir = $uploadDir["basedir"] . "/rfplugin-invoices";
        if (file_exists($invoiceDir)) {
            self::deleteDirectory($invoiceDir);
        }

        restore_current_blog();
    }

    /**
     * Create directory for invoice JSON storage
     *
     * @return void
     */
    private static function createInvoiceDirectory(): void
    {
        $uploadDir = wp_upload_dir();
        $invoiceDir = $uploadDir["basedir"] . "/rfplugin-invoices";

        if (!file_exists($invoiceDir)) {
            wp_mkdir_p($invoiceDir);
            
            // Enterprise hardening: Block all direct access
            $htaccess = "Order Deny,Allow\nDeny from all\n<FilesMatch \"\.(json|pdf|html|php)$\">\n    Order Deny,Allow\n    Deny from all\n</FilesMatch>";
            file_put_contents($invoiceDir . "/.htaccess", $htaccess);

            file_put_contents(
                $invoiceDir . "/index.php",
                "<?php\n// Security by silence\nexit;"
            );
        }
    }

    /**
     * Create directory for secure documentation storage
     *
     * @return void
     */
    private static function createTechDocDirectory(): void
    {
        $uploadDir = wp_upload_dir();
        $docDir = $uploadDir["basedir"] . "/rfplugin-docs";

        if (!file_exists($docDir)) {
            wp_mkdir_p($docDir);

            // Enterprise hardening: Block all direct access
            $htaccess = "Order Deny,Allow\nDeny from all\n<FilesMatch \"\.(pdf|zip|docx|xlsx|php)$\">\n    Order Deny,Allow\n    Deny from all\n</FilesMatch>";
            file_put_contents($docDir . "/.htaccess", $htaccess);

            file_put_contents(
                $docDir . "/index.php",
                "<?php\n// Security by silence\nexit;"
            );
        }
    }

    /**
     * Add custom capabilities to administrator role
     *
     * @return void
     */
    private static function addCapabilities(): void
    {
        $adminRole = get_role("administrator");

        if ($adminRole) {
            $capabilities = [
                "manage_rfplugin",
                "view_rfplugin_invoices",
                "create_rfplugin_invoices",
                "edit_rfplugin_invoices",
                "delete_rfplugin_invoices",
            ];

            foreach ($capabilities as $cap) {
                $adminRole->add_cap($cap);
            }
        }

        // Add network-specific capabilities for super admins
        if (is_multisite()) {
            $superAdminRole = get_role("super_admin");
            if ($superAdminRole) {
                $superAdminRole->add_cap("manage_rfplugin_network");
            }
        }
    }

    /**
     * Create Technical Center page if it doesn't exist
     *
     * @return void
     */
    private static function createTechnicalCenterPage(): void
    {
        $page_title = __('Technical Center', 'rfplugin');
        $page_slug = 'technical-center';
        
        $existing_page = get_page_by_path($page_slug);
        
        if (!$existing_page) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_content'  => '', // Content is handled by the template
            ]);

            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', 'technical-center.php');
            }
        } else {
            // Ensure template is set even if page exists but template was changed
            update_post_meta($existing_page->ID, '_wp_page_template', 'technical-center.php');
        }
    }

    /**
     * Set default plugin options
     *
     * @return void
     */
    private static function setDefaultOptions(): void
    {
        $defaults = [
            "rfplugin_version" => RFPLUGIN_VERSION,
            "rfplugin_invoice_prefix" => "RF",
            "rfplugin_enable_pdf" => false,
            "rfplugin_enable_erp" => false,
        ];

        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }

    /**
     * Recursively delete directory
     *
     * @param string $dir Directory path
     * @return void
     */
    private static function deleteDirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), [".", ".."]);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? self::deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
