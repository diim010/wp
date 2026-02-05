<?php
/**
 * Gradient Heading Component
 *
 * iOS-style gradient text heading
 *
 * @package RFPlugin\Templates\Partials
 * @since 2.0.0
 *
 * @param array $args {
 *     @type string $text        Heading text
 *     @type string $tag         HTML tag (default: 'h1')
 *     @type string $size        Size class (default: 'text-4xl md:text-5xl')
 *     @type string $gradient    Gradient colors (default: 'from-blue-600 to-blue-500')
 *     @type string $class       Additional CSS classes
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'text' => '',
    'tag' => 'h1',
    'size' => 'text-4xl md:text-5xl',
    'gradient' => 'from-blue-600 to-blue-500 dark:from-blue-400 dark:to-blue-300',
    'class' => '',
];

$args = wp_parse_args($args ?? [], $defaults);

$classes = "font-bold bg-gradient-to-r {$args['gradient']} bg-clip-text text-transparent {$args['size']} {$args['class']}";

printf(
    '<%1$s class="%2$s">%3$s</%1$s>',
    tag_escape($args['tag']),
    esc_attr(trim($classes)),
    wp_kses_post($args['text'])
);
