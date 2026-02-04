<?php
/**
 * Archive Template for Case Studies
 */

get_header(); ?>

<div class="rf-archive-case rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-1"></div>

    <div class="rf-container" style="padding: 80px 0;">
        <header class="rf-archive-header" style="text-align: center; margin-bottom: 80px;">
            <span class="rf-badge"><?php _e('Success Stories', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="font-size: 3.5rem; margin: 20px 0;"><?php _e('Our Work in Action', 'rfplugin'); ?></h1>
            <p class="rf-subtitle" style="max-width: 600px; margin: 0 auto;"><?php _e('Explore how we solve complex packaging challenges for industry leaders.', 'rfplugin'); ?></p>
        </header>

        <div class="rf-case-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 40px;">
            <?php if (have_posts()) : while (have_posts()) : the_post(); 
                $client = get_field('field_case_client');
                $industry = get_field('field_case_industry_text');
            ?>
                <article class="rf-case-card" style="position: relative; border-radius: 24px; overflow: hidden; height: 400px; isolation: isolate;">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', ['style' => 'width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: -1; transition: transform 0.6s;']); ?>
                    <?php else : ?>
                        <div style="background: linear-gradient(45deg, #1e293b, #0f172a); width: 100%; height: 100%; position: absolute; inset: 0; z-index: -1;"></div>
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="rf-case-overlay" style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.1)); display: flex; flex-direction: column; justify-content: flex-end; padding: 40px; text-decoration: none; transition: background 0.3s;">
                        <?php if ($industry) : ?>
                            <span class="rf-badge-outline" style="border-color: rgba(255,255,255,0.3); color: white; align-self: flex-start; margin-bottom: 16px;"><?php echo esc_html($industry); ?></span>
                        <?php endif; ?>
                        
                        <h2 class="rf-h3" style="color: white; font-size: 1.75rem; margin-bottom: 8px;"><?php the_title(); ?></h2>
                        
                        <?php if ($client) : ?>
                            <p style="color: #94a3b8; margin: 0;"><?php echo sprintf(__('Client: %s', 'rfplugin'), esc_html($client)); ?></p>
                        <?php endif; ?>
                        
                        <div class="rf-read-more" style="margin-top: 24px; color: var(--rf-primary); font-weight: 700; opacity: 0; transform: translateY(10px); transition: 0.3s;">
                            <?php _e('View Case Study', 'rfplugin'); ?> <span class="dashicons dashicons-arrow-right-alt2"></span>
                        </div>
                    </a>
                </article>
            <?php endwhile; endif; ?>
        </div>
    </div>
    
    <style>
        .rf-case-card:hover img { transform: scale(1.05); }
        .rf-case-card:hover .rf-case-overlay { background: linear-gradient(to top, rgba(0,0,0,0.95), rgba(0,0,0,0.2)); }
        .rf-case-card:hover .rf-read-more { opacity: 1; transform: translateY(0); }
    </style>
</div>

<?php get_footer(); ?>
