(function ($) {
    $.fn.WppSaveCart = function () {

        var $data = {
            action: 'wpp_save_cart_details',
            security: WppAjaxCart.security,
            actual_url: WppAjaxCart.actual_url
        }

        $.post(WppAjaxCart.ajax_url, $data, function ($response) {

            if ($response.success) {
                history.pushState(null, null, $response.data.url)
            }

            return false;
        });
    }
}(jQuery))