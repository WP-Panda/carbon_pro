<!--</div>-->
<?php
$iPod   = stripos( $_SERVER['HTTP_USER_AGENT'], "iPod" );
$iPhone = stripos( $_SERVER['HTTP_USER_AGENT'], "iPhone" );
$iPad   = stripos( $_SERVER['HTTP_USER_AGENT'], "iPad" );
$webOS  = stripos( $_SERVER['HTTP_USER_AGENT'], "webOS" );

?>
</main>
<footer class="footer-light container-fluid">
    <style>
        .call-btn.call-get {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/start.svg');
            background-color: #404cbf;
        }

        .call-btn.call-get.activated {
            background-image: url(/wp-content/themes/wpp-brabus/assets/img/icons/cross.svg);
            background-color: #404cbf;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-left: 25px;
        }

        .call-btn.call-get:hover {
            opacity: 0.8;
        }

        .call-btn {
            width: 50px;
            background-color: #000;
            background-repeat: no-repeat;
            margin: 20px;
            height: 50px;
            background-position: 50%;
            background-size: 28px;
            border-radius: 19px;
            cursor: pointer;
        }

        .call-wrap {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 999;
        }

        .call-btn.call-whatsapp {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/whatsapp.svg');
            background-color: #25D366;
            background-size: 25px

        }

        .call-btn.call-whatsapp a span {
            background-color: #25D366;
        }

        .call-btn.call-telegramm {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/telegramm.svg');
            background-color: #0088cc;
            background-size: 23px;
            background-position: 45% 50%;
        }

        .call-btn.call-telegramm a span {
            background-color: #0088cc;
        }

        .call-btn.call-imeagge {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/start.svg');
            background-color: rgb(142 142 147);
            background-size: 25px
        }

        .call-btn.call-imeagge a span {
            background-color: rgb(142 142 147);
        }

        .call-btn.send-sms {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/sms.svg');
            background-color: rgb(255 159 10);
            background-size: 25px;
        }

        .call-btn.send-sms a span {
            background-color: rgb(255 159 10);
        }

        .call-btn.send-emali {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/email.svg');
            background-color: rgb(255 69 58);
            background-size: 25px
        }

        .call-btn.send-emali a span {
            background-color: rgb(255 69 58);
        }

        .call-btn.send-emali a span {
            background-color: rgb(255 69 58);
        }

        .call-btn.send-address {
            background-image: url('/wp-content/themes/wpp-brabus/assets/img/feed_icon/svg/address.svg');
            background-color: #000;
            background-size: 25px
        }

        .call-btn.send-address a span {
            background-color: #000;
        }

        .call-btn a {
            background-color: transparent;
            width: 100%;
            text-align: right;
            right: 20px;
            width: 100%;
            height: 50px;
        }

        .call-btn a span {
            height: 50px;
            color: #fff !important;
            text-align: left;
            float: right;
            margin-right: 65px;
            background-color: inherit;
            font-size: 20px;
            line-height: 50px;
            padding: 0 20px;
            border-radius: 12px;
            display: none;
        }

        .call-actions {
            display: none;
        }

        .call-btn:hover a, .call-btn:hover a span {
            display: block;
        }
    </style>
    <div class="call-wrap">
        <div class="call-actions">
            <div class="call-btn call-whatsapp"><a target="_blank" href="//wa.me/+79853337733"><span>WhatsApp</span></a>
            </div>
            <div class="call-btn call-telegramm">
                <a target="_blank" href="tg://resolve?domain=@Carbon_pro"><span>Telegramm</span></a></div>
			<?php if ( ! empty( $iPod ) || ! empty( $iPad ) || ! empty( $iPhone ) || ! empty( $webOS ) ) : ?>
                <div class="call-btn call-imeagge"><a href="imessage://+79853337733"><span>Imessage</span></a></div>
			<?php endif; ?>
            <div class="call-btn send-sms"><a href="sms:+19725551212"><span>Sms</span></a></div>
            <div class="call-btn send-emali"><a href="mailto:info@carbon.pro"><span>Email</span></a></div>
            <div class="call-btn send-address"><a target="_blank" href="/contact/"><span>Address</span></a></div>
        </div>
        <div class="call-btn call-get"></div>
    </div>

	<?php
	wpp_get_template_part( 'templates/globals/subscribe' );
	wpp_get_template_part( 'templates/globals/navs/footer' );
	wpp_get_template_part( 'templates/globals/socials' );
	#wpp_get_template_part('templates/globals/preloader');
	#wpp_get_template_part('footer-styles');

	if ( wpp_fr_user_is_admin() ) {
		printf( '<div class="object_id">%d</div>', get_queried_object_id() );
	}
	?>

