<?php
/**
 * Frontend Quote Form
 * 
 * Adds "Request Quote" button to product pages and handles the modal form.
 * 
 * @package RFPlugin\Frontend
 * @since 1.0.0
 */

namespace RFPlugin\Frontend;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * QuoteForm class
 * 
 * @since 1.0.0
 */
class QuoteForm
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('woocommerce_single_product_summary', [$this, 'addQuoteButton'], 35);
        add_action('wp_footer', [$this, 'renderModalForm']);
    }

    /**
     * Add "Request Quote" button
     * 
     * @return void
     */
    public function addQuoteButton(): void
    {
        echo '<button type="button" id="rf-request-quote-btn" class="rf-btn rf-btn-primary" style="margin-top: 20px; width: 100%;">';
        echo '<span class="dashicons dashicons-email" style="margin-right: 8px;"></span>';
        echo esc_html__('Request a Quote', 'rfplugin');
        echo '</button>';
    }

    /**
     * Render the modal form
     * 
     * @return void
     */
    public function renderModalForm(): void
    {
        if (!is_product()) {
            return;
        }

        ?>
        <div id="rf-quote-modal" class="rf-modal" style="display: none;">
            <div class="rf-modal-content rf-glass-card">
                <span class="rf-modal-close">&times;</span>
                <h2 class="rf-h2"><?php esc_html_e('Request a Quote', 'rfplugin'); ?></h2>
                <p class="rf-p"><?php esc_html_e('Fill out the form below and we will get back to you with a personalized offer.', 'rfplugin'); ?></p>
                
                <form id="rf-quote-form" class="rf-form" style="margin-top: 24px;">
                    <input type="hidden" name="source_url" value="<?php echo esc_url(get_permalink()); ?>">
                    <input type="hidden" name="product_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                    <input type="hidden" name="form_id" value="product_quote">
                    
                    <div class="rf-field-group" style="margin-bottom: 16px;">
                        <label class="rf-label"><?php esc_html_e('Full Name', 'rfplugin'); ?></label>
                        <input type="text" name="customer_name" class="rf-input" required>
                    </div>
                    
                    <div class="rf-field-group" style="margin-bottom: 16px;">
                        <label class="rf-label"><?php esc_html_e('Email Address', 'rfplugin'); ?></label>
                        <input type="email" name="customer_email" class="rf-input" required>
                    </div>
                    
                    <div class="rf-field-group" style="margin-bottom: 16px;">
                        <label class="rf-label"><?php esc_html_e('Phone Number', 'rfplugin'); ?></label>
                        <input type="text" name="customer_phone" class="rf-input">
                    </div>
                    
                    <div class="rf-field-group" style="margin-bottom: 24px;">
                        <label class="rf-label"><?php esc_html_e('Your Message', 'rfplugin'); ?></label>
                        <textarea name="form_message" class="rf-textarea" rows="4"></textarea>
                    </div>
                    
                    <button type="submit" class="rf-btn rf-btn-primary" style="width: 100%;">
                        <?php esc_html_e('Send Request', 'rfplugin'); ?>
                    </button>
                    
                    <div id="rf-quote-response" style="margin-top: 16px; display: none;"></div>
                </form>
            </div>
        </div>

        <style>
            .rf-modal {
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(4px);
            }
            .rf-modal-content {
                width: 100%;
                max-width: 500px;
                position: relative;
                padding: 40px;
            }
            .rf-modal-close {
                position: absolute;
                right: 20px;
                top: 20px;
                font-size: 24px;
                cursor: pointer;
                color: var(--rf-neutral-400);
            }
            .rf-input, .rf-textarea {
                width: 100%;
                padding: 12px;
                border: 1px solid var(--rf-neutral-200);
                border-radius: 8px;
                background: white;
            }
            .rf-label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: var(--rf-neutral-800);
            }
        </style>
        <?php
    }
}
