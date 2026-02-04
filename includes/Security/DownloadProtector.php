<?php
/**
 * Download Protector Service
 * 
 * Handles IP throttling, lock management, and suspicious activity detection
 * to ensure secure and controlled file downloads.
 * 
 * @package RFPlugin\Security
 * @since 1.0.0
 */

namespace RFPlugin\Security;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * DownloadProtector class
 */
class DownloadProtector
{
    /**
     * Check if an IP address has an active download lock
     * 
     * @param string $ip
     * @return bool
     */
    public static function hasActiveLock(string $ip): bool
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_locks';
        
        // Cleanup expired locks first (Enterprise: could be moved to a cron)
        $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE expires_at < %s",
            current_time('mysql')
        ));

        $lock = $wpdb->get_row($wpdb->prepare(
            "SELECT id, expires_at FROM $table WHERE ip_address = %s",
            $ip
        ));

        return !empty($lock);
    }

    /**
     * Create a download lock for an IP
     * 
     * @param string $ip
     * @param int $post_id
     * @param int $minutes Duration in minutes
     * @return void
     */
    public static function createLock(string $ip, int $post_id, int $minutes = 5): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_locks';
        
        $wpdb->insert($table, [
            'ip_address' => $ip,
            'post_id'    => $post_id,
            'expires_at' => date('Y-m-d H:i:s', strtotime("+{$minutes} minutes")),
        ]);
    }

    /**
     * Release a download lock for an IP
     * 
     * @param string $ip
     * @return void
     */
    public static function releaseLock(string $ip): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_locks';
        $wpdb->delete($table, ['ip_address' => $ip]);
    }

    /**
     * Check if a user is considered dangerous based on history
     * 
     * @param string $ip
     * @return bool
     */
    public static function isDangerous(string $ip): bool
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_history';
        
        // If marked suspicious more than 5 times ever
        $suspicious_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE ip_address = %s AND is_suspicious = 1",
            $ip
        ));

        if ((int)$suspicious_count > 5) {
            return true;
        }

        // Check for rapid downloads in the last 24 hours
        $daily_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE ip_address = %s AND download_at > %s",
            $ip,
            date('Y-m-d H:i:s', strtotime('-24 hours'))
        ));

        return (int)$daily_count > 100; // Enterprise threshold
    }

    /**
     * Check if current activity is suspicious
     * 
     * @param string $ip
     * @param int $threshold Max downloads per minute
     * @return bool
     */
    public static function checkSuspicion(string $ip, int $threshold = 10): bool
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_history';
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE ip_address = %s AND download_at > %s",
            $ip,
            date('Y-m-d H:i:s', strtotime('-1 minute'))
        ));

        return (int)$count > $threshold;
    }

    /**
     * Log a download attempt
     * 
     * @param int $post_id
     * @param string $ip
     * @param string $version_timestamp
     * @return void
     */
    public static function logDownload(int $post_id, string $ip, string $version_timestamp = ''): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_history';
        
        $wpdb->insert($table, [
            'user_id'           => get_current_user_id(),
            'ip_address'        => $ip,
            'post_id'           => $post_id,
            'user_agent'        => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'version_timestamp' => $version_timestamp,
        ]);
    }

    /**
     * Clear all download history
     * 
     * @return void
     */
    public static function clearHistory(): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_history';
        $wpdb->query("TRUNCATE TABLE $table");
    }

    /**
     * Clear all active locks
     * 
     * @return void
     */
    public static function clearLocks(): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'rf_download_locks';
        $wpdb->query("TRUNCATE TABLE $table");
    }
}
