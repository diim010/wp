<?php
/**
 * Single Template for Technical Documentation
 * 
 * @package RFPlugin
 */

get_header();

$doc_id = get_the_ID();

// Security access check
if (!\RFPlugin\Security\Permissions::canViewTechDoc($doc_id)): ?>
    <div class="rf-access-error" style="padding: 120px 0; text-align: center; background: #fff; min-height: 90vh; display: flex; align-items: center;">
        <div class="rf-container">
            <div style="width: 120px; height: 120px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 40px;">
                <span class="dashicons dashicons-lock" style="font-size: 48px; width: 48px; height: 48px; color: #ef4444;"></span>
            </div>
            <h1 style="font-size: 3rem; font-weight: 800; color: #111827; margin-bottom: 24px; letter-spacing: -0.02em;"><?php _e('Access Restricted', 'rfplugin'); ?></h1>
            <p style="font-size: 1.25rem; color: #6b7280; max-width: 550px; margin: 0 auto 48px; line-height: 1.6;">
                <?php _e('This specialized technical resource is reserved for authorized partners and administrators. Please log in or request a credential upgrade.', 'rfplugin'); ?>
            </p>
            <div style="display: flex; justify-content: center; gap: 20px;">
                <?php if (!is_user_logged_in()): ?>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>" class="rf-btn-premium" style="background: #111827; color: #fff; padding: 16px 40px; border-radius: 12px; font-weight: 600; text-decoration: none;">
                        <?php _e('Secure Login', 'rfplugin'); ?>
                    </a>
                <?php endif; ?>
                <a href="<?php echo get_permalink(get_page_by_path('technical-center')); ?>" class="rf-btn-premium" style="background: #f3f4f6; color: #374151; padding: 16px 40px; border-radius: 12px; font-weight: 600; text-decoration: none;">
                    <?php _e('Return to Technical Center', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </div>
<?php 
    get_footer();
    exit;
endif;

$file_data = get_field('field_tech_doc_file', $doc_id);
$file_url = is_array($file_data) ? ($file_data['url'] ?? '') : (string)$file_data;
$file_type = get_field('file_type', $doc_id) ?: 'manual';
$file_size = '';
if (!empty($file_data['ID'])) {
    $file_path = get_attached_file($file_data['ID']);
    if ($file_path && file_exists($file_path)) {
        $file_size = size_format(filesize($file_path));
    }
}

$download_url = add_query_arg('_wpnonce', wp_create_nonce('wp_rest'), rest_url('rfplugin/v1/techdocs/' . $doc_id . '/download'));
$thumbnail = get_the_post_thumbnail_url($doc_id, 'full') ?: RFPLUGIN_URL . 'assets/images/doc-placeholder.png';
?>

<div class="rf-single-doc-premium rf-premium-ui">
    <div class="rf-container">
        <!-- Top Nav -->
        <nav class="rf-doc-nav" style="border-bottom: 2px solid #f8fafc; margin-bottom: 60px; padding: 32px 0;">
            <a href="<?php echo get_permalink(get_page_by_path('technical-center')); ?>" class="rf-back-link" style="text-decoration: none; color: var(--rf-text-muted); font-weight: 700; display: inline-flex; align-items: center; gap: 12px; transition: all 0.3s;">
                <span class="dashicons dashicons-arrow-left-alt" style="font-size: 18px; width: 18px; height: 18px;"></span>
                <?php _e('Technical Center', 'rfplugin'); ?>
            </a>
            <div class="rf-doc-id" style="color: #94a3b8; font-size: 0.9rem; font-weight: 800; background: #f8fafc; padding: 6px 16px; border-radius: 100px;">
                <?php echo 'ID: #' . str_pad($doc_id, 5, '0', STR_PAD_LEFT); ?>
            </div>
        </nav>

        <!-- Decorative Elements -->
        <div class="rf-blob rf-blob-1" style="width: 400px; height: 400px; top: -100px; right: -100px; background: hsla(220, 90%, 50%, 0.05);"></div>
        <div class="rf-blob rf-blob-2" style="width: 300px; height: 300px; bottom: 20%; left: -50px; background: hsla(150, 70%, 50%, 0.03);"></div>

        <div class="rf-doc-grid-focused" style="display: grid; grid-template-columns: 1fr 380px; gap: 60px;">
            <!-- Left Side: Content -->
            <main class="rf-doc-content-side" style="animation: rfFadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);">
                <div class="rf-doc-heading" style="margin-bottom: 50px;">
                    <span class="rf-badge" style="background: var(--rf-primary-light); color: var(--rf-primary); margin-bottom: 24px;">
                        <?php echo strtoupper(esc_html($file_type)); ?>
                    </span>
                    <h1 class="rf-title" style="font-size: clamp(2.5rem, 6vw, 4rem); text-align: left; background: none; -webkit-text-fill-color: initial; margin-bottom: 32px;">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="rf-doc-meta-simple" style="display: flex; gap: 32px; color: var(--rf-text-muted); font-size: 1rem; font-weight: 600;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span class="dashicons dashicons-calendar-alt" style="font-size: 18px;"></span> 
                            <?php _e('Updated', 'rfplugin'); ?> <?php echo get_the_modified_date(); ?>
                        </span>
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span class="dashicons dashicons-shield-lock" style="font-size: 18px; color: var(--rf-success);"></span> 
                            <?php _e('Verified Secure Asset', 'rfplugin'); ?>
                        </span>
                    </div>
                </div>

                <div class="rf-doc-body" style="font-size: 1.25rem; line-height: 1.8; color: #334155; margin-bottom: 60px;">
                    <?php the_content(); ?>
                </div>

                <?php if (str_ends_with(strtolower($file_url), '.pdf')): ?>
                    <div class="rf-preview-window" style="border-radius: 32px; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: var(--rf-shadow-premium); background: #f8fafc;">
                        <div class="rf-preview-header" style="padding: 24px 32px; background: #fff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <h5 style="margin: 0; font-weight: 900; color: #0f172a; font-size: 1.1rem;"><?php _e('Secure Document Preview', 'rfplugin'); ?></h5>
                            <span style="font-size: 0.75rem; color: var(--rf-text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.1em;"><?php _e('Encrypted Stream', 'rfplugin'); ?></span>
                        </div>
                        <div id="rf-pdf-viewer" style="height: 500px; display: flex; align-items: center; justify-content: center; background: #f8fafc; position: relative; overflow: hidden;">
                            <div class="rf-preview-overlay" style="text-align: center; padding: 60px; z-index: 2; position: relative;">
                                <div style="background: #fef2f2; width: 100px; height: 100px; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 32px;">
                                    <span class="dashicons dashicons-pdf" style="font-size: 48px; width: 48px; height: 48px; color: #ef4444;"></span>
                                </div>
                                <h4 style="color: #0f172a; font-weight: 900; font-size: 1.5rem; margin-bottom: 16px;"><?php _e('Technical Specification', 'rfplugin'); ?></h4>
                                <p style="color: #64748b; margin-bottom: 32px; font-size: 1rem; max-width: 400px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                                    <?php _e('This proprietary document is protected by our enterprise security layer. Download to access full technical details and specifications.', 'rfplugin'); ?>
                                </p>
                                <button onclick="document.getElementById('rf-download-btn').click()" class="rf-btn" style="padding: 16px 40px; border-radius: 16px;">
                                    <?php _e('Download Now', 'rfplugin'); ?>
                                </button>
                            </div>
                            <div style="position: absolute; top:0; left:0; width: 100%; height: 100%; opacity: 0.03; background-image: radial-gradient(var(--rf-primary) 1px, transparent 1px); background-size: 20px 20px;"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </main>

            <!-- Right Side: Action Box -->
            <aside class="rf-doc-action-side">
                <div class="rf-card" style="padding: 40px; border-radius: 32px; position: sticky; top: 40px; box-shadow: var(--rf-shadow-premium);">
                    <button id="rf-download-btn" class="rf-btn" style="width: 100%; padding: 20px; border-radius: 20px; font-size: 1.1rem; margin-bottom: 32px; display: flex; align-items: center; justify-content: center; gap: 12px;">
                        <span class="dashicons dashicons-download"></span>
                        <span class="rf-btn-text"><?php _e('Download Asset', 'rfplugin'); ?></span>
                    </button>

                    <div class="rf-meta-list" style="display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f8fafc; padding-bottom: 16px;">
                            <span style="color: var(--rf-text-muted); font-weight: 600;"><?php _e('Filename', 'rfplugin'); ?></span>
                            <span style="font-weight: 800; color: #0f172a; max-width: 180px; overflow: hidden; text-overflow: ellipsis;"><?php echo basename($file_url); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f8fafc; padding-bottom: 16px;">
                            <span style="color: var(--rf-text-muted); font-weight: 600;"><?php _e('Format', 'rfplugin'); ?></span>
                            <span style="font-weight: 800; color: var(--rf-primary);"><?php echo strtoupper(pathinfo($file_url, PATHINFO_EXTENSION) ?: 'PDF'); ?></span>
                        </div>
                        <?php if ($file_size): ?>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f8fafc; padding-bottom: 16px;">
                            <span style="color: var(--rf-text-muted); font-weight: 600;"><?php _e('Size', 'rfplugin'); ?></span>
                            <span style="font-weight: 800; color: #0f172a;"><?php echo esc_html($file_size); ?></span>
                        </div>
                        <?php endif; ?>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f8fafc; padding-bottom: 16px;">
                            <span style="color: var(--rf-text-muted); font-weight: 600;"><?php _e('Version', 'rfplugin'); ?></span>
                            <span style="font-weight: 800; color: #0f172a;"><?php echo get_field('field_last_file_update', $doc_id) ?: '1.0.0'; ?></span>
                        </div>
                    </div>

                    <!-- Tags Group -->
                    <div class="rf-tag-group" style="margin-top: 40px;">
                        <h6 style="margin:0 0 20px; font-size: 0.75rem; text-transform: uppercase; color: var(--rf-text-muted); font-weight: 800; letter-spacing: 0.1em;"><?php _e('Classified Tags', 'rfplugin'); ?></h6>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <?php 
                            $tags = wp_get_post_terms($doc_id, 'rf_techdoc_tag');
                            foreach ($tags as $tag): ?>
                                <span style="background: #f1f5f9; color: #475569; padding: 8px 16px; border-radius: 12px; font-size: 0.85rem; font-weight: 700;"><?php echo esc_html($tag->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Assistance Card -->
                <div style="margin-top: 32px; padding: 40px; background: linear-gradient(135deg, var(--rf-primary-light) 0%, #fff 100%); border-radius: 32px; border: 1px solid var(--rf-primary-light); position: relative; overflow: hidden;">
                    <h5 style="margin: 0 0 16px; color: var(--rf-primary-dark); font-weight: 900; font-size: 1.25rem; position: relative; z-index: 1;"><?php _e('Expert Support', 'rfplugin'); ?></h5>
                    <p style="margin: 0; color: var(--rf-primary-dark); font-size: 1rem; line-height: 1.6; opacity: 0.8; position: relative; z-index: 1;">
                        <?php _e('Direct access to technical engineers for in-depth consultation on this specific asset.', 'rfplugin'); ?>
                    </p>
                    <a href="#" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-weight: 800; color: var(--rf-primary); text-decoration: none; font-size: 1rem; position: relative; z-index: 1;">
                        <?php _e('Consult an Expert', 'rfplugin'); ?> 
                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                    <div style="position: absolute; bottom: -20px; right: -20px; font-size: 100px; color: var(--rf-primary); opacity: 0.05;">
                        <span class="dashicons dashicons-businessman"></span>
                    </div>
                </div>
            </aside>
        </div>
    <div id="rf-toast-root" class="rf-toast-container"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const downloadBtn = document.getElementById('rf-download-btn');
    const toastRoot = document.getElementById('rf-toast-root');
    const downloadUrl = '<?php echo $download_url; ?>';

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `rf-toast ${type === 'error' ? 'is-error' : ''}`;
        toast.innerHTML = `
            <span class="dashicons dashicons-${type === 'error' ? 'warning' : 'yes'}"></span>
            <span>${message}</span>
        `;
        toastRoot.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            downloadBtn.classList.add('is-loading');
            const icon = downloadBtn.querySelector('.dashicons');
            const originalIcon = icon.className;
            icon.className = 'dashicons dashicons-update';

            // We initiate a background check/head request to see if it's accessible
            // Since it's a direct download URL that streams, we just use a small delay 
            // to show the "enterprise" feel and then navigate.
            // But actually, let's do a real fetch check for permissions.
            
            fetch(downloadUrl, {
                method: 'HEAD', // Just check headers first
            }).then(response => {
                if (response.ok) {
                    showToast('<?php _e('Download started...', 'rfplugin'); ?>');
                    window.location.href = downloadUrl;
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || '<?php _e('Access denied or file missing.', 'rfplugin'); ?>');
                    });
                }
            }).catch(error => {
                showToast(error.message, 'error');
            }).finally(() => {
                setTimeout(() => {
                    downloadBtn.classList.remove('is-loading');
                    icon.className = originalIcon;
                }, 1000);
            });
        });
    }
});
</script>

<?php get_footer(); ?>
