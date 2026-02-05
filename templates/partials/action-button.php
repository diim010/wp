<?php
/**
 * Action Button Component
 *
 * iOS-style action button with animations
 *
 * @package RFPlugin\Templates\Partials
 * @since 2.0.0
 *
 * @param array $args {
 *     @type string $text        Button text
 *     @type string $url         Button URL
 *     @type string $icon        Dashicon class (optional)
 *     @type string $color       Color variant (blue, emerald, purple, amber)
 *     @type string $size        Size (sm, md, lg)
 *     @type string $class       Additional CSS classes
 *     @type bool   $outline     Outline style (default: false)
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'text' => '',
    'url' => '#',
    'icon' => 'dashicons-arrow-right-alt',
    'color' => 'blue',
    'size' => 'md',
    'class' => '',
    'outline' => false,
];

$args = wp_parse_args($args ?? [], $defaults);

// Color variants
$colors = [
    'blue' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'emerald' => 'bg-emerald-600 hover:bg-emerald-700 text-white',
    'purple' => 'bg-purple-600 hover:bg-purple-700 text-white',
    'amber' => 'bg-amber-600 hover:bg-amber-700 text-white',
];

$outline_colors = [
    'blue' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white',
    'emerald' => 'border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-600 hover:text-white',
    'purple' => 'border-2 border-purple-600 text-purple-600 hover:bg-purple-600 hover:text-white',
    'amber' => 'border-2 border-amber-600 text-amber-600 hover:bg-amber-600 hover:text-white',
];

// Size variants
$sizes = [
    'sm' => 'px-4 py-2 text-sm gap-2',
    'md' => 'px-6 py-3 text-base gap-3',
    'lg' => 'px-8 py-4 text-lg gap-4',
];

$color_class = $args['outline'] ? $outline_colors[$args['color']] : $colors[$args['color']];
$size_class = $sizes[$args['size']];

$classes = "inline-flex items-center {$size_class} {$color_class}
            rounded-2xl shadow-lg hover:shadow-xl
            transition-all duration-300 hover:scale-105 active:scale-95
            font-semibold {$args['class']}";
?>

<a href="<?php echo esc_url($args['url']); ?>" class="<?php echo esc_attr(trim($classes)); ?>">
    <span><?php echo esc_html($args['text']); ?></span>
    <?php if ($args['icon']) : ?>
        <span class="dashicons <?php echo esc_attr($args['icon']); ?> text-xl"></span>
    <?php endif; ?>
</a>
