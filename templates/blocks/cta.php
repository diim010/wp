<?php
/**
 * CTA Block Template
 * 
 * Optimized with Tailwind CSS and GSAP animations.
 */

$id = 'rf-cta-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-cta-block rf-relative rf-overflow-hidden rf-rounded-3xl rf-my-12 rf-shadow-premium';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$title = get_field('title');
$subtitle = get_field('subtitle');
$cta = get_field('cta_button');
$bg_color = get_field('bg_color') ?: 'primary';
$bg_image = get_field('bg_image');

$bg_classes = [
    'primary' => 'rf-bg-slate-900 rf-text-white',
    'accent'  => 'rf-bg-primary-600 rf-text-white',
    'white'   => 'rf-bg-white rf-text-slate-900 rf-border rf-border-slate-100',
];

$title_classes = [
    'primary' => 'rf-text-white',
    'accent'  => 'rf-text-white',
    'white'   => 'rf-text-slate-900',
];

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> <?php echo $bg_classes[$bg_color]; ?>">
    <?php if ($bg_image): ?>
        <div class="rf-absolute rf-inset-0 rf-z-0">
            <img src="<?php echo esc_url($bg_image); ?>" alt="" class="rf-w-full rf-h-full rf-object-cover rf-opacity-20 rf-filter rf-brightness-50">
            <div class="rf-absolute rf-inset-0 rf-bg-gradient-to-r rf-from-black/60 rf-to-transparent"></div>
        </div>
    <?php endif; ?>

    <!-- Decorative Blobs -->
    <div class="rf-blob rf-absolute rf-w-96 rf-h-96 -rf-bottom-24 -rf-right-24 rf-bg-white/5 rf-rounded-full rf-blur-3xl rf-pointer-events-none"></div>

    <div class="rf-container rf-relative rf-z-10 rf-mx-auto rf-px-12 rf-py-20 rf-typography">
        <div class="rf-max-w-2xl">
            <?php if ($title): ?>
                <h2 class="rf-animate-up rf-text-3xl md:rf-text-5xl rf-font-black rf-tracking-tight rf-mb-6 <?php echo $title_classes[$bg_color]; ?>">
                    <?php echo esc_html($title); ?>
                </h2>
            <?php endif; ?>

            <?php if ($subtitle): ?>
                <p class="rf-animate-up rf-text-lg md:rf-text-xl rf-opacity-80 rf-mb-10 rf-leading-relaxed">
                    <?php echo esc_html($subtitle); ?>
                </p>
            <?php endif; ?>

            <?php if ($cta): ?>
                <div class="rf-animate-up">
                    <a href="<?php echo esc_url($cta['url']); ?>" 
                       target="<?php echo esc_attr($cta['target'] ?: '_self'); ?>" 
                       class="rf-btn-premium <?php echo $bg_color === 'white' ? '' : 'rf-bg-white rf-text-primary-600 hover:rf-bg-slate-50'; ?> rf-px-8 rf-py-4 rf-text-lg rf-shadow-xl">
                        <?php echo esc_html($cta['title']); ?>
                        <svg class="rf-w-5 rf-h-5 rf-ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
