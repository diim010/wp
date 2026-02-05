<?php

/**
 * Network Statistics Service
 *
 * Aggregates data across all sites in a multisite network.
 * Includes caching for performance.
 *
 * @package RFPlugin\Services
 * @since 2.0.0
 */

namespace RFPlugin\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * NetworkStats class
 */
class NetworkStats
{
    /**
     * Cache group
     */
    private const CACHE_GROUP = 'rfplugin_network';

    /**
     * Cache expiration (1 hour)
     */
    private const CACHE_EXPIRY = 3600;

    /**
     * Get aggregated statistics from all network sites
     *
     * @return array
     */
    public static function getAggregatedStats(): array
    {
        if (!\is_multisite()) {
            return self::getSingleSiteStats();
        }

        $cached = \wp_cache_get('network_stats', self::CACHE_GROUP);
        if ($cached !== false) {
            return $cached;
        }

        $stats = [
            'total_sites' => 0,
            'active_sites' => 0,
            'total_products' => 0,
            'total_services' => 0,
            'total_cases' => 0,
            'total_resources' => 0,
            'total_invoices' => 0,
            'sites' => [],
        ];

        $sites = \get_sites(['number' => 500]);
        $stats['total_sites'] = \count($sites);

        foreach ($sites as $site) {
            \switch_to_blog($site->blog_id);

            $site_data = [
                'id' => $site->blog_id,
                'name' => \get_bloginfo('name'),
                'url' => \get_home_url(),
                'admin_url' => \get_admin_url(),
                'domain' => $site->domain,
                'path' => $site->path,
                'products' => (int) (\wp_count_posts('product')->publish ?? 0),
                'services' => (int) (\wp_count_posts('rf_service')->publish ?? 0),
                'cases' => (int) (\wp_count_posts('rf_case_study')->publish ?? 0),
                'resources' => (int) (\wp_count_posts('rf_resource')->publish ?? 0),
                'invoices' => (int) (\wp_count_posts('rf_invoice')->publish ?? 0),
                'plugin_active' => \is_plugin_active(RFPLUGIN_BASENAME),
                'last_updated' => \get_option('rfplugin_last_activity', ''),
            ];

            // Aggregate totals
            $stats['total_products'] += $site_data['products'];
            $stats['total_services'] += $site_data['services'];
            $stats['total_cases'] += $site_data['cases'];
            $stats['total_resources'] += $site_data['resources'];
            $stats['total_invoices'] += $site_data['invoices'];

            if ($site_data['plugin_active']) {
                $stats['active_sites']++;
            }

            $stats['sites'][] = $site_data;

            \restore_current_blog();
        }

        \wp_cache_set('network_stats', $stats, self::CACHE_GROUP, self::CACHE_EXPIRY);

        return $stats;
    }

    /**
     * Get stats for single site (non-multisite)
     *
     * @return array
     */
    private static function getSingleSiteStats(): array
    {
        return [
            'total_sites' => 1,
            'active_sites' => 1,
            'total_products' => (int) (\wp_count_posts('product')->publish ?? 0),
            'total_services' => (int) (\wp_count_posts('rf_service')->publish ?? 0),
            'total_cases' => (int) (\wp_count_posts('rf_case_study')->publish ?? 0),
            'total_resources' => (int) (\wp_count_posts('rf_resource')->publish ?? 0),
            'total_invoices' => (int) (\wp_count_posts('rf_invoice')->publish ?? 0),
            'sites' => [[
                'id' => \get_current_blog_id(),
                'name' => \get_bloginfo('name'),
                'url' => \get_home_url(),
                'admin_url' => \admin_url(),
                'products' => (int) (\wp_count_posts('product')->publish ?? 0),
                'services' => (int) (\wp_count_posts('rf_service')->publish ?? 0),
                'cases' => (int) (\wp_count_posts('rf_case_study')->publish ?? 0),
                'resources' => (int) (\wp_count_posts('rf_resource')->publish ?? 0),
                'invoices' => (int) (\wp_count_posts('rf_invoice')->publish ?? 0),
                'plugin_active' => true,
            ]],
        ];
    }

