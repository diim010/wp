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
use RFPlugin\REST\Controllers\CasesController;
use RFPlugin\REST\Controllers\InvoicesController;
use RFPlugin\REST\Controllers\ResourcesController;
use RFPlugin\REST\Controllers\FormsController;

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
    private string $namespace = 'rfplugin/v1';

    /**
     * Controller instances
     * 
     * @var array<string, object>
     */
    private array $controllers = [];

    /**
     * Register all REST routes
     * 
     * @return void
     */
    public function registerRoutes(): void
    {
        $this->controllers['products'] = new ProductsController($this->namespace);
        $this->controllers['services'] = new ServicesController($this->namespace);
        $this->controllers['cases'] = new CasesController($this->namespace);
        $this->controllers['invoices'] = new InvoicesController($this->namespace);
        $this->controllers['resources'] = new ResourcesController($this->namespace);
        $this->controllers['forms'] = new FormsController($this->namespace);

        foreach ($this->controllers as $controller) {
            $controller->registerRoutes();
        }
    }
}
