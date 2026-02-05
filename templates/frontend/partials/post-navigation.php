<?php

/**
 * Post Navigation Component
 *
 * Previous/Next navigation for single post templates.
 *
 * @package RFPlugin
 * @since 2.0.0
 *
 * @param string $post_type (passed via $args) - For aria-label context
 */

defined('ABSPATH') || exit;

$post_type_label = $args['post_type_label'] ?? __('Post', 'rfplugin');
$prev_post = get_previous_post();
$next_post = get_next_post();

if (!$prev_post && !$next_post) return;
?>

<nav class="rf-corp-post-nav"
    aria-label="<?php echo esc_attr(sprintf(__('%s navigation', 'rfplugin'), $post_type_label)); ?>">

    <div class="rf-corp-post-nav__grid">
        <div class="rf-corp-post-nav__prev">
            <?php if ($prev_post) : ?>
                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>"
                    class="rf-corp-card rf-corp-card--glass rf-corp-post-nav__link">
                    <span class="rf-corp-post-nav__label">
                        <span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span>
                        <?php esc_html_e('Previous', 'rfplugin'); ?>
                    </span>
                    <span class="rf-corp-post-nav__title">
                        <?php echo esc_html($prev_post->post_title); ?>
                    </span>
                </a>
            <?php endif; ?>
        </div>

        <div class="rf-corp-post-nav__next">
            <?php if ($next_post) : ?>
                <a href="<?php echo esc_url(get_permalink($next_post)); ?>"
                    class="rf-corp-card rf-corp-card--glass rf-corp-post-nav__link rf-corp-post-nav__link--next">
                    <span class="rf-corp-post-nav__label">
                        <?php esc_html_e('Next', 'rfplugin'); ?>
                        <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                    </span>
                    <span class="rf-corp-post-nav__title">
                        <?php echo esc_html($next_post->post_title); ?>
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>