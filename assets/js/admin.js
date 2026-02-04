/**
 * RFPlugin Admin JavaScript
 * 
 * @package RFPlugin
 * @since 1.0.0
 */

(function ($) {
    'use strict';

    const RFPluginAdmin = {
        /**
         * Initialize admin scripts
         */
        init: function () {
            this.bindEvents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function () {
            $(document).on('click', '.rfplugin-refresh-stats', this.refreshStats);
            $(document).on('mousedown', '.rf-btn', this.createRipple);
        },

        /**
         * Create material ripple effect
         */
        createRipple: function (e) {
            const $btn = $(this);
            const $ripple = $('<span class="rf-ripple"></span>');
            const rect = this.getBoundingClientRect();

            const x = e.pageX - rect.left - window.scrollX;
            const y = e.pageY - rect.top - window.scrollY;

            $ripple.css({
                top: y + 'px',
                left: x + 'px',
                width: '10px',
                height: '10px'
            });

            $btn.append($ripple);

            setTimeout(() => {
                $ripple.remove();
            }, 600);
        },

        /**
         * Refresh dashboard statistics
         */
        refreshStats: function (e) {
            e.preventDefault();

            $.ajax({
                url: rfpluginAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rfplugin_refresh_stats',
                    nonce: rfpluginAdmin.nonce
                },
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        }
    };

    $(document).ready(function () {
        RFPluginAdmin.init();
    });

})(jQuery);
