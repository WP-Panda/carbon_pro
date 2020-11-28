jQuery(function ($) {

    /**
     * Смена oтображения
     */
    $(document).on('click', '.swith-item', function (e) {
        e.preventDefault();

        Cookies.set('wpp_view', $(this).data('swith'), {expires: 365});

        location.reload();
    })
})