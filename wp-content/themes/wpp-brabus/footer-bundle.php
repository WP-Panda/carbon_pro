<!--</div>-->
</main>
<footer class="footer-light container-fluid">
	<?php /**
	 * $segments = wpp_fr_mc_get_all_segmets();
	 * if ( ! empty( $segments ) ) :
	 * ?>
	 * <div class="container wpp-mc-row">
	 * <div class="row">
	 * <div class="col-sm-2">
	 * </div>
	 * <div class="col-sm-8">
	 * <form id="wpp-mc-subscribe" class="row">
	 *
	 * <div class="wpp-mc-fr form--row col-md-4">
	 * <label for="mc-email" class="form--label"><?php _e( 'E-mail', 'wpp-br' ); ?> </label>
	 * <input type="text" class="form--text form--border-bottom" id="mc-email" name="mc-email">
	 * </div>
	 *
	 *
	 * <div class="wpp-mc-fr form--row in col-md-4">
	 * <label for="mc-target" class="form--label"><?php _e( 'Theme', 'wpp-br' ); ?></label>
	 * <select class="form--text form--border-bottom dirty" id="mc-target" name="mc-target">
	 * <option value=""><?php _e( 'Select Model', 'wpp-br' ); ?></option>
	 * <?php foreach ( $segments as $segment ) :
	 * printf( '<option value="%s">%s</option>', $segment->intersest_id, trim( $segment->name, '/' ) );
	 * endforeach; ?>
	 * </select>
	 * </div>
	 * <div class="wpp-mc-fr form--row in col-md-4">
	 * <button type="submit"
	 * class="form--button-dark form--button--cta"><?php _e( 'Subscribe', 'wpp-br' ); ?></button>
	 * </div>
	 * </form>
	 * </div>
	 * </div>
	 * </div>
	 * <?php endif;
	 * */ ?>
	<?php
	wpp_get_template_part( 'templates/globals/subscribe' );
	wpp_get_template_part( 'templates/globals/navs/footer' );
	wpp_get_template_part( 'templates/globals/socials' );
	#wpp_get_template_part( 'templates/globals/preloader' );
	#wpp_get_template_part( 'footer-styles' );
	?>
