jQuery(function ($) {
// Сложить в корзину похожие товары
    $(document).on('click', '.add_all_products_to_cart', function (e) {
        e.preventDefault();
        console.log('.add_all_products_to_cart');
        var $get = [];

        $('.wpp-cart-need').find('.wpp-grid-item').each(function (i) {
            let $this = $(this);
            $get[i] = $this.find('.wpp-add-cart-categ:eq(0)').attr('href');
            $this.find('.wpp-plus-icon:eq(0)').attr('src', WppAjaxCart.icons.loader).addClass('flag_load');
        });

        var $data = {
            action: 'wpp_ajax_cart_package',
            security: WppAjaxCart.security,
            products: $get
        };

        $.post(WppAjaxCart.ajax_url + '?wc-ajax=get_refreshed_fragments', $data, function ($response) {
            $('.flag_load').attr('src', WppAjaxCart.icons.check).removeClass('flag_load');
            $('.wpp-head-cart').html($response.fragments.wpp_cart_frag)
        });

    });
});