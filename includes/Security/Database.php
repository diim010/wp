<?php
/**
 * Security Database Class
 * 
 * Handles creation and management of custom tables for
 * download throttling and history tracking.
 * 
 * @package RFPlugin\Security
 * @since 1.0.0
 */

namespace RFPlugin\Security;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Database class
 */
class Database
{
    /**
     * Initialize database tables
     * 
     * @return void
     */
    public static function init(): void
    {
        self::createDownloadLocksTable();
        self::createDownloadHistoryTable();
    }

    /**
     * Create table for tracking active downloads (for throttling)
     * 
     * @return void
     */
    private static function createDownloadLocksTable(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rf_download_locks';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ip_address varchar(45) NOT NULL,
            post_id bigint(20) NOT NULL,
            started_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            expires_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY ip_address (ip_address)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Create table for tracking download history (for versioning and security)
     * 
     * @return void
     */
    private static function createDownloadHistoryTable(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rf_download_history';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT 0 NOT NULL,
            ip_address varchar(45) NOT NULL,
            post_id bigint(20) NOT NULL,
            download_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            user_agent text NOT NULL,
            version_timestamp varchar(50) DEFAULT '' NOT NULL,
            is_suspicious tinyint(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY ip_address (ip_address),
            KEY post_id (post_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
