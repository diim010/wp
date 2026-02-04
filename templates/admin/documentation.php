<?php
/**
 * Modern RoyalFoam Documentation Template
 */

$doc = $doc ?? 'index';
$doc_file = RFPLUGIN_PATH . "templates/admin/docs/{$doc}.php";

if (!file_exists($doc_file)) {
    $doc_file = RFPLUGIN_PATH . "templates/admin/docs/index.php";
}
?>
<div class="wrap rf-admin-wrap">
    <header class="rf-dashboard-header rf-fade-in">
        <div class="rf-header-content">
            <h1 class="rf-h1"><?php esc_html_e('Documentation & Tutorials', 'rfplugin'); ?></h1>
            <p class="rf-p"><?php esc_html_e('Learn how to master your enterprise foam management environment.', 'rfplugin'); ?></p>
        </div>
        <div class="rf-header-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam')); ?>" class="rf-btn rf-btn-outline">
                <span class="dashicons dashicons-dashboard" style="margin-right: 8px;"></span>
                <?php esc_html_e('Return to Dashboard', 'rfplugin'); ?>
            </a>
        </div>
    </header>

    <div class="rf-doc-container rf-grid rf-fade-in" style="animation-delay: 0.1s; display: grid; grid-template-columns: 280px 1fr; gap: 40px; align-items: start;">
        <!-- Sidebar Navigation -->
        <aside class="rf-glass-card rf-doc-sidebar" style="position: sticky; top: 32px; padding: 24px;">
            <h3 class="rf-h3" style="margin-bottom: 20px; font-size: 14px; text-transform: uppercase; color: var(--rf-neutral-400); letter-spacing: 0.05em;">
                <?php esc_html_e('Getting Started', 'rfplugin'); ?>
            </h3>
            <ul class="rf-doc-nav-list" style="list-style: none; padding: 0; margin: 0 0 32px 0;">
                <li style="margin-bottom: 12px;">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs&doc=index')); ?>" class="rf-doc-link <?php echo $doc === 'index' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-flag" style="margin-right: 10px; font-size: 18px;"></span>
                        <?php esc_html_e('Overview', 'rfplugin'); ?>
                    </a>
                </li>
            </ul>

            <h3 class="rf-h3" style="margin-bottom: 20px; font-size: 14px; text-transform: uppercase; color: var(--rf-neutral-400); letter-spacing: 0.05em;">
                <?php esc_html_e('Integrations', 'rfplugin'); ?>
            </h3>
            <ul class="rf-doc-nav-list" style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 12px;">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=royalfoam-docs&doc=zoho-crm')); ?>" class="rf-doc-link <?php echo $doc === 'zoho-crm' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-cloud" style="margin-right: 10px; font-size: 18px;"></span>
                        <?php esc_html_e('Zoho CRM Sync', 'rfplugin'); ?>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Documentation Content -->
        <article class="rf-glass-card rf-doc-content" style="padding: 48px; min-height: 600px;">
            <?php include $doc_file; ?>
        </article>
    </div>
</div>

<style>
    .rf-doc-link {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        color: var(--rf-neutral-600);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .rf-doc-link:hover {
        background: var(--rf-neutral-50);
        color: var(--rf-primary);
    }
    .rf-doc-link.active {
        background: var(--rf-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }
    
    .rf-doc-content h2 { margin-top: 0; font-size: 2.25rem; color: var(--rf-neutral-900); }
    .rf-doc-content h3 { margin-top: 40px; font-size: 1.5rem; color: var(--rf-neutral-800); }
    .rf-doc-content p { font-size: 1.125rem; line-height: 1.75; color: var(--rf-neutral-600); margin-bottom: 24px; }
    .rf-doc-content ul { padding-left: 24px; margin-bottom: 24px; }
    .rf-doc-content li { margin-bottom: 12px; font-size: 1.125rem; color: var(--rf-neutral-600); }
    .rf-doc-content code { background: var(--rf-neutral-100); padding: 4px 8px; border-radius: 4px; font-family: monospace; color: var(--rf-primary); }
    
    .rf-step { display: flex; gap: 20px; margin-bottom: 32px; }
    .rf-step-num { 
        width: 32px; height: 32px; border-radius: 50%; background: var(--rf-primary); color: white; 
        display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;
    }

    @media (max-width: 1024px) {
        .rf-doc-container { grid-template-columns: 1fr !important; }
        .rf-doc-sidebar { position: relative !important; top: 0 !important; }
    }
</style>
