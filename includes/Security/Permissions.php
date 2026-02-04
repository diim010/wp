<?php
/**
 * Security and Permissions Manager
 * 
 * Handles authentication, authorization, and security
 * for the RFPlugin system.
 * 
 * @package RFPlugin\Security
 * @since 1.0.0
 */

namespace RFPlugin\Security;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Permissions class
 * 
 * @since 1.0.0
 */
class Permissions
{
    /**
     * Initialize security hooks
     * 
     * @return void
     */
    public static function init(): void
    {
        add_action('rest_authentication_errors', [__CLASS__, 'restAuthenticationErrors']);
        self::ensureAdminCapabilities();
    }

    /**
     * Check if user can create invoices
     * 
     * @return bool
     */
    public static function canCreateInvoice(): bool
    {
        return is_user_logged_in();
    }

    /**
     * Check if user can view invoices
     * 
     * @param int $userId User ID (0 for current user)
     * @param int $invoiceId Invoice post ID
     * @return bool
     */
    public static function canViewInvoice(int $userId = 0, int $invoiceId = 0): bool
    {
        if ($userId === 0) {
            $userId = get_current_user_id();
        }

        if (!$userId) {
            return false;
        }

        if (current_user_can('view_rfplugin_invoices')) {
            return true;
        }

        if ($invoiceId) {
            $invoice = get_post($invoiceId);
            return $invoice && (int)$invoice->post_author === $userId;
        }

        return false;
    }

    /**
     * Check if user can manage invoices (admin only)
     * 
     * @return bool
     */
    public static function canManageInvoices(): bool
    {
        return current_user_can('edit_rfplugin_invoices');
    }

    /**
     * Verify REST API nonce
     * 
     * @param string $nonce
     * @return bool
     */
    public static function verifyRestNonce(string $nonce = ''): bool
    {
        return wp_verify_nonce(
            $nonce ?: ($_SERVER['HTTP_X_WP_NONCE'] ?? ''),
            'wp_rest'
        );
    }

    /**
     * Verify a specific action nonce
     * 
     * @param string $action Action name
     * @param string $nonce Nonce value
     * @return bool
     */
    public static function verifyActionNonce(string $action, string $nonce): bool
    {
        return wp_verify_nonce($nonce, $action);
    }

    /**
     * Handle REST authentication errors
     * 
     * @param \WP_Error|mixed $result Authentication result
     * @return \WP_Error|mixed
     */
    public static function restAuthenticationErrors($result)
    {
        if (!empty($result)) {
            return $result;
        }

        return $result;
    }

