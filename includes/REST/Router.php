<?php

/**
 * REST API Router
 *
 * Registers and manages all REST API endpoints for the plugin.
 *
 * @package RFPlugin\REST
 * @since 1.0.0
 */

namespace RFPlugin\REST;

use RFPlugin\REST\Controllers\ProductsController;
use RFPlugin\REST\Controllers\ServicesController;
use RFPlugin\REST\Controllers\CaseStudiesController;
use RFPlugin\REST\Controllers\InvoicesController;
use RFPlugin\REST\Controllers\ResourcesController;
use RFPlugin\REST\Controllers\FormsController;
use RFPlugin\REST\Middleware\RateLimiter;
use RFPlugin\REST\Middleware\AuthMiddleware;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Router class
 *
 * @since 1.0.0
 */
class Router
{
    /**
     * API namespace
     *
     * @var string
     */
    private string $namespace = 'rf/v1';

    /**
     * Controller instances
     *
     * @var array<string, object>
     */
    private array $controllers = [];

    /**
     * Rate limiter instance
     *
     * @var RateLimiter
     */
    private RateLimiter $rateLimiter;

    /**
     * Auth middleware instance
     *
     * @var AuthMiddleware
     */
    private AuthMiddleware $authMiddleware;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rateLimiter = new RateLimiter();
        $this->authMiddleware = new AuthMiddleware();
    }

    /**
     * Register all REST routes
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        // Initialize controllers
        $this->controllers['services'] = new ServicesController($this->namespace);
        $this->controllers['case-studies'] = new CaseStudiesController($this->namespace);
        $this->controllers['products'] = new ProductsController($this->namespace);
        $this->controllers['invoices'] = new InvoicesController($this->namespace);
        $this->controllers['resources'] = new ResourcesController($this->namespace);
        $this->controllers['forms'] = new FormsController($this->namespace);

        // Register each controller's routes
        foreach ($this->controllers as $controller) {
            $controller->registerRoutes();
        }

        // Add rate limiting to all responses
        add_filter('rest_pre_dispatch', [$this, 'applyRateLimiting'], 10, 3);
        add_filter('rest_post_dispatch', [$this, 'addRateLimitHeaders'], 10, 3);
    }

    /**
     * Apply rate limiting to requests
     *
     * @param mixed $result Response
     * @param \WP_REST_Server $server Server instance
     * @param \WP_REST_Request $request Request object
     * @return mixed
     */
    public function applyRateLimiting($result, $server, $request)
    {
        // Only apply to our API namespace
        $route = $request->get_route();
        if (strpos($route, '/' . $this->namespace . '/') !== 0) {
            return $result;
        }

        $check = $this->rateLimiter->checkRateLimit($request);

        if (is_wp_error($check)) {
            return $check;
        }

        return $result;
    }

    /**
     * Add rate limit headers to response
     *
     * @param \WP_REST_Response $response Response object
     * @param \WP_REST_Server $server Server instance
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response
     */
    public function addRateLimitHeaders($response, $server, $request)
    {
        // Only apply to our API namespace
        $route = $request->get_route();
        if (strpos($route, '/' . $this->namespace . '/') !== 0) {
            return $response;
        }

        return $this->rateLimiter->addRateLimitHeaders($response, $request);
    }
}
