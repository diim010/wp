<?php
/**
 * Glass Card Component
 *
 * Reusable iOS-style glassmorphic card component
 *
 * @package RFPlugin\Templates\Partials
 * @since 2.0.0
 *
 * @param array $args {
 *     @type string $class       Additional CSS classes
 *     @type string $link        Optional link URL
 *     @type bool   $hover       Enable hover effects (default: true)
 *     @type string $padding     Padding class (default: 'p-6 md:p-8')
 *     @type string $content     Card content (HTML)
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'class' => '',
    'link' => '',
    'hover' => true,
    'padding' => 'p-6 md:p-8',
    'content' => '',
];

$args = wp_parse_args($args ?? [], $defaults);

$base_classes = 'glass-card backdrop-blur-xl bg-white/80 dark:bg-slate-800/80
                 border border-white/20 dark:border-slate-700/50 rounded-3xl
                 shadow-xl transition-all duration-500';

$hover_classes = $args['hover'] ? 'hover:shadow-2xl hover:scale-105 hover:-translate-y-1 active:scale-95' : '';

$all_classes = trim("{$base_classes} {$hover_classes} {$args['padding']} {$args['class']}");

if ($args['link']) : ?>
    <a href="<?php echo esc_url($args['link']); ?>" class="<?php echo esc_attr($all_classes); ?> block group">
        <?php echo $args['content']; ?>
    </a>
<?php else : ?>
    <div class="<?php echo esc_attr($all_classes); ?>">
        <?php echo $args['content']; ?>
    </div>
<?php endif;
