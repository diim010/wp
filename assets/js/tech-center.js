/**
 * Technical Center AJAX Logic
 * 
 * Handles searching, filtering, and tab switching for
 * unified Resource Library.
 */
(function ($) {
    'use strict';

    const TechCenter = {
        init: function () {
            this.$container = $('.rf-tech-center');
            if (!this.$container.length) return;

            this.config = this.$container.data('config');
            this.currentMode = 'all';
            this.activeController = null;

            this.cacheElements();
            this.bindEvents();
            this.loadInitialData();
        },

        cacheElements: function () {
            this.$search = $('#rf-unified-search');
            this.$category = $('#rf-resource-cat');
            this.$tabs = $('.rf-tab');
            this.$grid = $('#rf-resource-grid');
        },

        bindEvents: function () {
            const self = this;

            // Mode/Tab Switching
            this.$tabs.on('click', function (e) {
                e.preventDefault();
                const mode = $(this).data('target');
                self.switchMode(mode);
            });

            // Unified Search (Debounced)
            let searchTimeout;
            this.$search.on('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => self.handleUpdate(), 400);
            });

            // Category Filter
            this.$category.on('change', () => this.handleUpdate());
        },

        switchMode: function (mode) {
            if (this.currentMode === mode) return;

            this.currentMode = mode;
            this.$tabs.removeClass('active').attr('aria-selected', 'false');
            $(`.rf-tab[data-target="${mode}"]`).addClass('active').attr('aria-selected', 'true');

            this.handleUpdate();
        },

        loadInitialData: function () {
            this.handleUpdate();
        },

        handleUpdate: function () {
            const params = {
                search: this.$search.val(),
                category: this.$category.val()
            };

            if (this.currentMode !== 'all') {
                params.mode = this.currentMode;
            }

            this.fetchResources(params);
        },

        fetchResources: function (params) {
            const self = this;

            if (this.activeController) {
                this.activeController.abort();
            }
            this.activeController = new AbortController();

            this.showSkeletons();

            const url = new URL(this.config.restUrl);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            fetch(url, {
                signal: this.activeController.signal,
                headers: { 'X-WP-Nonce': this.config.nonce }
            })
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        self.renderResources(response.data);
                    }
                })
                .catch(err => {
                    if (err.name === 'AbortError') return;
                    self.$grid.html(`<p style="color: #ef4444; text-align: center; grid-column: 1/-1;">Error loading resources.</p>`);
                })
                .finally(() => {
                    self.activeController = null;
                });
        },

        showSkeletons: function () {
            this.$grid.empty();
            for (let i = 0; i < 6; i++) {
                this.$grid.append(`
                    <div class="rf-card rf-skeleton-card" style="opacity: ${1 - (i * 0.15)}">
                        <div class="rf-card-icon rf-skeleton" style="width: 56px; height: 56px; border-radius: 12px; margin-bottom: 24px;"></div>
                        <div class="rf-skeleton" style="height: 24px; width: 80%; margin-bottom: 16px;"></div>
                        <div class="rf-skeleton" style="height: 16px; width: 100%; margin-bottom: 24px;"></div>
                        <div class="rf-card-footer"><div class="rf-skeleton" style="height: 48px; width: 120px; border-radius: 12px;"></div></div>
                    </div>
                `);
            }
        },

        renderResources: function (items) {
            this.$grid.empty();

            if (!items.length) {
                this.$grid.append(`<div class="rf-empty-state" style="grid-column:1/-1; text-align:center; padding:100px 0;">No resources found.</div>`);
                return;
            }

            items.forEach((item, index) => {
                this.$grid.append(this.getResourceHtml(item, index));
            });
        },

        getResourceHtml: function (item, index) {
            const delay = index * 0.05;
            let actionText = 'View Details';
            let icon = 'media-document';

            if (item.mode === 'faq') { icon = 'editor-help'; actionText = 'Read FAQ'; }
            if (item.mode === 'video') { icon = 'video-alt3'; actionText = 'Watch Video'; }
            if (item.mode === '3d') { icon = 'visibility'; actionText = 'View 3D'; }

            return `
                <article class="rf-card rf-fade-in" style="animation: rfFadeUp 0.6s ease-out both; animation-delay: ${delay}s;">
                    <div class="rf-card-icon" style="background: rgba(37, 99, 235, 0.05); color: var(--rf-primary);">
                        <span class="dashicons dashicons-${icon}"></span>
                    </div>
                    <span class="rf-badge-outline" style="font-size: 9px; margin-bottom: 8px;">${item.mode.toUpperCase()}</span>
                    <h3 class="rf-card-title" style="font-size: 1.25rem; margin: 12px 0;">${item.title}</h3>
                    <div class="rf-card-excerpt" style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 24px;">${item.excerpt}</div>
                    <div class="rf-card-footer" style="margin-top: auto; display: flex; justify-content: space-between; align-items: center;">
                        <a href="${item.permalink}" class="rf-btn-link" style="color: var(--rf-primary); font-weight: 700; text-decoration: none; font-size: 0.9rem;">
                            ${actionText} <span class="dashicons dashicons-arrow-right-alt2" style="margin-left: 6px;"></span>
                        </a>
                        ${item.file_url ? `<a href="${item.download_url}" class="rf-icon-btn" download><span class="dashicons dashicons-download"></span></a>` : ''}
                    </div>
                </article>
            `;
        }
    };

    $(document).ready(() => TechCenter.init());

})(jQuery);
