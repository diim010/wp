<?php
/**
 * Core Plugin Class
 * 
 * Main plugin orchestrator using singleton pattern.
 * Initializes all components, registers hooks, and manages plugin lifecycle.
 * 
 * @package RFPlugin\Core
 * @since 1.0.0
 */

namespace RFPlugin\Core;


use RFPlugin\Security\Database;
use RFPlugin\PostTypes\InvoicePostType;
use RFPlugin\PostTypes\ResourcePostType;
use RFPlugin\ACF\Blocks\BlockLoader;
use RFPlugin\Taxonomies\ProductTypeTaxonomy;
use RFPlugin\Taxonomies\MaterialTaxonomy;
use RFPlugin\Taxonomies\ResourceTypeTaxonomy;
use RFPlugin\Taxonomies\ResourceCategoryTaxonomy;
use RFPlugin\Admin\Menu;
use RFPlugin\Admin\Branding;
use RFPlugin\Admin\CommentsRemover;
use RFPlugin\Frontend\QuoteForm;
use RFPlugin\Core\WoocommerceHooks;
use RFPlugin\REST\Router;
use RFPlugin\ACF\FieldGroups;
use RFPlugin\Security\Permissions;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin Class
 * 
 * @since 1.0.0
 */
class Plugin
{
    /**
     * Single instance of the plugin
     * 
     * @var Plugin|null
     */
    private static ?Plugin $instance = null;

    /**
     * Post type instances
     * 
     * @var array<string, object>
     */
    private array $postTypes = [];

    /**
     * Taxonomy instances
     * 
     * @var array<string, object>
     */
    private array $taxonomies = [];

    /**
     * Admin menu instance
     * 
     * @var Menu|null
     */
    private ?Menu $menu = null;

    /**
     * REST router instance
     * 
     * @var Router|null
     */
    private ?Router $restRouter = null;

