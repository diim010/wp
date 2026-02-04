<?php
/**
 * Single Case Study Template
 */

get_header(); 

$client = get_field('field_case_client');
$industry = get_field('field_case_industry_text');
$challenge = get_field('field_case_challenge');
$solution = get_field('field_case_solution');
$result = get_field('field_case_result');
?>

<div class="rf-single-case rf-premium-ui">
    <!-- Hero Section -->
    <div class="rf-case-hero" style="position: relative; height: 70vh; min-height: 600px; display: flex; align-items: flex-end; overflow: hidden;">
        <?php if (has_post_thumbnail()) : ?>
            <div class="rf-hero-bg" style="position: absolute; inset: 0; z-index: 1;">
                <?php the_post_thumbnail('full', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
            </div>
        <?php endif; ?>
        
        <div class="rf-overlay" style="position: absolute; inset: 0; background: linear-gradient(to top, #0f172a, rgba(15, 23, 42, 0.4)); z-index: 2;"></div>

        <div class="rf-container" style="position: relative; z-index: 3; width: 100%; padding-bottom: 80px;">
            <div class="rf-hero-content" style="max-width: 800px;">
                <?php if ($industry) : ?>
                    <span class="rf-badge" style="margin-bottom: 24px;"><?php echo esc_html($industry); ?></span>
                <?php endif; ?>
                
                <h1 class="rf-title" style="font-size: clamp(3rem, 6vw, 5rem); line-height: 1.1; margin-bottom: 24px;"><?php the_title(); ?></h1>
                
                <?php if ($client) : ?>
                    <div class="rf-client-info" style="display: flex; align-items: center; gap: 16px; font-size: 1.25rem; color: #cbd5e1;">
                        <span class="dashicons dashicons-businesswoman" style="font-size: 24px;"></span>
                        <?php echo sprintf(__('Solution for %s', 'rfplugin'), '<strong>' . esc_html($client) . '</strong>'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="rf-container" style="padding: 100px 0;">
        <div class="rf-case-layout" style="display: grid; grid-template-columns: 1fr 300px; gap: 80px;">
            
            <main class="rf-main-content">
                <?php if ($challenge || $solution || $result) : ?>
                    <!-- Structured Case Data -->
                     <div class="rf-case-segments" style="display: flex; flex-direction: column; gap: 60px;">
                        <?php if ($challenge) : ?>
                            <section class="rf-segment rf-fade-in" style="animation-delay: 0.1s;">
                                <h2 class="rf-h3" style="color: var(--rf-primary); margin-bottom: 24px;"><?php _e('The Challenge', 'rfplugin'); ?></h2>
                                <div class="rf-text-lg" style="font-size: 1.25rem; line-height: 1.8; color: #cbd5e1;">
                                    <?php echo wp_kses_post($challenge); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php if ($solution) : ?>
                            <section class="rf-segment rf-fade-in" style="animation-delay: 0.2s;">
                                <h2 class="rf-h3" style="color: var(--rf-primary); margin-bottom: 24px;"><?php _e('Our Solution', 'rfplugin'); ?></h2>
                                <div class="rf-text-lg" style="font-size: 1.25rem; line-height: 1.8; color: #cbd5e1;">
                                    <?php echo wp_kses_post($solution); ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php if ($result) : ?>
                            <section class="rf-segment rf-fade-in" style="animation-delay: 0.3s;">
                                <div class="rf-glass-card" style="padding: 40px; border-left: 4px solid var(--rf-green);">
                                    <h2 class="rf-h3" style="color: var(--rf-green); margin-bottom: 24px;"><?php _e('Key Results', 'rfplugin'); ?></h2>
                                    <div class="rf-text-lg" style="font-size: 1.25rem; line-height: 1.8; color: white;">
                                        <?php echo wp_kses_post($result); ?>
                                    </div>
                                </div>
                            </section>
                        <?php endif; ?>
                     </div>
                <?php else : ?>
                    <!-- Fallback to standard content if no structured fields -->
                    <div class="rf-content-body rf-text-lg" style="font-size: 1.125rem; line-height: 1.8; color: #cbd5e1;">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            </main>

            <aside class="rf-sidebar">
                <div class="rf-glass-card rf-sticky-widget" style="padding: 32px; position: sticky; top: 120px;">
                    <h3 class="rf-h4" style="margin-bottom: 24px;"><?php _e('Project Stats', 'rfplugin'); ?></h3>
                    
                    <ul class="rf-stats-list" style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 20px;">
                            <span style="display: block; font-size: 0.8rem; color: #94a3b8; margin-bottom: 4px;"><?php _e('Date', 'rfplugin'); ?></span>
                            <span style="font-size: 1.1rem; color: white;"><?php echo get_the_date(); ?></span>
                        </li>
                        <?php if ($industry) : ?>
                        <li style="margin-bottom: 20px;">
                            <span style="display: block; font-size: 0.8rem; color: #94a3b8; margin-bottom: 4px;"><?php _e('Industry', 'rfplugin'); ?></span>
                            <span style="font-size: 1.1rem; color: white;"><?php echo esc_html($industry); ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <!-- Related Services lookup could go here -->
                    </ul>

                    <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <a href="<?php echo get_post_type_archive_link('rf_service'); ?>" class="rf-btn rf-btn-primary rf-btn-block" style="width: 100%; justify-content: center;">
                            <?php _e('View Our Services', 'rfplugin'); ?>
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <!-- Next Case Navigation -->
    <?php 
    $next_post = get_next_post();
    if ($next_post) : 
        $next_img = get_the_post_thumbnail_url($next_post->ID, 'large');
    ?>
    <a href="<?php echo get_permalink($next_post->ID); ?>" class="rf-next-case" style="display: block; position: relative; height: 300px; overflow: hidden; text-decoration: none;">
        <?php if ($next_img) : ?>
            <div style="position: absolute; inset: 0; background-image: url('<?php echo esc_url($next_img); ?>'); background-size: cover; background-position: center; filter: grayscale(100%) brightness(0.5); transition: 0.5s;"></div>
        <?php else : ?>
            <div style="position: absolute; inset: 0; background: #0f172a;"></div>
        <?php endif; ?>
        
        <div class="rf-container" style="position: relative; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center; z-index: 2;">
            <div>
                <span style="display: block; font-size: 1rem; color: #94a3b8; margin-bottom: 12px; letter-spacing: 2px; text-transform: uppercase;"><?php _e('Next Project', 'rfplugin'); ?></span>
                <h2 style="font-size: 3rem; color: white; margin: 0;"><?php echo get_the_title($next_post->ID); ?></h2>
            </div>
        </div>
        
        <style>
            .rf-next-case:hover div[style*="background-image"] { filter: grayscale(0%) brightness(0.7); transform: scale(1.05); }
        </style>
    </a>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
