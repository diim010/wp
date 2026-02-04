<?php
/**
 * Resource Partial: 3D Model (Production Ready)
 * 
 * Handles 3D model resource type with interactive viewer,
 * specifications display, and fullscreen capability.
 * 
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$resource_id = get_the_ID();
$embed_code = get_field('resource_3d_embed', $resource_id);
$model_file = get_field('resource_3d_file', $resource_id);
$model_specs = get_field('resource_3d_specs', $resource_id);

// Generate unique ID for fullscreen functionality
$viewer_id = 'rf-3d-viewer-' . $resource_id;
?>

<div class="rf-resource-partial rf-3d-view">
    
    <?php if ($embed_code || $model_file) : ?>
        <!-- 3D Viewer Section -->
        <section class="rf-3d-viewer-section" aria-label="<?php esc_attr_e('3D Model Viewer', 'rfplugin'); ?>">
            
            <!-- Viewer Controls -->
            <div class="rf-viewer-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 12px 16px; background: rgba(255,255,255,0.03); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; align-items: center; gap: 12px; color: #94a3b8; font-size: 0.85rem;">
                    <span class="dashicons dashicons-visibility" style="font-size: 18px; width: 18px; height: 18px; color: var(--rf-primary);" aria-hidden="true"></span>
                    <span><?php esc_html_e('Interactive 3D Model', 'rfplugin'); ?></span>
                </div>
                
                <div class="rf-viewer-actions" style="display: flex; gap: 8px;">
                    <button type="button" 
                            class="rf-btn rf-btn-small rf-btn-outline rf-fullscreen-btn"
                            data-target="<?php echo esc_attr($viewer_id); ?>"
                            aria-label="<?php esc_attr_e('Toggle fullscreen', 'rfplugin'); ?>"
                            style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 0.85rem;">
                        <span class="dashicons dashicons-fullscreen-alt" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                        <span class="rf-fullscreen-text"><?php esc_html_e('Fullscreen', 'rfplugin'); ?></span>
                    </button>
                </div>
            </div>
            
            <!-- 3D Viewer Container -->
            <div id="<?php echo esc_attr($viewer_id); ?>" 
                 class="rf-3d-wrapper" 
                 style="border-radius: 24px; overflow: hidden; background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); min-height: 500px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border: 1px solid rgba(255,255,255,0.1); position: relative;">
                
                <!-- Loading State -->
                <div class="rf-3d-loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; z-index: 1;">
                    <div class="rf-spinner" style="width: 48px; height: 48px; border: 3px solid rgba(255,255,255,0.1); border-top-color: var(--rf-primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 16px;"></div>
                    <p style="color: #64748b; font-size: 0.9rem;"><?php esc_html_e('Loading 3D model...', 'rfplugin'); ?></p>
                </div>
                
                <!-- Embed Container -->
                <div class="rf-embed-container" style="width: 100%; height: 100%; min-height: 500px;">
                    <?php 
                    if ($embed_code) {
                        // Sanitize embed code while allowing necessary attributes
                        echo wp_kses($embed_code, [
                            'iframe' => [
                                'src' => true,
                                'width' => true,
                                'height' => true,
                                'frameborder' => true,
                                'allow' => true,
                                'allowfullscreen' => true,
                                'loading' => true,
                                'title' => true,
                                'style' => true,
                                'class' => true,
                            ],
                            'model-viewer' => [
                                'src' => true,
                                'alt' => true,
                                'poster' => true,
                                'camera-controls' => true,
                                'auto-rotate' => true,
                                'ar' => true,
                                'style' => true,
                                'class' => true,
                            ],
                            'div' => [
                                'class' => true,
                                'style' => true,
                                'id' => true,
                            ],
                            'script' => [
                                'type' => true,
                                'src' => true,
                            ],
                        ]);
                    } elseif ($model_file) {
                        // Use model-viewer web component for GLB/GLTF files
                        $file_url = is_array($model_file) ? $model_file['url'] : $model_file;
                        ?>
                        <model-viewer 
                            src="<?php echo esc_url($file_url); ?>"
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            camera-controls
                            auto-rotate
                            style="width: 100%; height: 100%; min-height: 500px;"
                            loading="lazy">
                        </model-viewer>
                        <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
                        <?php
                    }
                    ?>
                </div>
                
                <!-- Interaction Hint -->
                <div class="rf-3d-hint" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.7); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #94a3b8; pointer-events: none;">
                    <span class="dashicons dashicons-move" style="font-size: 14px; width: 14px; height: 14px;" aria-hidden="true"></span>
                    <?php esc_html_e('Drag to rotate • Scroll to zoom', 'rfplugin'); ?>
                </div>
            </div>
        </section>
    <?php else : ?>
        <!-- No 3D Model Available -->
        <div class="rf-notice rf-notice-info" 
             role="alert"
             style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); padding: 32px; border-radius: 16px; margin-bottom: 40px; text-align: center;">
            <span class="dashicons dashicons-visibility" style="font-size: 48px; width: 48px; height: 48px; color: #3b82f6; margin-bottom: 16px;" aria-hidden="true"></span>
            <h3 style="color: white; margin: 0 0 8px;"><?php esc_html_e('3D Model Not Available', 'rfplugin'); ?></h3>
            <p style="margin: 0; color: #93c5fd;">
                <?php esc_html_e('The 3D model for this resource has not been uploaded yet. Please check back later.', 'rfplugin'); ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Model Specifications -->
    <section class="rf-model-content" aria-labelledby="model-specs-heading">
        <h2 id="model-specs-heading" class="rf-h3" style="margin-bottom: 24px; font-size: 1.5rem;">
            <?php esc_html_e('Model Specifications', 'rfplugin'); ?>
        </h2>
        
        <?php if ($model_specs && is_array($model_specs)) : ?>
            <!-- Specifications Grid -->
            <div class="rf-specs-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 40px;">
                <?php foreach ($model_specs as $spec) : 
                    $label = isset($spec['label']) ? $spec['label'] : '';
                    $value = isset($spec['value']) ? $spec['value'] : '';
                    if (!$label || !$value) continue;
                ?>
                    <div class="rf-spec-item rf-glass-card" style="padding: 20px; border-radius: 12px;">
                        <dt style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 8px;">
                            <?php echo esc_html($label); ?>
                        </dt>
                        <dd style="margin: 0; font-size: 1.25rem; font-weight: 600; color: white;">
                            <?php echo esc_html($value); ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Additional Content -->
        <div class="rf-content-body rf-prose" style="color: #cbd5e1; line-height: 1.8; font-size: 1.05rem;">
            <?php 
            $content = get_the_content();
            if ($content) {
                echo wp_kses_post(apply_filters('the_content', $content));
            } else {
                echo '<p style="color: #64748b;">' . esc_html__('No additional specifications available for this model.', 'rfplugin') . '</p>';
            }
            ?>
        </div>
    </section>
</div>

<style>
/* Spinner Animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Hide loading when content loaded */
.rf-embed-container:not(:empty) ~ .rf-3d-loading {
    display: none;
}

