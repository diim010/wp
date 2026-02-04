<?php
/**
 * Archive Template for Technical Documentation Center
 * 
 * @package RFPlugin
 */

get_header();

$current_user = wp_get_current_user();
?>

<div class="rf-doc-center rf-premium-ui">
    <div class="rf-container">
        <!-- Decorative Elements -->
        <div class="rf-blob rf-blob-1" style="width: 600px; height: 600px; top: -150px; left: -150px; background: hsla(215, 90%, 50%, 0.05);"></div>
        <div class="rf-blob rf-blob-2" style="width: 400px; height: 400px; bottom: -100px; right: -100px; background: hsla(150, 70%, 50%, 0.03);"></div>

        <!-- Hero Section -->
        <header class="rf-hero-section" style="margin-bottom: 80px; text-align: center; position: relative; z-index: 10;">
            <span class="rf-badge" style="margin-bottom: 24px;"><?php _e('Engineering Resources', 'rfplugin'); ?></span>
            <h1 class="rf-title" style="margin-bottom: 24px; font-size: clamp(3rem, 8vw, 5rem);"><?php _e('Technical Documentation', 'rfplugin'); ?></h1>
            <p style="font-size: 1.25rem; color: var(--rf-text-muted); max-width: 700px; margin: 0 auto; line-height: 1.6;">
                <?php _e('Access our centralized vault of professional manuals, technical drawings, and security certifications. Looking for support articles?', 'rfplugin'); ?>
                <a href="<?php echo get_permalink(get_page_by_path('technical-center')); ?>" style="color: var(--rf-primary); font-weight: 800; text-decoration: none; border-bottom: 2px solid var(--rf-primary-light);"><?php _e('Technical Center', 'rfplugin'); ?></a>.
            </p>
        </header>

        <!-- Search & Filters -->
        <div class="rf-search-bar" style="margin-bottom: 60px; max-width: 900px; margin-left: auto; margin-right: auto; flex-direction: row; gap: 16px;">
            <div style="position: relative; flex-grow: 1;">
                <span class="dashicons dashicons-search" style="position: absolute; left: 24px; top: 50%; transform: translateY(-50%); color: var(--rf-text-muted); font-size: 20px; width: 20px; height: 20px;"></span>
                <input type="text" id="rf-doc-search" placeholder="<?php _e('Search technical archives...', 'rfplugin'); ?>" style="width: 100%; padding: 20px 20px 20px 64px; border-radius: 20px; border: 1px solid #f1f5f9; font-size: 1.1rem; box-shadow: var(--rf-shadow-premium); background: white;">
            </div>
            
            <div class="rf-filter-dropdown" style="display: block; width: 250px;">
                <select id="filter-type" style="width: 100%; padding: 20px; border-radius: 20px; border: 1px solid #f1f5f9; font-weight: 700; color: #1e293b; appearance: none; background: white url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fxml%3E') no-repeat right 20px center; background-size: 12px; box-shadow: var(--rf-shadow-premium);">
                    <option value=""><?php _e('All Documents', 'rfplugin'); ?></option>
                    <?php
                    $types = [
                        'manual' => __('User Manuals', 'rfplugin'),
                        'datasheet' => __('Data Sheets', 'rfplugin'),
                        'guide' => __('Installation Guides', 'rfplugin'),
                        'drawing' => __('Technical Drawings', 'rfplugin'),
                        'certificate' => __('Certification', 'rfplugin'),
                    ];
                    foreach ($types as $val => $label) {
                        echo '<option value="' . esc_attr($val) . '">' . esc_html($label) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Documents Grid -->
        <div id="rf-doc-results" class="rf-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 32px;">
            <?php 
            $i = 0;
            if (have_posts()): while (have_posts()): the_post(); 
                $doc_id = get_the_ID();
                if (!\RFPlugin\Security\Permissions::canViewTechDoc($doc_id)) continue;

                $file_data = get_field('field_tech_doc_file', $doc_id);
                $file_url = is_array($file_data) ? ($file_data['url'] ?? '') : (string)$file_data;
                $file_type = get_field('file_type', $doc_id) ?: 'document';
                $thumbnail = get_the_post_thumbnail_url($doc_id, 'medium') ?: RFPLUGIN_URL . 'assets/images/doc-placeholder.png';
                $tags = wp_get_post_terms($doc_id, 'rf_techdoc_tag', ['fields' => 'names']);
                $delay = ($i % 6) * 0.1;
            ?>
                <article class="rf-card" 
                         style="animation: rfFadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: <?php echo $delay; ?>s;"
                         data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>" 
                         data-type="<?php echo esc_attr($file_type); ?>"
                         data-tags="<?php echo esc_attr(strtolower(implode(' ', $tags))); ?>">
                    
                    <div class="rf-card-icon">
                        <span class="dashicons dashicons-pdf" aria-hidden="true"></span>
                    </div>
                    
                    <h3 class="rf-card-title"><?php the_title(); ?></h3>
                    <div class="rf-card-excerpt">
                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                    </div>
                    
                    <div class="rf-card-footer">
                        <div class="rf-card-meta">
                            <span style="background: var(--rf-primary-light); color: var(--rf-primary); padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;">
                                <?php echo strtoupper(esc_html($file_type)); ?>
                            </span>
                        </div>
                        <?php 
                        $download_url = rest_url('rfplugin/v1/techdocs/' . $doc_id . '/download');
                        $secure_download_url = add_query_arg('_wpnonce', wp_create_nonce('wp_rest'), $download_url);
                        ?>
                        <div style="display: flex; gap: 8px;">
                            <button class="rf-btn rf-download-trigger" 
                                    data-url="<?php echo esc_url($secure_download_url); ?>" 
                                    style="border: none; cursor: pointer; padding: 10px 16px;">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <a href="<?php the_permalink(); ?>" class="rf-btn" style="padding: 10px 16px;">
                                <span class="dashicons dashicons-arrow-right-alt2"></span>
                            </a>
                        </div>
                    </div>
                </article>
            <?php 
                $i++;
                endwhile; else: 
            ?>
                <div class="rf-empty-state" style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                    <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <span class="dashicons dashicons-category" style="font-size: 32px; width: 32px; height: 32px; color: #94a3b8;"></span>
                    </div>
                    <h4 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 12px;"><?php _e('No documents found', 'rfplugin'); ?></h4>
                    <p style="font-size: 1.1rem; color: #64748b; margin: 0;"><?php _e('Try adjusting your search filters or browse other categories.', 'rfplugin'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div id="rf-toast-root" class="rf-toast-container"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('rf-doc-search');
    const typeFilter = document.getElementById('filter-type');
    const cards = document.querySelectorAll('.rf-doc-item');
    const toastRoot = document.getElementById('rf-toast-root');

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `rf-toast ${type === 'error' ? 'is-error' : ''}`;
        toast.innerHTML = `
            <span class="dashicons dashicons-${type === 'error' ? 'warning' : 'yes'}"></span>
            <span>${message}</span>
        `;
        toastRoot.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function filterDocs() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;

        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            const type = card.getAttribute('data-type');
            const tags = card.getAttribute('data-tags');
            
            const matchesSearch = title.includes(searchTerm) || tags.includes(searchTerm);
            const matchesType = !selectedType || type === selectedType;

            if (matchesSearch && matchesType) {
                card.style.display = 'flex';
                card.style.animation = 'none';
                card.offsetHeight; /* trigger reflow */
                card.style.animation = 'rfFadeUp 0.4s ease-out forwards';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterDocs);
    if (typeFilter) typeFilter.addEventListener('change', filterDocs);

    // Secure Download Handler
    document.querySelectorAll('.rf-download-trigger').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const icon = this.querySelector('.dashicons');
            const originalIconClass = icon.className;

            this.classList.add('is-loading');
            icon.className = 'dashicons dashicons-update';

            fetch(url, { method: 'HEAD' })
                .then(response => {
                    if (response.ok) {
                        showToast('<?php _e('Download started...', 'rfplugin'); ?>');
                        window.location.href = url;
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.message || '<?php _e('Permission denied.', 'rfplugin'); ?>');
                        });
                    }
                })
                .catch(error => {
                    showToast(error.message, 'error');
                })
                .finally(() => {
                    setTimeout(() => {
                        this.classList.remove('is-loading');
                        icon.className = originalIconClass;
                    }, 800);
                });
        });
    });
});
</script>

<?php get_footer(); ?>
