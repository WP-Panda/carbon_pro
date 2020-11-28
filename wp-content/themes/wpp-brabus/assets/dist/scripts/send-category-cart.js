jQuery(function ($) {
    // в корзину из категории
    $(document).on('click', '.wpp-add-cart-categ', function (e) {
        e.preventDefault();

        var $url = $(this).attr('href'),
            $img = $(this).find('img.wpp-plus-icon'),
            $data = {
                action: 'wpp_ajax_cart_cat',
                security: WppAjaxCart.security,
                data_product: $url
            };

        $img.attr('src', WppAjaxCart.icons.loader);

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(WppAjaxCart.ajax_url + '?wc-ajax=get_refreshed_fragments', $data, function ($response) {
            $img.attr('src', WppAjaxCart.icons.check);
            $('.wpp-head-cart').html($response.fragments.wpp_cart_frag)

        });
    });

})