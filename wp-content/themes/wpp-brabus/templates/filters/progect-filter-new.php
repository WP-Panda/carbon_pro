<?php
	/**
	 * @package carbon.coms
	 * @author  WP_Panda
	 * @version 1.0.0
	 */

	defined( 'ABSPATH' ) || exit;
	$filters_data = wpp_br_sort_filter_args();


?>
<style>
    .filter-select {
        width: 100%;
        border: 1px solid #afafaf;
        padding: 10px 5px 7px;
    }

    form#wpp-project-filter select {
        font-size: 17px;
    }

    .show-drop .filter-select {
        border: none;
        padding: 0;
    }

    .filter-select button {
        width: 100%;
        background-color: #fff;
        border: none;
        text-align: left;
        font-size: 16px;
        color: #353535;
        position: relative;
    }

    .filter-select button:focus {
        border: none;
        outline: none;
    }

    .show-drop .filter-select button {
        display: none;
    }

    button:after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 32px;
        height: auto;
        content: "";
        opacity: .543;
        background: url("data:image/svg+xml;charset=utf-8,<svg width='24' height='24' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M15.483 9.297l-3.9 3.9-3.9-3.9a.99.99 0 00-1.4 1.4l4.593 4.593a1 1 0 001.414 0l4.593-4.593a.99.99 0 10-1.4-1.4z'/></svg>") 50% 50% no-repeat;
        transition: transform .1s ease-out;
    }

    .filter-drop {
        display: none;
        position: absolute;
        z-index: 99;
        background-color: #fff;
        width: calc(100% - 21px);
        border: 1px solid #d2d2d2;
        box-sizing: border-box;
        max-height: 400px;
        overflow-y: auto;
    }

    .filter-select input:focus {
        outline: none;
        border: 1px solid #000;
    }

    .filter-select input {
        width: 100%;
        padding: 10px 5px 7px;
        border: 1px solid #eee;
        border-radius: 0;
        box-shadow: none;
        box-sizing: border-box;
    }

    .filter-drop ul {
        padding: 10px 10px 10px 35px;
        list-style: none;
        font-size: 18px;
    }

    .filter-drop ul ul {
        padding: 0 0 0 15px;
        display: none;
    }

    li .check-filter:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        left: 7px;
        background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='10'><path d='M.295 5.477a.951.951 0 010-1.378l-.033.031a1.042 1.042 0 011.437.004l2.88 2.77L11.464.282a1.045 1.045 0 011.431.001l-.033-.031a.949.949 0 01-.004 1.382L5.3 8.902a1.053 1.053 0 01-1.438.005L.295 5.477z'/></svg>") 50% 50% no-repeat;
    }

    li.filter-sublavel .trigger:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        background: url("data:image/svg+xml;charset=utf-8,<svg width='25' height='25' xmlns='http://www.w3.org/2000/svg'><path fill='currentColor' d='M12.857 11.143h4.286a.857.857 0 010 1.714h-4.286v4.286a.857.857 0 01-1.714 0v-4.286H6.857a.857.857 0 010-1.714h4.286V6.857a.857.857 0 011.714 0v4.286z'></path></svg>");

    }

    li.filter-sublavel .trigger.opened:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 384 512' ><path fill='currentColor' d='M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z' class=''></path></svg>");
        background-repeat: no-repeat;
        background-position: 50%;
    }

    .filter-drop li {
        padding-top: 10px;
        cursor: pointer;
    }

    .filter-drop li .filter-item:hover {
        border-bottom: 1px solid #d2d2d2;
    }

    span.trigger {
        cursor: pointer;
        border: 1px solid;
        width: 27px;
        height: 27px;
        position: absolute;
        right: 23px;
        border-radius: 5px;
        margin-top: 6px;
    }

    span.trigger:hover {
        background-color: #eee;
        border-color: #eee;
    }

    input#filter-models-str {
        position: absolute;
        left: 0;
        top: 0;
        background-color: transparent;
    }

    .wpp-mix-filter-wrap select {
        border: 1px solid #afafaf;
    }

    form#wpp-form-filter select, .wpp-form-filter select {
        margin: 0 !important;
    }

    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/
    /*************************************************************/

    form#wpp-project-filter {
        float: left;
        margin-bottom: 24px;
        padding: 20px;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(0, 0, 0, .12);
    }

    .wpp-f-active-button {
        width: 100%;
        background-color: #fff;
        border: none;
        text-align: left;
        font-size: 16px;
        color: #353535;
        position: relative;
    }

    .wpp-f-active-button:after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 32px;
        height: auto;
        content: "";
        opacity: .543;
        background: url("data:image/svg+xml;charset=utf-8,<svg width='24' height='24' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M15.483 9.297l-3.9 3.9-3.9-3.9a.99.99 0 00-1.4 1.4l4.593 4.593a1 1 0 001.414 0l4.593-4.593a.99.99 0 10-1.4-1.4z'/></svg>") 50% 50% no-repeat;
        transition: transform .1s ease-out;
    }

    .wpp-f-active-button:focus {
        border: none;
        outline: none;
    }

    .wpp-f-trigger-group {
        width: 100%;
        border: 1px solid #afafaf;
        padding: 10px 5px 7px;
        position: relative;
    }

    ul.wpp-f-drop-list {
        list-style: none;
        display: none;
        font-size: 18px;
        font-weight: 100;
        padding: 0;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 30px 0 rgba(0, 0, 0, .1);
        animation-duration: .3s;
        animation-timing-function: ease-in-out;
        animation-fill-mode: forwards;
        position: absolute;
        width: calc(100% - 15px);
        z-index: 99;
    }

    li.wpp-f-all {
        border-bottom: 1px solid #eee;
    }

    li.wpp-f-all:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        left: 10px;
        background: url("data:image/svg+xml;charset=utf-8,<svg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' fill='%23757575' d='M10.586 12L7.05 8.464A1 1 0 018.464 7.05L12 10.586l3.536-3.536a1 1 0 011.414 1.414L13.414 12l3.536 3.536a1 1 0 01-1.414 1.414L12 13.414 8.464 16.95a1 1 0 01-1.414-1.414L10.586 12z'/></svg>") 50% 50% no-repeat;

    }

    li.wpp-f-all:hover:after {
        background: url("data:image/svg+xml;charset=utf-8,<svg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' fill='%23ffffff' d='M10.586 12L7.05 8.464A1 1 0 018.464 7.05L12 10.586l3.536-3.536a1 1 0 011.414 1.414L13.414 12l3.536 3.536a1 1 0 01-1.414 1.414L12 13.414 8.464 16.95a1 1 0 01-1.414-1.414L10.586 12z'/></svg>") 50% 50% no-repeat;
    }

    ul.wpp-f-drop-list li {
        cursor: pointer;
        line-height: 26px;
        position: relative;
    }

    span.f-item-text, .wpp-f-all {
        width: 100%;
        display: inline-block;
        padding: 8px 10px 5px 40px;
    }

    ul.wpp-f-drop-list li .f-item-text:hover, .wpp-f-all:hover {
        background-color: #256dc7;
        color: #fff;
    }

    ul.wpp-f-drop-list li.checked_item:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        left: 10px;
        margin-top: 6px;
        top: 0;
        background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='10'><path d='M.295 5.477a.951.951 0 010-1.378l-.033.031a1.042 1.042 0 011.437.004l2.88 2.77L11.464.282a1.045 1.045 0 011.431.001l-.033-.031a.949.949 0 01-.004 1.382L5.3 8.902a1.053 1.053 0 01-1.438.005L.295 5.477z'/></svg>") 50% 50% no-repeat;
    }

    /* ul.wpp-f-drop-list li.checked_item:hover:after, {
		 background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='10'><path fill-rule='evenodd' fill='%23ffffff' d='M.295 5.477a.951.951 0 010-1.378l-.033.031a1.042 1.042 0 011.437.004l2.88 2.77L11.464.282a1.045 1.045 0 011.431.001l-.033-.031a.949.949 0 01-.004 1.382L5.3 8.902a1.053 1.053 0 01-1.438.005L.295 5.477z'/></svg>") 50% 50% no-repeat;

	 }*/

    input.wpp-f-search-input, input.wpp-f-display-input {
        position: absolute;
        left: 0;
        width: 100%;
        border: none;
        background-color: transparent;
        padding: 10px 0 8px 10px;
        top: 0;
    }

    input.wpp-f-search-input:focus, input.wpp-f-display-input:focus {
        outline: none;
    }

    input.wpp-f-display-input, input.wpp-f-display-input:focus {
        background-color: #fff;
    }

    input.wpp-f-display-input {
        background-color: transparent;
    }

    ul.wpp-f-drop-list ul {
        list-style: none;
        display: none;
        padding-left: 0;
    }

    ul.wpp-f-drop-list ul li .f-item-text {
        padding-left: 60px;
    }

