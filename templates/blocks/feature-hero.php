<?php
/**
 * Feature Hero Block Template
 */

$id = 'feature-hero-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$className = 'rf-feature-hero rf-premium-ui';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$title = get_field('title');
$subtitle = get_field('subtitle');
$image = get_field('image');
$cta = get_field('cta');

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="position: relative; padding: 120px 0; background: #0f172a; border-radius: 32px; overflow: hidden; margin: 40px 0; box-shadow: var(--rf-shadow-premium);">
    <!-- Atmospheric Background -->
    <?php if ($image): ?>
        <div class="rf-hero-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;">
            <img src="<?php echo esc_url($image); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.3; filter: grayscale(20%) contrast(110%);">
            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15, 23, 42, 0.4) 0%, #0f172a 100%);"></div>
        </div>
    <?php else: ?>
        <div style="position: absolute; inset: 0; background: radial-gradient(circle at 70% 30%, hsla(215, 90%, 60%, 0.15), transparent 70%);"></div>
    <?php endif; ?>

    <!-- Decorative Elements -->
    <div class="rf-blob rf-blob-1" style="width: 500px; height: 500px; top: -200px; left: -200px; background: hsla(215, 90%, 50%, 0.1);"></div>
    <div class="rf-blob rf-blob-2" style="width: 300px; height: 300px; bottom: -100px; right: -100px; background: hsla(150, 70%, 50%, 0.05);"></div>

    <div class="rf-container" style="position: relative; z-index: 10; max-width: 900px; margin: 0 auto; text-align: center; padding: 0 40px;">
        <span class="rf-badge" style="margin-bottom: 32px; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);"><?php _e('Featured Resource', 'rfplugin'); ?></span>
        
        <?php if ($title): ?>
            <h2 class="rf-title" style="color: white; margin-bottom: 24px; font-size: clamp(2.5rem, 6vw, 4.5rem); text-align: center; background: linear-gradient(135deg, #fff 0%, #94a3b8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.2));"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($subtitle): ?>
            <p style="color: #94a3b8; font-size: 1.25rem; margin-bottom: 48px; line-height: 1.6; max-width: 700px; margin-left: auto; margin-right: auto;"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <?php if ($cta): ?>
            <div style="display: flex; justify-content: center; gap: 20px;">
                <a href="<?php echo esc_url($cta['url']); ?>" target="<?php echo esc_attr($cta['target'] ?: '_self'); ?>" class="rf-btn" style="padding: 18px 40px; font-size: 1.1rem; gap: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                    <?php echo esc_html($cta['title']); ?>
                    <span class="dashicons dashicons-arrow-right-alt2" style="font-size: 20px; width: 20px; height: 20px;"></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
