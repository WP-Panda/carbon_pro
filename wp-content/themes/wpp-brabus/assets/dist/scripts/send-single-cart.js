jQuery(function ($) {
    $(document).on('submit', 'form.cart', function (e) {
        e.preventDefault();

        var $url = $(this).attr('href'),
            $btn = $('.single_add_to_cart_button'),
            $text = $btn.html(),
            $img = '<img src="' + WppAjaxCart.icons.loader + '">',
            $data = {
                action: 'wpp_ajax_cart_cat',
                security: WppAjaxCart.security,
                data_product: $(this).serialize(),
                single: true,
                actual_url: WppAjaxCart.actual_url
            };


        $btn.before('<img class="wpp_single_loader" src="' + WppAjaxCart.icons.loader + '">');
        $btn.hide();

        $.post(WppAjaxCart.ajax_url, $data, function ($response) {

            $('.wpp-head-cart').html($response.fragments.wpp_cart_frag);
            $('.wpp_single_loader').remove();
            $btn.show();

        });
    });
})