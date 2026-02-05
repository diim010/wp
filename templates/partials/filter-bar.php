<?php
/**
 * Filter Bar Component
 *
 * iOS-style filter chips for taxonomies
 *
 * @package RFPlugin\Templates\Partials
 * @since 2.0.0
 *
 * @param array $args {
 *     @type string $taxonomy    Taxonomy name
 *     @type string $current     Current term slug
 *     @type string $base_url    Base URL for filter links
 *     @type bool   $show_all    Show "All" option (default: true)
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'taxonomy' => '',
    'current' => '',
    'base_url' => '',
    'show_all' => true,
];

$args = wp_parse_args($args ?? [], $defaults);

if (empty($args['taxonomy'])) {
    return;
}

$terms = get_terms([
    'taxonomy' => $args['taxonomy'],
    'hide_empty' => true,
]);

if (empty($terms) || is_wp_error($terms)) {
    return;
}
?>

<div class="filter-bar flex flex-wrap gap-3 mb-6 md:mb-8">
    <?php if ($args['show_all']) : ?>
        <a href="<?php echo esc_url($args['base_url']); ?>"
           class="filter-chip px-4 py-2 backdrop-blur-md rounded-full
                  transition-all duration-300 font-medium text-sm
                  <?php echo empty($args['current'])
                      ? 'bg-blue-600 text-white shadow-lg'
                      : 'bg-white/60 dark:bg-slate-800/60 border border-slate-200/50 dark:border-slate-700/50 hover:bg-blue-500 hover:text-white'; ?>">
            <?php _e('All', 'rfplugin'); ?>
        </a>
    <?php endif; ?>

    <?php foreach ($terms as $term) :
        $is_active = $args['current'] === $term->slug;
        $term_url = add_query_arg('filter', $term->slug, $args['base_url']);
    ?>
        <a href="<?php echo esc_url($term_url); ?>"
           class="filter-chip px-4 py-2 backdrop-blur-md rounded-full
                  transition-all duration-300 font-medium text-sm
                  <?php echo $is_active
                      ? 'bg-blue-600 text-white shadow-lg'
                      : 'bg-white/60 dark:bg-slate-800/60 border border-slate-200/50 dark:border-slate-700/50 hover:bg-blue-500 hover:text-white'; ?>">
            <?php echo esc_html($term->name); ?>
            <span class="ml-1 text-xs opacity-75">(<?php echo $term->count; ?>)</span>
        </a>
    <?php endforeach; ?>
</div>
