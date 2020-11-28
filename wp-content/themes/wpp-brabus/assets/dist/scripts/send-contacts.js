jQuery(function ($) {
    /**
     * ОТправка конгтактов
     */

    $(document).on('submit', 'form#contact', function (e) {
        e.preventDefault();

        var $data = {
            action: 'wpp_contats_send',
            data: $(this).serialize(),
        };


        $.post(WppAjaxAct.ajax_url, $data, function ($response) {

            if ($response.success) {
                console.log($response.data.message);
                $('.ok-for').remove();
                $('.form-sends').html($response.data.message);

            } else {
                $.each($response.data.errors, function ($k, $v) {
                    $('[name="' + $k + '"]').parents('.form--row').addClass('wpp-error').append('<span class="wpp-error-text">' + $v + '</span>');
                })
            }
        });
    })
})