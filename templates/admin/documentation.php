<?php
/**
 * Modern RoyalFoam Documentation Template
 * 
 * Optimized for Network Control Center with role-based theming.
 */

defined('ABSPATH') || exit;

use RFPlugin\Admin\SuperAdminTheme;

$doc = $doc ?? 'index';
$doc_file = RFPLUGIN_PATH . "templates/admin/docs/{$doc}.php";

if (!file_exists($doc_file)) {
    $doc_file = RFPLUGIN_PATH . "templates/admin/docs/index.php";
}

$saved_theme = 'dark'; // Default
?>

<div class="rf-admin-wrap" data-rf-theme="<?php echo esc_attr($saved_theme); ?>">
    <header class="rf-admin-header">
        <div class="rf-admin-header__content">
            <div class="rf-admin-header__left">
                <h1 class="rf-admin-header__title">
                    <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                    <?php esc_html_e('Documentation Hub', 'rfplugin'); ?>
                </h1>
                <p class="rf-admin-header__subtitle">
                    <?php esc_html_e('Master your enterprise foam management environment with our guides.', 'rfplugin'); ?>
                </p>
            </div>
            <div class="rf-admin-header__right">
                <button id="rf-theme-toggle" class="rf-admin-btn rf-admin-btn--icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
                <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam')); ?>" class="rf-admin-btn rf-admin-btn--ghost">
                    <span class="dashicons dashicons-dashboard"></span>
                    <?php esc_html_e('Dashboard', 'rfplugin'); ?>
                </a>
            </div>
        </div>
    </header>

    <div class="rf-doc-container">
        <!-- Sidebar Navigation -->
        <aside class="rf-admin-card rf-doc-sidebar">
            <h3 class="rf-admin-card__title rf-admin-mb-4"><?php esc_html_e('Guides', 'rfplugin'); ?></h3>
            <nav class="rf-doc-nav">
                <ul class="rf-doc-nav-list">
                    <li class="rf-admin-mb-2">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs&doc=index')); ?>" class="rf-doc-link <?php echo $doc === 'index' ? 'active' : ''; ?>">
                            <span class="dashicons dashicons-flag"></span>
                            <?php esc_html_e('Overview', 'rfplugin'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs&doc=zoho-crm')); ?>" class="rf-doc-link <?php echo $doc === 'zoho-crm' ? 'active' : ''; ?>">
                            <span class="dashicons dashicons-cloud"></span>
                            <?php esc_html_e('Zoho CRM Sync', 'rfplugin'); ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Documentation Content -->
        <article class="rf-admin-card rf-doc-content rf-admin-p-10">
            <div class="rf-admin-animate-in">
                <?php include $doc_file; ?>
            </div>
        </article>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle
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
