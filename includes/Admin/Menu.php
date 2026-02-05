<?php

/**
 * Admin Menu Manager
 *
 * Creates the RoyalFoam admin menu structure and organizes
 * all custom post types under a unified interface.
 * Now with multisite network admin support.
 *
 * @package RFPlugin\Admin
 * @since 1.0.0
 */

namespace RFPlugin\Admin;

if (!defined("ABSPATH")) {
    exit();
}

/**
 * Menu class
 *
 * @since 1.0.0
 */
class Menu
{
    /**
     * Menu slug for the main page
     *
     * @var string
     */
    private string $menuSlug = "royalfoam";

    /**
     * Register the admin menu
     *
     * @return void
     */
    public function register(): void
    {
        // Regular admin menu
        $this->registerSiteMenu();

        // Network admin menu for multisite
        if (is_multisite()) {
            add_action("network_admin_menu", [$this, "registerNetworkMenu"]);
        }

        // Redirect default dashboard to RoyalFoam dashboard
        add_action("admin_init", [$this, "redirectDefaultDashboard"]);
    }

    /**
     * Redirect default dashboard to RoyalFoam dashboard
     *
     * @return void
     */
    public function redirectDefaultDashboard(): void
    {
        global $pagenow;

        if ($pagenow === "index.php" && !isset($_GET["page"])) {
            wp_safe_redirect(admin_url("admin.php?page=" . $this->menuSlug));
            exit();
        }
    }

    /**
     * Register site-level admin menu
     *
     * @return void
     */
    private function registerSiteMenu(): void
    {
        add_menu_page(
            __("RoyalFoam", "rfplugin"),
            __("RoyalFoam", "rfplugin"),
            "manage_options",
            $this->menuSlug,
            [$this, "renderDashboard"],
            "dashicons-admin-home",
            3,
        );

        $this->registerSiteSubmenus();
    }

    /**
     * Register site-level submenus
     *
     * @return void
     */
    private function registerSiteSubmenus(): void
    {
        add_submenu_page(
            $this->menuSlug,
            __("Dashboard", "rfplugin"),
            __("Dashboard", "rfplugin"),
            "manage_options",
            $this->menuSlug,
            [$this, "renderDashboard"],
        );

        // Products subpage removed in favor of WooCommerce main menu
        // Services and Cases subpages removed

        add_submenu_page(
            $this->menuSlug,
            __("Invoices", "rfplugin"),
            __("Invoices", "rfplugin"),
            "edit_rf_invoices",
            "edit.php?post_type=rf_invoice",
        );

        add_menu_page(
            __("Tech Center", "rfplugin"),
            __("Tech Center", "rfplugin"),
            "edit_posts",
            "rf-tech-center",
            [$this, "renderTechCenterDashboard"],
            "dashicons-category",
            4,
        );

        add_submenu_page(
            "rf-tech-center",
            __("Tech Center Dashboard", "rfplugin"),
            __("Dashboard", "rfplugin"),
            "edit_posts",
            "rf-tech-center",
            [$this, "renderTechCenterDashboard"],
        );

        add_submenu_page(
            "rf-tech-center",
            __("All Resources", "rfplugin"),
            __("Library", "rfplugin"),
            "edit_posts",
            "edit.php?post_type=rf_resource",
        );

        add_submenu_page(
            "rf-tech-center",
            __("Resource Types", "rfplugin"),
            __("Types", "rfplugin"),
            "manage_categories",
            "edit-tags.php?taxonomy=rf_resource_type&post_type=rf_resource",
        );

        add_submenu_page(
            "rf-tech-center",
            __("Resource Categories", "rfplugin"),
            __("Categories", "rfplugin"),
            "manage_categories",
            "edit-tags.php?taxonomy=rf_resource_category&post_type=rf_resource",
        );

        add_submenu_page(
            $this->menuSlug,
            __("Settings", "rfplugin"),
            __("Settings", "rfplugin"),
            "manage_options",
            $this->menuSlug . "-settings",
            [$this, "renderSettings"],
        );

        add_submenu_page(
            $this->menuSlug,
            __("Documentation", "rfplugin"),
            __("Documentation", "rfplugin"),
            "manage_options",
            $this->menuSlug . "-docs",
            [$this, "renderDocumentation"],
        );

        add_submenu_page(
            $this->menuSlug,
            __("Security Stats", "rfplugin"),
            __("Security Stats", "rfplugin"),
            "manage_options",
            $this->menuSlug . "-security",
            [$this, "renderSecurityStats"],
        );
    }

    /**
     * Register network admin menu for multisite
     *
     * @return void
     */
    public function registerNetworkMenu(): void
    {
        add_menu_page(
            __("RoyalFoam Network", "rfplugin"),
            __("RoyalFoam", "rfplugin"),
            "manage_network_options",
            $this->menuSlug . "-network",
            [$this, "renderNetworkDashboard"],
            "dashicons-networking",
            3,
        );

        add_submenu_page(
            $this->menuSlug . "-network",
            __("Network Dashboard", "rfplugin"),
            __("Dashboard", "rfplugin"),
            "manage_network_options",
            $this->menuSlug . "-network",
            [$this, "renderNetworkDashboard"],
        );

        add_submenu_page(
            $this->menuSlug . "-network",
            __("Network Settings", "rfplugin"),
            __("Settings", "rfplugin"),
            "manage_network_options",
            $this->menuSlug . "-network-settings",
            [$this, "renderNetworkSettings"],
        );
    }

