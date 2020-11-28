jQuery(document).ready(function ($) {


    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    if ( ( typeof ( getUrlParameter('action'))  && getUrlParameter('action') == 'rp' ) && typeof ( getUrlParameter('key')) && typeof ( getUrlParameter('login')) ) {
        $('#get_login_modal').trigger('click');
    }


    /**
     * Change form
     */
    $(document).on('click', 'a.wpp-al-href', function (e) {
        e.preventDefault();
        $('.wpp-al-loading').show();
        var $data = {
            action: 'wpp_al_change_form',
            security: WppAl.security,
            target: $(this).data('action')
        };

        $.post(WppAl.ajaxurl, $data, function (response) {
            if (response.error) {
                console.error(response.data.message)
                return false;
            }

            $('.wpp-al-response-form').html(response.data.form);
            $('.wpp-al-loading').hide();
            return false;
        });
    });

    /**
     * Login
     */
    $(document).on('submit', '#wpp-al-login-form', function (e) {
        e.preventDefault();
        var $data = {
            action: 'wpp_al_login_send',
            security: WppAl.security,
            log: $('[name="log"]').val(),
            pwd: $('[name="pwd"]').val()
        };

        $.post(WppAl.ajaxurl, $data, function (response) {

            if (response.error) {
                console.error(response.data.message);
            } else {
                window.location.reload()
            }
            $('.wpp-al-response-message').html(response.data.message);
            return false;
        });
    });

    /**
     * Login
     */
    $(document).on('submit', '#wpp-al-register-form', function (e) {
        e.preventDefault();
        var $data = {
            action: 'wpp_al_register_send',
            security: WppAl.security,
            data: $('#wpp-al-register-form').serialize()
        };


        $.post(WppAl.ajaxurl, $data, function (response) {

            if (response.error) {
                console.error(response.data.message);
            } else {
                // window.location.reload()
            }
            $('.wpp-al-response-message').html(response.data.message);
            return false;
        });
    });

    /**
     * Set Pass
     */
    $(document).on('submit', '#wpp-al-set-form', function (e) {
        e.preventDefault();
        var $data = {
            action: 'wpp_al_set_send',
            security: WppAl.security,
            pass1: $('#pass1').val(),
            rp_key: $('[name="rp_key"]').val()
        };

        $.post(WppAl.ajaxurl, $data, function (response) {

            if (response.success) {
                $('.wpp-al-response-form').html('');
            } else {
                console.error(response.data.message);
            }
            $('.wpp-al-response-message').html(response.data.message);
            return false;
        });
    });

    /**
     * Lost pass
     */
    $(document).on('submit', '#wpp-al-lost-form', function (e) {
        e.preventDefault();
        var $data = {
            action: 'wpp_al_lost_send',
            security: WppAl.security,
            user_login: $('#user_login').val()
        };

        $.post(WppAl.ajaxurl, $data, function (response) {

            if (response.success) {
                $('.wpp-al-response-form').html('');
            } else {
                console.error(response.data.message);
            }
            $('.wpp-al-response-message').html(response.data.message);
            return false;
        });
    });


});