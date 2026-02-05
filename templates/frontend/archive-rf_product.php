<?php
/**
 * Archive Products Template - iOS Style
 *
 * Premium products archive with glassmorphism and filters
 *
 * @package RFPlugin\Templates\Frontend
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$current_category = get_query_var('filter') ?: '';
$current_material = get_query_var('material') ?: '';
?>

<div class="rf-archive-products min-h-screen bg-gradient-to-br from-slate-50 via-slate-50 to-blue-50/30 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 py-8 sm:py-12 md:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

        <!-- Header -->
        <div class="mb-8 md:mb-12 text-center animate-fade-in">
            <?php
            get_template_part('templates/partials/gradient-heading', null, [
                'text' => __('Our Products', 'rfplugin'),
                'tag' => 'h1',
                'size' => 'text-5xl sm:text-6xl md:text-7xl',
                'gradient' => 'from-blue-600 via-purple-600 to-blue-600 dark:from-blue-400 dark:via-purple-400 dark:to-blue-400',
                'class' => 'mb-4 md:mb-6'
            ]);
            ?>
            <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">
                <?php _e('Explore our range of premium architectural solutions', 'rfplugin'); ?>
            </p>
        </div>

        <!-- Filters -->
        <div class="mb-8 md:mb-12 space-y-6 animate-slide-in">
            <!-- Category Filter -->
            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
                    <?php _e('Categories', 'rfplugin'); ?>
                </h3>
                <?php
                get_template_part('templates/partials/filter-bar', null, [
                    'taxonomy' => 'rf_product_category',
                    'current' => $current_category,
                    'base_url' => get_post_type_archive_link('rf_product'),
                    'show_all' => true
                ]);
                ?>
            </div>

            <!-- Material Filter -->
            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
                    <?php _e('Materials', 'rfplugin'); ?>
                </h3>
                <?php
                get_template_part('templates/partials/filter-bar', null, [
                    'taxonomy' => 'rf_product_material',
                    'current' => $current_material,
                    'base_url' => get_post_type_archive_link('rf_product'),
                    'show_all' => true
                ]);
                ?>
            </div>
        </div>

        <!-- Products Grid -->
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mb-12">
                <?php
                $delay = 0;
                while (have_posts()) : the_post();
                    $product_id = get_the_ID();
                    $sku = get_post_meta($product_id, 'product_sku', true);
                    $price = get_post_meta($product_id, 'product_base_price', true);
                    $configurable = get_post_meta($product_id, 'product_configurable', true);
                    $categories = wp_get_post_terms($product_id, 'rf_product_category');
                    $materials = wp_get_post_terms($product_id, 'rf_product_material');
                ?>
                    <article class="product-card group animate-fade-in-up" style="animation-delay: <?php echo $delay * 50; ?>ms;">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="glass-card backdrop-blur-xl bg-white/80 dark:bg-slate-800/80 border border-white/20 dark:border-slate-700/50 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2">

                                <!-- Image -->
                                <div class="aspect-square bg-slate-100 dark:bg-slate-900 overflow-hidden relative">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium_large', [
                                            'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700'
                                        ]); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800">
                                            <span class="dashicons dashicons-products text-6xl text-slate-400 dark:text-slate-600"></span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Badges -->
                                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                                        <?php if ($configurable) : ?>
                                            <span class="px-3 py-1 bg-emerald-600/90 backdrop-blur-md text-white rounded-full text-xs font-bold flex items-center gap-1">
                                                <span class="dashicons dashicons-admin-tools text-sm"></span>
                                                <?php _e('Configurable', 'rfplugin'); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($categories)) : ?>
                                            <span class="px-3 py-1 bg-blue-600/90 backdrop-blur-md text-white rounded-full text-xs font-bold">
                                                <?php echo esc_html($categories[0]->name); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        <?php the_title(); ?>
                                    </h2>

                                    <?php if ($sku) : ?>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mb-3">
                                            <?php _e('SKU:', 'rfplugin'); ?> <?php echo esc_html($sku); ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="text-sm text-slate-600 dark:text-slate-300 mb-4 line-clamp-2">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </div>

                                    <!-- Materials -->
                                    <?php if (!empty($materials)) : ?>
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <?php foreach (array_slice($materials, 0, 3) as $material) : ?>
                                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-lg text-xs">
                                                    <?php echo esc_html($material->name); ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if (count($materials) > 3) : ?>
                                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-lg text-xs">
                                                    +<?php echo count($materials) - 3; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Price & CTA -->
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-200/50 dark:border-slate-700/50">
                                        <div>
                                            <?php if ($price) : ?>
                                                <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                                                    $<?php echo number_format($price, 2); ?>
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                                    <?php _e('Starting from', 'rfplugin'); ?>
                                                </div>
                                            <?php else : ?>
                                                <div class="text-sm font-semibold text-slate-600 dark:text-slate-400">
                                                    <?php _e('Contact for pricing', 'rfplugin'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold text-sm group-hover:gap-3 transition-all">
                                            <?php _e('View', 'rfplugin'); ?>
                                            <span class="dashicons dashicons-arrow-right-alt text-lg"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php
                    $delay++;
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center animate-fade-in">
                <?php
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span> ' . __('Previous', 'rfplugin'),
                    'next_text' => __('Next', 'rfplugin') . ' <span class="dashicons dashicons-arrow-right-alt2"></span>',
                    'class' => 'flex gap-2'
                ]);
                ?>
            </div>

        <?php else : ?>
            <!-- No Products -->
            <div class="text-center py-16 animate-fade-in">
                <div class="glass-card backdrop-blur-xl bg-white/80 dark:bg-slate-800/80 border border-white/20 dark:border-slate-700/50 rounded-3xl p-12 max-w-2xl mx-auto">
                    <span class="dashicons dashicons-search text-6xl text-slate-400 dark:text-slate-600 mb-4 block"></span>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">
                        <?php _e('No products found', 'rfplugin'); ?>
                    </h2>
                    <p class="text-slate-600 dark:text-slate-300 mb-6">
                        <?php _e('Try adjusting your filters or check back later for new products.', 'rfplugin'); ?>
                    </p>
                    <a href="<?php echo get_post_type_archive_link('rf_product'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 font-semibold">
                        <?php _e('Clear Filters', 'rfplugin'); ?>
                        <span class="dashicons dashicons-update"></span>
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-in {
    animation: slide-in 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
}

/* Pagination styling */
.pagination {
    @apply flex items-center gap-2;
}

.pagination .page-numbers {
    @apply px-4 py-2 bg-white/60 dark:bg-slate-800/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 rounded-xl text-slate-700 dark:text-slate-300 font-medium transition-all duration-300 hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:scale-105;
}

.pagination .page-numbers.current {
    @apply bg-blue-600 text-white border-blue-600 shadow-lg;
}

.pagination .page-numbers.dots {
    @apply bg-transparent border-transparent hover:bg-transparent hover:text-slate-700 dark:hover:text-slate-300 hover:scale-100;
}
</style>

<?php
get_footer();
