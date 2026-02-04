<?php
/**
 * Resource Partial: 3D Model
 */

$embed_code = get_field('field_resource_3d_embed');
?>
<div class="rf-resource-partial rf-3d-view">
    <?php if ($embed_code) : ?>
        <div class="rf-3d-wrapper" style="border-radius: 24px; overflow: hidden; background: #0f172a; min-height: 600px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; box-shadow: var(--rf-shadow-premium); border: 1px solid rgba(255,255,255,0.1);">
            <div class="rf-embed-container" style="width: 100%; height: 100%; text-align: center;">
                <?php echo $embed_code; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="rf-model-content rf-glass-card" style="padding: 40px;">
        <h2 class="rf-h3" style="margin-bottom: 24px;"><?php _e('Model Specifications', 'rfplugin'); ?></h2>
        <div class="rf-content-body">
            <?php the_content(); ?>
        </div>
    </div>
</div>
