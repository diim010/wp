<?php
/**
 * Technical Center Dashboard Template
 *
 * System information and diagnostics for super admins.
 *
 * @package RFPlugin\Templates\Admin
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Variables available: $system_info
?>

<div class="rf-superadmin-theme" id="rf-tech-wrapper">
    <div class="rf-admin-wrap min-h-screen bg-slate-50 dark:bg-slate-900 p-6 md:p-8 transition-colors duration-200">

        <!-- Hero Section -->
        <div class="rf-cc-animate-in mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-slate-100 mb-2 flex items-center gap-3">
                        <span class="dashicons dashicons-admin-tools text-blue-600 dark:text-blue-400 text-4xl"></span>
                        <?php _e('Technical Center', 'rfplugin'); ?>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 text-lg">
                        <?php _e('System diagnostics and technical information', 'rfplugin'); ?>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="rf-tech-theme-toggle" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 hover:shadow-lg transition-all duration-200">
                        <span class="dashicons dashicons-admin-appearance text-blue-600 dark:text-blue-400"></span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 hidden sm:inline"><?php _e('Theme', 'rfplugin'); ?></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- System Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            <!-- PHP Info Card -->
            <div class="rf-cc-animate-in bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="dashicons dashicons-editor-code text-white text-xl"></span>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-2 py-1 rounded-full">PHP</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2"><?php _e('PHP Version', 'rfplugin'); ?></h3>
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-2"><?php echo esc_html($system_info['php_version']); ?></div>
                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between">
                        <span><?php _e('Max Execution:', 'rfplugin'); ?></span>
                        <span class="font-medium"><?php echo esc_html($system_info['max_execution_time']); ?>s</span>
                    </div>
                    <div class="flex justify-between">
                        <span><?php _e('Upload Max:', 'rfplugin'); ?></span>
                        <span class="font-medium"><?php echo esc_html($system_info['upload_max_filesize']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span><?php _e('Post Max:', 'rfplugin'); ?></span>
                        <span class="font-medium"><?php echo esc_html($system_info['post_max_size']); ?></span>
                    </div>
                </div>
            </div>

            <!-- MySQL Info Card -->
            <div class="rf-cc-animate-in bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <span class="dashicons dashicons-database text-white text-xl"></span>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2 py-1 rounded-full">MySQL</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2"><?php _e('Database', 'rfplugin'); ?></h3>
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-2"><?php echo esc_html($system_info['mysql_version']); ?></div>
                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between">
                        <span><?php _e('Cache:', 'rfplugin'); ?></span>
                        <span class="font-medium">
                            <?php if ($system_info['cache_enabled']): ?>
                                <span class="text-green-600 dark:text-green-400">✓ <?php _e('Enabled', 'rfplugin'); ?></span>
                            <?php else: ?>
                                <span class="text-amber-600 dark:text-amber-400">○ <?php _e('Disabled', 'rfplugin'); ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- WordPress Info Card -->
            <div class="rf-cc-animate-in bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <span class="dashicons dashicons-wordpress text-white text-xl"></span>
                    </div>
                    <span class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-900/30 px-2 py-1 rounded-full">WP</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2"><?php _e('WordPress', 'rfplugin'); ?></h3>
                <div class="text-2xl font-bold text-cyan-600 dark:text-cyan-400 mb-2"><?php echo esc_html($system_info['wp_version']); ?></div>
                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between">
                        <span><?php _e('Memory Limit:', 'rfplugin'); ?></span>
                        <span class="font-medium"><?php echo esc_html($system_info['wp_memory_limit']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span><?php _e('Debug Mode:', 'rfplugin'); ?></span>
                        <span class="font-medium">
                            <?php if ($system_info['debug_mode']): ?>
                                <span class="text-amber-600 dark:text-amber-400">ON</span>
                            <?php else: ?>
                                <span class="text-green-600 dark:text-green-400">OFF</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span><?php _e('Multisite:', 'rfplugin'); ?></span>
                        <span class="font-medium"><?php echo $system_info['multisite'] ? __('Yes', 'rfplugin') : __('No', 'rfplugin'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Server Info Card -->
            <div class="rf-cc-animate-in bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="dashicons dashicons-cloud text-white text-xl"></span>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2 py-1 rounded-full">Server</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2"><?php _e('Server', 'rfplugin'); ?></h3>
                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400 mb-2 truncate" title="<?php echo esc_attr($system_info['server_software']); ?>">
                    <?php echo esc_html($system_info['server_software']); ?>
                </div>
                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between">
                        <span><?php _e('Max Input Vars:', 'rfplugin'); ?></span>
                        <span class="font-medium"><?php echo esc_html(number_format($system_info['max_input_vars'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Plugins & Theme Card -->
            <div class="rf-cc-animate-in bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <span class="dashicons dashicons-admin-plugins text-white text-xl"></span>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded-full">Plugins</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2"><?php _e('Active Plugins', 'rfplugin'); ?></h3>
                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mb-2"><?php echo esc_html($system_info['active_plugins']); ?></div>
                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between">
                        <span><?php _e('Theme:', 'rfplugin'); ?></span>
                        <span class="font-medium truncate ml-2" title="<?php echo esc_attr($system_info['active_theme']); ?>">
                            <?php echo esc_html($system_info['active_theme']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- PHP Extensions Card -->
            <div class="rf-cc-animate-in bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <span class="dashicons dashicons-admin-generic text-white text-xl"></span>
                    </div>
                    <span class="text-xs font-semibold text-pink-600 dark:text-pink-400 bg-pink-100 dark:bg-pink-900/30 px-2 py-1 rounded-full">Extensions</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2"><?php _e('PHP Extensions', 'rfplugin'); ?></h3>
                <div class="text-2xl font-bold text-pink-600 dark:text-pink-400 mb-2"><?php echo count($system_info['php_extensions']); ?></div>
                <div class="max-h-20 overflow-y-auto text-xs text-slate-600 dark:text-slate-400">
                    <?php echo esc_html(implode(', ', array_slice($system_info['php_extensions'], 0, 20))); ?>
                    <?php if (count($system_info['php_extensions']) > 20): ?>
                        <span class="text-slate-400">...</span>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Back to Dashboard -->
        <div class="text-center">
            <a href="<?php echo admin_url('admin.php?page=rf-control-center'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200">
                <span class="dashicons dashicons-arrow-left-alt"></span>
                <?php _e('Back to Ground Control', 'rfplugin'); ?>
            </a>
        </div>

    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Theme toggle functionality
    const wrapper = document.getElementById('rf-tech-wrapper');
    const toggleBtn = document.getElementById('rf-tech-theme-toggle');
    const savedTheme = localStorage.getItem('rf-gc-theme') || 'light';

    // Apply saved theme
    if (savedTheme === 'dark') {
        wrapper.classList.add('dark');
    }

    // Toggle theme
    toggleBtn.addEventListener('click', function() {
        wrapper.classList.toggle('dark');
        const newTheme = wrapper.classList.contains('dark') ? 'dark' : 'light';
        localStorage.setItem('rf-gc-theme', newTheme);
    });

    // Animate cards on load
    $('.rf-cc-animate-in').each(function(index) {
        $(this).css({
            'animation-delay': (index * 0.05) + 's'
        });
    });
});
</script>

<style>
.rf-cc-animate-in {
    animation: slideInUp 0.4s ease-out forwards;
    opacity: 0;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
