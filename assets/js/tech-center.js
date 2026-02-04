/**
 * Technical Center AJAX Logic
 * 
 * Handles searching, filtering, and tab switching for
 * FAQs and Technical Documentation.
 */
(function ($) {
    'use strict';

    const TechCenter = {
        init: function () {
            this.$container = $('.rf-tech-center');
            if (!this.$container.length) return;

            this.config = this.$container.data('config');
            this.currentTab = 'faq';
            this.activeController = null; // For AbortController

            this.cacheElements();
            this.bindEvents();
            this.loadInitialData();
        },

        cacheElements: function () {
            this.$search = $('#rf-unified-search');
            this.$faqCat = $('#rf-faq-cat');
            this.$docType = $('#rf-doc-type');
            this.$tabs = $('.rf-tab');
            this.$sections = $('.rf-content-section');
            this.$faqGrid = $('#rf-results-faq');
            this.$docGrid = $('#rf-results-docs');
        },

        bindEvents: function () {
            const self = this;

            // Tab Switching
            this.$tabs.on('click', function (e) {
                e.preventDefault();
                const target = $(this).data('target');
                self.switchTab(target);
            });

            // Unified Search (Debounced)
            let searchTimeout;
            this.$search.on('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => self.handleFilter(), 400);
            });

            // Category/Type Filters
            this.$faqCat.on('change', () => this.handleFilter());
            this.$docType.on('change', () => this.handleFilter());
        },

        switchTab: function (target) {
            if (this.currentTab === target) return;

            const self = this;
            this.currentTab = target;

            // UI Feedback & ARIA
            this.$tabs.removeClass('active').attr('aria-selected', 'false');
            $(`.rf-tab[data-target="${target}"]`).addClass('active').attr('aria-selected', 'true');

            // Smooth content transition
            this.$sections.fadeOut(200, function () {
                $(this).removeClass('active');
                if ($(this).attr('id') === `rf-section-${target}`) {
                    $(this).fadeIn(300).addClass('active');
                }
            });

            // Toggle Filters with transition
            $('.rf-filter-dropdown').fadeOut(200, function () {
                if (target === 'faq' && $(this).hasClass('rf-faq-only')) {
                    $(this).fadeIn(200);
                } else if (target === 'docs' && $(this).hasClass('rf-doc-only')) {
                    $(this).fadeIn(200);
                }
            });

            // Refresh data for the new tab
            this.handleFilter();
        },

        loadInitialData: function () {
            this.handleFilter();
        },

        handleFilter: function () {
            const searchTerm = this.$search.val();
            const tab = this.currentTab;

            let params = { search: searchTerm };
            let endpoint = tab === 'faq' ? 'faq' : 'techdocs';

            if (tab === 'faq') {
                params.category = this.$faqCat.val();
            } else {
                params.file_type = this.$docType.val();
            }

            this.fetchResults(endpoint, params, tab);
        },

        fetchResults: function (endpoint, params, tab) {
            const self = this;
            const $grid = tab === 'faq' ? this.$faqGrid : this.$docGrid;

            // Cancel previous request if still pending
            if (this.activeController) {
                this.activeController.abort();
            }
            this.activeController = new AbortController();

            // Show Skeletons
            this.showSkeletons($grid);

            const url = new URL(`${this.config.restUrl}/${endpoint}`);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            fetch(url, {
                signal: this.activeController.signal,
                headers: {
                    'X-WP-Nonce': this.config.nonce
                }
            })
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        self.renderResults(response.data, tab);
                    } else {
                        throw new Error(response.message || 'Failed to fetch results');
                    }
                })
                .catch(err => {
                    if (err.name === 'AbortError') return;
                    console.error('TechCenter Error:', err);
                    $grid.html(`<div class="rf-error-state" style="grid-column: 1/-1; text-align: center; color: #ef4444; padding: 40px; border-radius: 20px; background: #fef2f2; border: 1px solid #fee2e2;">
                        <span class="dashicons dashicons-warning" style="font-size: 40px; width: 40px; height: 40px; margin-bottom: 16px;"></span>
                        <p style="font-weight: 700; font-size: 1.1rem; margin: 0;">Failed to load resources</p>
                        <p style="color: #991b1b; margin-top: 8px;">Please check your connection and try again.</p>
                    </div>`);
                })
                .finally(() => {
                    self.activeController = null;
                });
        },

        showSkeletons: function ($grid) {
            $grid.empty();
            for (let i = 0; i < 6; i++) {
                $grid.append(`
                    <div class="rf-card rf-skeleton-card" style="opacity: ${1 - (i * 0.15)}">
                        <div class="rf-card-icon rf-skeleton" style="width: 56px; height: 56px; border-radius: 12px; margin-bottom: 24px;"></div>
                        <div class="rf-skeleton" style="height: 24px; width: 80%; margin-bottom: 16px;"></div>
                        <div class="rf-skeleton" style="height: 16px; width: 100%; margin-bottom: 8px;"></div>
                        <div class="rf-skeleton" style="height: 16px; width: 90%; margin-bottom: 24px;"></div>
                        <div class="rf-card-footer">
                            <div class="rf-skeleton" style="height: 48px; width: 120px; border-radius: 12px;"></div>
                        </div>
                    </div>
                `);
            }
        },

        renderResults: function (items, tab) {
            const $grid = tab === 'faq' ? this.$faqGrid : this.$docGrid;
            $grid.empty();

            if (!items.length) {
                $grid.append(`<div class="rf-empty-state" style="grid-column: 1/-1; text-align: center; padding: 100px 0; animation: rfFadeUp 0.6s ease-out;">
                    <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <span class="dashicons dashicons-search" style="font-size: 32px; width: 32px; height: 32px; color: #94a3b8;"></span>
                    </div>
                    <h4 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 12px;">No resources matched your search</h4>
                    <p style="font-size: 1.1rem; color: #64748b; max-width: 400px; margin: 0 auto;">Try using different keywords or broadening your filters.</p>
                </div>`);
                return;
            }

            items.forEach((item, index) => {
                const html = tab === 'faq' ? this.getFAQHtml(item, index) : this.getDocHtml(item, index);
                $grid.append(html);
            });
        },

        getFAQHtml: function (item, index) {
            const delay = index * 0.1;
            const categories = item.categories.map(c => `<span style="background: var(--rf-primary-light); color: var(--rf-primary); padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;">${c}</span>`).join('');
            return `
                <article class="rf-card" style="animation: rfFadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: ${delay}s;">
                    <div class="rf-card-icon">
                        <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                    </div>
                    <h3 class="rf-card-title">${item.question}</h3>
                    <div class="rf-card-excerpt">${item.excerpt}</div>
                    <div class="rf-card-footer">
                        <div class="rf-card-meta" style="display: flex; gap: 8px; flex-wrap: wrap;">
                            ${categories}
                        </div>
                        <a href="${item.permalink}" class="rf-btn">
                            View Answer <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true" style="margin-left: 8px;"></span>
                        </a>
                    </div>
                </article>
            `;
        },

        getDocHtml: function (item, index) {
            const delay = index * 0.1;
            return `
                <article class="rf-card" style="animation: rfFadeUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: ${delay}s;">
                    <div class="rf-card-icon" style="background: hsl(150, 70%, 96%); color: var(--rf-success);">
                        <span class="dashicons dashicons-media-document" aria-hidden="true"></span>
                    </div>
                    <h3 class="rf-card-title">${item.title}</h3>
                    <div class="rf-card-excerpt">${item.excerpt}</div>
                    <div class="rf-card-footer">
                        <span style="font-weight: 800; color: var(--rf-success); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em;">${item.file_type || 'Document'}</span>
                        <div style="display: flex; gap: 12px;">
                            <a href="${item.permalink}" class="rf-btn" style="background: #f1f5f9; color: #475569; padding: 12px 20px;">Details</a>
                            <a href="${item.download_url}?_wpnonce=${this.config.nonce}" class="rf-btn" download style="padding: 12px 24px;">
                                <span class="dashicons dashicons-download" aria-hidden="true" style="margin-right: 8px;"></span>
                                Download
                            </a>
                        </div>
                    </div>
                </article>
            `;
        }
    };

    $(document).ready(() => TechCenter.init());

})(jQuery);
