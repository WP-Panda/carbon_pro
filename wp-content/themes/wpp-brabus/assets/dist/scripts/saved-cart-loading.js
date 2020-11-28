(function ($) {
// Сохранение корзины

    if ($('body.woocommerce-cart').length) {
        $(document).WppSaveCart();
    }

}(jQuery))