    /**
     * Render the dashboard page
     *
     * @return void
     */
    public function renderDashboard(): void
    {
        $stats = $this->getStatistics();
        include RFPLUGIN_PATH . "templates/admin/dashboard.php";
    }

    /**
     * Render Tech Center dashboard
     *
     * @return void
     */
    public function renderTechCenterDashboard(): void
    {
        $stats = $this->getStatistics();
        include RFPLUGIN_PATH . "templates/admin/tech-center-dashboard.php";
    }

    /**
     * Render network dashboard
     *
     * @return void
     */
    public function renderNetworkDashboard(): void
    {
        $networkStats = $this->getNetworkStatistics();
        include RFPLUGIN_PATH . "templates/admin/network-dashboard.php";
    }

    /**
     * Render network settings
     *
     * @return void
     */
    public function renderNetworkSettings(): void
    {
        if (isset($_POST["rfplugin_save_network_settings"])) {
            $this->saveNetworkSettings();
        }

        include RFPLUGIN_PATH . "templates/admin/network-settings.php";
    }

    /**
     * Render the settings page
     *
     * @return void
     */
    public function renderSettings(): void
    {
        if (isset($_POST["rfplugin_save_settings"])) {
            $this->handleSettingsSave();
        }

        if (isset($_POST["rfplugin_import_all"])) {
            $this->handleDataImport("all");
        }

        if (isset($_POST["rfplugin_import_products"])) {
            $this->handleDataImport("products");
        }

        if (isset($_POST["rfplugin_import_resources"])) {
            $this->handleDataImport("resources");
        }

        if (isset($_POST["rfplugin_flush_rules"])) {
            $this->handleFlushRules();
        }

        include RFPLUGIN_PATH . "templates/admin/settings.php";
    }

    /**
     * Handle rewrite rules flush
     *
     * @return void
     */
    private function handleFlushRules(): void
    {
        check_admin_referer("rfplugin_settings");

        if (!current_user_can("manage_options")) {
            return;
        }

        flush_rewrite_rules();

        add_settings_error(
            "rfplugin_messages",
            "rfplugin_rules_flushed",
            __("Rewrite rules successfully rebuilt.", "rfplugin"),
            "updated",
        );
    }

    /**
     * Render the documentation page
     *
     * @return void
     */
    public function renderDocumentation(): void
    {
        $page = $_GET['page'] ?? '';
        $doc = $_GET['doc'] ?? 'index';

        // Security check for doc path
        $doc = basename($doc);

        include RFPLUGIN_PATH . "templates/admin/documentation.php";
    }

    /**
     * Render security stats page
     *
     * @return void
     */
    public function renderSecurityStats(): void
    {
        global $wpdb;
        $history_table = $wpdb->prefix . 'rf_download_history';
        $locks_table = $wpdb->prefix . 'rf_download_locks';

        // 1. Handle Purging
        if (isset($_POST['rfplugin_clear_security_data'])) {
            check_admin_referer('rfplugin_clear_security');
            if (current_user_can('manage_options')) {
                \RFPlugin\Security\DownloadProtector::clearHistory();
                \RFPlugin\Security\DownloadProtector::clearLocks();
                add_settings_error('rfplugin_messages', 'rfplugin_message', __('All security data purged.', 'rfplugin'), 'updated');
            }
        }

        // 2. Prepare Filters
        $where = ["1=1"];
        $params = [];

        if (!empty($_GET['ip_filter'])) {
            $where[] = "h.ip_address LIKE %s";
            $params[] = '%' . $wpdb->esc_like(sanitize_text_field($_GET['ip_filter'])) . '%';
        }

        if (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') {
            $where[] = "h.is_suspicious = %d";
            $params[] = (int)$_GET['status_filter'];
        }

        if (!empty($_GET['date_from'])) {
            $where[] = "h.download_at >= %s";
            $params[] = sanitize_text_field($_GET['date_from']) . ' 00:00:00';
        }

        if (!empty($_GET['date_to'])) {
            $where[] = "h.download_at <= %s";
            $params[] = sanitize_text_field($_GET['date_to']) . ' 23:59:59';
        }

        $where_sql = count($params) > 0 ? $wpdb->prepare(implode(' AND ', $where), $params) : implode(' AND ', $where);

        $stats = [
            'total_downloads' => $wpdb->get_var("SELECT COUNT(*) FROM $history_table"),
            'active_locks' => $wpdb->get_var("SELECT COUNT(*) FROM $locks_table"),
            'suspicious_count' => $wpdb->get_var("SELECT COUNT(*) FROM $history_table WHERE is_suspicious = 1"),
            'dangerous_users' => $wpdb->get_results("SELECT ip_address, COUNT(*) as suspicious_hits FROM $history_table WHERE is_suspicious = 1 GROUP BY ip_address HAVING suspicious_hits > 3"),
            'recent_history' => $wpdb->get_results("SELECT h.*, p.post_title FROM $history_table h LEFT JOIN {$wpdb->posts} p ON h.post_id = p.ID WHERE $where_sql ORDER BY h.download_at DESC LIMIT 100"),
        ];

        include RFPLUGIN_PATH . "templates/admin/security-stats.php";
    }