</footer>
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.1.2/dist/lazyload.min.js"></script>
<?php wp_footer(); ?>
<script>
    (function ($) {
        'use strict';

        function UpdateQueryString(key, value, url) {
            if (!url) url = window.location.href;
            var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
                hash;

            if (re.test(url)) {
                if (typeof value !== 'undefined' && value !== null) {
                    return url.replace(re, '$1' + key + "=" + value + '$2$3');
                }
                else {
                    hash = url.split('#');
                    url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
                    if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                        url += '#' + hash[1];
                    }
                    return url;
                }
            }
            else {
                if (typeof value !== 'undefined' && value !== null) {
                    var separator = url.indexOf('?') !== -1 ? '&' : '?';
                    hash = url.split('#');
                    url = hash[0] + separator + key + '=' + value;
                    if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                        url += '#' + hash[1];
                    }
                    return url;
                }
                else {
                    return url;
                }
            }
        }

        (function (d, c) {
            "object" === typeof exports && "undefined" !== typeof module ? module.exports = c() : "function" === typeof define && define.amd ? define(c) : (d = d || self, d.currency = c())
        })(this, function () {
            function d(b, a) {
                if (!(this instanceof d)) return new d(b, a);
                a = Object.assign({}, m, a);
                var f = Math.pow(10, a.precision);
                this.intValue = b = c(b, a);
                this.value = b / f;
                a.increment = a.increment || 1 / f;
                a.groups = a.useVedic ? n : p;
                this.s = a;
                this.p = f
            }

            function c(b, a) {
                var f = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : !0, c = a.decimal,
                    g = a.errorOnInvalid;
                var e = Math.pow(10, a.precision);
                var h = "number" === typeof b;
                if (h || b instanceof d) e *= h ? b : b.value; else if ("string" === typeof b) g = new RegExp("[^-\\d" + c + "]", "g"), c = new RegExp("\\" + c, "g"), e = (e *= b.replace(/\((.*)\)/, "-$1").replace(g, "").replace(c, ".")) || 0; else {
                    if (g) throw Error("Invalid Input");
                    e = 0
                }
                e = e.toFixed(4);
                return f ? Math.round(e) : e
            }

            var m = {
                symbol: "$",
                separator: ",",
                decimal: ".",
                formatWithSymbol: !1,
                errorOnInvalid: !1,
                precision: 2,
                pattern: "!#",
                negativePattern: "-!#"
            }, p = /(\d)(?=(\d{3})+\b)/g, n = /(\d)(?=(\d\d)+\d\b)/g;
            d.prototype = {
                add: function (b) {
                    var a = this.s, f = this.p;
                    return d((this.intValue + c(b, a)) / f, a)
                }, subtract: function (b) {
                    var a = this.s, f = this.p;
                    return d((this.intValue - c(b, a)) / f, a)
                }, multiply: function (b) {
                    var a = this.s;
                    return d(this.intValue * b / Math.pow(10, a.precision), a)
                }, divide: function (b) {
                    var a = this.s;
                    return d(this.intValue / c(b, a, !1), a)
                }, distribute: function (b) {
                    for (var a = this.intValue, f = this.p, c = this.s, g = [], e = Math[0 <= a ? "floor" : "ceil"](a / b), h = Math.abs(a - e * b); 0 !== b; b--) {
                        var k = d(e / f, c);
                        0 < h-- && (k = 0 <= a ? k.add(1 / f) : k.subtract(1 /
                            f));
                        g.push(k)
                    }
                    return g
                }, dollars: function () {
                    return ~~this.value
                }, cents: function () {
                    return ~~(this.intValue % this.p)
                }, format: function (b) {
                    var a = this.s, c = a.pattern, d = a.negativePattern, g = a.formatWithSymbol, e = a.symbol,
                        h = a.separator, k = a.decimal;
                    a = a.groups;
                    var l = (this + "").replace(/^-/, "").split("."), m = l[0];
                    l = l[1];
                    "undefined" === typeof b && (b = g);
                    return (0 <= this.value ? c : d).replace("!", b ? e : "").replace("#", "".concat(m.replace(a, "$1" + h)).concat(l ? k + l : ""))
                }, toString: function () {
                    var b = this.s, a = b.increment;
                    return (Math.round(this.intValue /
                        this.p / a) * a).toFixed(b.precision)
                }, toJSON: function () {
                    return this.value
                }
            };
            return d
        });

        function sum(obj) {
            let $sum = 0;

            $.each(obj, function (i, v) {

                $sum = $sum + v
            });
            return $sum;
        }


        Object.size = function (obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };


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
                $el.css({'background': 'red'})
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


        var $q_obj = {}, $ouy = {}, $sum = 0;
        $(document).ready(function () {


            var $a = 0;
            // console.log($a)
            $.each($('.wpp-as-options-flag:eq(0)').data('options'), function ($i, $v) {
                var $variant = '';
                //console.log($v)
                if ($v.default == true) {


                    $a++;
                    $variant = $i.split('-');


                    $.each($variant, function ($f, $b) {
                        var $el = $('.wpp-variation[value="' + $b + '"]');

                        wppChecker($el)
                    });

                }


            });

            if ($a === 0) {
                var $el = $('.wpp-variation').eq(0);
                wppChecker($el)
            }


            $('.wpp-as-options-flag').each(function () {
                var $options = $(this).data('options'),
                    $size = Object.size($options),
                    $post = $(this).data('product');

                $.each($options, function (i, v) {

                    if ($size == 1) {
                        $q_obj[$post] = i;
                        $ouy [$post] = parseInt(v.opt_price, 10);
                    } else if (v.default == true) {
                        $q_obj[$post] = i;
                        $ouy [$post] = parseInt(v.opt_price, 10);

                    }

                })
            });

            $.each($ouy, function (i, v) {
                $sum = $sum + parseInt(v, 10);
            });

        });


        $(document).on('change', '.wpp-variation', function () {

            var $el = $(this),
                $attr = $('#wpp-as-options-flag').data('options'),
                $row_index = $el.parents('.product-attribute--gutter').index(),
                $option = $el.val(),
                $assembly_wrap = $('#assembly').parent(),
                $paint_wrap = $('#paint').parent();

            wppChecker($el);
            var $val = [];

            $('.wpp-variation:checked').each(function () {
                if ($(this).attr('disabled') !== 'disabled') {
                    $val.push($(this).val());
                }
            });
            var $vall = $val.join('-');


            $('.wpp-as-options-flag').each(function () {
                var $options = $(this).data('options'),
                    $size = Object.size($options),
                    $post = $(this).data('product');

                var testArray = $vall in $options;

                if (testArray == true) {
                    $.each($options, function (i, v) {
                        if (i == $vall) {
                            $q_obj[$post] = i;
                            $ouy [$post] = parseInt(v.opt_price, 10);
                        }
                    })
                }


            });


            var $sum = 0;
            $.each($ouy, function (i, v) {
                $sum = $sum + parseInt(v, 10);
            });


            var ids = '',
                variants = '',
                flag = 1,
                simbol = $('.wpp-bundle-price').find('.woocommerce-Price-currencySymbol').html();


            $.each($q_obj, function (i, v) {
                    var sep = flag == 1 ? '' : ',';
                    ids = ids + sep + i;
                    variants = variants + sep + v;
                    flag++;
                }
            )

            var url = '<?php echo get_home_url();?>/?bundle=<?php echo get_queried_object_id(); ?>&add-to-cart=' + ids + '&wpp_add_variants=' + variants;


            $('.wpp-bundle-price .woocommerce-Price-amount').html(currency($sum, {
                separator: ' ',
                precision: 0
            }).format() + '&nbsp;<span class="woocommerce-Price-currencySymbol">' + simbol + '</span>');

            if ($('[name="b_paint"]').is(":checked")) {
                url = UpdateQueryString('b_paint', 1, url)
            } else {
                url = UpdateQueryString('b_paint', '', url)
            }

            if ($('input#assembly').is(":checked")) {
                url = UpdateQueryString('b_assembly', 1, url)
            } else {
                url = UpdateQueryString('b_assembly', '', url)
            }

            $('.wpp-add-cart-bundle').attr('href', url);


        });

        $(document).on('change', '[name="b_paint"]', function (e) {
            e.preventDefault();
            if ($(this).is(":checked")) {
                var $url = UpdateQueryString('b_paint', 1, $('.wpp-add-cart-bundle').attr('href'))
            } else {
                var $url = UpdateQueryString('b_paint', '', $('.wpp-add-cart-bundle').attr('href'))
            }
            // console.log($url)
            $('.wpp-add-cart-bundle').attr('href', $url)
        })

        $(document).on('change', 'input#assembly', function (e) {
            e.preventDefault();
            if ($(this).is(":checked")) {
                var $url = UpdateQueryString('b_assembly', 1, $('.wpp-add-cart-bundle').attr('href'))
            } else {
                var $url = UpdateQueryString('b_assembly', '', $('.wpp-add-cart-bundle').attr('href'))
            }
            // console.log($url)
            $('.wpp-add-cart-bundle').attr('href', $url)
        })


    })(jQuery);
</script>
</body>
</html>