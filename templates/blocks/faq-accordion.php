<?php
/**
 * FAQ Accordion Block Template
 * 
 * Optimized with Tailwind CSS and GSAP animations.
 */

$id = 'rf-faq-block-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-premium-faq-accordion rf-relative rf-py-16 md:rf-py-24';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$block_title = get_field('block_title');
$mode = get_field('selection_mode') ?: 'all';
$category = get_field('category');
$manual_faqs = get_field('manual_faqs');

// Build query
$args = [
    'post_type' => 'rf_resource',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => 'resource_mode',
            'value' => 'faq',
            'compare' => '=',
        ]
    ],
];

if ($mode === 'manual' && $manual_faqs) {
    $args['post__in'] = $manual_faqs;
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

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="rf-container rf-mx-auto rf-px-8 rf-max-w-4xl rf-typography">
        <?php if ($block_title): ?>
            <header class="rf-faq-header rf-text-center rf-mb-16">
                <span class="rf-badge rf-animate-up rf-inline-flex rf-px-4 rf-py-1 rf-bg-accent-50 rf-text-accent-700 rf-rounded-full rf-text-xs rf-font-bold rf-uppercase rf-tracking-wider rf-mb-4">
                    <?php _e('Support Documentation', 'rfplugin'); ?>
                </span>
                <h2 class="rf-animate-up rf-text-3xl md:rf-text-5xl rf-font-black rf-text-slate-900 rf-tracking-tight rf-m-0">
                    <?php echo esc_html($block_title); ?>
                </h2>
            </header>
        <?php endif; ?>

        <div class="rf-accordion-container rf-flex rf-flex-col rf-gap-6">
            <?php 
            if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); 
                $faq_id = get_the_ID();
                $related_items = get_field('field_resource_related_items', $faq_id);
                $answer = get_field('field_resource_answer', $faq_id);
            ?>
                <div class="rf-faq-item rf-animate-up">
                    <button class="rf-faq-trigger rf-group rf-w-full rf-flex rf-items-center rf-justify-between rf-px-8 rf-py-6 rf-bg-white rf-border rf-border-slate-100 rf-rounded-2xl rf-cursor-pointer rf-transition-all rf-duration-300 hover:rf-border-primary-200 hover:rf-shadow-premium rf-shadow-sm" 
                            aria-expanded="false">
                        <span class="rf-text-xl rf-font-bold rf-text-slate-900 rf-text-left rf-flex-grow rf-mr-4 group-hover:rf-text-primary-600">
                            <?php the_title(); ?>
                        </span>
                        <span class="rf-faq-toggle-icon rf-text-primary-600 rf-transition-transform rf-duration-500">
                            <svg class="rf-w-6 rf-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>
                    <div class="rf-faq-content rf-max-h-0 rf-opacity-0 rf-overflow-hidden rf-px-8">
                        <div class="rf-faq-answer-inner rf-py-8 rf-text-lg rf-leading-relaxed rf-text-slate-600">
                            <?php echo wp_kses_post($answer); ?>
                            
                            <?php if ($related_items): ?>
                                <div class="rf-faq-attachments rf-mt-8 rf-pt-8 rf-border-t rf-border-slate-100">
                                    <h4 class="rf-text-xs rf-font-black rf-uppercase rf-tracking-widest rf-text-slate-400 rf-mb-4">
                                        <?php _e('Related Solutions', 'rfplugin'); ?>
                                    </h4>
                                    <div class="rf-flex rf-flex-wrap rf-gap-3">
                                        <?php foreach ($related_items as $item_id): ?>
                                            <a href="<?php echo get_permalink($item_id); ?>" 
                                               class="rf-inline-flex rf-items-center rf-px-5 rf-py-3 rf-bg-slate-50 rf-text-slate-900 rf-rounded-xl rf-text-sm rf-font-bold rf-border rf-border-slate-100 hover:rf-bg-primary-50 hover:rf-text-primary-700 hover:rf-border-primary-100 rf-transition-all">
                                                <svg class="rf-w-4 rf-h-4 rf-mr-2 rf-text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                <?php echo get_the_title($item_id); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile; wp_reset_postdata(); else: 
            ?>
                <div class="rf-empty-state rf-text-center rf-py-12 rf-bg-slate-50 rf-rounded-3xl">
                    <p class="rf-text-slate-500"><?php _e('No frequently asked questions matched your criteria.', 'rfplugin'); ?></p>
                </div>
            <?php endif; ?>
        </div>
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
                const isOpen = item.classList.contains('rf-is-open');
                const icon = this.querySelector('.rf-faq-toggle-icon');

                if (isOpen) {
                    gsap.to(content, { maxHeight: 0, opacity: 0, duration: 0.4, ease: "power2.inOut" });
                    gsap.to(icon, { rotation: 0, duration: 0.4 });
                    item.classList.remove('rf-is-open');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    gsap.to(content, { maxHeight: content.scrollHeight + 100, opacity: 1, duration: 0.5, ease: "power2.out" });
                    gsap.to(icon, { rotation: 180, duration: 0.5 });
                    item.classList.add('rf-is-open');
                    this.setAttribute('aria-expanded', 'true');
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

