<?php
/**
 * Plugin Deactivation Handler
 * 
 * Handles plugin deactivation cleanup tasks.
 * Does not remove data to allow for reactivation.
 * 
 * @package RFPlugin\Core
 * @since 1.0.0
 */

namespace RFPlugin\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Deactivator class
 * 
 * @since 1.0.0
 */
class Deactivator
{
    /**
     * Execute deactivation tasks
     * 
     * @return void
     */
    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }
}
