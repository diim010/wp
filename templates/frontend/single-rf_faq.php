<?php
/**
 * Single Template for FAQ
 * 
 * @package RFPlugin
 */

get_header();

$faq_id = get_the_ID();
$faq_priority = get_field('field_faq_priority', $faq_id) ?: 0;
$categories = get_the_terms($faq_id, 'rf_faq_category');
$tags = get_the_terms($faq_id, 'rf_faq_tag');
$attached_docs = get_field('attached_docs', $faq_id);
?>
<div class="rf-faq-single rf-premium-ui">
    <div class="rf-container" style="max-width: 900px;">
        
        <nav class="rf-breadcrumb" style="margin-bottom: 50px;">
            <a href="<?php echo get_permalink(get_page_by_path('technical-center')); ?>" class="rf-back-btn" style="text-decoration: none; color: var(--rf-text-muted); font-weight: 700; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s;">
                <span class="dashicons dashicons-arrow-left-alt2" style="font-size: 16px; width: 16px; height: 16px;"></span> 
                <?php _e('Technical Center', 'rfplugin'); ?>
            </a>
        </nav>

        <article class="rf-faq-card rf-card" style="padding: 60px; border-radius: 32px; animation: rfFadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);">
            <header class="rf-faq-header" style="margin-bottom: 48px; border-bottom: 1px solid #f1f5f9; padding-bottom: 40px;">
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="rf-badge" style="margin: 0; padding: 10px 24px; font-size: 0.85rem;">
                            <span class="dashicons dashicons-editor-help" style="font-size: 16px; margin-right: 8px;"></span>
                            <?php _e('Support Article', 'rfplugin'); ?>
                        </span>
                        <?php if ($faq_priority > 0): ?>
                            <span style="color: #f59e0b; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 4px;">
                                <span class="dashicons dashicons-star-filled" style="font-size: 14px; width: 14px;"></span>
                                <?php _e('Top Priority', 'rfplugin'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <h1 class="rf-title" style="font-size: clamp(2rem, 5vw, 3.5rem); margin: 0; text-align: left; background: none; -webkit-text-fill-color: initial;"><?php the_title(); ?></h1>
                        
                        <div style="margin-top: 24px; display: flex; flex-wrap: wrap; gap: 12px;">
                            <?php if ($categories && !is_wp_error($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <span style="background: var(--rf-primary-light); color: var(--rf-primary); padding: 6px 16px; border-radius: 12px; font-size: 0.8rem; font-weight: 800; text-transform: uppercase;">
                                        <?php echo esc_html($cat->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <div class="rf-faq-content" style="line-height: 1.8; color: #334155; font-size: 1.25rem; margin-bottom: 60px;">
                <?php the_content(); ?>
            </div>

            <?php if ($attached_docs): ?>
                <div class="rf-faq-resources" style="background: #f8fafc; border-radius: 24px; padding: 40px; border: 1px solid #f1f5f9; margin-bottom: 60px;">
                    <h3 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0 0 24px 0; display: flex; align-items: center; gap: 12px;">
                        <span class="dashicons dashicons-media-document" style="color: var(--rf-primary); font-size: 24px; width: 24px; height: 24px;"></span>
                        <?php _e('Expert Technical Documentation', 'rfplugin'); ?>
                    </h3>
                    <div style="display: grid; gap: 16px;">
                        <?php foreach ($attached_docs as $doc_id): ?>
                            <a href="<?php echo get_permalink($doc_id); ?>" class="rf-resource-item" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; background: white; border-radius: 16px; border: 1px solid #f1f5f9; text-decoration: none; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span class="dashicons dashicons-pdf" style="font-size: 20px; color: #ef4444;"></span>
                                    <span style="font-weight: 700; color: #1e293b; font-size: 1.1rem;"><?php echo get_the_title($doc_id); ?></span>
                                </div>
                                <span class="rf-btn" style="padding: 8px 16px; font-size: 0.8rem; background: #f1f5f9; color: #475569; box-shadow: none; border-radius: 8px;">
                                    <?php _e('View Specs', 'rfplugin'); ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($tags && !is_wp_error($tags)): ?>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 60px;">
                    <?php foreach ($tags as $tag): ?>
                        <span style="color: #94a3b8; font-size: 0.9rem; font-weight: 700; background: #f8fafc; padding: 6px 12px; border-radius: 8px;">
                            #<?php echo esc_html($tag->name); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <footer style="padding-top: 48px; border-top: 2px solid #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 32px;">
                <div style="color: #94a3b8; font-size: 1rem; font-weight: 600;">
                    <span class="dashicons dashicons-clock" style="font-size: 18px; margin-right: 8px; vertical-align: middle;"></span>
                    <?php _e('Updated', 'rfplugin'); ?> <?php echo get_the_modified_date(); ?>
                </div>
                <div class="rf-faq-feedback" style="display: flex; align-items: center; gap: 16px; background: #f1f5f9; padding: 8px 12px; border-radius: 16px;">
                    <span style="font-size: 1rem; color: #475569; font-weight: 800; margin-left: 8px;"><?php _e('Helpful?', 'rfplugin'); ?></span>
                    <div style="display: flex; gap: 8px;">
                        <button class="rf-btn-feedback rf-feedback-trigger" data-type="yes" style="padding: 10px 20px; border-radius: 10px; border: none; background: white; color: #059669; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <span class="dashicons dashicons-thumbs-up"></span> <?php _e('Yes', 'rfplugin'); ?>
                        </button>
                        <button class="rf-btn-feedback rf-feedback-trigger" data-type="no" style="padding: 10px 20px; border-radius: 10px; border: none; background: white; color: #dc2626; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <span class="dashicons dashicons-thumbs-down"></span> <?php _e('No', 'rfplugin'); ?>
                        </button>
                    </div>
                </div>
            </footer>
        </article>

        <!-- Structured Data for FAQ -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "FAQPage",
          "mainEntity": [{
            "@type": "Question",
            "name": "<?php echo esc_js(get_the_title()); ?>",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "<?php echo esc_js(wp_strip_all_tags(get_the_content())); ?>"
            }
          }]
        }
        </script>

    <div id="rf-toast-root" class="rf-toast-container"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastRoot = document.getElementById('rf-toast-root');
    const feedbackBtns = document.querySelectorAll('.rf-feedback-trigger');

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'rf-toast';
        toast.innerHTML = `<span class="dashicons dashicons-heart" style="color:#ef4444;"></span><span>${message}</span>`;
        toastRoot.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    feedbackBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            feedbackBtns.forEach(b => b.classList.remove('is-selected'));
            this.classList.add('is-selected');
            showToast("<?php _e('Thank you for your feedback!', 'rfplugin'); ?>");
            
            // In a real staging, we would send this to a REST endpoint
            // fetch('<?php echo rest_url('rfplugin/v1/faq/' . $faq_id . '/feedback'); ?>', {
            //     method: 'POST',
            //     headers: { 'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>' },
            //     body: JSON.stringify({ type: this.dataset.type })
            // });
        });
    });
});
</script>

<?php get_footer(); ?>
