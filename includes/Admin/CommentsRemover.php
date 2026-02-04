<?php
/**
 * Comments Removal Handler
 * 
 * Disables all comment-related functionality from the WordPress admin
 * and frontend to clean up the interface as required by the project spec.
 * 
 * @package RFPlugin\Admin
 * @since 1.0.0
 */

namespace RFPlugin\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * CommentsRemover class
 */
class CommentsRemover
{
    /**
     * Initialize the remover
     */
    public static function init(): void
    {
        // Disable support for comments and trackbacks in post types
        add_action('admin_init', [__CLASS__, 'disableCommentsSupport']);

        // Close comments on the front-end
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);

        // Remove comments page in menu
        add_action('admin_menu', [__CLASS__, 'disableCommentsAdminMenu']);

        // Redirect any user trying to access comments page
        add_action('admin_init', [__CLASS__, 'disableCommentsAdminMenuRedirect']);

        // Remove comments links from admin bar
        add_action('init', [__CLASS__, 'disableCommentsAdminBar']);
    }

    /**
     * Disable support for comments and trackbacks in post types
     */
    public static function disableCommentsSupport(): void
    {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    /**
     * Remove comments page in menu
     */
    public static function disableCommentsAdminMenu(): void
    {
        remove_menu_page('edit-comments.php');
    }

    /**
     * Redirect any user trying to access comments page
     */
    public static function disableCommentsAdminMenuRedirect(): void
    {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_safe_redirect(admin_url());
            exit;
        }
    }

    /**
     * Remove comments links from admin bar
     */
    public static function disableCommentsAdminBar(): void
    {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }
}
