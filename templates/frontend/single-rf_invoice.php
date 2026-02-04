<?php
/**
 * Single Invoice / Submission Template
 * 
 * Secure, private-first view for form submissions and quotes.
 */

defined('ABSPATH') || exit;

get_header();

$invoice_id = get_the_ID();
$customer_name = get_field('customer_name', $invoice_id);
$customer_email = get_field('customer_email', $invoice_id);
$zoho_status = get_field('sync_status', $invoice_id) ?: 'pending';
$product_id = get_field('selected_product', $invoice_id);

// Security Check: Only admins or the submitter (if we had tracking) can view
// For now, we enforce manage_options to view individual submission records
if (!current_user_can('manage_options')) {
    include RFPLUGIN_PATH . 'templates/frontend/access-denied.php';
    get_footer();
    exit;
}

?>

<div class="rf-single-invoice rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-1"></div>

    <div class="rf-container" style="padding: 100px 0;">
        <header class="rf-invoice-header" style="margin-bottom: 60px; text-align: center;">
            <span class="rf-badge"><?php _e('Submission Record', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="margin: 20px 0;"><?php the_title(); ?></h1>
            <div class="rf-sync-status" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                 <span class="rf-badge <?php echo $zoho_status === 'synced' ? 'online' : ($zoho_status === 'failed' ? 'failed' : 'pending'); ?>" style="padding: 6px 16px; border-radius: 99px;">
                    <?php echo strtoupper($zoho_status); ?>
                 </span>
                 <span style="color: #64748b; font-size: 0.9rem;"><?php _e('Zoho CRM Sync', 'rfplugin'); ?></span>
            </div>
        </header>

        <div class="rf-invoice-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
            <!-- Main Record -->
            <div class="rf-invoice-main">
                <div class="rf-glass-card" style="padding: 60px; border-radius: 32px; margin-bottom: 40px;">
                    <h3 class="rf-h3" style="margin-bottom: 32px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 16px;">
                        <?php _e('Submission Content', 'rfplugin'); ?>
                    </h3>
                    
                    <div class="rf-submission-details" style="display: grid; gap: 24px;">
                        <div class="rf-detail-row">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 8px;"><?php _e('Message / Inquiry', 'rfplugin'); ?></label>
                            <div class="rf-p" style="font-size: 1.1rem; background: rgba(255,255,255,0.03); padding: 24px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                                <?php echo nl2br(get_field('form_message', $invoice_id)); ?>
                            </div>
                        </div>

                        <?php $options = get_field('selected_options', $invoice_id); ?>
                        <?php if ($options): ?>
                            <div class="rf-detail-row">
                                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 8px;"><?php _e('Selected Options', 'rfplugin'); ?></label>
                                <div style="font-family: monospace; font-size: 0.9rem; color: #94a3b8; padding: 20px; border-radius: 12px; background: #0f172a;">
                                    <?php echo nl2br(esc_html($options)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="rf-glass-card" style="padding: 40px; border-radius: 24px;">
                     <h4 style="margin-top: 0; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px;">
                        <?php _e('Technical Metadata', 'rfplugin'); ?>
                     </h4>
                     <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                        <div>
                            <span style="display: block; color: #475569; font-size: 0.7rem;"><?php _e('Submission ID', 'rfplugin'); ?></span>
                            <span style="font-weight: 700; word-break: break-all;">#<?php echo $invoice_id; ?></span>
                        </div>
                        <div>
                            <span style="display: block; color: #475569; font-size: 0.7rem;"><?php _e('Form ID', 'rfplugin'); ?></span>
                            <span style="font-weight: 700;"><?php echo get_field('form_id', $invoice_id) ?: '—'; ?></span>
                        </div>
                        <div>
                            <span style="display: block; color: #475569; font-size: 0.7rem;"><?php _e('Source URL', 'rfplugin'); ?></span>
                            <a href="<?php echo esc_url(get_field('source_url', $invoice_id)); ?>" target="_blank" style="color: var(--rf-primary); font-size: 0.8rem; text-decoration: none;">
                                <span class="dashicons dashicons-external" style="font-size: 14px;"></span> <?php _e('Origin View', 'rfplugin'); ?>
                            </a>
                        </div>
                     </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="rf-invoice-sidebar">
                <div class="rf-glass-card" style="padding: 32px; border-radius: 24px; margin-bottom: 40px; border: 1px solid rgba(16, 185, 129, 0.2);">
                    <h4 style="margin-top: 0; margin-bottom: 20px; font-size: 0.9rem; color: #10b981;"><?php _e('Customer Contact', 'rfplugin'); ?></h4>
                    <div style="margin-bottom: 16px;">
                        <span style="display: block; font-weight: 700; color: white; font-size: 1.1rem;"><?php echo esc_html($customer_name); ?></span>
                        <a href="mailto:<?php echo esc_attr($customer_email); ?>" style="color: var(--rf-primary); text-decoration: none; font-size: 0.9rem;"><?php echo esc_html($customer_email); ?></a>
                    </div>
                    <?php if ($phone = get_field('customer_phone', $invoice_id)): ?>
                        <div style="color: #94a3b8; font-size: 0.9rem;">
                            <span class="dashicons dashicons-phone" style="font-size: 14px;"></span> <?php echo esc_html($phone); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($product_id): ?>
                    <div class="rf-glass-card" style="padding: 32px; border-radius: 24px;">
                        <h4 style="margin-top: 0; margin-bottom: 20px; font-size: 0.9rem; color: #64748b;"><?php _e('Associated Product', 'rfplugin'); ?></h4>
                        <div style="display: flex; gap: 16px; align-items: center;">
                            <?php if (has_post_thumbnail($product_id)) : ?>
                                <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden;">
                                    <?php echo get_the_post_thumbnail($product_id, 'thumbnail', ['style' => 'width: 100%; height: auto;']); ?>
                                </div>
                            <?php endif; ?>
                            <a href="<?php echo get_permalink($product_id); ?>" style="color: white; font-weight: 600; text-decoration: none; font-size: 0.95rem;">
                                <?php echo get_the_title($product_id); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>
