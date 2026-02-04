<?php
/**
 * Logger Utility
 * 
 * Custom logging system for debugging and monitoring.
 * 
 * @package RFPlugin\Utils
 * @since 1.0.0
 */

namespace RFPlugin\Utils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Logger class
 * 
 * @since 1.0.0
 */
class Logger
{
    /**
     * Log file path
     * 
     * @var string
     */
    private static string $logFile;

    /**
     * Initialize logger
     * 
     * @return void
     */
    public static function init(): void
    {
        $uploadDir = wp_upload_dir();
        self::$logFile = $uploadDir['basedir'] . '/rfplugin-debug.log';
    }

    /**
     * Log info message
     * 
     * @param string $message Log message
     * @param array<string, mixed> $context Additional context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log error message
     * 
     * @param string $message Log message
     * @param array<string, mixed> $context Additional context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log warning message
     * 
     * @param string $message Log message
     * @param array<string, mixed> $context Additional context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Write log entry
     * 
     * @param string $level Log level
     * @param string $message Log message
     * @param array<string, mixed> $context Additional context
     * @return void
     */
    private static function log(string $level, string $message, array $context): void
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        if (empty(self::$logFile)) {
            self::init();
        }

        $timestamp = current_time('mysql');
        $contextStr = !empty($context) ? ' ' . wp_json_encode($context) : '';
        $logEntry = sprintf(
            "[%s] [%s] %s%s\n",
            $timestamp,
            $level,
            $message,
            $contextStr
        );

        error_log($logEntry, 3, self::$logFile);
    }
}
