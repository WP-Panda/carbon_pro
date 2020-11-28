jQuery(function ($) {
    /**
     * Устиановка даты прооизводства
     */

    $(document).on('change', '.wpp_create_period_date', function (e) {
        e.preventDefault();

        var $data = {
            action: 'wpp_create_date_set',
            time: $(this).val(),
            id: $(this).parents('.wpp-grid-item').data('prd_id')
        };


        $.post(WppTools.ajax_url, $data, function ($response) {

            if ($response.success) {

            } else {
                $.each($response.data.errors, function ($k, $v) {

                })
            }
        });

    });

    $(document).on('change', 'input.wpp-bundle-edit', function () {


        var $data = {
            action: 'wpp_change_bundle',
            bundle: $(this).data('bundle'),
            val: $(this).val()
        };


        $.post(WppTools.ajax_url, $data, function ($response) {

            if ($response.success) {

            } else {
                $.each($response.data.errors, function ($k, $v) {

                })
            }
        });

    });


    $(".wpp-sortable").sortable();
    $(".wpp-sortable").disableSelection();


    $('.wpp-posts-ordering').sortable({
        stop: function (event, ui) {
            var $sort_parent = $('#object-data').data('id'),
                $posts = [];

            $('div[data-prd_id]').each(function (i) {
                $posts[i] = $(this).data('prd_id');
            });

         //   AddLoader();
            var $data = {
                action: 'wpp_sort_posts',
                posts: $posts,
                parent: $sort_parent
            };

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                } else {
                    $.each($response.data.errors, function ($k, $v) {
                    })
                }
             //   RemoveLoader();
            });


        }
    });
    $('.wpp-posts-ordering').disableSelection();


    /**
     * Cjhnbhjdrf
     */
    if ($('.wpp-sortable-ajax').length) {
        $(".wpp-sortable-ajax").sortable({
            stop: function (event, ui) {
             //   AddLoader();
                var $data = {
                    action: 'wpp_sort_terms_img',
                    images: ui.item.parents('form.wpp_sl_bundle').serialize(),
                    term: ui.item.parents('form.wpp_sl_bundle').data('cat')
                };

                $.post(WppTools.ajax_url, $data, function ($response) {
                    if ($response.success) {
                   //     RemoveLoader()
                    } else {
                        $.each($response.data.errors, function ($k, $v) {
                        })
                    }
                });

            }
        });

        $(".wpp-sortable-ajax").disableSelection();
    }


    $(document).on('click', '.wpp-bundle-sort-send', function (e) {
        e.preventDefault();

        var $data = {
            action: 'wpp_sort_bundle',
            bundle: $('.wpp-b-sort-form').serialize(),
            id: $(this).data('id')
        };

        $.post(WppTools.ajax_url, $data, function ($response) {
            if ($response.success) {
            } else {
                $.each($response.data.errors, function ($k, $v) {
                })
            }
        });
    });


});