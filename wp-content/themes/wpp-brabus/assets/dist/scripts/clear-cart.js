(function ($) {

    // очистка корзины
    $(document).on('click', '.wpp-clear-cart', function (e) {
        e.preventDefault();

        var $data = {
            action: 'wpp_br_clear_cart',
            security: WppAjaxCart.security
        };

        $.post(WppAjaxCart.ajax_url, $data, function ($response) {
            document.location.replace(WppAjaxCart.actual_url + '/cart');
        });
    });

}(jQuery))