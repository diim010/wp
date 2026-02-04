<?php
/**
 * Archive Template for Resources
 * 
 * Acts as a server-side accessible view of the Technical Center.
 */

// If a dedicated page exists for the Tech Center, we could redirect there.
// For now, we render a premium archive view.

get_header(); ?>

<div class="rf-archive-resource rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-2"></div>

    <div class="rf-container" style="padding: 80px 0;">
        <header class="rf-archive-header" style="text-align: center; margin-bottom: 60px;">
            <span class="rf-badge"><?php _e('Support Hub', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="font-size: 3.5rem; margin: 20px 0;"><?php _e('Resource Library', 'rfplugin'); ?></h1>
            
            <p class="rf-subtitle" style="max-width: 600px; margin: 0 auto; margin-bottom: 40px;">
                <?php _e('Unified access to technical manuals, video guides, FAQs, and 3D specifications.', 'rfplugin'); ?>
            </p>

            <!-- Filter Navigation -->
            <nav class="rf-pill-nav" style="display: inline-flex; background: rgba(255,255,255,0.05); padding: 6px; border-radius: 99px; border: 1px solid rgba(255,255,255,0.1);">
                <a href="<?php echo get_post_type_archive_link('rf_resource'); ?>" class="rf-pill active" style="padding: 10px 24px; border-radius: 99px; text-decoration: none; color: white; background: var(--rf-primary); font-weight: 600;"><?php _e('All', 'rfplugin'); ?></a>
                
                <?php
                $types = ['faq' => 'FAQs', 'document' => 'Documents', 'video' => 'Videos', '3d' => '3D Models'];
                foreach ($types as $slug => $label) : 
                    // Note: Ideally we link to taxonomy terms, but here we simulate filters or link to filtered URL params
                    $url = add_query_arg('mode', $slug, get_post_type_archive_link('rf_resource'));
                ?>
                    <a href="<?php echo esc_url($url); ?>" class="rf-pill" style="padding: 10px 24px; border-radius: 99px; text-decoration: none; color: #cbd5e1; transition: 0.3s;"><?php echo esc_html($label); ?></a>
                <?php endforeach; ?>
            </nav>
        </header>

        <div class="rf-resource-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            <?php if (have_posts()) : while (have_posts()) : the_post(); 
                $mode = get_field('field_resource_mode') ?: 'document';
                $icon = 'media-document';
                $action_text = __('View Resource', 'rfplugin');

                switch($mode) {
                    case 'faq': $icon = 'editor-help'; $action_text = __('Read FAQ', 'rfplugin'); break;
                    case 'video': $icon = 'video-alt3'; $action_text = __('Watch Video', 'rfplugin'); break;
                    case '3d': $icon = 'visibility'; $action_text = __('View Model', 'rfplugin'); break;
                }
            ?>
                <article class="rf-resource-card rf-glass-card rf-fade-in" style="padding: 30px; display: flex; flex-direction: column; align-items: flex-start;">
                    <div class="rf-card-icon" style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--rf-primary); margin-bottom: 24px;">
                        <span class="dashicons dashicons-<?php echo esc_attr($icon); ?>" style="font-size: 24px;"></span>
                    </div>

                    <span class="rf-badge-outline" style="font-size: 10px; margin-bottom: 12px; padding: 4px 8px;"><?php echo strtoupper($mode); ?></span>

                    <h2 class="rf-h4" style="font-size: 1.25rem; margin: 0 0 12px; color: white;">
                        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;"><?php the_title(); ?></a>
                    </h2>

                    <div class="rf-excerpt" style="font-size: 0.95rem; color: #94a3b8; line-height: 1.6; margin-bottom: 24px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php the_excerpt(); ?>
                    </div>

                    <a href="<?php the_permalink(); ?>" class="rf-btn-link" style="margin-top: auto; color: var(--rf-primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <?php echo esc_html($action_text); ?> <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                </article>
            <?php endwhile; else : ?>
                <div class="rf-empty" style="grid-column: 1/-1; text-align: center; padding: 60px; color: #64748b;">
                    <p><?php _e('No resources found matching your criteria.', 'rfplugin'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="rf-pagination" style="margin-top: 60px; display: flex; justify-content: center; gap: 8px;">
            <?php
            echo paginate_links([
                'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
                'next_text' => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
                'mid_size'  => 2,
            ]);
            ?>
        </div>
    </div>
</div>

<style>
    .rf-pagination .page-numbers {
        display: flex; width: 40px; height: 40px; align-items: center; justify-content: center;
        border-radius: 8px; background: rgba(255,255,255,0.05); color: white; text-decoration: none; transition: 0.3s;
    }
    .rf-pagination .page-numbers.current, .rf-pagination .page-numbers:hover {
        background: var(--rf-primary);
    }
</style>

<?php get_footer(); ?>
