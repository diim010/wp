<?php
/**
 * Ground Control Dashboard Template
 *
 * Modern, responsive dashboard for super admins with network statistics.
 * Uses Tailwind CSS for styling.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

// Variables available: $stats, $activity
?>

<div class="rf-superadmin-theme" id="rf-gc-wrapper">
    <div class="rf-admin-wrap min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-blue-50/30 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 p-4 sm:p-6 md:p-8 lg:p-10 transition-colors duration-300">

        <!-- Hero Section -->
        <div class="rf-cc-animate-in mb-6 md:mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
                <div class="flex-1">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 dark:text-white mb-2 md:mb-3 flex items-center gap-3 md:gap-4">
                        <span class="text-4xl sm:text-5xl md:text-6xl">🚀</span>
                        <?php _e('Ground Control', 'rfplugin'); ?>
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl text-slate-600 dark:text-slate-300 font-medium">
                        <?php _e('Mission Control Dashboard', 'rfplugin'); ?>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="rf-theme-toggle" class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2.5 sm:py-3 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-xl transition-all duration-300 group">
                        <span class="dashicons dashicons-admin-appearance text-blue-600 dark:text-blue-400 text-xl sm:text-2xl group-hover:rotate-180 transition-transform duration-500"></span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 hidden sm:inline text-sm sm:text-base"><?php _e('Theme', 'rfplugin'); ?></span>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=' . \RFPlugin\Admin\ControlCenter::MENU_SLUG); ?>" class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2.5 sm:py-3 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-xl transition-all duration-300 group">
                        <span class="dashicons dashicons-update text-emerald-600 dark:text-emerald-400 text-xl sm:text-2xl group-hover:rotate-180 transition-transform duration-500"></span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 hidden sm:inline text-sm sm:text-base"><?php _e('Refresh', 'rfplugin'); ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-6 mb-8">

            <!-- Products Card -->
            <a href="<?php echo admin_url('edit.php?post_type=rf_product'); ?>" class="rf-stat-card group block">
                <div class="rf-glass-card h-full p-6 rounded-3xl backdrop-blur-xl bg-gradient-to-br from-white/80 to-white/40 dark:from-slate-800/80 dark:to-slate-800/40 border border-white/20 dark:border-slate-700/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-1 active:scale-95">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-blue-500/50">
                            <span class="dashicons dashicons-products text-white text-2xl"></span>
                        </div>
                        <?php if (is_multisite()): ?>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-100/80 dark:bg-slate-700/80 backdrop-blur-sm px-3 py-1.5 rounded-full"><?php echo $stats['active_sites']; ?> sites</span>
                        <?php endif; ?>
                    </div>
                    <div class="rf-cc-stat-value text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-500 dark:from-blue-400 dark:to-blue-300 bg-clip-text text-transparent mb-2"><?php echo number_format($stats['total_products']); ?></div>
                    <div class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?php _e('Products', 'rfplugin'); ?></div>
                    <div class="mt-3 pt-3 border-t border-slate-200/50 dark:border-slate-700/50">
                        <div class="flex items-center text-xs text-blue-600 dark:text-blue-400 font-medium">
                            <span class="group-hover:translate-x-1 transition-transform duration-300"><?php _e('Manage', 'rfplugin'); ?></span>
                            <span class="dashicons dashicons-arrow-right-alt text-sm ml-1 group-hover:translate-x-1 transition-transform duration-300"></span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Services Card -->
            <a href="<?php echo admin_url('edit.php?post_type=rf_service'); ?>" class="rf-stat-card group block">
                <div class="rf-glass-card h-full p-6 rounded-3xl backdrop-blur-xl bg-gradient-to-br from-white/80 to-white/40 dark:from-slate-800/80 dark:to-slate-800/40 border border-white/20 dark:border-slate-700/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-1 active:scale-95">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-emerald-500/50">
                            <span class="dashicons dashicons-admin-tools text-white text-2xl"></span>
                        </div>
                    </div>
                    <div class="rf-cc-stat-value text-4xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-500 dark:from-emerald-400 dark:to-emerald-300 bg-clip-text text-transparent mb-2"><?php echo number_format($stats['total_services']); ?></div>
                    <div class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?php _e('Services', 'rfplugin'); ?></div>
                    <div class="mt-3 pt-3 border-t border-slate-200/50 dark:border-slate-700/50">
                        <div class="flex items-center text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                            <span class="group-hover:translate-x-1 transition-transform duration-300"><?php _e('Manage', 'rfplugin'); ?></span>
                            <span class="dashicons dashicons-arrow-right-alt text-sm ml-1 group-hover:translate-x-1 transition-transform duration-300"></span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Case Studies Card -->
            <a href="<?php echo admin_url('edit.php?post_type=rf_case_study'); ?>" class="rf-stat-card group block">
                <div class="rf-glass-card h-full p-6 rounded-3xl backdrop-blur-xl bg-gradient-to-br from-white/80 to-white/40 dark:from-slate-800/80 dark:to-slate-800/40 border border-white/20 dark:border-slate-700/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-1 active:scale-95">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-purple-500/50">
                            <span class="dashicons dashicons-portfolio text-white text-2xl"></span>
                        </div>
                    </div>
                    <div class="rf-cc-stat-value text-4xl font-bold bg-gradient-to-r from-purple-600 to-purple-500 dark:from-purple-400 dark:to-purple-300 bg-clip-text text-transparent mb-2"><?php echo number_format($stats['total_cases']); ?></div>
                    <div class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?php _e('Case Studies', 'rfplugin'); ?></div>
                    <div class="mt-3 pt-3 border-t border-slate-200/50 dark:border-slate-700/50">
                        <div class="flex items-center text-xs text-purple-600 dark:text-purple-400 font-medium">
                            <span class="group-hover:translate-x-1 transition-transform duration-300"><?php _e('Manage', 'rfplugin'); ?></span>
                            <span class="dashicons dashicons-arrow-right-alt text-sm ml-1 group-hover:translate-x-1 transition-transform duration-300"></span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Resources Card -->
            <a href="<?php echo admin_url('edit.php?post_type=rf_resource'); ?>" class="rf-stat-card group block">
                <div class="rf-glass-card h-full p-6 rounded-3xl backdrop-blur-xl bg-gradient-to-br from-white/80 to-white/40 dark:from-slate-800/80 dark:to-slate-800/40 border border-white/20 dark:border-slate-700/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-1 active:scale-95">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-amber-500/50">
                            <span class="dashicons dashicons-category text-white text-2xl"></span>
                        </div>
                    </div>
                    <div class="rf-cc-stat-value text-4xl font-bold bg-gradient-to-r from-amber-600 to-amber-500 dark:from-amber-400 dark:to-amber-300 bg-clip-text text-transparent mb-2"><?php echo number_format($stats['total_resources']); ?></div>
                    <div class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?php _e('Resources', 'rfplugin'); ?></div>
                    <div class="mt-3 pt-3 border-t border-slate-200/50 dark:border-slate-700/50">
                        <div class="flex items-center text-xs text-amber-600 dark:text-amber-400 font-medium">
                            <span class="group-hover:translate-x-1 transition-transform duration-300"><?php _e('Manage', 'rfplugin'); ?></span>
                            <span class="dashicons dashicons-arrow-right-alt text-sm ml-1 group-hover:translate-x-1 transition-transform duration-300"></span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Invoices Card -->
            <a href="<?php echo admin_url('edit.php?post_type=rf_invoice'); ?>" class="rf-stat-card group block">
                <div class="rf-glass-card h-full p-6 rounded-3xl backdrop-blur-xl bg-gradient-to-br from-white/80 to-white/40 dark:from-slate-800/80 dark:to-slate-800/40 border border-white/20 dark:border-slate-700/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-1 active:scale-95">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-cyan-500/50">
                            <span class="dashicons dashicons-media-spreadsheet text-white text-2xl"></span>
                        </div>
                    </div>
                    <div class="rf-cc-stat-value text-4xl font-bold bg-gradient-to-r from-cyan-600 to-cyan-500 dark:from-cyan-400 dark:to-cyan-300 bg-clip-text text-transparent mb-2"><?php echo number_format($stats['total_invoices']); ?></div>
                    <div class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?php _e('Invoices', 'rfplugin'); ?></div>
                    <div class="mt-3 pt-3 border-t border-slate-200/50 dark:border-slate-700/50">
                        <div class="flex items-center text-xs text-cyan-600 dark:text-cyan-400 font-medium">
                            <span class="group-hover:translate-x-1 transition-transform duration-300"><?php _e('Manage', 'rfplugin'); ?></span>
                            <span class="dashicons dashicons-arrow-right-alt text-sm ml-1 group-hover:translate-x-1 transition-transform duration-300"></span>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <!-- Quick Actions & Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Quick Actions -->
            <div class="rf-cc-animate-in lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 h-full">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                        <span class="dashicons dashicons-admin-generic text-blue-600 dark:text-blue-400 dark:text-blue-400"></span>
                        <?php _e('Quick Actions', 'rfplugin'); ?>
                    </h2>

                    <div class="space-y-3">
                        <a href="<?php echo admin_url('admin.php?page=royalfoam'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-700 hover:bg-blue-50 dark:hover:bg-slate-600 hover:border-blue-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <span class="dashicons dashicons-admin-settings text-blue-600 dark:text-blue-400"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Settings', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('Configure plugin', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-all"></span>
                        </a>

                        <a href="<?php echo admin_url('admin.php?page=rf-gc-docs'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                <span class="dashicons dashicons-book text-emerald-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Documentation', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('View guides', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all"></span>
                        </a>

                        <a href="<?php echo admin_url('edit.php?post_type=rf_resource'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-amber-50 hover:border-amber-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                                <span class="dashicons dashicons-category text-amber-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Resources', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('Manage content', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-amber-600 group-hover:translate-x-1 transition-all"></span>
                        </a>

                        <a href="<?php echo admin_url('edit.php?post_type=rf_service'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                <span class="dashicons dashicons-hammer text-emerald-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Manage Services', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('Add/edit services', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all"></span>
                        </a>

                        <a href="<?php echo admin_url('edit.php?post_type=rf_case_study'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-purple-50 hover:border-purple-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                <span class="dashicons dashicons-portfolio text-purple-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Manage Cases', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('Add/edit case studies', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all"></span>
                        </a>

                        <a href="<?php echo admin_url('edit.php?post_type=rf_invoice'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-cyan-50 hover:border-cyan-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                                <span class="dashicons dashicons-media-spreadsheet text-cyan-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Manage Invoices', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('View/create invoices', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-cyan-600 group-hover:translate-x-1 transition-all"></span>
                        </a>

                        <?php if (is_multisite()): ?>
                        <a href="<?php echo network_admin_url('admin.php?page=royalfoam-network'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-purple-50 hover:border-purple-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                <span class="dashicons dashicons-networking text-purple-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Network Settings', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('Multisite config', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all"></span>
                        </a>
                        <?php endif; ?>

                        <a href="<?php echo admin_url('admin.php?page=royalfoam'); ?>" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-red-50 hover:border-red-200 border border-transparent dark:border-transparent transition-all duration-200 group">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                <span class="dashicons dashicons-shield text-red-600"></span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900"><?php _e('Security Stats', 'rfplugin'); ?></div>
                                <div class="text-xs text-slate-500"><?php _e('View activity', 'rfplugin'); ?></div>
                            </div>
                            <span class="dashicons dashicons-arrow-right-alt2 text-slate-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all"></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="rf-cc-animate-in lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 h-full">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="dashicons dashicons-clock text-blue-600 dark:text-blue-400"></span>
                        <?php _e('Recent Activity', 'rfplugin'); ?>
                    </h2>

                    <?php if (!empty($activity)): ?>
                        <div class="space-y-3 max-h-[600px] overflow-y-auto">
                            <?php foreach ($activity as $item): ?>
                                <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-700 hover:bg-blue-50 dark:hover:bg-slate-600 border border-transparent dark:border-transparent hover:border-blue-200 transition-all duration-200 group">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="dashicons dashicons-edit text-white text-sm"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <a href="<?php echo esc_url($item['edit_url']); ?>" class="font-semibold text-slate-900 hover:text-blue-600 dark:text-blue-400 transition-colors truncate">
                                                <?php echo esc_html($item['post_title']); ?>
                                            </a>
                                            <span class="text-xs text-slate-500 whitespace-nowrap">
                                                <?php echo human_time_diff($item['modified'], current_time('timestamp')); ?> <?php _e('ago', 'rfplugin'); ?>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-600">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white dark:bg-slate-800 rounded-md border border-slate-200">
                                                <?php echo esc_html($item['post_type_label']); ?>
                                            </span>
                                            <?php if (is_multisite() && !empty($item['site_name'])): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded-md border border-blue-200">
                                                    <span class="dashicons dashicons-admin-site-alt3 text-xs"></span>
                                                    <?php echo esc_html($item['site_name']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <span class="dashicons dashicons-info text-slate-300 text-6xl mb-4"></span>
                            <p class="text-slate-500"><?php _e('No recent activity found', 'rfplugin'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <?php if (is_multisite() && !empty($stats['sites'])): ?>
        <!-- Sites Overview -->
        <div class="rf-cc-animate-in mt-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200">
                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="dashicons dashicons-networking text-blue-600 dark:text-blue-400"></span>
                    <?php _e('Network Sites', 'rfplugin'); ?>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($stats['sites'] as $site): ?>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700 hover:bg-blue-50 dark:hover:bg-slate-600 border border-slate-200 hover:border-blue-300 transition-all duration-200 group">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <a href="<?php echo esc_url($site['admin_url']); ?>" class="font-semibold text-slate-900 hover:text-blue-600 dark:text-blue-400 transition-colors truncate block">
                                        <?php echo esc_html($site['name']); ?>
                                    </a>
                                    <div class="text-xs text-slate-500 truncate"><?php echo esc_html($site['domain'] . $site['path']); ?></div>
                                </div>
                                <?php if ($site['plugin_active']): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Active
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div class="text-center p-2 bg-white dark:bg-slate-800 rounded-lg">
                                    <div class="font-bold text-slate-900"><?php echo $site['products']; ?></div>
                                    <div class="text-slate-500">Products</div>
                                </div>
                                <div class="text-center p-2 bg-white dark:bg-slate-800 rounded-lg">
                                    <div class="font-bold text-slate-900"><?php echo $site['services']; ?></div>
                                    <div class="text-slate-500">Services</div>
                                </div>
                                <div class="text-center p-2 bg-white dark:bg-slate-800 rounded-lg">
                                    <div class="font-bold text-slate-900"><?php echo $site['resources']; ?></div>
                                    <div class="text-slate-500">Resources</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
