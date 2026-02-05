<?php
/**
 * Authentication Middleware
 *
 * Provides authentication and authorization utilities for REST API.
 *
 * @package RFPlugin\REST\Middleware
 * @since 1.0.0
 */

namespace RFPlugin\REST\Middleware;

use WP_REST_Request;
use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * AuthMiddleware class
 *
 * @since 1.0.0
 */
class AuthMiddleware
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_filter('rest_authentication_errors', [$this, 'authenticateAPIKey']);
    }

    /**
     * Authenticate using API key
     *
     * @param WP_Error|null|bool $result Current authentication result
     * @return WP_Error|null|bool
     */
    public function authenticateAPIKey($result)
    {
        // If already authenticated, return
        if (!empty($result)) {
            return $result;
        }

        // Check for API key in header
        $apiKey = $this->getAPIKey();

        if (empty($apiKey)) {
            return $result;
        }

        // Validate API key
        $user = $this->validateAPIKey($apiKey);

        if (is_wp_error($user)) {
            return $user;
        }

        if ($user) {
            wp_set_current_user($user->ID);
            return true;
        }

        return $result;
    }

    /**
     * Get API key from request
     *
     * @return string|null
     */
    private function getAPIKey(): ?string
    {
        // Check X-API-Key header
        if (!empty($_SERVER['HTTP_X_API_KEY'])) {
            return sanitize_text_field($_SERVER['HTTP_X_API_KEY']);
        }

        // Check Authorization header (Bearer token)
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
            if (preg_match('/^Bearer\s+(.+)$/i', $auth, $matches)) {
                return sanitize_text_field($matches[1]);
            }
        }

        return null;
    }

    /**
     * Validate API key and return associated user
     *
     * @param string $apiKey API key
     * @return \WP_User|WP_Error|null
     */
    private function validateAPIKey(string $apiKey)
    {
        // Get stored API keys (stored as user meta)
        $users = get_users([
            'meta_key' => 'rf_api_key',
            'meta_value' => $apiKey,
            'number' => 1,
        ]);

        if (empty($users)) {
            return new WP_Error(
                'rest_invalid_api_key',
                __('Invalid API key provided.', 'rfplugin'),
                ['status' => 401]
            );
        }

        return $users[0];
    }

    /**
     * Generate API key for user
     *
     * @param int $userId User ID
     * @return string
     */
    public function generateAPIKey(int $userId): string
    {
        $apiKey = wp_generate_password(32, false);
        $hashedKey = wp_hash_password($apiKey);

        update_user_meta($userId, 'rf_api_key', $hashedKey);
        update_user_meta($userId, 'rf_api_key_created', time());

        return $apiKey;
    }

    /**
     * Revoke API key for user
     *
     * @param int $userId User ID
     * @return bool
     */
    public function revokeAPIKey(int $userId): bool
    {
        delete_user_meta($userId, 'rf_api_key');
        delete_user_meta($userId, 'rf_api_key_created');

        return true;
    }

    /**
     * Check if user has capability for action
     *
     * @param string $action Action to check (read, create, update, delete)
     * @param string $postType Post type
     * @return bool
     */
    public function hasCapability(string $action, string $post Type = ''): bool
    {
        $capabilityMap = [
            'read' => 'read',
            'create' => 'edit_posts',
            'update' => 'edit_posts',
            'delete' => 'delete_posts',
        ];

        $capability = $capabilityMap[$action] ?? 'read';

        return current_user_can($capability);
    }

    /**
     * Check if request has valid nonce
     *
     * @param WP_REST_Request $request Request object
     * @return bool
     */
    public function validateNonce(WP_REST_Request $request): bool
    {
        $nonce = $request->get_header('X-WP-Nonce');

        if (empty($nonce)) {
            return false;
        }

        return wp_verify_nonce($nonce, 'wp_rest');
    }

    /**
     * Require authentication
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function requireAuth(WP_REST_Request $request)
    {
        if (!is_user_logged_in() && !$this->getAPIKey()) {
            return new WP_Error(
                'rest_unauthorized',
                __('You must be authenticated to access this endpoint.', 'rfplugin'),
                ['status' => 401]
            );
        }

        return true;
    }

    /**
     * Require specific capability
     *
     * @param string $capability Required capability
     * @return bool|WP_Error
     */
    public function requireCapability(string $capability)
    {
        if (!current_user_can($capability)) {
            return new WP_Error(
                'rest_forbidden',
                __('You do not have permission to perform this action.', 'rfplugin'),
                ['status' => 403]
            );
        }

        return true;
    }

    /**
     * Log authentication attempt
     *
     * @param int $userId User ID
     * @param bool $success Whether authentication succeeded
     * @param string $method Authentication method (api_key, nonce, cookie)
     * @return void
     */
    private function logAuthAttempt(int $userId, bool $success, string $method): void
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        error_log(sprintf(
            '[RF API Auth] User %d - Method: %s - Success: %s',
            $userId,
            $method,
            $success ? 'yes' : 'no'
        ));
    }
}
