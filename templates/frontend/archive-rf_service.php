<?php
/**
 * Archive Template for Services
 */

get_header(); ?>

<div class="rf-archive-service rf-premium-ui">
    <div class="rf-bg-blob rf-bg-blob-2"></div>

    <div class="rf-container" style="padding: 80px 0;">
        <header class="rf-archive-header" style="text-align: center; margin-bottom: 60px;">
            <span class="rf-badge"><?php _e('Our Capabilities', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="font-size: 3.5rem; margin: 20px 0;"><?php _e('Advanced Foam Services', 'rfplugin'); ?></h1>
            <p class="rf-subtitle" style="max-width: 600px; margin: 0 auto;"><?php _e('From rapid prototyping to high-volume manufacturing, we offer end-to-end support for your packaging projects.', 'rfplugin'); ?></p>
        </header>

        <div class="rf-grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
            <?php if (have_posts()) : while (have_posts()) : the_post(); 
                $price = get_field('field_service_price');
                $model = get_field('field_service_pricing_model');
                $model_label = $model ? ucfirst($model) : '';
            ?>
                <article class="rf-glass-card rf-service-card rf-fade-in" style="display: flex; flex-direction: column;">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="rf-card-image" style="height: 200px; overflow: hidden; border-radius: 12px; margin-bottom: 24px;">
                            <?php the_post_thumbnail('medium_large', ['style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;']); ?>
                        </div>
                    <?php endif; ?>

                    <h2 class="rf-h3" style="margin-bottom: 12px; font-size: 1.5rem;">
                        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;"><?php the_title(); ?></a>
                    </h2>
                    
                    <div class="rf-excerpt" style="margin-bottom: 24px; color: #94a3b8; line-height: 1.6; flex-grow: 1;">
                        <?php the_excerpt(); ?>
                    </div>

                    <div class="rf-card-footer" style="padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center;">
                        <?php if ($price) : ?>
                            <div class="rf-price">
                                <span style="font-size: 1.25rem; font-weight: 700; color: white;">€<?php echo number_format((float)$price, 2); ?></span>
                                <?php if ($model_label) : ?>
                                    <span style="font-size: 0.8rem; color: #64748b;">/ <?php echo esc_html($model_label); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <span style="font-size: 0.9rem; color: #64748b;"><?php _e('Custom Pricing', 'rfplugin'); ?></span>
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="rf-btn rf-btn-sm rf-btn-outline">
                            <?php _e('Learn More', 'rfplugin'); ?>
                        </a>
                    </div>
                </article>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
