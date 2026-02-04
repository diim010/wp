<?php
/**
 * Resource Partial: Video (Production Ready)
 * 
 * Handles video resource type with responsive embed,
 * Schema.org VideoObject markup, and chapter markers.
 * 
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$resource_id = get_the_ID();
$video_url = get_field('resource_video_url', $resource_id);
$video_duration = get_field('resource_video_duration', $resource_id);
$video_chapters = get_field('resource_video_chapters', $resource_id);

// Parse video URL to get provider and ID
$video_provider = '';
$video_id = '';
if ($video_url) {
    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
        $video_provider = 'YouTube';
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches);
        $video_id = $matches[1] ?? '';
    } elseif (strpos($video_url, 'vimeo.com') !== false) {
        $video_provider = 'Vimeo';
        preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $video_url, $matches);
        $video_id = $matches[1] ?? '';
    }
}
?>

<div class="rf-resource-partial rf-video-view"
     itemscope 
     itemtype="https://schema.org/VideoObject">
    
    <!-- Schema.org metadata -->
    <meta itemprop="name" content="<?php echo esc_attr(get_the_title()); ?>" />
    <meta itemprop="description" content="<?php echo esc_attr(get_the_excerpt()); ?>" />
    <meta itemprop="uploadDate" content="<?php echo esc_attr(get_the_date('c')); ?>" />
    <?php if ($video_duration) : ?>
        <meta itemprop="duration" content="<?php echo esc_attr($video_duration); ?>" />
    <?php endif; ?>
    <?php if (has_post_thumbnail()) : ?>
        <meta itemprop="thumbnailUrl" content="<?php echo esc_url(get_the_post_thumbnail_url($resource_id, 'large')); ?>" />
    <?php endif; ?>
    
    <?php if ($video_url) : ?>
        <!-- Video Player Section -->
        <section class="rf-video-player" aria-label="<?php esc_attr_e('Video player', 'rfplugin'); ?>">
            <div class="rf-video-wrapper" 
                 style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); background: #0f172a; margin-bottom: 40px;">
                
                <!-- Video Embed -->
                <div class="rf-video-embed" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                    <?php 
                    $embed = wp_oembed_get($video_url, [
                        'width' => 1280,
                        'height' => 720
                    ]);
                    
                    if ($embed) {
                        // Add title attribute for accessibility
                        $embed = str_replace('<iframe', '<iframe title="' . esc_attr(get_the_title()) . '"', $embed);
                        // Add loading lazy
                        $embed = str_replace('<iframe', '<iframe loading="lazy"', $embed);
                        echo $embed; // Already sanitized by wp_oembed_get
                    } else {
                        // Fallback link
                        ?>
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; gap: 20px; padding: 40px;">
                            <span class="dashicons dashicons-video-alt3" style="font-size: 64px; width: 64px; height: 64px; color: #64748b;" aria-hidden="true"></span>
                            <p style="color: #94a3b8; text-align: center; margin: 0;">
                                <?php esc_html_e('Video cannot be embedded. Click below to watch.', 'rfplugin'); ?>
                            </p>
                            <a href="<?php echo esc_url($video_url); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="rf-btn rf-btn-primary">
                                <?php esc_html_e('Watch on', 'rfplugin'); ?> <?php echo esc_html($video_provider ?: __('External Site', 'rfplugin')); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            
            <!-- Video Info Bar -->
            <div class="rf-video-info" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 40px; padding: 20px 24px; background: rgba(255,255,255,0.03); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; gap: 24px; flex-wrap: wrap; color: #94a3b8; font-size: 0.9rem;">
                    <?php if ($video_provider) : ?>
                        <span style="display: flex; align-items: center; gap: 6px;">
                            <span class="dashicons dashicons-video-alt3" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                            <?php echo esc_html($video_provider); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($video_duration) : ?>
                        <span style="display: flex; align-items: center; gap: 6px;">
                            <span class="dashicons dashicons-clock" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                            <?php echo esc_html($video_duration); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Share Actions -->
                <div class="rf-video-actions" style="display: flex; gap: 8px;">
                    <button type="button" 
                            class="rf-btn rf-btn-icon rf-copy-link"
                            data-url="<?php echo esc_url(get_permalink()); ?>"
                            aria-label="<?php esc_attr_e('Copy link to clipboard', 'rfplugin'); ?>"
                            style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <span class="dashicons dashicons-admin-links" style="font-size: 18px; width: 18px; height: 18px;" aria-hidden="true"></span>
                    </button>
                    <a href="<?php echo esc_url($video_url); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="rf-btn rf-btn-icon"
                       aria-label="<?php esc_attr_e('Open in new tab', 'rfplugin'); ?>"
                       style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <span class="dashicons dashicons-external" style="font-size: 18px; width: 18px; height: 18px;" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </section>
    <?php else : ?>
        <!-- No Video Available -->
        <div class="rf-notice rf-notice-warning" 
             role="alert"
             style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); padding: 24px; border-radius: 12px; margin-bottom: 40px; display: flex; align-items: center; gap: 16px;">
            <span class="dashicons dashicons-warning" style="font-size: 24px; width: 24px; height: 24px; color: #f59e0b;" aria-hidden="true"></span>
            <p style="margin: 0; color: #fcd34d;">
                <?php esc_html_e('No video URL has been provided for this resource.', 'rfplugin'); ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Video Description -->
    <section class="rf-video-content" aria-labelledby="video-desc-heading">
        <h2 id="video-desc-heading" class="rf-h3" style="margin-bottom: 24px; font-size: 1.5rem;">
            <?php esc_html_e('Video Description', 'rfplugin'); ?>
        </h2>
        
        <div class="rf-content-body rf-prose" 
             itemprop="description"
             style="color: #cbd5e1; line-height: 1.8; font-size: 1.05rem;">
            <?php 
            $content = get_the_content();
            if ($content) {
                echo wp_kses_post(apply_filters('the_content', $content));
            } else {
                echo '<p style="color: #64748b;">' . esc_html__('No additional description available for this video.', 'rfplugin') . '</p>';
            }
            ?>
        </div>
    </section>

    <!-- Video Chapters (if available) -->
    <?php if ($video_chapters && is_array($video_chapters)) : ?>
        <section class="rf-video-chapters" 
                 aria-labelledby="chapters-heading"
                 style="margin-top: 48px;">
            <h3 id="chapters-heading" class="rf-h4" style="margin-bottom: 20px; font-size: 1.25rem; display: flex; align-items: center; gap: 10px;">
                <span class="dashicons dashicons-list-view" style="font-size: 20px; width: 20px; height: 20px; color: var(--rf-primary);" aria-hidden="true"></span>
                <?php esc_html_e('Chapters', 'rfplugin'); ?>
            </h3>
            
            <ol class="rf-chapter-list" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                <?php foreach ($video_chapters as $index => $chapter) : 
                    $timestamp = isset($chapter['timestamp']) ? $chapter['timestamp'] : '';
                    $title = isset($chapter['title']) ? $chapter['title'] : '';
                ?>
                    <li class="rf-chapter-item rf-glass-card" 
                        style="display: flex; align-items: center; gap: 16px; padding: 16px 20px; border-radius: 10px; transition: all 0.2s ease;">
                        <span class="rf-chapter-num" style="font-size: 0.8rem; color: #64748b; font-weight: 600; min-width: 24px;">
                            <?php echo esc_html($index + 1); ?>
                        </span>
                        <?php if ($timestamp) : ?>
                            <span class="rf-chapter-time" style="font-family: monospace; font-size: 0.85rem; color: var(--rf-primary); background: rgba(37, 99, 235, 0.1); padding: 4px 8px; border-radius: 4px;">
                                <?php echo esc_html($timestamp); ?>
                            </span>
                        <?php endif; ?>
                        <span class="rf-chapter-title" style="color: white; font-weight: 500;">
                            <?php echo esc_html($title); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </section>
    <?php endif; ?>
</div>

<style>
/* Video wrapper iframe responsive */
.rf-video-embed iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

/* Button Icon Styles */
.rf-btn-icon {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: #94a3b8;
    cursor: pointer;
    transition: all 0.2s ease;
}
.rf-btn-icon:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}
.rf-btn-icon:focus {
    outline: 2px solid var(--rf-primary);
    outline-offset: 2px;
}

/* Chapter Item Hover */
.rf-chapter-item:hover {
    background: rgba(255,255,255,0.08);
}

/* Copy Link Success State */
.rf-copy-link.copied {
    background: hsl(142, 76%, 36%);
    border-color: hsl(142, 76%, 36%);
    color: white;
}

/* Prose Styles */
.rf-prose p {
    margin-bottom: 1.25em;
}
.rf-prose a {
    color: var(--rf-primary);
    text-decoration: underline;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.querySelector('.rf-copy-link');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const url = this.dataset.url;
            navigator.clipboard.writeText(url).then(() => {
                this.classList.add('copied');
                setTimeout(() => {
                    this.classList.remove('copied');
                }, 2000);
            });
        });
    }
});
</script>
