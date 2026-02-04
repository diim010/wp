<?php
/**
 * Resource Partial: Video
 */

$video_url = get_field('field_resource_video_url');
?>
<div class="rf-resource-partial rf-video-view">
    <?php if ($video_url) : ?>
        <div class="rf-video-wrapper" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 20px; box-shadow: var(--rf-shadow-premium); background: #000; margin-bottom: 40px;">
            <?php echo wp_oembed_get($video_url, ['width' => 1200]); ?>
        </div>
    <?php endif; ?>

    <div class="rf-video-content rf-glass-card" style="padding: 40px;">
        <h2 class="rf-h3" style="margin-bottom: 24px;"><?php _e('Video Description', 'rfplugin'); ?></h2>
        <div class="rf-content-body">
            <?php the_content(); ?>
        </div>
    </div>
</div>
