jQuery(function ($) {
    // удаление покраски установки
    $(document).on('click', '.dell_ass', function (e) {
        e.preventDefault();

        var $data = {
            action: 'ajax_remove_additional',
        }

        $.post(WppAjaxCart.ajax_url, $data, function ($response) {

            $('.neos-contentcollection').html($response);
            $(document).WppChangeCountInput();
            $(document).WppSaveCart();

        });
    });
});