    /**
     * Get recent activity across network
     *
     * @param int $limit
     * @return array
     */
    public static function getNetworkActivity(int $limit = 10): array
    {
        if (!\is_multisite()) {
            return self::getSiteActivity($limit);
        }

        $all_activity = [];
        $sites = \get_sites(['number' => 100]);

        foreach ($sites as $site) {
            \switch_to_blog($site->blog_id);

            $posts = \get_posts([
                'post_type' => ['product', 'rf_service', 'rf_case_study', 'rf_resource', 'rf_invoice'],
                'posts_per_page' => 5,
                'orderby' => 'modified',
                'order' => 'DESC',
            ]);

            foreach ($posts as $post) {
                $all_activity[] = [
                    'site_id' => $site->blog_id,
                    'site_name' => \get_bloginfo('name'),
                    'post_id' => $post->ID,
                    'post_title' => $post->post_title,
                    'post_type' => $post->post_type,
                    'post_type_label' => \get_post_type_object($post->post_type)->labels->singular_name ?? $post->post_type,
                    'modified' => \strtotime($post->post_modified),
                    'edit_url' => \get_edit_post_link($post->ID, 'raw'),
                ];
            }

            \restore_current_blog();
        }

        // Sort by modified date descending
        \usort($all_activity, function ($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return \array_slice($all_activity, 0, $limit);
    }

    /**
     * Get activity for single site
     *
     * @param int $limit
     * @return array
     */
    private static function getSiteActivity(int $limit = 10): array
    {
        $posts = \get_posts([
            'post_type' => ['product', 'rf_service', 'rf_case_study', 'rf_resource', 'rf_invoice'],
            'posts_per_page' => $limit,
            'orderby' => 'modified',
            'order' => 'DESC',
        ]);

        $activity = [];
        foreach ($posts as $post) {
            $activity[] = [
                'site_id' => \get_current_blog_id(),
                'site_name' => \get_bloginfo('name'),
                'post_id' => $post->ID,
                'post_title' => $post->post_title,
                'post_type' => $post->post_type,
                'post_type_label' => \get_post_type_object($post->post_type)->labels->singular_name ?? $post->post_type,
                'modified' => \strtotime($post->post_modified),
                'edit_url' => \get_edit_post_link($post->ID, 'raw'),
            ];
        }

        return $activity;
    }

    /**
     * Clear network stats cache
     *
     * @return void
     */
    public static function clearCache(): void
    {
        \wp_cache_delete('network_stats', self::CACHE_GROUP);
    }

    /**
     * Get aggregated resources for Tech Center
     *
     * @param string $type Filter by resource type (faq, tech_doc, video, 3d_model)
     * @param int $site_id Filter by site (0 = all sites)
     * @return array
     */
    public static function getNetworkResources(string $type = '', int $site_id = 0): array
    {
        $resources = [];

        if (!\is_multisite() || $site_id > 0) {
            $blog_id = $site_id ?: \get_current_blog_id();
            if (\is_multisite()) {
                \switch_to_blog($blog_id);
            }

            $resources = self::fetchSiteResources($type);

            if (\is_multisite()) {
                \restore_current_blog();
            }

            return $resources;
        }

        // Aggregate from all sites
        $sites = \get_sites(['number' => 100]);
        foreach ($sites as $site) {
            \switch_to_blog($site->blog_id);

            $site_resources = self::fetchSiteResources($type);
            foreach ($site_resources as &$resource) {
                $resource['site_id'] = $site->blog_id;
                $resource['site_name'] = \get_bloginfo('name');
            }

            $resources = \array_merge($resources, $site_resources);
            \restore_current_blog();
        }

        return $resources;
    }

    /**
     * Fetch resources from current site
     *
     * @param string $type
     * @return array
     */
    private static function fetchSiteResources(string $type = ''): array
    {
        $args = [
            'post_type' => 'rf_resource',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        if ($type) {
            $args['tax_query'] = [[
                'taxonomy' => 'rf_resource_type',
                'field' => 'slug',
                'terms' => $type,
            ]];
        }

        $posts = \get_posts($args);
        $resources = [];

        foreach ($posts as $post) {
            $resource_types = \wp_get_post_terms($post->ID, 'rf_resource_type', ['fields' => 'slugs']);

            $resources[] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'excerpt' => \get_the_excerpt($post),
                'type' => $resource_types[0] ?? 'document',
                'url' => \get_permalink($post),
                'edit_url' => \get_edit_post_link($post->ID, 'raw'),
                'modified' => $post->post_modified,
            ];
        }

        return $resources;
    }
}
