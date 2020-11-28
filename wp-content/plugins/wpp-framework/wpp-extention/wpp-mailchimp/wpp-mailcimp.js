jQuery(function ($) {

    //Валидация Email
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    //Отправка формы
    $(document).on('submit', '#wpp-mc-subscribe', function (e) {
        e.preventDefault();


        var $error = {},
            $email = $('#mc-email').val(),
            $target = $('#mc-target').val(),
            $btn = $('#wpp-mc-subscribe').find('button'),
            $text = $btn.text(),
            $img = '/wp-content/themes/wpp-brabus/assets/img/icons/loader.svg';

        $btn.html('<img src="' + $img + '"/>');
        $btn.attr('disabled', 'disabled');

        if ($email.length < 1) {
            $error['mc-email'] = WppMc.mail_empty_message;
        }

        if ($target.length < 1) {
            $error['mc-target'] = WppMc.target_empty_message;
        }

        if (!validateEmail($email)) {
            $error['mc-email'] = WppMc.mail_validate_message;
        }


        if (Object.keys($error).length > 0) {
            $.each($error, function (key, value) {
                var $el = $('#' + key);
                if (!$el.hasClass('wpp-error')) {
                    $el.addClass('wpp-error');
                    $el.parent().find('label').append('<i class="wpp-error-notis">' + value + '</i>');
                }
            });
            console.info('Mailchimp Form Error', $error);
            return false;
        }

        var $data = {
            action: 'wpp_mc_add_user',
            security: WppMc.security,
            target: $target,
            email: $email
        }

        $.post(WppMc.ajaxurl, $data, function ($response) {

            if (!$response.success) {
                if ($response.data.form_error) {
                    $.each($response.data.form_error, function (key, value) {
                        var $el = $('#' + key);
                        if (!$el.hasClass('wpp-error')) {
                            $el.addClass('wpp-error');
                            $el.parent().find('label').append('<i class="wpp-error-notis">' + value + '</i>');
                        }
                    });
                    console.info('Mailchimp Form Error', $error);
                    return false;
                }
            } else {
                $('#wpp-mc-subscribe').html('<h2 class="col-sm-12 text-center">' + $response.data.message + '</h2>')
            }

            $btn.html($text);
            $btn.removeAttr('disabled');

        });

    });

//удавление ошибок
    $(document).on('focus', '.wpp-error', function (e) {
        e.preventDefault();
        $(this).parent().find('label').find('.wpp-error-notis').remove();
        $(this).removeClass('wpp-error');
    });
});