    /**
     * Get singleton instance
     * 
     * @return Plugin
     */
    public static function getInstance(): Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $this->initHooks();
    }

    /**
     * Initialize WordPress hooks
     * 
     * @return void
     */
    private function initHooks(): void
    {
        add_action('init', [$this, 'registerPostTypes']);
        add_action('init', [$this, 'registerTaxonomies']);
        add_action('init', [$this, 'loadTextDomain']);
        add_action('admin_menu', [$this, 'registerAdminMenu']);
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
        add_action('acf/init', [$this, 'registerACFFields']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);

        // Plugin Templates
        add_filter('template_include', [$this, 'loadPluginTemplates']);

        // Activation / Deactivation
        register_activation_hook(RFPLUGIN_BASENAME, [$this, 'activate']);

        new Branding();
        CommentsRemover::init();
        WoocommerceHooks::init();
        Permissions::init();
        new QuoteForm();
        (new BlockLoader())->init();
        Database::init();
        add_action('acf/save_post', [$this, 'handlePostSave'], 20);
    }

    /**
     * Load plugin text domain for translations
     * 
     * @return void
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'rfplugin',
            false,
            dirname(RFPLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Register all custom post types
     * 
     * @return void
     */
    public function registerPostTypes(): void
    {
        // ProductPostType removed in favor of WooCommerce
        // Service and Case post types removed
        $this->postTypes['invoice'] = new InvoicePostType();
        $this->postTypes['resource'] = new ResourcePostType();

        foreach ($this->postTypes as $postType) {
            $postType->register();
        }
    }

    /**
     * Register all custom taxonomies
     * 
     * @return void
     */
    public function registerTaxonomies(): void
    {
        $this->taxonomies['product_type'] = new ProductTypeTaxonomy();
        $this->taxonomies['material'] = new MaterialTaxonomy();
        // Case Industry and Service Category taxonomies removed
        $this->taxonomies['resource_type'] = new ResourceTypeTaxonomy();
        $this->taxonomies['resource_category'] = new ResourceCategoryTaxonomy();

        foreach ($this->taxonomies as $taxonomy) {
            $taxonomy->register();
        }
    }

    /**
     * Register admin menu
     * 
     * @return void
     */
    public function registerAdminMenu(): void
    {
        $this->menu = new Menu();
        $this->menu->register();
    }

    /**
     * Register REST API routes
     * 
     * @return void
     */
    public function registerRestRoutes(): void
    {
        $this->restRouter = new Router();
        $this->restRouter->registerRoutes();
    }

    /**
     * Register ACF field groups
     * 
     * @return void
     */
    public function registerACFFields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            FieldGroups::register();
        }
    }

    /**
     * Enqueue admin assets
     * 
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueueAdminAssets(string $hook): void
    {
        wp_enqueue_style(
            'rfplugin-design',
            RFPLUGIN_URL . 'assets/css/design-system.css',
            [],
            RFPLUGIN_VERSION
        );

        wp_enqueue_style(
            'rfplugin-admin',
            RFPLUGIN_URL . 'assets/css/admin.css',
            [],
            RFPLUGIN_VERSION
        );

        wp_enqueue_script(
            'rfplugin-admin',
            RFPLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            RFPLUGIN_VERSION,
            true
        );

        wp_localize_script('rfplugin-admin', 'rfpluginAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('rfplugin/v1'),
            'nonce' => wp_create_nonce('rfplugin_admin'),
        ]);
    }

    /**
     * Enqueue frontend assets
     * 
     * @return void
     */
    public function enqueueFrontendAssets(): void
    {
        wp_enqueue_style(
            'rfplugin-design',
            RFPLUGIN_URL . 'assets/css/design-system.css',
            [],
            RFPLUGIN_VERSION
        );

        wp_enqueue_style(
            'rfplugin-frontend',
            RFPLUGIN_URL . 'assets/css/frontend.css',
            [],
            RFPLUGIN_VERSION
        );

        wp_enqueue_script(
            'rfplugin-react',
            RFPLUGIN_URL . 'assets/js/frontend.js',
            ['react', 'react-dom'],
            RFPLUGIN_VERSION,
            true
        );

        wp_enqueue_script(
            'rfplugin-forms',
            RFPLUGIN_URL . 'assets/js/forms.js',
            ['jquery'],
            RFPLUGIN_VERSION,
            true
        );

        // Technical Center Assets
        if (is_page_template('technical-center.php')) {
            wp_enqueue_script(
                'rf-tech-center',
                RFPLUGIN_URL . 'assets/js/tech-center.js',
                ['jquery'],
                RFPLUGIN_VERSION,
                true
            );
        }

        wp_localize_script('rfplugin-react', 'rfpluginData', [
            'restUrl' => rest_url('rfplugin/v1'),
            'nonce' => wp_create_nonce('wp_rest'),
            'isUserLoggedIn' => is_user_logged_in(),
        ]);
    }

    /**
     * Load custom templates for plugin post types
     * 
     * @param string $template
     * @return string
     */
    public function loadPluginTemplates(string $template): string
    {
        // Resource Library Templates
        if (is_post_type_archive('rf_resource')) {
            $plugin_template = RFPLUGIN_PATH . 'templates/frontend/archive-rf_resource.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        if (is_singular('rf_resource')) {
            $plugin_template = RFPLUGIN_PATH . 'templates/frontend/single-rf_resource.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        // Product (WooCommerce) single template priority
        if (is_singular('product')) {
            $plugin_template = RFPLUGIN_PATH . 'templates/frontend/single-product.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        // Invoice single template priority
        if (is_singular('rf_invoice')) {
            $plugin_template = RFPLUGIN_PATH . 'templates/frontend/single-rf_invoice.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        // Service and Case templates removed

        // Technical Center Template
        if (is_page_template('technical-center.php')) {
            $plugin_template = RFPLUGIN_PATH . 'templates/frontend/technical-center.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        return $template;
    }


    /**
     * Handle plugin activation
     * 
     * @return void
     */
    public function activate(): void
    {
        $this->registerPostTypes();
        $this->registerTaxonomies();
        flush_rewrite_rules();
    }

    /**
     * Handle Tech Doc or Product save to move files to secure storage.
     * 
     * @param int|string $post_id
     * @return void
     */
    public function handlePostSave($post_id): void
    {
        $post_type = get_post_type($post_id);
        
        if (!in_array($post_type, ['rf_resource', 'product']) || wp_is_post_revision($post_id)) {
            return;
        }

        // 1. Logic for Resources
        if ($post_type === 'rf_resource') {
            $file_field = get_field('field_resource_file', $post_id);
            if ($file_field) {
                $file_id = is_array($file_field) ? ($file_field['ID'] ?? 0) : attachment_url_to_postid($file_field);
                if ($file_id) {
                    \RFPlugin\Security\Permissions::protectFile($file_id, 'rfplugin-docs');
                }
                update_field('field_last_resource_update', time(), $post_id);
            }
        }

        // 2. Logic for Products (Tech Files)
        if ($post_type === 'product') {
            $tech_files = get_field('tech_files', $post_id);
            if ($tech_files && is_array($tech_files)) {
                foreach ($tech_files as $file) {
                    $file_id = is_array($file) ? ($file['ID'] ?? 0) : attachment_url_to_postid($file);
                    if ($file_id) {
                        \RFPlugin\Security\Permissions::protectFile($file_id, 'rfplugin-docs');
                    }
                }
            }
        }
    }

    /**
     * Prevent cloning
     * 
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserialization
     * 
     * @return void
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}
