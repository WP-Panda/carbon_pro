jQuery(function ($) {
    /**
     * Убрать уведомления об ошибках
     */
    $(document).on('focus', '.wpp-error input,.wpp-error textarea,.wpp-error checkbox', function (e) {
        e.preventDefault();
        $(this).parents('.form--row').removeClass('wpp-error').find('.wpp-error-text').remove();
    });
})