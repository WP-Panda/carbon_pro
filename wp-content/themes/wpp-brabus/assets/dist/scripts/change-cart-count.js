(function ($) {

//cмена количества в корзне
    $(document).on('change', 'input.qty', function () {

        var $item_hash = $(this).attr('name').replace(/cart\[([\w]+)\]\[qty\]/g, "$1"),
            $item_quantity = $(this).val(),
            $currentVal = parseFloat($item_quantity),
            $type = $(this).data('type');

        function qty_cart() {

            $.ajax({
                type: 'POST',
                url: WppAjaxCart.ajax_url,
                data: {
                    action: 'qty_cart',
                    hash: $item_hash,
                    quantity: $currentVal,
                    type: $type
                },
                success: function ($response) {
                    $('.neos-contentcollection').html($response);
                    $(document).WppChangeCountInput();
                    $(document).WppSaveCart();

                    var lazyLoadInstance = new LazyLoad();
                }

            });

        }

        qty_cart();
    });

}(jQuery))