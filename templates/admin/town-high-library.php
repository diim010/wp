<?php
/**
 * Town High Design System - Component Library
 * 
 * Showcases all standard elements, buttons, and form controls
 * available in the Town High library.
 */

defined('ABSPATH') || exit;

use RFPlugin\Admin\SuperAdminTheme;

$saved_theme = 'dark'; // Default for Super Admin
?>

<div class="rf-admin-wrap" data-rf-theme="<?php echo esc_attr($saved_theme); ?>">
    
    <header class="rf-admin-header">
        <div class="rf-admin-header__content">
            <div class="rf-admin-header__left">
                <h1 class="rf-admin-header__title">
                    <span class="dashicons dashicons-layout" aria-hidden="true"></span>
                    <?php esc_html_e('Town High Library', 'rfplugin'); ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php esc_html_e('Unified design system components for premium enterprise interfaces.', 'rfplugin'); ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
            </div>
        </div>
    </header>

    <div class="rf-admin-grid rf-admin-grid-2">
        
        <!-- Typography System -->
        <section class="rf-admin-card">
            <h2 class="rf-admin-card__title rf-admin-mb-6"><?php esc_html_e('Typography & Content', 'rfplugin'); ?></h2>
            
            <div class="rf-admin-mb-8">
                <p class="th-p">
                    <strong>.th-p</strong>: <?php esc_html_e('This is a standard paragraph styled within the Town High library. It features optimized line-height and spacing for long-form reading.', 'rfplugin'); ?>
                </p>
                <p class="th-text-lead"><?php esc_html_e('Lead Text: Used for important introductory sections.', 'rfplugin'); ?></p>
                <p class="th-text-small"><?php esc_html_e('Small Muted Text: Used for labels, meta information, and secondary notes.', 'rfplugin'); ?></p>
            </div>

            <h3 class="th-label"><?php esc_html_e('Standard Unordered List (.th-list)', 'rfplugin'); ?></h3>
            <ul class="th-list">
                <li><?php esc_html_e('High-fidelity HSL design tokens', 'rfplugin'); ?></li>
                <li><?php esc_html_e('Glassmorphic dark theme components', 'rfplugin'); ?></li>
                <li><?php esc_html_e('Scalable CSS architecture', 'rfplugin'); ?></li>
            </ul>
        </section>

        <!-- Button System -->
        <section class="rf-admin-card">
            <h2 class="rf-admin-card__title rf-admin-mb-6"><?php esc_html_e('Button Variants', 'rfplugin'); ?></h2>
            
            <div class="rf-admin-flex rf-admin-flex-wrap rf-admin-gap-4 rf-admin-mb-8">
                <button class="th-btn th-btn--primary">
                    <?php esc_html_e('Primary Action', 'rfplugin'); ?>
                </button>
                <button class="th-btn th-btn--secondary">
                    <?php esc_html_e('Secondary', 'rfplugin'); ?>
                </button>
                <button class="th-btn th-btn--ghost">
                    <?php esc_html_e('Ghost Button', 'rfplugin'); ?>
                </button>
                <button class="th-btn th-btn--secondary th-btn--icon">
                    <span class="dashicons dashicons-admin-settings"></span>
                </button>
            </div>

            <h3 class="th-label"><?php esc_html_e('States (Hover / Focus / Disabled)', 'rfplugin'); ?></h3>
            <div class="rf-admin-flex rf-admin-gap-4">
                <button class="th-btn th-btn--primary" style="opacity: 0.6; cursor: not-allowed;" disabled>
                    <?php esc_html_e('Disabled State', 'rfplugin'); ?>
                </button>
            </div>
        </section>

        <!-- Form Elements -->
        <section class="rf-admin-card rf-admin-grid-span-2">
            <h2 class="rf-admin-card__title rf-admin-mb-6"><?php esc_html_e('Interactive Form Controls', 'rfplugin'); ?></h2>
            
            <div class="rf-admin-grid rf-admin-grid-3 rf-admin-gap-x-10">
                
                <div class="th-form-group">
                    <label class="th-label"><?php esc_html_e('Standard Input Field', 'rfplugin'); ?></label>
                    <input type="text" class="th-input" placeholder="<?php esc_attr_e('Enter text...', 'rfplugin'); ?>">
                    <p class="th-text-small rf-admin-mt-2"><?php esc_html_e('Focus to see the glow effect.', 'rfplugin'); ?></p>
                </div>

                <div class="th-form-group">
                    <label class="th-label"><?php esc_html_e('Custom Select', 'rfplugin'); ?></label>
                    <select class="th-select">
                        <option value=""><?php esc_html_e('Select an option...', 'rfplugin'); ?></option>
                        <option value="1"><?php esc_html_e('Option Alpha', 'rfplugin'); ?></option>
                        <option value="2"><?php esc_html_e('Option Beta', 'rfplugin'); ?></option>
                    </select>
                </div>

                <div class="th-form-group">
                    <label class="th-label"><?php esc_html_e('Toggle Switch', 'rfplugin'); ?></label>
                    <label class="th-toggle">
                        <input type="checkbox" checked>
                        <span class="th-toggle-slider"></span>
                        <span class="rf-admin-text-secondary"><?php esc_html_e('Enable System Alerts', 'rfplugin'); ?></span>
                    </label>
                </div>

                <div class="th-form-group rf-admin-grid-span-3">
                    <label class="th-label"><?php esc_html_e('Formatted Textarea', 'rfplugin'); ?></label>
                    <textarea class="th-textarea" rows="4" placeholder="<?php esc_attr_e('Write your management protocols here...', 'rfplugin'); ?>"></textarea>
                </div>

            </div>
        </section>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared theme toggle logic
    const toggle = document.getElementById('rf-theme-toggle');
    const wrap = document.querySelector('.rf-admin-wrap');
    if (toggle && wrap) {
        toggle.addEventListener('click', () => {
            const current = wrap.dataset.rfTheme || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            wrap.dataset.rfTheme = next;
            document.documentElement.dataset.rfTheme = next;
            localStorage.setItem('rf-admin-theme', next);
        });
        
        const saved = localStorage.getItem('rf-admin-theme') || 'dark';
        wrap.dataset.rfTheme = saved;
        document.documentElement.dataset.rfTheme = saved;
    }
});
</script>