/* Fullscreen Styles */
.rf-3d-wrapper.fullscreen {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 99999 !important;
    border-radius: 0 !important;
    margin: 0 !important;
}
.rf-3d-wrapper.fullscreen .rf-embed-container,
.rf-3d-wrapper.fullscreen .rf-embed-container iframe,
.rf-3d-wrapper.fullscreen model-viewer {
    min-height: 100vh !important;
}

/* Model Viewer Styles */
model-viewer {
    --poster-color: transparent;
}

/* Fullscreen Button Active State */
.rf-fullscreen-btn.active {
    background: var(--rf-primary);
    border-color: var(--rf-primary);
    color: white;
}

/* Prose Styles */
.rf-prose h3, .rf-prose h4 {
    color: white;
    margin-top: 1.5em;
    margin-bottom: 0.75em;
}
.rf-prose p {
    margin-bottom: 1.25em;
}
.rf-prose ul, .rf-prose ol {
    margin-bottom: 1.25em;
    padding-left: 1.5em;
}
.rf-prose table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5em 0;
}
.rf-prose th, .rf-prose td {
    padding: 12px 16px;
    border: 1px solid rgba(255,255,255,0.1);
    text-align: left;
}
.rf-prose th {
    background: rgba(255,255,255,0.05);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .rf-3d-wrapper {
        min-height: 350px !important;
    }
    .rf-embed-container {
        min-height: 350px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fullscreen Toggle
    const fullscreenBtn = document.querySelector('.rf-fullscreen-btn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const viewer = document.getElementById(targetId);
            const textSpan = this.querySelector('.rf-fullscreen-text');
            
            if (viewer) {
                viewer.classList.toggle('fullscreen');
                this.classList.toggle('active');
                
                if (viewer.classList.contains('fullscreen')) {
                    textSpan.textContent = '<?php echo esc_js(__('Exit', 'rfplugin')); ?>';
                    document.body.style.overflow = 'hidden';
                } else {
                    textSpan.textContent = '<?php echo esc_js(__('Fullscreen', 'rfplugin')); ?>';
                    document.body.style.overflow = '';
                }
            }
        });
        
        // ESC key to exit fullscreen
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const fullscreenViewer = document.querySelector('.rf-3d-wrapper.fullscreen');
                if (fullscreenViewer) {
                    fullscreenViewer.classList.remove('fullscreen');
                    fullscreenBtn.classList.remove('active');
                    fullscreenBtn.querySelector('.rf-fullscreen-text').textContent = '<?php echo esc_js(__('Fullscreen', 'rfplugin')); ?>';
                    document.body.style.overflow = '';
                }
            }
        });
    }
    
    // Hide loading indicator when embed loads
    const embedContainer = document.querySelector('.rf-embed-container');
    if (embedContainer) {
        const iframe = embedContainer.querySelector('iframe');
        if (iframe) {
            iframe.addEventListener('load', function() {
                const loading = document.querySelector('.rf-3d-loading');
                if (loading) loading.style.display = 'none';
            });
        }
    }
});
</script>