</style>
<script>
    jQuery(function ($) {

        $(document).on('click', '.trigger', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation()
            let $_this = $(this),
                $_list = $_this.parent().find('ul').first();

            if (!$_this.hasClass('opened')) {
                $_list.show();
                $_this.addClass('opened');
            } else {
                $_list.hide();
                $_this.removeClass('opened');
            }

        })

        /**
         * Очистка поля
         */
        function wppResetField($el) {
            $el.find('button').html( $el.find('button').data('text')).css({'opacity': '1'});
            $el.find('input').val('').trigger('change');
            $el.find('li').removeClass('checked_item');
        }

        /**
         * Поиск по текстовому полю
         */
        function wppSearchLi($str, $el) {
            let $list = $el.parents('.wpp-form-row').find('.wpp-f-drop-list'),
                $display = $el.parents('.wpp-form-row').find('.wpp-f-display-input'),
                $btn = $el.parents('.wpp-form-row').find('button'),
                $_values_input = $el.parents('.wpp-form-row').find('.wpp-f-keys-input');
            $list.find('li:contains("' + $str + '")').show().siblings().hide();

            $display.val('').trigger('change');
            $_values_input.val('').trigger('change');
            $btn.html($btn.data('text'));


        }

        /**
         * Поиск по содержимому
         */
        $(document).on('keyup', '.wpp-f-search-input', function (e) {
            e.preventDefault();
            wppSearchLi($(this).val(), $(this));
        });

        /**
         * действия по кнопке фильтра
         */
        $(document).on('click', '.wpp-f-active-button', function (e) {
            e.preventDefault();

            let $_btn = $(this),
                $_trigger_wrap = $_btn.parent('.wpp-f-trigger-group'),
                $_display_input = $_trigger_wrap.find('.wpp-f-display-input'),
                $_search_input = $_trigger_wrap.find('.wpp-f-search-input'),
                $_values_input = $_trigger_wrap.find('.wpp-f-keys-input'),
                $_drop_list = $_btn.parents('.wpp-form-row').find('.wpp-f-drop-list');

            //показываю список
            $_drop_list.show()
            //убираю кнопку
            $_btn.css('opacity', '0');

            //показываю интпуты и устанавливаю фокус
            $_display_input.attr('type', 'text');
            $_search_input.attr('type', 'text').focus();

        });

        /**
         * Действия по элеиенту фильтра
         */
        $(document).on('click', '.wpp-f-drop-list li', function (e) {
            e.preventDefault();

            let $_this = $(this),
                $_parent_row = $_this.parents('.wpp-form-row'),
                $_drop_list = $_this.parent('.wpp-f-drop-list'),
                $_display_input = $_this.parents('.wpp-form-row').find('.wpp-f-display-input'),
                $_display_btn = $_this.parents('.wpp-form-row').find('button'),
                $_search_input = $_this.parents('.wpp-f-search-input'),
                $_values_input = $_this.parents('.wpp-form-row').find('.wpp-f-keys-input'),
                $_vals = [],
                $_labels = [];

            // не отчекиваем первый пункт
            if ($_this.hasClass('wpp-f-all')) {
                wppResetField($_this.parents('.wpp-filers-wrap'))
            } else {
                //убираем галку если есть
                if ($_this.hasClass('checked_item')) {
                    $_this.removeClass('checked_item');
                } else {

                    //убираю галку для первого пункта
                    if ($_parent_row.hasClass('car_brand_class')) {
                        $('.wpp-f-drop-list li').removeClass('checked_item');
                    }

                    //ставим галку если нет
                    $_this.addClass('checked_item');
                    $('.model-drop').html($_this.data('cars'));

                }

                // проходим выбранные пкнкты и набиваем маассивы значениями
                $_drop_list.find('.checked_item').each(function (i) {
                    $_labels.push($(this).find('.f-item-text').html());
                    $_vals.push($(this).data('value'));
                });

                // установка значений инпутов
                var $labels = $_labels.join(',');

                if ($labels.length > 0) {
                    $_display_btn.html($labels);
                } else {
                    $_display_btn.html($_display_btn.data('text'));
                }

                $_display_input.val($labels).trigger('change');

                $_values_input.val($_vals.join(',')).trigger('change');
                //сброс значения поиск
                $_search_input.val('').trigger('change');
            }

            window.history.pushState( '', '', '?' + $('#wpp-project-filter').serialize());


        });

        /**
         *
         */
        $(document).click(function () {

            let $_btn = $('.wpp-f-active-button'),
                $_trigger_wrap = $('.wpp-f-trigger-group'),
                $_display_input = $_trigger_wrap.find('.wpp-f-display-input'),
                $_search_input = $_trigger_wrap.find('.wpp-f-search-input'),
                $_drop_list = $_btn.parents('.wpp-form-row').find('.wpp-f-drop-list');

            if (!$(event.target).is(".wpp-filers-wrap *")) {
                $_drop_list.hide();
                $_drop_list.find('li').show();
                $_btn.css('opacity', '1');
                $_display_input.attr('type', 'hidden');
                $_search_input.attr('type', 'hidden').val('').trigger('change');
            }


        })


    })


