<?php
/**
 * Tech Doc List Block Template
 * 
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

$id = 'rf-tech-block-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-block-techdoc-premium';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$section_title = get_field('section_title');
$mode = get_field('selection_mode') ?: 'latest';
$count = get_field('item_count') ?: 6;
$category = get_field('category');
$manual_docs = get_field('manual_docs');

// Build query
$args = [
    'post_type' => 'rf_resource',
    'post_status' => 'publish',
    'posts_per_page' => $count,
    'meta_query' => [
        [
            'key' => 'resource_mode',
            'value' => ['document', 'sheet', '3d', 'video'],
            'compare' => 'IN',
        ]
    ],
];

if ($mode === 'manual' && $manual_docs) {
    $args['post__in'] = $manual_docs;
    $args['orderby'] = 'post__in';
} elseif ($mode === 'category' && $category) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'rf_resource_category',
            'field' => 'term_id',
            'terms' => $category,
        ],
    ];
}

$query = new \WP_Query($args);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> rf-premium-ui">
    <div class="rf-container" style="position: relative;">
        <!-- Decorative Elements -->
        <div class="rf-blob rf-blob-1" style="width: 400px; height: 400px; top: -100px; right: -100px; background: hsla(220, 90%, 50%, 0.05);"></div>

        <div class="rf-block-header" style="margin-bottom: 60px; display: flex; justify-content: space-between; align-items: flex-end; position: relative; z-index: 10;">
            <div>
                <span class="rf-badge" style="margin-bottom: 24px;"><?php _e('Engineering Resources', 'rfplugin'); ?></span>
                <h2 class="rf-title" style="margin: 0; font-size: clamp(2rem, 5vw, 3.5rem); text-align: left; background: none; -webkit-text-fill-color: initial; color: #0f172a;"><?php echo esc_html($section_title ?: __('Technical Documentation', 'rfplugin')); ?></h2>
            </div>
            <?php if (!$is_preview): ?>
                <a href="<?php echo get_post_type_archive_link('rf_resource'); ?>" class="rf-btn" style="padding: 12px 24px; border-radius: 12px; font-size: 0.9rem; gap: 10px;">
                    <?php _e('Explore Library', 'rfplugin'); ?> <span class="dashicons dashicons-arrow-right-alt2" style="font-size: 18px; width: 18px; height: 18px;"></span>
                </a>
            <?php endif; ?>
        </div>

    <div id="rf-doc-results" class="rf-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 32px; position: relative; z-index: 10;">
        <?php 
        $i = 0;
        if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); 
            $doc_id = get_the_ID();
            
            // Security Check
            if (!\RFPlugin\Security\Permissions::canViewPost($doc_id)) continue;

            $file_mode = get_field('field_resource_mode', $doc_id) ?: 'document'; 
            $file_data = get_field('field_resource_file', $doc_id);
            $download_url = rest_url('rfplugin/v1/resources/' . $doc_id . '/download');
            $delay = ($i % 8) * 0.1;
        ?>
            <article class="rf-card" style="animation: rfFadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: <?php echo $delay; ?>s;">
                <div class="rf-card-icon">
                    <span class="dashicons dashicons-<?php 
                        echo $file_mode === 'video' ? 'video-alt3' : ($file_mode === '3d' ? 'visibility' : 'media-document'); 
                    ?>" aria-hidden="true"></span>
                </div>
                
                <h3 class="rf-card-title"><?php the_title(); ?></h3>
                <div class="rf-card-excerpt">
                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                </div>
                
                <div class="rf-card-footer">
                    <div class="rf-card-meta">
                        <span style="background: var(--rf-primary-light); color: var(--rf-primary); padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;">
                            <?php echo strtoupper(esc_html($file_mode)); ?>
                        </span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <?php if (in_array($file_mode, ['document', 'sheet'])): ?>
                            <a href="<?php echo esc_url($download_url); ?>" class="rf-btn" style="padding: 10px 16px;" download>
                                <span class="dashicons dashicons-download"></span>
                            </a>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="rf-btn" style="padding: 10px 16px;">
                            <span class="dashicons dashicons-arrow-right-alt2"></span>
                        </a>
                    </div>
                </div>
            </article>
        <?php 
            $i++;
            endwhile; wp_reset_postdata(); else: 
        ?>
            <div class="rf-empty-state" style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <span class="dashicons dashicons-category" style="font-size: 32px; width: 32px; height: 32px; color: #94a3b8;"></span>
                </div>
                <h4 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 12px;"><?php _e('No resources matched', 'rfplugin'); ?></h4>
                <p style="font-size: 1.1rem; color: #64748b; margin: 0;"><?php _e('Please select different criteria in the block settings.', 'rfplugin'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
