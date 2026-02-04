<?php
/**
 * Resource Partial: Document / Sheet
 */

$file = get_field('field_resource_file');
$resource_id = get_the_ID();
?>
<div class="rf-resource-partial rf-document-view">
    <?php if ($file) : ?>
        <div class="rf-file-hero" style="display: flex; align-items: center; gap: 40px; background: rgba(255,255,255,0.05); padding: 40px; border-radius: 24px; margin-bottom: 40px; border: 1px solid rgba(255,255,255,0.1);">
            <div class="rf-file-icon" style="width: 100px; height: 100px; background: var(--rf-primary); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 40px rgba(37, 99, 235, 0.3);">
                <span class="dashicons dashicons-pdf" style="font-size: 50px; width: 50px; height: 50px; color: white;"></span>
            </div>
            
            <div class="rf-file-info">
                <h3 style="margin: 0 0 8px; font-size: 1.5rem; color: white;"><?php echo esc_html($file['filename']); ?></h3>
                <div style="display: flex; gap: 16px; margin-bottom: 24px; color: #94a3b8; font-size: 0.9rem;">
                    <span><?php echo size_format($file['filesize']); ?></span>
                    <span>•</span>
                    <span><?php echo strtoupper($file['subtype']); ?></span>
                    <span>•</span>
                    <span><?php echo get_the_date(); ?></span>
                </div>
                
                <?php $download_url = rest_url('rfplugin/v1/resources/' . $resource_id . '/download'); ?>
                <a href="<?php echo esc_url($download_url); ?>" class="rf-btn rf-btn-primary" download>
                    <span class="dashicons dashicons-download"></span> <?php _e('Secure Download', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="rf-doc-content rf-glass-card" style="padding: 40px;">
        <h2 class="rf-h3" style="margin-bottom: 24px;"><?php _e('Document Overview', 'rfplugin'); ?></h2>
        <div class="rf-content-body">
            <?php the_content(); ?>
        </div>
    </div>
</div>
