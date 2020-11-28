jQuery(function ($) {


    $.when(

        $.Deferred(function (deferred) {
            $(deferred.resolve);
        })
    ).done(function () {
    });


    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };


    /**
     * Смена валюты
     */
    $(document).on('click', '.wpp-cur-swith', function (e) {
        e.preventDefault();

        var $saved_cart = getUrlParameter('saved-cart'),
            $s_lng = getUrlParameter('s-lng'),
            $s_cur = getUrlParameter('s-cur'),
            $url = location.protocol + '//' + location.host + location.pathname;

        var $n = 0;
        if ($saved_cart) {
            var $cart = '&saved-cart=' + $saved_cart;
            $n++;
        } else {
            var $cart = '';
        }

        if ($s_lng) {
            var $lng = '&s-lng=' + $s_lng;
            $n++;
        } else {
            var $lng = '';
        }

        var $preff = $n > 0 ? '?' : '',
            $url_target = $url + $preff + $cart + $lng,
            $cur = $(this).data('cur');

        Cookies.set('wpp_currency', $cur, {expires: 365});

        var $data = {
            action: 'wpp_recalculate_cart',
        };

        $.post(WppAjaxAct.ajax_url, $data, function ($response) {

            if ($response.success) {
                window.location.href = $url_target;
            } else {

            }
        });

    });


    /**
     *  Смена Языка
     */
    $(document).on('click', '.wpp-lng-swith', function (e) {
        e.preventDefault();

        var $saved_cart = getUrlParameter('saved-cart'),
            $s_lng = getUrlParameter('s-lng'),
            $s_cur = getUrlParameter('s-cur'),
            $url = location.protocol + '//' + location.host + location.pathname;

        var $n = 0;
        if ($saved_cart) {
            var $cart = '&saved-cart=' + $saved_cart;
            $n++;
        } else {
            var $cart = '';
        }

        if ($s_cur) {
            var $curs = '&s-cur=' + $s_cur;
            $n++;
        } else {
            var $curs = '';
        }

        var $preff = $n > 0 ? '?' : '',
            $url_target = $url + $preff + $cart + $curs,
            $cur = $(this).data('lng');
        Cookies.set('wpp_lng', $cur, {expires: 365});
        window.location.href = $url_target;
    });


    //Добавление класса к картинке для видимости
    $(document).on({
        mouseenter: function () {
            $(this).addClass('opened');
        },
        mouseleave: function () {
            $(this).removeClass('opened');
        }
    }, '.wpp-grid_imgs');


    //Класс для показа списка действий
    $(document).on('click', '.wpp-btn-more', function (e) {
        let $el = $(this).parent();

        if ($el.hasClass('opened-list')) {
            $el.removeClass('opened-list');
        } else {
            $el.addClass('opened-list');
        }

    });

    // Зкрытие листа действий элемета по клику вне него
    $(document).mouseup(function (e) {
        var container = $(".wpp-acton-list"),
            container_2 = $(".more-share-popup");
        if ($(e.target).attr('class') === 'wpp-btn-more' && $(e.target).parent().hasClass('opened-list')) {
            return false;
        }
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.wpp-right-nav-list').removeClass('opened-list');
        }

        if (!container_2.is(e.target) && container_2.has(e.target).length === 0) {
            $('.wpp-right-nav-list').removeClass('opened-shared');
        }
    });

    //Переход по ссылке удалить отредактировать
    /*$(document).on('click', '.wpp-del-post-li,.wpp-ed-post-li', function (e) {
        window.location.href = $(this).find('span').data('href');
    });*/


    //обработка клипбордав
    var $btnCopy = $('.wpp-copy-post-li');

    $btnCopy.on('click', function () {
        var clipboard = new ClipboardJS('.wpp-copy-post-li');

        clipboard.on('success', function (e) {
            $btnCopy.find('.wpp-c-t').text(WppAjaxAct.copied);

            setTimeout(function () {
                $btnCopy.find('.wpp-c-t').text(WppAjaxAct.copy);
            }, 2000);
        });
    });

    $(document).on('click', '.wpp-share-post-li', function (e) {
        var $wrap = $(this).parents('.wpp-right-nav-list'),
            $popup = $wrap.find('.more-share-popup');
        console.info('Pop', $popup.attr('class'));
        $wrap.addClass('opened-shared');
        //$('.wpp-acton-list').hide();
    });


    function WppSwiper() {

    }

    $(document).ready(function () {
        if ($('.swiper-container').length) {
            console.info('swiper', 'yes');
            var swiper = new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '"></span>';
                    },
                },
            });
        }
    });

    $(document).ready(function () {
        $('.wpp-slick').not('.slick-initialized').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
            arrows: true
        });
    });

    $(".news-teaser__list--slider").not('.slick-initialized').slick({
        arrows: !0,
        asNavFor: ".news-teaser__list--cover",
        centerMode: !0,
        centerPadding: "0",
        dots: !1,
        focusOnSelect: !0,
        infinite: !1,
        lazyLoad: "ondemand",
        responsive: [{
            breakpoint: 576,
            settings: {
                asNavFor: null,
                slidesToShow: 1
            }
        }],
        slidesToScroll: 1,
        slidesToShow: 3,
        speed: 200
    });


    $(window).ready(function () {
        $('[class^="picture-teaser-container"]').on("init", function (event, slick, currentSlide, nextSlide) {

            $('.picture-teaser-content').css('opacity', 1)
        }).on("init afterChange", function (event, slick, currentSlide, nextSlide) {
            var videos = $("[data-slick] .slick-slide").find("video:not(.lazy)");
            if (videos && 0 < videos.length) {
                videos.get(0).pause();
                var current = $("[data-slick] .slick-active").find("video").get(0);
                current && current.play()
            }
        }),
        0 < $("[data-slick]").length && ($("[data-slick]").not('.slick-initialized').slick({})),
        0 < $(".news-teaser__list--cover").length && ($(".news-teaser__list--cover").not('.slick-initialized').slick({
            arrows: true,
            asNavFor: ".news-teaser__list--slider",
            fade: false,
            //'accessibility' : true,
            lazyLoad: "ondemand",
            slidesToScroll: 1,
            slidesToShow: 1,
            speed: 200
        }))
    });

    var debug = !1
        , galleryOptions = {
        isAnimated: !1,
        inViewStatus: [],
        inviewOptions: {
            threshold: null
        }
    };


    var flickityConfiguration, onePictureMode = 1 === $(".carousel").find(".carousel-cell").length,
        ua = window.navigator.userAgent, isIe = 0 < ua.indexOf("Trident/") || 0 < ua.indexOf("Edge");
    flickityConfiguration = onePictureMode ? {
        draggable: !1,
        imagesLoaded: !0,
        pageDots: !1,
        prevNextButtons: !1
    } : {
        draggable: !isIe,
        selectedAttraction: .01,
        friction: .18,
        imagesLoaded: !0,
        contain: !1,
        cellAlign: "left",
        percentPosition: !1,
        freeScroll: !0,
        wrapAround: !0,
        pageDots: !1
    };


    var $carousel = $(".carousel");

    $(".hone-news-productcts").flickity({
        pageDots: !1,
        imagesLoaded: true,
        arrowShape: {
            x0: 30,
            x1: 55, y1: 50,
            x2: 60, y2: 50,
            x3: 35
        },
        wrapAround: true
    });


    $carousel.flickity(flickityConfiguration);

    var _height = $(".carousel-cell img").height() / 2;
    galleryOptions.inviewOptions = {
        threshold: _height
    }

   /* $(window).on("resize scroll", function () {
        $(".carousel.flickity-enabled").each(function (index, elem) {
            galleryOptions.inViewStatus[index] = $(elem).isInViewport(galleryOptions.inviewOptions),
            $(elem).isInViewport(galleryOptions.inviewOptions) && !galleryOptions.isAnimated && ($(elem).flickity("next"),
                galleryOptions.isAnimated = !0)
        }),
        console.log(galleryOptions.inViewStatus)
    });
    $(window).trigger("resize");*/


    $(document).on('click', '.menu-maker-trigger', function (e) {
        e.preventDefault();
        var $text = $(this).data('text');

        if ($(this).hasClass('wpp-mi-active')) {
            $('.menu-maker-items-wrap').slideUp();
            $(this).removeClass('wpp-mi-active')
        } else {
            $('.menu-maker-items-wrap').slideDown();
            $(this).addClass('wpp-mi-active').text($text);
            $('.menu-model-items-wrap').html('');
            $('.menu-model-trigger').addClass('wpp-disabled').removeClass('wpp-mi-active')
        }

    });

    $(document).on('click', '.menu-model-trigger', function (e) {
        e.preventDefault();

        if ($(this).hasClass('wpp-disabled')) {
            return false;
        }

        if ($(this).hasClass('wpp-mi-active')) {
            $('.menu-model-items-wrap').slideUp();
            $(this).removeClass('wpp-mi-active')
        } else {
            $('.menu-model-items-wrap').slideDown();
            $(this).addClass('wpp-mi-active');
        }

    });

    $(document).on('click', '.menu-maker-link', function (e) {
        e.preventDefault();

        var $el = $(this),
            $text = $el.text(),
            $models_wrap = $('.menu-model-items-wrap');


        if ($el.data('child').length > 0) {

            var $child = $el.data('child');

            $models_wrap.html('');
            $el.attr('data-text', $text);
            $('.menu-maker-items-wrap').slideUp();
            $('.menu-maker-trigger').text($text).removeClass('wpp-mi-active');

            $.each($child[0], function (i, v) {
                $.each(v, function (a, f) {
                    $models_wrap.append('<li><a href="' + f.link + '" title="">' + f.name + '</a></li>');
                });
            });

            $('.menu-model-trigger').removeClass('wpp-disabled').addClass('wpp-mi-active');
            $('.menu-model-items-wrap').slideDown();
        }

    });


    $slick = $('.slider-main');
    // new slick
    $('[class^="picture-teaser-container"]').on("init", function (event, slick, currentSlide, nextSlide) {

        $('.picture-teaser-content').css('opacity', 1);
        $('.wpp_hero figure.dark.picture-teaser--opaque:not(:first-child)').css({'display': 'block'});
        $('.fullscreen-slider img').css({'height': 'auto'});

    }), $slick.slick({
        //centerMode: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        asNavFor: '.slider-nav',
        autoplay: false,
        pauseOnDotsHover: false,
        pauseOnHover: false,
        pauseOnFocus: true
    });

    $slick.on({
        afterChange: function (event, slick, currentSlide, nextSlide) {
            $('.cur-text').text(((currentSlide ? currentSlide : 0) + 1) + '/' + slick.slideCount);
        },
        init: function (event, slick) {
            $('.row.picture-teaser-content').css({'opacity': 1});
        }
    });


    $slick_nav = $('.slider-nav');
    $('.slider-nav').on("init", function (event, slick, currentSlide, nextSlide) {

        let $slide_width = $(window).width() / (Math.floor($(window).width() / 120));
        $('.slider-nav .slick-slide img').css('width', $slide_width);
        $('.cur-text').css({'top': Math.floor($('.slider-nav').offset().top) - 60 + 'px'});

        $('.progress').each(function (i) {
            $(this).attr('data-slick-index', $(this).parent().data('slick-index'));
        });


    }), $slick_nav.slick({
        centerMode: true,
        focusOnSelect: true,
        asNavFor: ".slider-main",
        slidesToShow: Math.floor($(window).width() / 120),
        slidesToScroll: 1,
        arrows: false,
        centerPadding: 0,
        infinite: true,
    });

    var time = 10;
    var $bar,
        $slick,
        isPause,
        tick,
        percentTime;

    $bar = $('.progress');

    $('.slider-wrapper').on({
        mouseenter: function () {
            isPause = true;
        },
        mouseleave: function () {
            isPause = false;
        }
    })

    function startProgressbar() {
        resetProgressbar();
        percentTime = 0;
        isPause = false;
        tick = setInterval(interval, 10);
    }

    function interval() {
        if (isPause === false) {
            percentTime += 1 / (time + 0.1);
            $bar.css({
                width: percentTime + "%"
            });
            if (percentTime >= 100) {
                $slick_nav.slick('slickNext');
                startProgressbar();
            }
        }
    }


    function resetProgressbar() {
        $bar.css({
            width: 0 + '%'
        });
        clearTimeout(tick);
    }

    startProgressbar();


});