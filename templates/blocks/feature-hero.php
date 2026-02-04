<?php
/**
 * Feature Hero Block Template
 * 
 * Optimized with Tailwind CSS and GSAP animations.
 */

$id = 'feature-hero-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-feature-hero rf-relative rf-overflow-hidden rf-rounded-3xl rf-my-10 rf-bg-slate-900 rf-shadow-premium';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$title = get_field('title');
$subtitle = get_field('subtitle');
$image = get_field('image');
$cta = get_field('cta');

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <!-- Atmospheric Background -->
    <?php if ($image): ?>
        <div class="rf-hero-bg rf-absolute rf-inset-0 rf-z-0">
            <img src="<?php echo esc_url($image); ?>" alt="" class="rf-w-full rf-h-full rf-object-cover rf-opacity-30 rf-filter rf-grayscale-[20%] rf-contrast-[110%]">
            <div class="rf-absolute rf-inset-0 rf-bg-gradient-to-b rf-from-slate-900/40 rf-to-slate-900"></div>
        </div>
    <?php else: ?>
        <div class="rf-absolute rf-inset-0 rf-bg-[radial-gradient(circle_at_70%_30%,hsla(215,90%,60%,0.15),transparent_70%)]"></div>
    <?php endif; ?>

    <!-- Decorative Elements (Blobs) -->
    <div class="rf-blob rf-absolute rf-w-[500px] rf-h-[500px] -rf-top-[200px] -rf-left-[200px] rf-bg-primary-500/10 rf-rounded-full rf-blur-[100px] rf-pointer-events-none"></div>
    <div class="rf-blob rf-absolute rf-w-[300px] rf-h-[300px] -rf-bottom-[100px] -rf-right-[100px] rf-bg-accent-500/5 rf-rounded-full rf-blur-[80px] rf-pointer-events-none"></div>

    <div class="rf-container rf-relative rf-z-10 rf-max-w-4xl rf-mx-auto rf-text-center rf-py-20 rf-px-8 rf-typography">
        <span class="rf-badge rf-animate-up rf-inline-flex rf-items-center rf-px-4 rf-py-2 rf-mb-8 rf-text-xs rf-font-bold rf-uppercase rf-tracking-widest rf-text-white rf-bg-white/10 rf-border rf-border-white/20 rf-rounded-full">
            <?php _e('Featured Resource', 'rfplugin'); ?>
        </span>
        
        <?php if ($title): ?>
            <h1 class="rf-animate-up rf-text-white rf-mb-6 rf-text-4xl md:rf-text-6xl rf-font-black rf-tracking-tighter rf-bg-gradient-to-br rf-from-white rf-to-slate-400 rf-bg-clip-text rf-text-transparent rf-drop-shadow-2xl">
                <?php echo esc_html($title); ?>
            </h1>
        <?php endif; ?>

        <?php if ($subtitle): ?>
            <p class="rf-animate-up rf-text-slate-400 rf-text-lg md:rf-text-xl rf-mb-10 rf-max-w-2xl rf-mx-auto rf-leading-relaxed">
                <?php echo esc_html($subtitle); ?>
            </p>
        <?php endif; ?>

        <?php if ($cta): ?>
            <div class="rf-animate-up rf-flex rf-justify-center rf-gap-4">
                <a href="<?php echo esc_url($cta['url']); ?>" 
                   target="<?php echo esc_attr($cta['target'] ?: '_self'); ?>" 
                   class="rf-btn-premium rf-px-8 rf-py-4 rf-text-lg rf-shadow-2xl">
                    <?php echo esc_html($cta['title']); ?>
                    <svg class="rf-w-5 rf-h-5 rf-ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