    /**
     * Get plugin statistics for dashboard
     *
     * @return array<string, int>
     */
    /**
     * Get plugin statistics for dashboard
     *
     * @return array<string, int>
     */
    private function getStatistics(): array
    {
        $cached_stats = get_transient('rfplugin_dashboard_stats');

        if ($cached_stats !== false) {
            return $cached_stats;
        }

        $stats = [
            "products" => wp_count_posts("product")->publish ?? 0,
            "services" => wp_count_posts("rf_service")->publish ?? 0,
            "cases" => wp_count_posts("rf_case_study")->publish ?? 0,
            "invoices" => wp_count_posts("rf_invoice")->publish ?? 0,
            "resources" => wp_count_posts("rf_resource")->publish ?? 0,
            "recent_activity" => get_posts([
                'post_type' => ['product', 'rf_service', 'rf_case_study', 'rf_invoice', 'rf_resource'],
                'posts_per_page' => 10,
                'status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            ]),
        ];

        set_transient('rfplugin_dashboard_stats', $stats, HOUR_IN_SECONDS);

        return $stats;
    }

    /**
     * Get network-wide statistics
     *
     * @return array<string, mixed>
     */
    private function getNetworkStatistics(): array
    {
        if (!is_multisite()) {
            return $this->getStatistics();
        }

        $totalStats = [
            "total_sites" => get_blog_count(),
            "total_products" => 0,
            // Services and Cases stats removed
            "total_invoices" => 0,
            "total_resources" => 0,
        ];

        $sites = get_sites(["number" => 100]);
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            $stats = $this->getStatistics();
            $totalStats["total_products"] += $stats["products"];
            $totalStats["total_invoices"] += $stats["invoices"];
            $totalStats["total_resources"] += $stats["resources"];
            restore_current_blog();
        }

        return $totalStats;
    }

    /**
     * Save plugin settings
     *
     * @return void
     */
    private function handleSettingsSave(): void
    {
        check_admin_referer("rfplugin_settings");

        $options = [
            "rfplugin_invoice_prefix",
            "rfplugin_enable_pdf",
            "rfplugin_enable_erp",
            "rfplugin_zoho_client_id",
            "rfplugin_zoho_client_secret",
            "rfplugin_zoho_refresh_token",
        ];

        foreach ($options as $option) {
            if (isset($_POST[$option])) {
                update_option($option, sanitize_text_field($_POST[$option]));
            }
        }

        add_settings_error(
            "rfplugin_messages",
            "rfplugin_message",
            __("Settings saved successfully.", "rfplugin"),
            "updated",
        );
    }

    /**
     * Handle multi-type data import
     *
     * @return void
     */
    private function handleDataImport(string $type = ''): void
    {
        check_admin_referer("rfplugin_settings");

        if (!current_user_can('manage_options')) {
            return;
        }

        $importer = new \RFPlugin\Services\TestDataImporter();
        $results = [];

        if (isset($_POST["rfplugin_import_all"])) {
            $results[] = $importer->importFromXML('product');
            $results[] = $importer->importFromXML('rf_resource', 'rf_resource_category');
        } else {
            if (isset($_POST["rfplugin_import_products"])) {
                $results[] = $importer->importFromXML('product');
            }
            if (isset($_POST["rfplugin_import_test_data"]) || isset($_POST["rfplugin_import_resources"])) {
                $results[] = $importer->importFromXML('rf_resource', 'rf_resource_category');
            }
            // Services and Cases import handlers removed
        }

        foreach ($results as $res) {
            add_settings_error(
                "rfplugin_messages",
                "rfplugin_message",
                $res['message'],
                $res['success'] ? 'updated' : 'error'
            );
        }
    }

    /**
     * Legacy wrapper for FAQ import (kept for safety if called elsewhere)
     *
     * @return void
     */
    private function importTestData(): void
    {
        $this->handleDataImport();
    }

    /**
     * Save network settings
     *
     * @return void
     */
    private function saveNetworkSettings(): void
    {
        check_admin_referer("rfplugin_network_settings");

        $networkOptions = [
            "rfplugin_network_invoice_prefix",
            "rfplugin_network_enable_pdf",
            "rfplugin_network_enable_erp",
        ];

        foreach ($networkOptions as $option) {
            if (isset($_POST[$option])) {
                update_site_option(
                    $option,
                    sanitize_text_field($_POST[$option]),
                );
            }
        }

        add_settings_error(
            "rfplugin_network_messages",
            "rfplugin_network_message",
            __("Network settings saved successfully.", "rfplugin"),
            "updated",
        );
    }
}
