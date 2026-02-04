<?php
/**
 * Single Resource Template
 * 
 * Hybrid template for all resource types.
 */

get_header();

$resource_id = get_the_ID();
$mode = get_field('resource_mode', $resource_id) ?: 'document';
$visibility = get_field('resource_visibility', $resource_id) ?: 'guest';

// Security check based on visibility
if ($visibility !== 'guest' && !current_user_can($visibility) && !current_user_can('administrator')) {
    include RFPLUGIN_PATH . 'templates/frontend/access-denied.php';
    get_footer();
    exit;
}

?>

<div class="rf-resource-single rf-premium-ui rf-mode-<?php echo esc_attr($mode); ?>">
    <!-- Atmospheric Background -->
    <div class="rf-bg-blob rf-bg-blob-1"></div>
    <div class="rf-bg-blob rf-bg-blob-2"></div>

    <div class="rf-container" style="padding: 100px 0;">
        <nav class="rf-breadcrumb">
            <a href="<?php echo get_post_type_archive_link('rf_resource'); ?>"><?php _e('Library', 'rfplugin'); ?></a>
            <span class="sep">/</span>
            <span class="current"><?php echo strtoupper($mode); ?></span>
        </nav>

        <header class="rf-resource-intro" style="margin-bottom: 60px; text-align: center;">
            <span class="rf-badge"><?php echo strtoupper(esc_html($mode)); ?></span>
            <h1 class="rf-title" style="font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 24px;"><?php the_title(); ?></h1>
            <?php if (has_excerpt()): ?>
                <p class="rf-subtitle" style="max-width: 800px; margin: 0 auto;"><?php echo get_the_excerpt(); ?></p>
            <?php endif; ?>
        </header>

        <div class="rf-resource-content rf-glass-card" style="padding: 60px; position: relative; overflow: hidden;">
            <?php 
            $template_slug = 'content-resource';
            $template_name = $mode;

            // Map 'sheet' to 'document' if no specific sheet template exists
            if ($mode === 'sheet') {
                $template_name = 'document';
            }

            // Look for template in theme first, then plugin
            if (locate_template('partials/' . $template_slug . '-' . $template_name . '.php')) {
                get_template_part('partials/' . $template_slug, $template_name);
            } else {
                include RFPLUGIN_PATH . 'templates/frontend/partials/' . $template_slug . '-' . $template_name . '.php';
            }
            ?>
        </div>

        <!-- Sidebar / Meta info area below or aside -->
        <div class="rf-resource-footer" style="margin-top: 60px; display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <div class="rf-related-items">
                <h3 class="rf-h3" style="margin-bottom: 24px;"><?php _e('Applicable Solutions', 'rfplugin'); ?></h3>
                <?php 
                $related = get_field('related_items');
                if ($related): ?>
                    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                        <?php foreach ($related as $item_id): ?>
                            <a href="<?php echo get_permalink($item_id); ?>" class="rf-glass-card" style="padding: 12px 24px; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s;">
                                <span class="dashicons dashicons-share-alt2" style="font-size: 16px; color: var(--rf-primary);"></span>
                                <span style="font-weight: 600; color: white;"><?php echo get_the_title($item_id); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="color: #64748b;"><?php _e('General resource for all systems.', 'rfplugin'); ?></p>
                <?php endif; ?>
            </div>

            <div class="rf-meta-sidebar">
                <div class="rf-glass-card" style="padding: 32px;">
                    <h4 style="margin-top: 0;"><?php _e('Information', 'rfplugin'); ?></h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem;">
                        <li style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <span style="color: #64748b;"><?php _e('Published', 'rfplugin'); ?></span>
                            <span style="color: white;"><?php echo get_the_date(); ?></span>
                        </li>
                        <li style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <span style="color: #64748b;"><?php _e('Last Updated', 'rfplugin'); ?></span>
                            <span style="color: white;"><?php echo get_the_modified_date(); ?></span>
                        </li>
                        <li style="display: flex; justify-content: space-between; padding: 12px 0;">
                            <span style="color: #64748b;"><?php _e('Reference ID', 'rfplugin'); ?></span>
                            <span style="color: white;">#<?php echo $resource_id; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
