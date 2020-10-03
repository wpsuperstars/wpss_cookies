/**
 * Hide cookie message container
 */
jQuery(function ($) {
    $('.wpss-button-accept').on('click', function () {

        let wpss_cookie_message = $('.wpss-cookie-message');
        let wpss_ajax_url = wpss_cookie_message.attr('data-url');
        let wpss_ajax_security = wpss_cookie_message.attr('data-security');

        $.post(wpss_ajax_url, {
            action: 'wpss_set_cookie_action',
            security: wpss_ajax_security,
            beforeSend: function (xhr) {
                $('.wpss-button-accept').addClass('wpss-loading-btn');
            },
        }).success(function () {
            wpss_cookie_message.addClass('wpss-hide-message');
        });
    });
});