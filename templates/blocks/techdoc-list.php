<?php
/**
 * Tech Doc List Block Template
 * 
 * Optimized with Tailwind CSS and GSAP animations.
 */

$id = 'rf-tech-block-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-block-techdoc-premium rf-relative rf-py-16 md:rf-py-24';
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

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="rf-container rf-mx-auto rf-px-8 rf-relative rf-typography">
        <!-- Decorative Elements -->
        <div class="rf-blob rf-absolute rf-w-[400px] rf-h-[400px] -rf-top-24 -rf-right-24 rf-bg-primary-500/5 rf-rounded-full rf-blur-[100px] rf-pointer-events-none"></div>

        <header class="rf-block-header rf-flex rf-flex-col md:rf-flex-row rf-justify-between rf-items-start md:rf-items-end rf-mb-12 rf-relative rf-z-10">
            <div class="rf-max-w-2xl">
                <span class="rf-badge rf-animate-up rf-inline-flex rf-px-4 rf-py-1 rf-bg-primary-50 rf-text-primary-700 rf-rounded-full rf-text-xs rf-font-bold rf-uppercase rf-tracking-wider rf-mb-4">
                    <?php _e('Engineering Resources', 'rfplugin'); ?>
                </span>
                <h2 class="rf-animate-up rf-text-3xl md:rf-text-5xl rf-font-black rf-text-slate-900 rf-tracking-tight rf-m-0">
                    <?php echo esc_html($section_title ?: __('Technical Documentation', 'rfplugin')); ?>
                </h2>
            </div>
            <?php if (!$is_preview): ?>
                <a href="<?php echo get_post_type_archive_link('rf_resource'); ?>" class="rf-animate-up rf-btn-premium rf-mt-6 md:rf-mt-0">
                    <?php _e('Explore Library', 'rfplugin'); ?> 
                    <svg class="rf-w-4 rf-h-4 rf-ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            <?php endif; ?>
        </header>

        <div id="rf-doc-results" class="rf-grid rf-grid-cols-1 md:rf-grid-cols-2 lg:rf-grid-cols-3 rf-gap-8 rf-relative rf-z-10">
            <?php 
            if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); 
                $doc_id = get_the_ID();
                
                // Security Check
                if (!\RFPlugin\Security\Permissions::canViewPost($doc_id)) continue;

                $file_mode = get_field('field_resource_mode', $doc_id) ?: 'document'; 
                $download_url = rest_url('rfplugin/v1/resources/' . $doc_id . '/download');
            ?>
                <article class="rf-glass-card rf-animate-up rf-flex rf-flex-col rf-h-full">
                    <div class="rf-mb-6">
                        <div class="rf-w-14 rf-h-14 rf-bg-primary-100 rf-text-primary-600 rf-rounded-2xl rf-flex rf-items-center rf-justify-center rf-shadow-inner">
                            <?php if ($file_mode === 'video'): ?>
                                <svg class="rf-w-7 rf-h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <?php elseif ($file_mode === '3d'): ?>
                                <svg class="rf-w-7 rf-h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <?php else: ?>
                                <svg class="rf-w-7 rf-h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h3 class="rf-text-xl rf-font-bold rf-text-slate-900 rf-mb-3 rf-leading-tight"><?php the_title(); ?></h3>
                    
                    <div class="rf-text-slate-500 rf-text-base rf-mb-8 rf-flex-grow">
                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                    </div>
                    
                    <footer class="rf-flex rf-items-center rf-justify-between rf-pt-8 rf-border-t rf-border-slate-100">
                        <span class="rf-px-3 rf-py-1 rf-bg-slate-100 rf-text-slate-600 rf-text-[10px] rf-font-black rf-uppercase rf-tracking-widest rf-rounded-md">
                            <?php echo esc_html($file_mode); ?>
                        </span>
                        
                        <div class="rf-flex rf-gap-3">
                            <?php if (in_array($file_mode, ['document', 'sheet'])): ?>
                                <a href="<?php echo esc_url($download_url); ?>" class="rf-w-11 rf-h-11 rf-bg-slate-900 rf-text-white rf-rounded-xl rf-flex rf-items-center rf-justify-center rf-transition-transform hover:rf-scale-110" download>
                                    <svg class="rf-w-5 rf-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="rf-w-11 rf-h-11 rf-bg-primary-600 rf-text-white rf-rounded-xl rf-flex rf-items-center rf-justify-center rf-transition-transform hover:rf-scale-110">
                                <svg class="rf-w-5 rf-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </footer>
                </article>
            <?php 
                endwhile; wp_reset_postdata(); else: 
            ?>
                <div class="rf-empty-state rf-col-span-full rf-text-center rf-py-20 rf-bg-slate-50 rf-rounded-3xl">
                    <div class="rf-w-20 rf-h-20 rf-bg-white rf-rounded-full rf-shadow-sm rf-flex rf-items-center rf-justify-center rf-mx-auto rf-mb-6">
                        <svg class="rf-w-10 rf-h-10 rf-text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    <h4 class="rf-text-2xl rf-font-black rf-text-slate-900 rf-mb-2"><?php _e('No resources found', 'rfplugin'); ?></h4>
                    <p class="rf-text-slate-500"><?php _e('Please adjust your filter settings in the block editor.', 'rfplugin'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