</footer>
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.1.2/dist/lazyload.min.js"></script>
<?php wp_footer(); ?>
<script>
    (function ($) {
        'use strict';


        $(document).on('click', '.call-get', function (e) {
            var $el = $(this);
            if ($el.hasClass('activated')) {
                $('.call-actions').hide();
                $el.removeClass('activated')
            } else {
                $('.call-actions').show();
                $el.addClass('activated')
            }
        });

        /**
         * @TODO Code a function the calculate available combination instead of use WC hooks
         */

        /**
         * Включение опций
         */
        function wppChecker($el) {
            if ($el.hasClass("wpp-check")) {
                $(".wpp-check").prop('checked', false);
                $el.prop('checked', true);
            } else if ($el.hasClass("wpp-img-var")) {
                $el.parents('.product-attribute').find(".wpp-img-var").prop('checked', false);
                $el.prop('checked', true)
                $el.parents('.product-attribute').find('.product-attribute__value').removeClass('active');
                $el.parents('.product-attribute__value').addClass('active');
            }
        }

        /**
         * Выключение опций
         */
        function wppChecker_un($el) {
            if ($el.hasClass("wpp-check")) {
                $el.prop('checked', false);
            } else if ($el.hasClass("wpp-img-var")) {
                $el.parents('.product-attribute').find(".wpp-img-var").prop('checked', false);
                $el.parents('.product-attribute').find('.product-attribute__value').removeClass('active');
            }
        }


        $(document).ready(function () {

            var $a = 0;
            $.each($('#wpp-as-options-flag').data('options'), function ($i, $v) {
                var $variant = '';


                if ($v.default == true) {

                    $a++;
                    $variant = $i.split('-');

                    $.each($variant, function ($f, $b) {
                        $('.wpp-variation[value="' + $b + '"]').trigger('change');
                    });

                }


            });

            if ($a === 0) {
                $('.wpp-variation').eq(0).trigger('change');
            }
        });


        /**
         * Смена Вариации
         */
        $(document).on('change', '.wpp-variation', function () {
            var $el = $(this),
                $attr = $('#wpp-as-options-flag').data('options'),
                $row_index = $el.parents('.product-attribute--gutter').index(),
                $option = $el.val(),
                $assembly_wrap = $('#assembly').parent(),
                $paint_wrap = $('#paint').parent();

            wppChecker($el);
            var $val = [];


            //пройти все инпуты
            if ($row_index == 0) {
                $('.product-attribute--gutter').each(function (i) {
                    if (i > $row_index) {
                        wppChecker_un($(this));

                        $(this).find('.wpp-variation').each(function () {

                            var $atts_2 = $(this).data('deff');
                            if (parseInt($atts_2, 10) === 1) {
                                $(this).trigger('change');
                            }

                        });

                        $(this).find('.wpp-variation').each(function () {

                            var $atts = $(this).data('valls'),
                                $atts_array = Object.values($atts);

                            if ($atts_array.indexOf($option) == -1) {
                                // console.log(-1);
                                $(this).parents('.coll-hider').addClass('wpp-noter');
                                $(this).attr('disabled', 'disabled');
                                $(this).parents('.product-attribute__value').removeClass('active');

                            } else {
                                $(this).parents('.coll-hider').removeClass('wpp-noter');
                                $(this).removeAttr('disabled');
                                //console.log(1);
                            }


                        });


                    }

                });

            }


            $('.wpp-variation:checked').each(function () {
                if ($(this).attr('disabled') !== 'disabled') {
                    $val.push($(this).val());
                }
            });

            var $vall = $attr[$val.join('-')];
			<?php
			$user_lng = get_locale();
			$geo_flag = wpp_fr_is_russia();
			$geo = ( $geo_flag !== false || $user_lng === 'ru_RU' ) ? '1' : '0';
			?>
            if (typeof $vall !== 'undefined') {
                $('.price').html($vall.opt_price_html);
                $('.wpp_scu').html($vall.sku);
                $('#wpp_add_variants').val($vall.key);
                var $paint = $vall.paint,
                    $assembly = $vall.assembly,
                    $geo_js = <?php echo $geo; ?>;
                if (typeof $paint !== 'undefined') {
                    if ($paint !== 1) {
                        $('#paint').prop('checked', false);
                        $paint_wrap.parents('tr').hide();
                    } else {
                        $('#paint').prop('checked', true);
                        $paint_wrap.parents('tr').show();
                    }
                }

                if (typeof $assembly !== 'undefined') {
                    if ($assembly !== 1) {
                        //console.log('gg');
                        $('#assembly').prop('checked', false);
                        $assembly_wrap.parents('tr').hide();
                    } else if ($assembly === 1 && $geo_js === 0) {
                        $('#assembly').prop('checked', false);
                    } else {
                        //console.log('nn');
                        $('#assembly').prop('checked', true);
                        $assembly_wrap.parents('tr').show();
                    }
                }

            } else {
                $('.price').html('')
                $('.wpp_scu').html('');
                $('#wpp_add_variants').val('');
            }

        });


        $(document).on('change', '.filter-news-cats , .filter-news-model, #filter-models-data', function () {

            if ($(this).hasClass('filter-news-cats')) {
                $('.filter-news-model').attr('disabled', 'disabled');
            }
            $('#wpp-form-filter').submit();
        });

        $(document).on('click', '#wpp-form-filter a', function (e) {
            e.preventDefault();
            $('.filter-news-cats , .filter-news-model').attr('disabled', 'disabled');
            window.location.href = $(this).attr('href');

        })


        var media_uploader = null;

        function open_media_uploader_multiple_images($post, $group) {
            media_uploader = wp.media({
                frame: "post",
                state: "insert",
                multiple: true
            });
            media_uploader.on("insert", function () {
                AddLoader();
                var length = media_uploader.state().get("selection").length;
                var images = media_uploader.state().get("selection").models

                var $img = [];
                for (var iii = 0; iii < length; iii++) {
                    $img[iii] = images[iii].id;
                }
                var $data = {
                    action: 'wpp_save_term_slider',
                    category: $post,
                    tag: $group,
                    images: $img
                };

                $.post('/wp-admin/admin-ajax.php', $data, function (response) {
                    location.reload();
                });
            });

            media_uploader.open();
        }


        $(document).on('click', '.wpp-cat-img-edit', function (e) {
            e.preventDefault();

            var $post = $(this).data('term'),
                $group = $(this).data('name');

            console.info('$post', $post);
            console.info('$group', $group);
            open_media_uploader_multiple_images($post, $group);
        })


        $(document).on('click', '.wpp_remove_term_img', function (e) {
            e.preventDefault();

            var $parent = $(this).parent();
            AddLoader();

            var $data = {
                action: 'wpp_save_term_slider_remoove_img',
                category: $parent.data('term'),
                key: $parent.data('key'),
                imag: $parent.data('id')
            };

            $.post('/wp-admin/admin-ajax.php', $data, function (response) {
                $parent.remove();
                RemoveLoader();
            });
        })


        if ($('.wpp-tooltips').length) {
            $('.wpp-tooltips').tooltip({
                direction: "top",
            });
        }

        if ($('.wpp-tooltips-copy').length) {
            $('.wpp-tooltips-copy').tooltip({
                direction: "top",
            });
        }

        if ($('.wpp-tooltips-follow').length) {
            $('.wpp-tooltips-follow').tooltip({
                direction: "top",
                follow: true
            });
        }

        $(document).on('click', '.wpp-tag-format-trigger', function (e) {

            e.preventDefault();

            AddLoader();

            var $data = {
                action: 'wpp_terms_slider_format',
                key: $(this).data('key'),
                term: $(this).data('term'),
                format: $(this).data('format'),
            };

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                    location.reload();
                } else {
                    RemoveLoader()
                }
            });

        });

        $(document).on('click', '.wpp-single-format-trigger', function (e) {

            e.preventDefault();

            AddLoader();

            var $data = {
                action: 'wpp_single_slider_format',
                post_id: $(this).data('post'),
                format: $(this).data('format'),
            };

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                    //RemoveLoader()
                    location.reload();
                } else {
                    RemoveLoader()
                }
            });

        });


        /**
         * Добавит прелоадер
         * @constructor
         */
        function AddLoader() {
            $('body').append('<div class="pulse-wrap"><div class="pulse"></div></div>')
        }

        /**
         * Удалить прелоадер
         * @constructor
         */
        function RemoveLoader() {
            $('.pulse-wrap').remove()
        }


        /**
         * Машины на продажу
         */
        $(document).on('click', '.wpp-admin-car4sale', function (e) {
            let $parent = $(this).parent('.wpp-admin-car4sale-block'),
                $modal = $parent.find('.wpp-car4sale-modal');

            $modal.show();
        });


        $(document).on('click', '.wpp-admin-project', function (e) {
            let $parent = $(this).parent('.wpp-admin-project-block'),
                $modal = $parent.find('.wpp-project-modal');

            $modal.show();
        });

        $(document).on('click', '.wpp-project-close', function (e) {
            let $parent = $(this).parent('.wpp-project-modal');
            $parent.hide();
        });

        $(document).on('click', '.wpp-car4sale-close', function (e) {
            let $parent = $(this).parent('.wpp-car4sale-modal');
            $parent.hide();
        });


        $(document).on('submit', '.project-car-selector', function (e) {
            e.preventDefault();
            var $form = $(this),
                $post = $form.data('id'),
                $cars = $form.find('select').val();

            AddLoader();

            var $data = {
                action: 'wpp_project_car_bundle',
                post: $post,
                cars: $cars,
            };

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                    RemoveLoader()
                } else {
                    RemoveLoader()
                }
            });


        });


        $(document).on('submit', '.sale-car-selector', function (e) {
            e.preventDefault();
            var $form = $(this),
                $post = $form.data('id'),
                $cars = $form.find('select').val();

            AddLoader();

            var $data = {
                action: 'wpp_save_car_bundle',
                post: $post,
                cars: $cars,
            };

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                    RemoveLoader()
                } else {
                    RemoveLoader()
                }
            });


        });


        $(document).on('click', '.wpp_remove_project', function (e) {
            e.preventDefault();

            AddLoader();

            var $el = $(this), $data = {
                action: 'wpp_at_remove_project_detail',
                id: $el.attr('data-detail'),
                parent: $el.attr('data-parent'),
            }

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                    $el.parents('.wpp-grid-item').remove();
                    RemoveLoader()
                } else {
                    RemoveLoader()
                }
            });
        });


        $(document).on('click', '.wpp_remove_sale_car', function (e) {
            e.preventDefault();

            AddLoader();

            var $el = $(this), $data = {
                action: 'wpp_at_remove_sale_car_detail',
                id: $el.attr('data-detail'),
                parent: $el.attr('data-parent'),
            }

            $.post(WppTools.ajax_url, $data, function ($response) {
                if ($response.success) {
                    $el.parents('.wpp-grid-item').remove();
                    RemoveLoader()
                } else {
                    RemoveLoader()
                }
            });
        });


        function setTooltip(btn, message) {
            $(btn).tooltip('hide')
                .attr('data-original-title', message)
                .tooltip('show');
        }

        function hideTooltip(btn) {
            setTimeout(function () {
                $(btn).tooltip('hide').removeAttr('data-original-title')
            }, 1000);
        }

        if ($('.wpp-copy-btn').length) {
            var clipboard = new ClipboardJS('.wpp-copy-btn');


            clipboard.on('success', function (e) {
                setTooltip(e.trigger, 'Copied!');
                hideTooltip(e.trigger);
            });

            clipboard.on('error', function (e) {
                setTooltip(e.trigger, 'Failed!');
                hideTooltip(e.trigger);
            });

        }


        $(document).on('click', '.get_share', function (e) {
            e.preventDefault()
            $(this).next('.modal-share-box').show();
            $('body').append('<div class="share-overlay"></div>');
        });

        $(document).on('click', '.share-overlay', function (e) {
            e.preventDefault()
            $('.modal-share-box').hide();
            $(this).remove();
        });

        $(document).on('click', '.wpp-share-modal-href', function (e) {
            e.preventDefault();
            let $this = $(this),
                $id = $this.attr('id'),
                $href = $this.attr('href');
            window.open($href, $id, 'width=450,height=500top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
            return false;
        });


    })(jQuery);
</script>
<script>//var lazyLoadInstance = new LazyLoad();</script>
</body>
</html>