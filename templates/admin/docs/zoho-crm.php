<?php
/**
 * Zoho CRM Tutorial
 */
?>
<h2 class="rf-h2"><?php esc_html_e('Zoho CRM Integration Guide', 'rfplugin'); ?></h2>
<p class="rf-p"><?php esc_html_e('This tutorial will guide you through the process of connecting RoyalFoam to your Zoho CRM account using OAuth2.', 'rfplugin'); ?></p>

<section class="rf-doc-section">
    <div class="rf-step">
        <div class="rf-step-num">1</div>
        <div class="rf-step-content">
            <h3 style="margin-top: 0;"><?php esc_html_e('Create Zoho API Console Client', 'rfplugin'); ?></h3>
            <p><?php esc_html_e('Go to the Zoho API Console (api-console.zoho.com) and click "Add Client". Choose "Server-based Applications".', 'rfplugin'); ?></p>
            <ul>
                <li><strong>Client Name:</strong> RoyalFoam Website</li>
                <li><strong>Homepage URL:</strong> <code><?php echo esc_url(home_url()); ?></code></li>
                <li><strong>Authorized Redirect URIs:</strong> <code><?php echo esc_url(admin_url('admin.php?page=royalfoam-settings#zoho')); ?></code></li>
            </ul>
        </div>
    </div>

    <div class="rf-step">
        <div class="rf-step-num">2</div>
        <div class="rf-step-content">
            <h3><?php esc_html_e('Generate Refresh Token', 'rfplugin'); ?></h3>
            <p><?php esc_html_e('After creating the client, go to the "Self Client" tab to generate a code with the scope:', 'rfplugin'); ?></p>
            <code>ZohoCRM.modules.ALL, ZohoCRM.settings.ALL</code>
            <p style="margin-top: 16px;"><?php esc_html_e('Exchange this code for a Refresh Token using your Client ID and Client Secret via a tool like Postman or a simple CURL request.', 'rfplugin'); ?></p>
        </div>
    </div>

    <div class="rf-step">
        <div class="rf-step-num">3</div>
        <div class="rf-step-content">
            <h3><?php esc_html_e('Configure Plugin Settings', 'rfplugin'); ?></h3>
            <p><?php esc_html_e('Navigate to the Zoho CRM tab in the plugin settings and enter your credentials:', 'rfplugin'); ?></p>
            <ul>
                <li><strong>Client ID:</strong> <?php esc_html_e('Obtained from Zoho API Console', 'rfplugin'); ?></li>
                <li><strong>Client Secret:</strong> <?php esc_html_e('Obtained from Zoho API Console', 'rfplugin'); ?></li>
                <li><strong>Refresh Token:</strong> <?php esc_html_e('The permanent token generated in Step 2', 'rfplugin'); ?></li>
            </ul>
        </div>
    </div>

    <div class="rf-step" style="margin-bottom: 0;">
        <div class="rf-step-num">4</div>
        <div class="rf-step-content">
            <h3><?php esc_html_e('Verify Synchronization', 'rfplugin'); ?></h3>
            <p><?php esc_html_e('Once saved, invoices generated on the website will automatically sync to your Zoho CRM as Leads.', 'rfplugin'); ?></p>
            <div style="background: #fff7ed; padding: 16px; border-radius: 12px; border: 1px solid #ffedd5; color: #9a3412; font-size: 14px;">
                <strong>Note:</strong> Ensure your Zoho CRM environment (US, EU, etc.) matches the API endpoint configured in the sync engine.
            </div>
        </div>
    </div>
</section>
