<?php
/**
 * Single Product Template - iOS Style
 *
 * Premium product view with glassmorphism and modern animations
 *
 * @package RFPlugin\Templates\Frontend
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    $product_id = get_the_ID();
    $sku = get_post_meta($product_id, 'product_sku', true);
    $base_price = get_post_meta($product_id, 'product_base_price', true);
    $configurable = get_post_meta($product_id, 'product_configurable', true);
    $calc_method = get_post_meta($product_id, 'product_calculation_method', true);
    $min_order = get_post_meta($product_id, 'product_minimum_order', true);

    $categories = wp_get_post_terms($product_id, 'rf_product_category');
    $materials = wp_get_post_terms($product_id, 'rf_product_material');
?>

<div class="rf-single-product min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-blue-50/30 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 py-8 sm:py-12 md:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

        <!-- Breadcrumb -->
        <nav class="mb-6 md:mb-8 animate-fade-in">
            <div class="flex items-center gap-2 text-sm">
                <a href="<?php echo home_url(); ?>" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <?php _e('Home', 'rfplugin'); ?>
                </a>
                <span class="text-slate-400 dark:text-slate-600">/</span>
                <a href="<?php echo get_post_type_archive_link('rf_product'); ?>" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <?php _e('Products', 'rfplugin'); ?>
                </a>
                <span class="text-slate-400 dark:text-slate-600">/</span>
                <span class="text-slate-900 dark:text-white font-medium"><?php the_title(); ?></span>
            </div>
        </nav>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16">

            <!-- Left: Image & Gallery -->
            <div class="space-y-6 animate-slide-in-left">
                <!-- Main Image -->
                <div class="glass-card backdrop-blur-xl bg-white/80 dark:bg-slate-800/80 border border-white/20 dark:border-slate-700/50 rounded-3xl p-6 md:p-8 shadow-2xl overflow-hidden group">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900">
                            <?php the_post_thumbnail('large', [
                                'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700'
                            ]); ?>
                        </div>
                    <?php else : ?>
                        <div class="aspect-square rounded-2xl bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center">
                            <span class="dashicons dashicons-products text-6xl text-slate-400 dark:text-slate-600"></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Meta -->
                <div class="glass-card backdrop-blur-xl bg-white/80 dark:bg-slate-800/80 border border-white/20 dark:border-slate-700/50 rounded-3xl p-6 md:p-8 shadow-xl">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="dashicons dashicons-info-outline text-blue-600 dark:text-blue-400"></span>
                        <?php _e('Product Information', 'rfplugin'); ?>
                    </h3>
                    <div class="space-y-3">
                        <?php if ($sku) : ?>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200/50 dark:border-slate-700/50">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400"><?php _e('SKU', 'rfplugin'); ?></span>
                                <span class="text-sm font-bold text-slate-900 dark:text-white font-mono"><?php echo esc_html($sku); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($categories)) : ?>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200/50 dark:border-slate-700/50">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400"><?php _e('Category', 'rfplugin'); ?></span>
                                <span class="text-sm font-bold text-slate-900 dark:text-white"><?php echo esc_html($categories[0]->name); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($materials)) : ?>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200/50 dark:border-slate-700/50">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400"><?php _e('Material', 'rfplugin'); ?></span>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($materials as $material) : ?>
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-xs font-semibold">
                                            <?php echo esc_html($material->name); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($configurable) : ?>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400"><?php _e('Configurable', 'rfplugin'); ?></span>
                                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full text-xs font-bold flex items-center gap-1">
                                    <span class="dashicons dashicons-yes-alt text-sm"></span>
                                    <?php _e('Yes', 'rfplugin'); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Details & Actions -->
            <div class="space-y-6 md:space-y-8 animate-slide-in-right">

                <!-- Title & Description -->
                <div>
                    <?php
                    get_template_part('templates/partials/gradient-heading', null, [
                        'text' => get_the_title(),
                        'tag' => 'h1',
                        'size' => 'text-4xl sm:text-5xl lg:text-6xl',
                        'gradient' => 'from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400',
                        'class' => 'mb-4 md:mb-6'
                    ]);
                    ?>

                    <div class="prose prose-lg dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Price Card -->
                <div class="glass-card backdrop-blur-xl bg-gradient-to-br from-blue-500/10 to-purple-500/10 dark:from-blue-500/20 dark:to-purple-500/20 border border-blue-200/50 dark:border-blue-700/50 rounded-3xl p-6 md:p-8 shadow-2xl">
                    <div class="flex items-baseline gap-3 mb-4">
                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            <?php _e('Starting from', 'rfplugin'); ?>
                        </span>
                    </div>
                    <div class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent mb-2">
                        <?php echo $base_price ? '$' . number_format($base_price, 2) : __('Contact us', 'rfplugin'); ?>
                    </div>
                    <?php if ($calc_method) : ?>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            <?php
                            $methods = [
                                'fixed' => __('Fixed price', 'rfplugin'),
                                'per_sqm' => __('Per square meter', 'rfplugin'),
                                'per_linear_m' => __('Per linear meter', 'rfplugin'),
                            ];
                            echo $methods[$calc_method] ?? $calc_method;
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($min_order) : ?>
                        <div class="mt-4 pt-4 border-t border-slate-200/50 dark:border-slate-700/50">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                <span class="font-semibold"><?php _e('Minimum order:', 'rfplugin'); ?></span>
                                <?php echo esc_html($min_order); ?> <?php _e('units', 'rfplugin'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <?php
                    get_template_part('templates/partials/action-button', null, [
                        'text' => __('Request Quote', 'rfplugin'),
                        'url' => '#contact',
                        'icon' => 'dashicons-email-alt',
                        'color' => 'blue',
                        'size' => 'lg',
                        'class' => 'w-full sm:flex-1'
                    ]);

                    get_template_part('templates/partials/action-button', null, [
                        'text' => __('Contact Sales', 'rfplugin'),
                        'url' => get_permalink(get_page_by_path('contact')),
                        'icon' => 'dashicons-phone',
                        'color' => 'emerald',
                        'size' => 'lg',
                        'outline' => true,
                        'class' => 'w-full sm:flex-1'
                    ]);
                    ?>
                </div>

                <!-- Features -->
                <div class="glass-card backdrop-blur-xl bg-white/80 dark:bg-slate-800/80 border border-white/20 dark:border-slate-700/50 rounded-3xl p-6 md:p-8 shadow-xl">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="dashicons dashicons-yes-alt text-emerald-600 dark:text-emerald-400"></span>
                        <?php _e('Key Features', 'rfplugin'); ?>
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <span class="dashicons dashicons-shield text-blue-600 dark:text-blue-400 mt-1"></span>
                            <span class="text-slate-700 dark:text-slate-300"><?php _e('Certified quality standards', 'rfplugin'); ?></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="dashicons dashicons-admin-site-alt3 text-blue-600 dark:text-blue-400 mt-1"></span>
                            <span class="text-slate-700 dark:text-slate-300"><?php _e('Global shipping available', 'rfplugin'); ?></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="dashicons dashicons-admin-tools text-blue-600 dark:text-blue-400 mt-1"></span>
                            <span class="text-slate-700 dark:text-slate-300"><?php _e('Custom configuration options', 'rfplugin'); ?></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="dashicons dashicons-groups text-blue-600 dark:text-blue-400 mt-1"></span>
                            <span class="text-slate-700 dark:text-slate-300"><?php _e('Expert technical support', 'rfplugin'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in-left {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slide-in-right {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-in-left {
    animation: slide-in-left 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.animate-slide-in-right {
    animation: slide-in-right 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    animation-delay: 0.1s;
    opacity: 0;
    animation-fill-mode: forwards;
}
</style>

<?php
endwhile;
get_footer();
