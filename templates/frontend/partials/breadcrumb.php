<?php

/**
 * Breadcrumb Component
 *
 * Reusable breadcrumb navigation with Schema.org markup.
 *
 * @package RFPlugin
 * @since 2.0.0
 *
 * @param array $items (passed via $args) - Array of breadcrumb items
 *   Each item: ['label' => string, 'url' => string|null]
 */

defined('ABSPATH') || exit;

$items = $args['items'] ?? [];
if (empty($items)) return;
?>

<nav class="rf-corp-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'rfplugin'); ?>">
    <ol class="rf-corp-breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($items as $index => $item) :
            $is_last = ($index === count($items) - 1);
            $position = $index + 1;
        ?>
            <li class="rf-corp-breadcrumb__item"
                itemprop="itemListElement"
                itemscope
                itemtype="https://schema.org/ListItem"
                <?php echo $is_last ? 'aria-current="page"' : ''; ?>>

                <?php if (!$is_last && !empty($item['url'])) : ?>
                    <a href="<?php echo esc_url($item['url']); ?>"
                        class="rf-corp-breadcrumb__link"
                        itemprop="item">
                        <span itemprop="name"><?php echo esc_html($item['label']); ?></span>
                    </a>
                <?php else : ?>
                    <span class="rf-corp-breadcrumb__current" itemprop="name">
                        <?php echo esc_html($item['label']); ?>
                    </span>
                <?php endif; ?>

                <meta itemprop="position" content="<?php echo esc_attr($position); ?>">
            </li>

            <?php if (!$is_last) : ?>
                <li class="rf-corp-breadcrumb__separator" aria-hidden="true">/</li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>