    /**
     * Check if user can view a specific post based on visibility settings
     * 
     * @param int $post_id Post ID
     * @return bool
     */
    public static function canViewPost(int $post_id): bool
    {
        $id = $post_id ?: get_the_ID();
        if (!$id) {
            return false;
        }

        $post_type = get_post_type($id);
        
        // Default to public if not restricted by logic
        if (!in_array($post_type, ['rf_techdoc', 'rf_faq', 'rf_service', 'rf_case'])) {
            return true;
        }

        // Get visibility from field (centralized mapping)
        $visibility = get_field('field_visibility', $id) ?: get_field('visibility', $id) ?: 
                      get_field('field_tech_visibility', $id) ?: get_field('user_role_visibility', $id) ?: 'guest';

        // Public access
        if (in_array($visibility, ['guest', 'everyone', 'public', ''])) {
            return true;
        }

        // Must be logged in for any other restriction
        if (!is_user_logged_in()) {
            return false;
        }

        // Administrator and Shop Manager always have access
        if (current_user_can('manage_options') || current_user_can('manage_woocommerce')) {
            return true;
        }

        // Role-based capability mapping
        $role_capabilities = [
            'administrator'  => 'manage_options',
            'editor'         => 'edit_others_posts',
            'author'         => 'publish_posts',
            'contributor'    => 'edit_posts',
            'subscriber'     => 'read',
            'customer'       => 'read',
            'shop_manager'   => 'manage_woocommerce',
            'partner'        => 'read',
        ];

        // Specific role check
        if (isset($role_capabilities[$visibility])) {
            return current_user_can($role_capabilities[$visibility]);
        }
        
        // Fallback: If visibility matches user role exactly
        $user = wp_get_current_user();
        if (in_array($visibility, (array)$user->roles)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can view a specific tech doc (Legacy wrapper)
     * 
     * @param int $post_id Tech doc ID
     * @return bool
     */
    public static function canViewTechDoc(int $post_id): bool
    {
        return self::canViewPost($post_id);
    }

    /**
     * Sanitize invoice data
     * 
     * @param array<string, mixed> $data Invoice data
     * @return array<string, mixed>
     */
    public static function sanitizeInvoiceData(array $data): array
    {
        $sanitized = [];

        if (isset($data['customer_name'])) {
            $sanitized['customer_name'] = sanitize_text_field($data['customer_name']);
        }

        if (isset($data['customer_email'])) {
            $sanitized['customer_email'] = sanitize_email($data['customer_email']);
        }

        if (isset($data['customer_phone'])) {
            $sanitized['customer_phone'] = sanitize_text_field($data['customer_phone']);
        }

        if (isset($data['products']) && is_array($data['products'])) {
            $sanitized['products'] = array_map(function ($product) {
                return [
                    'id' => absint($product['id'] ?? 0),
                    'quantity' => absint($product['quantity'] ?? 1),
                    'specifications' => array_map('sanitize_text_field', $product['specifications'] ?? []),
                ];
            }, $data['products']);
        }

        if (isset($data['services']) && is_array($data['services'])) {
            $sanitized['services'] = array_map('absint', $data['services']);
        }

        if (isset($data['notes'])) {
            $sanitized['notes'] = sanitize_textarea_field($data['notes']);
        }

        return $sanitized;
    }

    /**
     * Get real IP address
     * 
     * @return string
     */
    public static function getRealIP(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Check if a new version is available for the current user/IP
     * 
     * @param int $post_id
     * @return bool
     */
    public static function hasNewVersionAvailable(int $post_id): bool
    {
        if (!get_field('field_periodically_updated', $post_id)) {
            return false;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_history';
        $ip = self::getRealIP();
        $user_id = get_current_user_id();

        // Get the latest download by this user/IP for this doc
        $query = $wpdb->prepare(
            "SELECT version_timestamp FROM $table WHERE post_id = %d AND (user_id = %d OR ip_address = %s) ORDER BY download_at DESC LIMIT 1",
            $post_id,
            $user_id,
            $ip
        );

        $last_downloaded_version = $wpdb->get_var($query);

        if (!$last_downloaded_version) {
            return false; // Never downloaded
        }

        $current_version = get_field('field_last_file_update', $post_id);

        // If current version is newer than what they last downloaded
        return (string)$current_version !== (string)$last_downloaded_version && (int)$current_version > (int)$last_downloaded_version;
    }

    /**
     * Move an attachment to a secure directory
     * 
     * @param int $attachment_id
     * @param string $subdir Subdirectory under uploads
     * @return bool
     */
    public static function protectFile(int $attachment_id, string $subdir = 'rfplugin-docs'): bool
    {
        $file_path = get_attached_file($attachment_id);
        if (!$file_path || !file_exists($file_path)) {
            return false;
        }

        $upload_dir = wp_upload_dir();
        $secure_dir = $upload_dir['basedir'] . '/' . $subdir;

        if (!file_exists($secure_dir)) {
            wp_mkdir_p($secure_dir);
            // Basic silence index
            if (!file_exists($secure_dir . "/index.php")) {
                file_put_contents($secure_dir . "/index.php", "<?php\nexit;");
            }
            // Basic htaccess if not exists
            if (!file_exists($secure_dir . "/.htaccess")) {
                file_put_contents($secure_dir . "/.htaccess", "Deny from all");
            }
        }

        $filename = basename($file_path);
        $new_path = $secure_dir . '/' . $filename;

        // If it's already in a secure dir (contains the subdir name), skip
        if (str_contains($file_path, $subdir)) {
            return true;
        }

        if (copy($file_path, $new_path)) {
            update_attached_file($attachment_id, $new_path);
            @unlink($file_path);
            return true;
        }

        return false;
    }

    /**
     * Ensure administrator role has all necessary capabilities
     * 
     * @return void
     */
    public static function ensureAdminCapabilities(): void
    {
        $adminRole = get_role('administrator');
        if (!$adminRole) {
            return;
        }

        $capabilities = [
            'manage_rfplugin',
            'view_rf_invoices',
            'create_rf_invoices',
            'edit_rf_invoices',
            'delete_rf_invoices',
            'edit_others_rf_invoices',
            'delete_others_rf_invoices',
            'publish_rf_invoices',
            'read_private_rf_invoices'
        ];

        foreach ($capabilities as $cap) {
            if (!$adminRole->has_cap($cap)) {
                $adminRole->add_cap($cap);
            }
        }
    }
}
