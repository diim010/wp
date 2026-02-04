<?php
/**
 * Resource Partial: FAQ
 */
?>
<div class="rf-resource-partial rf-faq-view">
    <div class="rf-faq-question rf-glass-card" style="padding: 40px; margin-bottom: 40px; border-left: 4px solid var(--rf-primary);">
        <h2 class="rf-h3" style="margin-bottom: 24px; color: white;"><?php _e('Question', 'rfplugin'); ?></h2>
        <div class="rf-text-lg" style="font-size: 1.5rem; font-weight: 500; color: white;">
            <?php the_title(); ?>
        </div>
    </div>

    <div class="rf-faq-answer rf-glass-card" style="padding: 40px;">
        <h2 class="rf-h3" style="margin-bottom: 24px; color: var(--rf-green);"><?php _e('Answer', 'rfplugin'); ?></h2>
        <div class="rf-content-body" style="font-size: 1.25rem; line-height: 1.8; color: #cbd5e1;">
            <?php echo wp_kses_post(get_field('field_resource_answer')); ?>
        </div>
    </div>
</div>
