<?php
/**
 * Resource Partial: Document / Sheet (Production Ready)
 * 
 * Handles document and datasheet resource types with secure download,
 * file information display, and content overview.
 * 
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$resource_id = get_the_ID();
$file = get_field('resource_file', $resource_id);
$mode = get_field('resource_mode', $resource_id) ?: 'document';

// File type icons mapping
$file_icons = [
    'pdf' => 'pdf',
    'doc' => 'media-document',
    'docx' => 'media-document',
    'xls' => 'media-spreadsheet',
    'xlsx' => 'media-spreadsheet',
    'ppt' => 'media-interactive',
    'pptx' => 'media-interactive',
    'zip' => 'media-archive',
    'default' => 'media-default'
];
?>

<div class="rf-resource-partial rf-document-view">
    <?php if ($file && is_array($file)) : 
        $file_ext = isset($file['subtype']) ? strtolower($file['subtype']) : 'default';
        $icon = $file_icons[$file_ext] ?? $file_icons['default'];
        $filename = isset($file['filename']) ? $file['filename'] : __('Document', 'rfplugin');
        $filesize = isset($file['filesize']) ? size_format($file['filesize']) : '';
        $filetype = isset($file['subtype']) ? strtoupper($file['subtype']) : __('File', 'rfplugin');
    ?>
        <!-- File Download Hero -->
        <section class="rf-file-hero" 
                 aria-label="<?php esc_attr_e('File download section', 'rfplugin'); ?>"
                 style="display: flex; flex-wrap: wrap; align-items: center; gap: 40px; background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(147, 51, 234, 0.05)); padding: clamp(24px, 4vw, 48px); border-radius: 24px; margin-bottom: 48px; border: 1px solid rgba(255,255,255,0.1);">
            
            <!-- File Icon -->
            <div class="rf-file-icon" 
                 aria-hidden="true"
                 style="width: 100px; height: 100px; min-width: 100px; background: var(--rf-primary); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 40px rgba(37, 99, 235, 0.3);">
                <span class="dashicons dashicons-<?php echo esc_attr($icon); ?>" 
                      style="font-size: 48px; width: 48px; height: 48px; color: white;"></span>
            </div>
            
            <!-- File Information -->
            <div class="rf-file-info" style="flex: 1; min-width: 200px;">
                <h2 style="margin: 0 0 12px; font-size: clamp(1.25rem, 3vw, 1.5rem); color: white; word-break: break-word;">
                    <?php echo esc_html($filename); ?>
                </h2>
                
                <dl class="rf-file-meta" style="display: flex; flex-wrap: wrap; gap: 16px; margin: 0 0 24px; color: #94a3b8; font-size: 0.9rem;">
                    <?php if ($filesize) : ?>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <dt class="screen-reader-text"><?php esc_html_e('File size', 'rfplugin'); ?></dt>
                            <dd style="margin: 0; display: flex; align-items: center; gap: 6px;">
                                <span class="dashicons dashicons-media-default" style="font-size: 14px; width: 14px; height: 14px;" aria-hidden="true"></span>
                                <?php echo esc_html($filesize); ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <dt class="screen-reader-text"><?php esc_html_e('File type', 'rfplugin'); ?></dt>
                        <dd style="margin: 0; display: flex; align-items: center; gap: 6px;">
                            <span class="dashicons dashicons-tag" style="font-size: 14px; width: 14px; height: 14px;" aria-hidden="true"></span>
                            <?php echo esc_html($filetype); ?>
                        </dd>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <dt class="screen-reader-text"><?php esc_html_e('Upload date', 'rfplugin'); ?></dt>
                        <dd style="margin: 0; display: flex; align-items: center; gap: 6px;">
                            <span class="dashicons dashicons-calendar-alt" style="font-size: 14px; width: 14px; height: 14px;" aria-hidden="true"></span>
                            <?php echo esc_html(get_the_date()); ?>
                        </dd>
                    </div>
                </dl>
                
                <!-- Download Button -->
                <?php 
                $download_url = rest_url('rfplugin/v1/resources/' . intval($resource_id) . '/download');
                $download_url = wp_nonce_url($download_url, 'wp_rest');
                ?>
                <a href="<?php echo esc_url($download_url); ?>" 
                   class="rf-btn rf-btn-primary rf-download-btn"
                   download
                   aria-label="<?php echo esc_attr(sprintf(__('Download %s', 'rfplugin'), $filename)); ?>"
                   style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; font-size: 1rem; font-weight: 600;">
                    <span class="dashicons dashicons-download" style="font-size: 18px; width: 18px; height: 18px;" aria-hidden="true"></span>
                    <?php esc_html_e('Secure Download', 'rfplugin'); ?>
                </a>
            </div>
        </section>
    <?php else : ?>
        <!-- No File Available -->
        <div class="rf-notice rf-notice-info" 
             role="alert"
             style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); padding: 24px; border-radius: 12px; margin-bottom: 40px; display: flex; align-items: center; gap: 16px;">
            <span class="dashicons dashicons-info" style="font-size: 24px; width: 24px; height: 24px; color: #3b82f6;" aria-hidden="true"></span>
            <p style="margin: 0; color: #93c5fd;">
                <?php esc_html_e('No downloadable file is attached to this document. Please refer to the content below.', 'rfplugin'); ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Document Content -->
    <section class="rf-doc-content" aria-labelledby="doc-overview-heading">
        <h2 id="doc-overview-heading" class="rf-h3" style="margin-bottom: 24px; font-size: 1.5rem;">
            <?php echo ($mode === 'sheet') 
                ? esc_html__('Datasheet Specifications', 'rfplugin') 
                : esc_html__('Document Overview', 'rfplugin'); ?>
        </h2>
        
        <div class="rf-content-body rf-prose" style="color: #cbd5e1; line-height: 1.8; font-size: 1.05rem;">
            <?php 
            $content = get_the_content();
            if ($content) {
                echo wp_kses_post(apply_filters('the_content', $content));
            } else {
                echo '<p style="color: #64748b;">' . esc_html__('No additional content available for this resource.', 'rfplugin') . '</p>';
            }
            ?>
        </div>
    </section>
</div>

<style>
/* Download Button Hover */
.rf-download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
}
.rf-download-btn:focus {
    outline: 2px solid white;
    outline-offset: 2px;
}

/* Prose Styles */
.rf-prose h2, .rf-prose h3, .rf-prose h4 {
    color: white;
    margin-top: 2em;
    margin-bottom: 0.75em;
}
.rf-prose p {
    margin-bottom: 1.5em;
}
.rf-prose ul, .rf-prose ol {
    margin-bottom: 1.5em;
    padding-left: 1.5em;
}
.rf-prose li {
    margin-bottom: 0.5em;
}
.rf-prose a {
    color: var(--rf-primary);
    text-decoration: underline;
}
.rf-prose a:hover {
    text-decoration: none;
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
    font-weight: 600;
}

/* Screen Reader Text */
.screen-reader-text {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Responsive */
@media (max-width: 640px) {
    .rf-file-hero {
        flex-direction: column;
        text-align: center;
    }
    .rf-file-meta {
        justify-content: center;
    }
}
</style>
