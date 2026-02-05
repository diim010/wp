<?php

/**
 * Base REST Controller
 *
 * Provides authentication, rate limiting, caching, and common functionality
 * for all REST API endpoints.
 *
 * @package RFPlugin\API\Controllers
 * @since 2.0.0
 */

namespace RFPlugin\API\Controllers;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Controller class
 *
 * @since 2.0.0
 */
abstract class BaseController extends WP_REST_Controller
{
    /**
     * Namespace for REST routes
     *
     * @var string
     */
    protected $namespace = 'rfplugin/v1';

    /**
     * Cache group
     *
     * @var string
     */
    protected $cache_group = 'rfplugin_api';

    /**
     * Cache expiration (in seconds)
     *
     * @var int
     */
    protected $cache_expiration = 3600; // 1 hour

    /**
     * Rate limit: max requests per time window
     *
     * @var int
     */
    protected $rate_limit = 100;

    /**
     * Rate limit time window (in seconds)
     *
     * @var int
     */
    protected $rate_limit_window = 3600; // 1 hour

    /**
     * Check authentication
     *
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function checkAuthentication(WP_REST_Request $request)
    {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            // Check for API key in header
            $api_key = $request->get_header('X-RF-API-Key');

            if ($api_key) {
                return $this->validateApiKey($api_key);
            }

            return new WP_Error(
                'rest_forbidden',
                __('Authentication required.', 'rfplugin'),
                ['status' => 401]
            );
        }

        return true;
    }

    /**
     * Validate API key
     *
     * @param string $api_key
     * @return bool|WP_Error
     */
    protected function validateApiKey(string $api_key)
    {
        // Get stored API keys from options
        $valid_keys = get_option('rfplugin_api_keys', []);

        foreach ($valid_keys as $key_data) {
            if (hash_equals($key_data['key'], $api_key)) {
                // Check if key is active
                if ($key_data['active'] && (!isset($key_data['expires']) || $key_data['expires'] > time())) {
                    return true;
                }
            }
        }

        return new WP_Error(
            'rest_forbidden',
            __('Invalid or expired API key.', 'rfplugin'),
            ['status' => 401]
        );
    }

    /**
     * Check rate limit
     *
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function checkRateLimit(WP_REST_Request $request)
    {
        $user_id = get_current_user_id();
        $ip_address = $this->getClientIp();

        // Use user ID if logged in, otherwise IP address
        $identifier = $user_id ? "user_{$user_id}" : "ip_{$ip_address}";

        $transient_key = "rfplugin_rate_limit_{$identifier}";
        $requests = get_transient($transient_key);

        if ($requests === false) {
            // First request in this window
            set_transient($transient_key, 1, $this->rate_limit_window);
            return true;
        }

        if ($requests >= $this->rate_limit) {
            return new WP_Error(
                'rest_rate_limit_exceeded',
                sprintf(
                    __('Rate limit exceeded. Maximum %d requests per hour.', 'rfplugin'),
                    $this->rate_limit
                ),
                ['status' => 429]
            );
        }

        // Increment request count
        set_transient($transient_key, $requests + 1, $this->rate_limit_window);

        return true;
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    protected function getClientIp(): string
    {
        $ip = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }

        return sanitize_text_field($ip);
    }

    /**
     * Get cached response
     *
     * @param string $cache_key
     * @return mixed|false
     */
    protected function getCachedResponse(string $cache_key)
    {
        return wp_cache_get($cache_key, $this->cache_group);
    }

    /**
     * Set cached response
     *
     * @param string $cache_key
     * @param mixed $data
     * @param int|null $expiration
     * @return bool
     */
    protected function setCachedResponse(string $cache_key, $data, ?int $expiration = null): bool
    {
        $expiration = $expiration ?? $this->cache_expiration;
        return wp_cache_set($cache_key, $data, $this->cache_group, $expiration);
    }

    /**
     * Invalidate cache
     *
     * @param string $cache_key
     * @return bool
     */
    protected function invalidateCache(string $cache_key): bool
    {
        return wp_cache_delete($cache_key, $this->cache_group);
    }

    /**
     * Prepare response with caching headers
     *
     * @param mixed $data
     * @param int $status
     * @param bool $cacheable
     * @return WP_REST_Response
     */
    protected function prepareResponse($data, int $status = 200, bool $cacheable = true): WP_REST_Response
    {
        $response = new WP_REST_Response($data, $status);

        if ($cacheable) {
            $response->header('Cache-Control', 'public, max-age=' . $this->cache_expiration);
            $response->header('Expires', gmdate('D, d M Y H:i:s', time() + $this->cache_expiration) . ' GMT');
        } else {
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');
        }

        // Add rate limit headers
        $response->header('X-RateLimit-Limit', (string)$this->rate_limit);

        return $response;
    }

    /**
     * Sanitize and validate request parameters
     *
     * @param WP_REST_Request $request
     * @param array $schema
     * @return array|WP_Error
     */
    protected function validateParams(WP_REST_Request $request, array $schema)
    {
        $params = [];
        $errors = [];

        foreach ($schema as $param => $rules) {
            $value = $request->get_param($param);

            // Check required
            if (isset($rules['required']) && $rules['required'] && empty($value)) {
                $errors[$param] = sprintf(__('%s is required.', 'rfplugin'), $param);
                continue;
            }

            // Skip if not provided and not required
            if (empty($value) && !isset($rules['required'])) {
                continue;
            }

            // Sanitize based on type
            if (isset($rules['type'])) {
                switch ($rules['type']) {
                    case 'integer':
                        $params[$param] = absint($value);
                        break;
                    case 'number':
                        $params[$param] = floatval($value);
                        break;
                    case 'boolean':
                        $params[$param] = rest_sanitize_boolean($value);
                        break;
                    case 'string':
                        $params[$param] = sanitize_text_field($value);
                        break;
                    case 'email':
                        $params[$param] = sanitize_email($value);
                        break;
                    case 'url':
                        $params[$param] = esc_url_raw($value);
                        break;
                    default:
                        $params[$param] = $value;
                }
            }

            // Custom validation
            if (isset($rules['validate']) && is_callable($rules['validate'])) {
                $validation_result = call_user_func($rules['validate'], $params[$param]);
                if (is_wp_error($validation_result)) {
                    $errors[$param] = $validation_result->get_error_message();
                }
            }
        }

        if (!empty($errors)) {
            return new WP_Error(
                'rest_invalid_params',
                __('Invalid parameters.', 'rfplugin'),
                ['status' => 400, 'errors' => $errors]
            );
        }

        return $params;
    }

    /**
     * Log API request
     *
     * @param WP_REST_Request $request
     * @param WP_REST_Response|WP_Error $response
     */
    protected function logRequest(WP_REST_Request $request, $response): void
    {
        if (!defined('RFPLUGIN_API_LOGGING') || !RFPLUGIN_API_LOGGING) {
            return;
        }

        $log_entry = [
            'timestamp' => current_time('mysql'),
            'method' => $request->get_method(),
            'route' => $request->get_route(),
            'user_id' => get_current_user_id(),
            'ip' => $this->getClientIp(),
            'status' => is_wp_error($response) ? $response->get_error_code() : $response->get_status(),
        ];

        // Store in custom table or use error_log
        error_log('RF API: ' . wp_json_encode($log_entry));
    }
}