</script>

<div class="container">
    <div class="wpp-filter-autoru">
        <form id="wpp-project-filter" class="wpp-form-filter row">

			<?php
				$html = '';
				$n    = 1;


				foreach ( $filters_data as $one_filter_key => $one_filter_item ) :
					if ( 'car_model' === $one_filter_key ) {
						continue;
					} else if ( 'car_brand' === $one_filter_key ) {
						$option = 'select_maker';
					} else {
						$option = 'tuning';
					}

					$html_s = '<li class="wpp-f-all">Любая</li>';
					//$html_s = '<li class="wpp-f-all">Любая' . /*wpp_br_lng( $option )*/ . '</li>';

					foreach ( $one_filter_item as $one_key => $one_name ) {

						if ( 'attach_brand' !== $one_filter_key ) {

							$data   = wpp_tax_tree_with_labels( [], '', false, 1, (int) $one_key );
							$json   = htmlspecialchars( $data );
							$html_s .= sprintf( '<li data-cars="%s" data-value=\'.t_%d\' ><span class="f-item-text">%s</span></li>', $json, (int) $one_key, $one_name );

						} else {
							$html_s .= sprintf( '<li data-value=\'.t_%d\' ><span class="f-item-text">%s</span></option>', (int) $one_key, $one_name );
						}
					}

					if ( $n === 2 ) {
						$html .= wpp_filter_data_wrap();
					}

					$labels    = wpp_br_lng( $option );
					$html_wrap = <<<WRAP
                        <div class="col-12 col-sm-4 col-lg-4 wpp-form-row wpp-filers-wrap %s_class">
                            <div class="wpp-f-trigger-group">
                                <button class="wpp-f-active-button" data-text="%s">%s</button>
                                <input type="hidden" class="wpp-f-display-input" name="">
                                <input type="hidden" class="wpp-f-search-input" name="">
                                <input type="hidden" class="wpp-f-keys-input wpp-mix-filter" value="%s" name="%s">
                            </div>
                            <ul class="wpp-f-drop-list">
                                %s
                            </ul>
                        </div>
