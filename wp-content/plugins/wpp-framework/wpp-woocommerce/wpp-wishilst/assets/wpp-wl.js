jQuery(function ($) {

    $(document).on('click', '.wpp-wish-wrap', function (e) {

        e.preventDefault();

        var $el = $(this),
            $id = $el.data('id'),
            $term = $el.data('term'),
            $action = $el.hasClass('in-wpp-wish') ? 'delete_wish_item' : 'add_wish_item',
            $parents = $el.parents('.wl-container'),
            $data = {
                action: $action,
                security: WppWlAjax.security,
                data: 'item_cat=' + $term + '&item_id=' + $id
            };

        if ($el.hasClass('wl-loading')) {
            return false;
        } else {
            $el.addClass('wl-loading');
        }

        $.post(WppWlAjax.ajaxurl, $data, function ($response) {

            if ($response.success) {

                if ($action === 'add_wish_item') {
                    $el.addClass('in-wpp-wish').removeClass('out-wpp-wish');
                    $el.find('.main-text').text(WppWlAjax.saved_title);
                    $el.find('.copy-text').text(WppWlAjax.saved_desc);
                } else {
                    if (WppWlAjax.wl == 'true') {

                        var $count = $parents.find('.wpp-grid-item').length - 1;
                        $el.parents('.wpp-grid-item').remove();

                        if ($count < 1) {
                            $parents.remove();
                        }

                    } else {
                        $el.removeClass('in-wpp-wish').addClass('out-wpp-wish');
                        $el.find('.main-text').text(WppWlAjax.save_title);
                        $el.find('.copy-text').text(WppWlAjax.save_desc);
                    }
                }


                $('.wpp-wl-icon').html($response.data.count)
            }
            $el.removeClass('wl-loading');
        });


    });

});