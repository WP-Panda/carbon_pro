jQuery(function ($) {
    /**
     * Смена картинок по ховеру
     */
    $('.imgg-wp-bull').hover(
        function () {
            $(this).parent().find('.wpp-im-' + $(this).data('i')).show();
            $(this).parent().find('.wpp-mu-im').not('.wpp-im-' + $(this).data('i')).hide();
        },
        function () {
            $(this).parent().find('.wpp-im-' + $(this).data('i')).hide();
            $(this).parent().find('.wpp-im-1').show();
        }
    );
})