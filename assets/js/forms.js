/**
 * Quote Form JS
 * 
 * Handles modal interaction and AJAX form submission.
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        const $modal = $('#rf-quote-modal');
        const $btn = $('#rf-request-quote-btn');
        const $close = $('.rf-modal-close');
        const $form = $('#rf-quote-form');
        const $response = $('#rf-quote-response');

        // Open Modal
        $btn.on('click', function () {
            $modal.fadeIn(300);
        });

        // Close Modal
        $close.on('click', function () {
            $modal.fadeOut(300);
        });

        // Close on outside click
        $(window).on('click', function (event) {
            if ($(event.target).is($modal)) {
                $modal.fadeOut(300);
            }
        });

        // Form Submission
        $form.on('submit', function (e) {
            e.preventDefault();

            const formData = $form.serializeArray();
            const data = {};

            formData.forEach(item => {
                data[item.name] = item.value;
            });

            $response.hide().removeClass('success error');
            $form.find('button[type="submit"]').prop('disabled', true).text('Sending...');

            $.ajax({
                url: rfpluginData.restUrl + '/forms/submit',
                method: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', rfpluginData.nonce);
                },
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function (res) {
                    $response.text(res.message).addClass('success').fadeIn();
                    $form.find('button[type="submit"]').text('Sent!');
                    setTimeout(() => {
                        $modal.fadeOut(300, () => {
                            $form[0].reset();
                            $form.find('button[type="submit"]').prop('disabled', false).text('Send Request');
                            $response.hide();
                        });
                    }, 2000);
                },
                error: function (err) {
                    const msg = err.responseJSON ? err.responseJSON.message : 'Error submitting form.';
                    $response.text(msg).addClass('error').fadeIn();
                    $form.find('button[type="submit"]').prop('disabled', false).text('Send Request');
                }
            });
        });
    });
})(jQuery);
