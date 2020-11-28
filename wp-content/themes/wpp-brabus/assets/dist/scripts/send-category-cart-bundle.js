jQuery(function ($) {
    // в корзину из категории набор
    $(document).on('click', '.wpp-add-cart-bundle', function (e) {
        e.preventDefault();

        var $el = $(this),
            $url = $el.attr('href'),
            $img = $el.find('img.wpp-plus-icon'),
            $data = {
                action: 'wpp_ajax_cart_bundle',
                security: WppAjaxCart.security,
                data_product: $url
            };

        if ($('.single-product ').length) {
            $el.before('<img class="wpp_single_loader" src="' + WppAjaxCart.icons.loader + '">');
            $el.hide();
        }

        $img.attr('src', WppAjaxCart.icons.loader);

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(WppAjaxCart.ajax_url + '?wc-ajax=get_refreshed_fragments', $data, function ($response) {
            console.log($response);
            $img.attr('src', WppAjaxCart.icons.check);
            $('.wpp-head-cart').html($response.fragments.wpp_cart_frag);
            $('.wpp_single_loader').remove();
            $el.show();

        });
    });
})