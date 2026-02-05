<?php

/**
 * Rate Limiter Middleware
 *
 * Provides rate limiting for REST API endpoints to prevent
 * abuse and ensure fair usage.
 *
 * @package RFPlugin\REST\Middleware
 * @since 1.0.0
 */

namespace RFPlugin\REST\Middleware;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * RateLimiter class
 *
 * @since 1.0.0
 */
class RateLimiter
{
    /**
     * Default rate limits
     *
     * @var array
     */
    private $limits = [
        'anonymous' => [
            'requests' => 100,
            'window' => 3600, // 1 hour
        ],
        'authenticated' => [
            'requests' => 1000,
            'window' => 3600, // 1 hour
        ],
        'admin' => [
            'requests' => 10000,
            'window' => 3600, // 1 hour
        ],
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Allow customization via filters
        $this->limits = apply_filters('rf_api_rate_limits', $this->limits);
    }

    /**
     * Check if request is allowed
     *
     * @param WP_REST_Request $request Request object
     * @return bool|WP_Error
     */
    public function checkRateLimit(WP_REST_Request $request)
    {
        $identifier = $this->getIdentifier($request);
        $limit = $this->getLimit($request);

        $key = 'rf_rate_limit_' . md5($identifier);
        $current = get_transient($key);

        if ($current === false) {
            // First request in window
            set_transient($key, 1, $limit['window']);
            return true;
        }

        if ($current >= $limit['requests']) {
            return new WP_Error(
                'rest_rate_limit_exceeded',
                sprintf(
                    __('Rate limit exceeded. Maximum %d requests per %d seconds allowed.', 'rfplugin'),
                    $limit['requests'],
                    $limit['window']
                ),
                ['status' => 429]
            );
        }

        // Increment counter
        set_transient($key, $current + 1, $limit['window']);

        return true;
    }

    /**
     * Get unique identifier for rate limiting
     *
     * @param WP_REST_Request $request Request object
     * @return string
     */
    private function getIdentifier(WP_REST_Request $request): string
    {
        $user_id = get_current_user_id();

        if ($user_id) {
            return 'user_' . $user_id;
        }

        // Use IP address for anonymous users
        $ip = $this->getClientIP();
        return 'ip_' . $ip;
    }

    /**
     * Get rate limit for request
     *
     * @param WP_REST_Request $request Request object
     * @return array
     */
    private function getLimit(WP_REST_Request $request): array
    {
        if (current_user_can('manage_options')) {
            return $this->limits['admin'];
        }

        if (get_current_user_id()) {
            return $this->limits['authenticated'];
        }

        return $this->limits['anonymous'];
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    private function getClientIP(): string
    {
        $ip = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    /**
     * Add rate limit headers to response
     *
     * @param WP_REST_Response $response Response object
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function addRateLimitHeaders(WP_REST_Response $response, WP_REST_Request $request): WP_REST_Response
    {
        $identifier = $this->getIdentifier($request);
        $limit = $this->getLimit($request);
        $key = 'rf_rate_limit_' . md5($identifier);
        $current = (int) get_transient($key);
        $remaining = max(0, $limit['requests'] - $current);

        $response->header('X-RateLimit-Limit', $limit['requests']);
        $response->header('X-RateLimit-Remaining', $remaining);
        $response->header('X-RateLimit-Reset', time() + $limit['window']);

        return $response;
    }

    /**
     * Get current usage for identifier
     *
     * @param string $identifier Identifier (user ID or IP)
     * @return array
     */
    public function getUsage(string $identifier): array
    {
        $key = 'rf_rate_limit_' . md5($identifier);
        $current = (int) get_transient($key);
        $ttl = get_option('_transient_timeout_' . $key);
        $reset = $ttl ? $ttl - time() : 0;

        return [
            'current' => $current,
            'reset_in' => $reset,
        ];
    }
}
