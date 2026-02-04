<?php
/**
 * FAQ Accordion Block Template
 * 
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

$id = 'rf-faq-block-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-premium-faq-accordion';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$block_title = get_field('block_title');
$mode = get_field('selection_mode') ?: 'all';
$category = get_field('category');
$manual_faqs = get_field('manual_faqs');
$style = get_field('style') ?: 'modern';

// Build query
$args = [
    'post_type' => 'rf_faq',
    'post_status' => 'publish',
    'posts_per_page' => -1,
];

if ($mode === 'manual' && $manual_faqs) {
    $args['post__in'] = $manual_faqs;
    $args['orderby'] = 'post__in';
} elseif ($mode === 'category' && $category) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'rf_faq_category',
            'field' => 'term_id',
            'terms' => $category,
        ],
    ];
}

$query = new \WP_Query($args);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> rf-premium-ui">
    <div class="rf-container" style="max-width: 900px;">
        <?php if ($block_title): ?>
            <header class="rf-faq-header" style="margin-bottom: 60px; text-align: center;">
                <span class="rf-badge" style="margin-bottom: 24px;"><?php _e('Support Documentation', 'rfplugin'); ?></span>
                <h2 class="rf-title" style="font-size: clamp(2rem, 5vw, 3rem); text-align: center; color: #0f172a; margin-bottom: 12px;"><?php echo esc_html($block_title); ?></h2>
            </header>
        <?php endif; ?>

    <div class="rf-accordion-container" style="display: flex; flex-direction: column; gap: 24px;">
        <?php 
        $i = 0;
        if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); 
            $faq_id = get_the_ID();
            $attached_docs = get_field('field_faq-attach-doc', $faq_id);
            $answer = get_field('field_faq_answer', $faq_id);
            $delay = ($i % 8) * 0.1;
        ?>
            <div class="rf-faq-item" style="animation: rfFadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: <?php echo $delay; ?>s;">
                <button class="rf-faq-trigger" aria-expanded="false" style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 24px 32px; background: white; border: 1px solid #f1f5f9; border-radius: 20px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: var(--rf-shadow-premium);">
                    <span class="rf-faq-q-text" style="font-size: 1.25rem; font-weight: 800; color: #0f172a; text-align: left; flex-grow: 1;"><?php the_title(); ?></span>
                    <span class="rf-faq-toggle-icon" style="color: var(--rf-primary); transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                        <span class="dashicons dashicons-arrow-down-alt2" style="font-size: 24px; width: 24px; height: 24px;"></span>
                    </span>
                </button>
                <div class="rf-faq-content" style="max-height: 0; opacity: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); padding: 0 32px;">
                    <div class="rf-faq-answer-inner" style="padding: 24px 0; font-size: 1.15rem; line-height: 1.8; color: #334155;">
                        <?php echo wp_kses_post($answer); ?>
                        
                        <?php if ($attached_docs): ?>
                            <div class="rf-faq-attachments-premium" style="margin-top: 32px; padding-top: 32px; border-top: 2px solid #f8fafc;">
                                <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--rf-text-muted); font-weight: 800; letter-spacing: 0.1em; margin-bottom: 20px;"><?php _e('Linked Specifications', 'rfplugin'); ?></h4>
                                <div class="rf-attachment-grid" style="display: flex; flex-wrap: wrap; gap: 12px;">
                                    <?php foreach ($attached_docs as $doc): 
                                        $ext = strtoupper(pathinfo(get_field('field_tech_doc_file', $doc->ID)['url'] ?? 'PDF', PATHINFO_EXTENSION) ?: 'PDF');
                                    ?>
                                        <a href="<?php echo esc_url(rest_url('rfplugin/v1/techdocs/' . $doc->ID . '/download')); ?>" class="rf-attachment-tag" style="background: #f8fafc; color: #0f172a; padding: 12px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; border: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; transition: all 0.2s;">
                                            <span class="dashicons dashicons-media-document" style="color: var(--rf-primary);"></span>
                                            <?php echo esc_html($doc->post_title); ?>
                                            <span style="font-size: 0.75rem; color: var(--rf-text-muted);">(<?php echo $ext; ?>)</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
            $i++;
            endwhile; wp_reset_postdata(); else: 
        ?>
            <div style="text-align: center; padding: 60px; color: var(--rf-text-muted);">
                <p><?php _e('No frequently asked questions available for this selection.', 'rfplugin'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function() {
    const handleFaq = () => {
        const triggers = document.querySelectorAll('.rf-faq-trigger');
        triggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const item = this.closest('.rf-faq-item');
                const content = item.querySelector('.rf-faq-content');
                const isOpen = item.classList.contains('is-open');
                const icon = this.querySelector('.rf-faq-toggle-icon');

                if (isOpen) {
                    content.style.maxHeight = '0';
                    content.style.opacity = '0';
                    item.classList.remove('is-open');
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    content.style.opacity = '1';
                    item.classList.add('is-open');
                    icon.style.transform = 'rotate(180deg)';
                }
            });
        });
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleFaq);
    } else {
        handleFaq();
    }
})();
</script>
