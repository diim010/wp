<?php
/**
 * Resource Partial: FAQ (Production Ready)
 * 
 * Handles FAQ resource type with question/answer display,
 * Schema.org FAQPage markup, and accessible structure.
 * 
 * @package RFPlugin
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$resource_id = get_the_ID();
$answer = get_field('resource_answer', $resource_id);
$question = get_the_title();

// Get related FAQs for additional context
$categories = get_the_terms($resource_id, 'rf_resource_category');
$related_faqs = [];
if ($categories && !is_wp_error($categories)) {
    $related_faqs = get_posts([
        'post_type' => 'rf_resource',
        'posts_per_page' => 3,
        'post__not_in' => [$resource_id],
        'meta_query' => [
            [
                'key' => 'resource_mode',
                'value' => 'faq',
                'compare' => '='
            ]
        ],
        'tax_query' => [
            [
                'taxonomy' => 'rf_resource_category',
                'field' => 'term_id',
                'terms' => wp_list_pluck($categories, 'term_id')
            ]
        ]
    ]);
}
?>

<div class="rf-resource-partial rf-faq-view" 
     itemscope 
     itemtype="https://schema.org/FAQPage">
    
    <!-- Question Section -->
    <section class="rf-faq-question rf-glass-card" 
             aria-labelledby="faq-question-heading"
             style="padding: clamp(24px, 4vw, 48px); margin-bottom: 40px; border-left: 4px solid var(--rf-primary); border-radius: 0 16px 16px 0;">
        
        <div class="rf-question-header" style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 24px;">
            <div class="rf-question-icon" 
                 aria-hidden="true"
                 style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, var(--rf-primary), hsl(262, 83%, 58%)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <span class="dashicons dashicons-editor-help" style="font-size: 24px; width: 24px; height: 24px; color: white;"></span>
            </div>
            <div>
                <span id="faq-question-heading" class="rf-label" style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--rf-primary); font-weight: 700; margin-bottom: 8px;">
                    <?php esc_html_e('Question', 'rfplugin'); ?>
                </span>
            </div>
        </div>
        
        <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <h2 itemprop="name" 
                style="font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 600; color: white; margin: 0; line-height: 1.4;">
                <?php echo esc_html($question); ?>
            </h2>
            <meta itemprop="answerCount" content="1" />
            
            <!-- Answer Section (nested in Question schema) -->
            <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                <div class="rf-faq-answer rf-glass-card" 
                     style="padding: clamp(24px, 4vw, 48px); margin-top: 40px; border-radius: 16px; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2);">
                    
                    <div class="rf-answer-header" style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                        <div class="rf-answer-icon" 
                             aria-hidden="true"
                             style="width: 40px; height: 40px; background: hsl(142, 76%, 36%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span class="dashicons dashicons-yes-alt" style="font-size: 20px; width: 20px; height: 20px; color: white;"></span>
                        </div>
                        <span class="rf-label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: hsl(142, 76%, 36%); font-weight: 700;">
                            <?php esc_html_e('Answer', 'rfplugin'); ?>
                        </span>
                    </div>
                    
                    <div itemprop="text" 
                         class="rf-content-body rf-prose"
                         style="font-size: clamp(1.1rem, 2vw, 1.25rem); line-height: 1.8; color: #cbd5e1;">
                        <?php 
                        if ($answer) {
                            echo wp_kses_post($answer);
                        } else {
                            // Fallback to post content
                            the_content();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related FAQs -->
    <?php if (!empty($related_faqs)) : ?>
        <section class="rf-related-faqs" aria-labelledby="related-faqs-heading">
            <h3 id="related-faqs-heading" class="rf-h4" style="margin-bottom: 24px; font-size: 1.25rem; color: white;">
                <?php esc_html_e('Related Questions', 'rfplugin'); ?>
            </h3>
            
            <div class="rf-faq-list" style="display: flex; flex-direction: column; gap: 16px;">
                <?php foreach ($related_faqs as $faq) : ?>
                    <a href="<?php echo esc_url(get_permalink($faq->ID)); ?>" 
                       class="rf-faq-link rf-glass-card"
                       style="display: flex; align-items: center; gap: 16px; padding: 20px 24px; text-decoration: none; border-radius: 12px; transition: all 0.3s ease;">
                        <span class="dashicons dashicons-editor-help" 
                              style="font-size: 20px; width: 20px; height: 20px; color: var(--rf-primary);"
                              aria-hidden="true"></span>
                        <span style="color: white; font-weight: 500; flex: 1;"><?php echo esc_html($faq->post_title); ?></span>
                        <span class="dashicons dashicons-arrow-right-alt2" 
                              style="font-size: 16px; width: 16px; height: 16px; color: #64748b;"
                              aria-hidden="true"></span>
                    </a>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Helpful Actions -->
    <section class="rf-faq-actions" style="margin-top: 48px; padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.1);">
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 16px;">
            <?php esc_html_e('Was this answer helpful?', 'rfplugin'); ?>
        </p>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button type="button" 
                    class="rf-btn rf-btn-outline rf-feedback-btn"
                    data-feedback="yes"
                    aria-label="<?php esc_attr_e('Yes, this was helpful', 'rfplugin'); ?>"
                    style="padding: 10px 20px; display: inline-flex; align-items: center; gap: 8px;">
                <span class="dashicons dashicons-thumbs-up" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                <?php esc_html_e('Yes', 'rfplugin'); ?>
            </button>
            <button type="button" 
                    class="rf-btn rf-btn-outline rf-feedback-btn"
                    data-feedback="no"
                    aria-label="<?php esc_attr_e('No, I need more help', 'rfplugin'); ?>"
                    style="padding: 10px 20px; display: inline-flex; align-items: center; gap: 8px;">
                <span class="dashicons dashicons-thumbs-down" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                <?php esc_html_e('No', 'rfplugin'); ?>
            </button>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
               class="rf-btn rf-btn-outline"
               style="padding: 10px 20px; display: inline-flex; align-items: center; gap: 8px; margin-left: auto;">
                <span class="dashicons dashicons-email" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
                <?php esc_html_e('Contact Support', 'rfplugin'); ?>
            </a>
        </div>
    </section>
</div>

<style>
/* FAQ Link Hover */
.rf-faq-link:hover {
    transform: translateX(4px);
    border-color: var(--rf-primary);
}
.rf-faq-link:focus {
    outline: 2px solid var(--rf-primary);
    outline-offset: 2px;
}

/* Feedback Button States */
.rf-feedback-btn:hover {
    background: rgba(255,255,255,0.1);
}
.rf-feedback-btn.active[data-feedback="yes"] {
    background: hsl(142, 76%, 36%);
    border-color: hsl(142, 76%, 36%);
    color: white;
}
.rf-feedback-btn.active[data-feedback="no"] {
    background: hsl(0, 84%, 60%);
    border-color: hsl(0, 84%, 60%);
    color: white;
}

/* Prose Styles */
.rf-prose p {
    margin-bottom: 1.25em;
}
.rf-prose ul, .rf-prose ol {
    margin-bottom: 1.25em;
    padding-left: 1.5em;
}
.rf-prose li {
    margin-bottom: 0.5em;
}
.rf-prose a {
    color: var(--rf-primary);
    text-decoration: underline;
}
.rf-prose strong {
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackBtns = document.querySelectorAll('.rf-feedback-btn');
    feedbackBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            feedbackBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Optional: Send feedback to server
            // const feedback = this.dataset.feedback;
            // console.log('Feedback:', feedback);
        });
    });
});
</script>
