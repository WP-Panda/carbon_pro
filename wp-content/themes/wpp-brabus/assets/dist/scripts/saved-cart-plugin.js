$.fn.WppSaveCart = function () {

    var $data = {
        action: 'wpp_save_cart_details',
        security: WppAjaxCart.security,
        actual_url: WppAjaxCart.actual_url
    }

    $.post(WppAjaxCart.ajax_url, $data, function ($response) {

        if ($response.success) {

            $('.saved-link').html($response.data.url);
            $('.wpp-copy-btn').attr('data-clipboard-text', $response.data.url);
            $('.copy-clipboard-row').show()
        }

        return false;
    });
}