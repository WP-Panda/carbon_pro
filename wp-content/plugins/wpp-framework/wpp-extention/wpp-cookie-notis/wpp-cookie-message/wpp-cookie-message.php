<?php
	/**
	 * Created by PhpStorm.
	 * User: WP Pands
	 * Date: 24.05.2017
	 * Time: 5:35
	 */

	function wpp_cookie_message_window() {

		if ( !empty( $_COOKIE[ '_conf_pol' ] ) ) {
			return null;
		}

		wp_enqueue_script( 'wpp_js_action' );
		wp_enqueue_style( 'wpp_js_cookie' );

		$html = <<<WINDOW
		<div class="wpp-cookie-message">
		    <span class="wpp-close-cookie">
				<?xml version="1.0"?>
				        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 44.238 44.238" style="enable-background:new 0 0 44.238 44.238;" xml:space="preserve" class=""><g><g>
					<g>
						<g>
							<path d="M15.533,29.455c-0.192,0-0.384-0.073-0.53-0.22c-0.293-0.293-0.293-0.769,0-1.062l13.171-13.171     c0.293-0.293,0.768-0.293,1.061,0s0.293,0.768,0,1.061L16.063,29.235C15.917,29.382,15.725,29.455,15.533,29.455z" data-original="#000000" class="active-path" data-old_color="#F3F3F3" fill="#FFFFFF"/>
						</g>
						<g>
							<path d="M28.704,29.455c-0.192,0-0.384-0.073-0.53-0.22L15.002,16.064c-0.293-0.293-0.293-0.768,0-1.061s0.768-0.293,1.061,0     l13.171,13.171c0.293,0.293,0.293,0.769,0,1.062C29.088,29.382,28.896,29.455,28.704,29.455z" data-original="#000000" class="active-path" data-old_color="#F3F3F3" fill="#FFFFFF"/>
						</g>
						<path d="M22.119,44.237C9.922,44.237,0,34.315,0,22.12C0,9.924,9.922,0.001,22.119,0.001S44.238,9.923,44.238,22.12    S34.314,44.237,22.119,44.237z M22.119,1.501C10.75,1.501,1.5,10.751,1.5,22.12s9.25,20.619,20.619,20.619    s20.619-9.25,20.619-20.619S33.488,1.501,22.119,1.501z" data-original="#000000" class="active-path" data-old_color="#F3F3F3" fill="#FFFFFF"/>
					</g>
				</g></g> </svg>
		    </span>
		    <p>
		        Нажимая на кнопку "Согласиться" вы даете разрешение на использхование cookie, чтобы анализировать трафик, подбирать для вас подходящий контент и рекламу, а также дать вам
		        возможность делиться информацией в социальных сетях. Мы передаем информацию о ваших действиях на сайте партнерам Google: социальным сетям и компаниям, занимающимся рекламой
		        и веб-аналитикой. Наши партнеры могут комбинировать эти сведения с предоставленной вами информацией, а также данными, которые они получили при использовании вами их
		        сервисов <a href="how-we-use-cookies">Подробнее</a>
		    </p>
		    <a class="wpp-cookie-apply" href="javascript:void(0);">Согласиться</a>
		</div>
WINDOW;

		echo $html;

	}

	add_action( 'wp_footer', 'wpp_cookie_message_window' );

	function wpp_cookie_assets() {

		if ( empty( $_COOKIE[ '_conf_pol' ] ) ) {
			wp_register_script( 'wpp_js_cookie', wpp_fr()->plugin_url() . '/wpp-extention/wpp-cookie-notis/wpp-cookie-message/js.cookie.js', [ 'jquery' ], '1.0.0', true );
			wp_register_script( 'wpp_js_action', wpp_fr()->plugin_url() . '/wpp-extention/wpp-cookie-notis/wpp-cookie-message/wpp-js-window-action.js', [ 'wpp_js_cookie' ], '1.0.0', true );
			wp_register_style( 'wpp_js_cookie', wpp_fr()->plugin_url() . '/wpp-extention/wpp-cookie-notis/wpp-cookie-message/stile.css', null, '1.0.0' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'wpp_cookie_assets' );