WRAP;


					$html .= sprintf( $html_wrap, $one_filter_key,$labels, $labels,  !empty($_GET[$one_filter_key]) ? $_GET[$one_filter_key] : '',$one_filter_key, $html_s );
					$n ++;
				endforeach;
				echo $html; ?>
        </form>
    </div>
</div>
<script>
    jQuery(function ($) {
        /**
         * Только уникальнык значения в массиве
         */
        function onlyUnique(value, index, self) {
            return self.indexOf(value) === index;
        }


        /**
         * Подстатновка в чексписок моделей
         */
        $(document).on('change', '[name="car_brand"]', function (e) {
            e.preventDefault();
            var $html = $(this).find(':selected').data('cars');

            // Наполнение селекта моделей
            $('.filter-drop').html($html);
            //Очистка полей формы
            $('[name="attach_brand"],.filter-display,.filter-values').val("").removeAttr('disabled').trigger('change');
            //замена текста на кнопке
            $('.filter-select button').html('Все модели')

        });


        /**
         * Подстановка в тюнинг
         */
        $(document).on('change', '#filter-models-data', function (e) {
            e.preventDefault();

            var $arg = [];

            $('.check-filter').each(function () {
                var $tun = $(this).data('tun'),
                    $tun_arr = $tun.split(',');
                $.each($tun_arr, function (a, f) {
                    $arg.push(f)
                })

            });

            var $arg_unique = $arg.filter(onlyUnique);


            $('[name="attach_brand"]').find('option').each(function () {
                if (!$arg_unique.includes($(this).val())) {
                    $(this).attr('disabled', 'disabled')
                } else {
                    $(this).removeAttr('disabled')
                }
            })

        })

    })
</script>