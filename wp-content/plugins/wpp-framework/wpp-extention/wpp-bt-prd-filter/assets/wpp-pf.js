jQuery(document).ready(function ($) {


    /**
     * Проверка файцла
     */
    $('#cr-submit-csv').click(function (e) {
        e.preventDefault();

        $('p.submit').hide().after('<h1 class="cr-loader">DATA PREPARATION</h1>');
        setInterval(function () {
            $('.cr-loader').toggleClass('active');
        }, 400);
        $('.appender').empty();

        var data = {
            action: 'cr_action',
            security: WppPf.security,
        };

        $.post(ajaxurl, data, function ($response) {

            if ($response.success) {
                $('.loader').attr('data-start', $response.data.count);
                $('.cr-loader').remove();
                senders($response.data.count);
            }

        });
    });


    /**
     * Обработка массива
     */

    jQuery(document).ready(function ($) {


        /**
         * Проверка файцла
         */
        $('#cr-submit-csv').click(function (e) {
            e.preventDefault();

            $('p.submit').hide().after('<h1 class="cr-loader">DATA PREPARATIONХ</h1>');
            setInterval(function () {
                $('.cr-loader').toggleClass('active');
            }, 400);
            $('.appender').empty();

            var data = {
                action: 'cr_action',
                security: WppPf.security,
            };

            $.post(ajaxurl, data, function ($response) {

                if ($response.success) {
                    $('.loader').attr('data-start', $response.data.count);
                    console.log('start ' + $response.data.count);
                    $('.cr-loader').remove();
                    senders($response.data.count);
                }
                //

            });
        });


        /**
         * Обработка массива
         */
        function senders($n) {

            var data = {
                action: 'cr_load_import',
                security: WppPf.security,
                offset: $n
            };


            $.post(ajaxurl, data, function ($response) {

                if ($response.data.count) {

                    $str = $response.data.message;
                    $count = parseInt($response.data.count, 10);

                    if ($count >= 0) {
                        $var = 100 - (100 * $count / parseInt($('.loader').data('start'), 10));

                        $('.loader span').html(Math.round(($var) * 100) / 100 + '% / ' + $count);
                        $('.loader .indnicator').css('width', $var + '%');

                        if ($str) {
                            $('.appender').prepend($str);
                        }

                        if ($('.wpp-results').length > 50) {
                            $('.wpp-results:last').remove();
                        }
                        console.log($count);
                        senders($count);

                    } else {
                        $('.loader span').html('100%');
                        $('.loader .indnicator').css('width', '100%');
                        return false;
                    }
                }

            });

        }


    });

});
