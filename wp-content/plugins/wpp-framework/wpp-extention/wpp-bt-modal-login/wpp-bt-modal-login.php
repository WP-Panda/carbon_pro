<?php
/**
 * File Description
 *
 * @author  WP Panda
 *
 * @package Time, it needs time
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function custom_wp_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
	$key = get_password_reset_key( $user );
	$message  = sprintf( __( 'Username: %s' ), $user->user_email ) . "\r\n\r\n";
	$message .= __( 'To set your password, visit the following address:' ) . "\r\n\r\n";
	$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login  ), 'login' ) . ">\r\n\r\n";
	$wp_new_user_notification_email['message'] = $message;

	return $wp_new_user_notification_email;
}

add_filter( 'wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3 );

/**
 * Add Login logout Link
 */
add_filter( 'wp_nav_menu_items', 'wpp_bt_login_link', 10, 2 );
function wpp_bt_login_link( $items, $args ) {

	if ( $args->theme_location === 'top_nav' ) :
		$items .= wpp_get_bt_login_link();
	endif;

	return $items;
}

function wpp_get_bt_login_link() {

	$url    = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
	$title  = is_user_logged_in() ? 'My Account' : apply_filters('wpp_fr_login_link','Login/Register');
	$href   = is_user_logged_in() ? esc_url( $url ) : 'javascript:void(0);';
	$toggle = is_user_logged_in() ? null : ' data-toggle="modal" data-target="#loginRegisterModal" id="get_login_modal"';

	return sprintf( '<li><a href="%s" title=""%s>%s</a></li>', $href, $toggle, $title );


}

function wpp_al_assets() {
	if ( ! is_user_logged_in() ) :
		#$replace = str_replace( ABSPATH, get_home_url() . '/', __DIR__ );
		wp_enqueue_script( 'wpp-al-front', plugins_url( '/assets/wpp-al-login.js', __FILE__ ) . '', array( 'jquery' ), '1.0.0' );
		wp_localize_script( 'wpp-al-front', 'WppAl', array(
			'security' => wp_create_nonce( 'wpp-al-string' ),
			'ajaxurl'  => admin_url( 'admin-ajax.php' )
		) );
	endif;
}

add_action( 'wp_enqueue_scripts', 'wpp_al_assets' );

/**
 * Подгрузка нужгной формы
 */
add_action( 'wp_ajax_wpp_al_change_form', 'wpp_al_change_form' );
add_action( 'wp_ajax_nopriv_wpp_al_change_form', 'wpp_al_change_form' );
function wpp_al_change_form() {
	check_ajax_referer( 'wpp-al-string', 'security' );

	$target = ! empty( $_POST['target'] ) ? esc_attr( $_POST['target'] ) : null;

	if ( empty( $target ) ) {
		wp_send_json_error( array( 'message' => 'Target Not Data' ) );
	}

	ob_start();

	if ( $target === 'lost' ) {
		wpp_al_lost_form();
	} elseif ( $target === 'login' ) {
		wpp_al_login_form();
	} elseif ( $target === 'register' ) {
		wpp_al_register_form();
	} else {
		echo '';
	}

	$code = ob_get_clean();
	if ( empty( $code ) ) {
		wp_send_json_error( array( 'message' => 'Target Not Valid' ) );
	}

	wp_send_json_success( array( 'message' => 'Target Is Valid', 'form' => $code ) );
}

/**
 * Авторизация
 */
add_action( 'wp_ajax_wpp_al_login_send', 'wpp_al_login_send' );
add_action( 'wp_ajax_nopriv_wpp_al_login_send', 'wpp_al_login_send' );
function wpp_al_login_send() {
	check_ajax_referer( 'wpp-al-string', 'security' );

	if ( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {
		$user_name = sanitize_user( $_POST['log'] );
		$user      = get_user_by( 'login', $user_name );
		if ( ! $user && strpos( $user_name, '@' ) ) {
			$user = get_user_by( 'email', $user_name );
		}
		if ( $user ) {
			if ( get_user_option( 'use_ssl', $user->ID ) ) {
				$secure_cookie = true;
				force_ssl_admin( true );
			}
		}
	}


	$user = wp_signon( array(), $secure_cookie );
	wp_set_auth_cookie( $user->ID );
	if ( empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {

		if ( headers_sent() ) {
			/* translators: 1: Browser cookie documentation URL, 2: Support forums URL */
			wp_send_json_error(
				array(
					'message' => sprintf(
						__(
							'<strong>ERROR</strong>: Cookies are blocked due to unexpected output. For help, please see <a href="%1$s">this documentation</a> or try the <a href="%2$s">support forums</a>.'
						), __( 'https://codex.wordpress.org/Cookies' ), __( 'https://wordpress.org/support/' )
					)
				)
			);
		} elseif ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[ TEST_COOKIE ] ) ) {
			// If cookies are disabled we can't log in even with a valid user+pass
			/* translators: %s: Browser cookie documentation URL */
			wp_send_json_error(
				array(
					'message' => sprintf(
						__(
							'<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href="%s">enable cookies</a> to use WordPress.'
						), __( 'https://codex.wordpress.org/Cookies' )
					)
				)
			);
		}
	}

	$array = array(
		'empty_password'     => 'Password Is Empty!!!',
		'empty_username'     => 'Username Is Empty!!!',
		'invalid_username'   => 'Invalid Username',
		'incorrect_password' => 'Invalid Password',
	);

	if ( ! is_wp_error( $user ) ) {
		wp_send_json_success( array( 'message' => 'Your Authorisation Was Successful', 'redirect' => 'yes' ) );
	} else {
		$errors = $user;

		$message = ! empty( $array[ $errors->get_error_code() ] ) ? '<strong>ERROR</strong>:' . $array[ $errors->get_error_code() ] : 'Undefended Error';

		wp_send_json_error( array( 'message' => sprintf( '<p class="error wpp-al-error-message">%s</p>', $message ) ) );
	}

}

/**
 * Отправка регистрации
 */
add_action( 'wp_ajax_wpp_al_register_send', 'wpp_al_register_send' );
add_action( 'wp_ajax_nopriv_wpp_al_register_send', 'wpp_al_register_send' );
function wpp_al_register_send() {
	check_ajax_referer( 'wpp-al-string', 'security' );

	if ( ! get_option( 'users_can_register' ) ) {
		wp_send_json_error( array( 'message' => sprintf( '<p class="error wpp-al-error-message">%s</p>', __( 'User registration is currently not allowed.', 'wpp-fr' ) ) ) );
	}


	parse_str( $_POST['data'], $data );

	if ( empty( $data['user_login'] ) ) {
		wp_send_json_error( array( 'message' => __( 'Email field is Empty', 'wpp-fr' ) ) );
	}

	if ( empty( $data['term'] ) || ( ! empty( $data['term'] ) && (int) $data['term'] !== 1 ) ) {
		wp_send_json_error( array( 'message' => __( 'To register, you must accept the user agreement', 'wpp-fr' ) ) );
	}




	$user_email = wp_unslash( $data['user_login'] );
	$user_login = esc_attr( str_replace( array( '.', '@', '-' ), array( '_', '_', '_' ), $data['user_login'] ) );
	$errors     = register_new_user( $user_login, $user_email );

	if ( ! is_wp_error( $errors ) ) {
		if ( ! empty( $data['type'] ) && (int) $data['type'] === 1 ) {
			update_user_meta( $errors, 'type', 'yes' );
		}
		wp_send_json_success( array( 'message' => __( 'You will be able to set your password once you confirm your email address.', 'wpp-fr' ) ) );


	} else {
		$errorss = $messages = '';
		foreach ( $errors->get_error_codes() as $code ) {
			$severity = $errors->get_error_data( $code );
			foreach ( $errors->get_error_messages( $code ) as $error_message ) {
				if ( 'message' == $severity ) {
					$messages .= '	' . $error_message . "<br />\n";
				} else {
					$errorss .= '	' . $error_message . "<br />\n";
				}
			}
		}

		wp_send_json_error( array( 'message' => sprintf( '<p class="error wpp-al-error-message">%s%s</p>', $errorss, $messages ) ) );
	}


}


/**
 * Выкидвыаем /wp-login из письма
 */
add_filter( 'wp_new_user_notification_email', 'filter_function_name_5756', 10, 3 );
function filter_function_name_5756( $wp_new_user_notification_email, $user, $blogname ) {

	$wp_new_user_notification_email['message'] = str_replace( '/wp-login.php', '', $wp_new_user_notification_email['message'] );

	return $wp_new_user_notification_email;
}

/**
 * Куки при запросе пароля
 * @return bool
 */
function wpp_al_set_login_cookies() {
	if ( is_user_logged_in() ) {
		return false;
	}
	if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) && ( isset( $_GET['action'] ) && $_GET['action'] === 'rp' ) ) {

		if ( isset( $_POST['pass1'] ) && ! hash_equals( $_GET['key'], $_POST['rp_key'] ) ) {
			return false;
		}

		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
		$value     = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
		setcookie( $rp_cookie, $value, 0, '/', COOKIE_DOMAIN, is_ssl(), true );
	}

}

add_action( 'init', 'wpp_al_set_login_cookies' );


/**
 * Установка пароля
 */
add_action( 'wp_ajax_wpp_al_set_send', 'wpp_al_set_send' );
add_action( 'wp_ajax_nopriv_wpp_al_set_send', 'wpp_al_set_send' );
function wpp_al_set_send() {
	check_ajax_referer( 'wpp-al-string', 'security' );
	$pass = ! empty( $_POST['pass1'] ) ? $_POST['pass1'] : false;
	if ( empty( $pass ) ) {
		wp_send_json_error( array( 'message' => sprintf( '<p class="error wpp-al-error-message"><strong>ERROR</strong>%s</p>', __( 'Password is Empty' ) ) ) );
	}

	$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
	if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
		list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
		$user = check_password_reset_key( $rp_key, $rp_login );

		if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
			$user = false;
		}
	} else {
		$user = false;
	}

	if ( ! $user || is_wp_error( $user ) ) {
		setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, '/', COOKIE_DOMAIN, is_ssl(), true );
		if ( $user && $user->get_error_code() === 'expired_key' ) {
			$text = __( 'Expired key' );
		} else {
			$text = __( 'Invalid key' );
		}
		wp_send_json_error( array( 'message' => sprintf( '<p class="error wpp-al-error-message"><strong>ERROR</strong>%s</p>', $text ) ) );
	}

	reset_password( $user, $_POST['pass1'] );
	setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, '/', COOKIE_DOMAIN, is_ssl(), true );
	wp_send_json_success(
		array(
			'message' => sprintf(
				'<p class="success wpp-al-success-message">%s <a href="javascript:void(0)" title="" class="wpp-al-href" data-action="login">Login</a></p>',
				__( 'Your password has been set' )
			)
		)
	);

}


/**
 * Удаление woocommerce gbcmvf
 */

add_action( 'init', 'wpp_bt_reset_woocommerce_emails' );
function wpp_bt_reset_woocommerce_emails() {
	if ( class_exists( 'WC_Email_Customer_Reset_Password' ) ) :
		add_action( 'woocommerce_email', 'unhook_those_pesky_emails' );
		remove_filter( 'woocommerce_login_redirect', 'filter_woocommerce_login_redirect', 10, 2 );
		/*remove_action( 'wp_loaded', array ( 'WC_Form_Handler', 'process_registration' ), 20 );
		remove_action( 'wp_loaded', array ( 'WC_Form_Handler', 'process_login' ), 20 );
		remove_action( 'wp_loaded', array ( 'WC_Form_Handler', 'process_lost_password' ), 20 );
		remove_action( 'wp_loaded', array ( 'WC_Form_Handler', 'process_reset_password' ), 20 );*/
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10, 1 );
	endif;
}


function wpp_al_unhook_woocommerce_reset_password( $email_class ) {

	remove_action( 'woocommerce_reset_password_notification', array(
		$email_class->emails['WC_Email_Customer_Reset_Password'],
		'trigger'
	) );

}

/**
 * Установка пароля
 */
add_action( 'wp_ajax_wpp_al_lost_send', 'wpp_al_lost_send' );
add_action( 'wp_ajax_nopriv_wpp_al_lost_send', 'wpp_al_lost_send' );
function wpp_al_lost_send() {
	check_ajax_referer( 'wpp-al-string', 'security' );
	global $wpdb;

	$account = $_POST['user_login'];

	if ( empty( $account ) ) {
		$error = 'Enter an username or e-mail address.';
	} else {
		if ( is_email( $account ) ) {
			if ( email_exists( $account ) ) {
				$get_by = 'email';
			} else {
				$error = 'There is no user registered with that email address.';
			}
		} else if ( validate_username( $account ) ) {
			if ( username_exists( $account ) ) {
				$get_by = 'login';
			} else {
				$error = 'There is no user registered with that username.';
			}
		} else {
			$error = 'Invalid username or e-mail address.';
		}
	}

	if ( empty ( $error ) ) {
		// lets generate our new password
		//$random_password = wp_generate_password( 12, false );
		$random_password = wp_generate_password();


		// Get user data by field and data, fields are id, slug, email and login
		$user = get_user_by( $get_by, $account );

		$update_user = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => $random_password ) );

		// if  update user return true then lets send user an email containing the new password
		if ( $update_user ) {

			$from = 'WRITE SENDER EMAIL ADDRESS HERE'; // Set whatever you want like mail@yourdomain.com

			if ( ! ( isset( $from ) && is_email( $from ) ) ) {
				$sitename = strtolower( $_SERVER['SERVER_NAME'] );
				if ( substr( $sitename, 0, 4 ) == 'www.' ) {
					$sitename = substr( $sitename, 4 );
				}
				$from = 'admin@' . $sitename;
			}

			$to      = $user->user_email;
			$subject = 'Your new password';
			$sender  = 'From: ' . get_option( 'name' ) . ' <' . $from . '>' . "\r\n";

			$message = 'Your new password is: ' . $random_password;

			$headers[] = 'MIME-Version: 1.0' . "\r\n";
			$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers[] = "X-Mailer: PHP \r\n";
			$headers[] = $sender;

			$mail = wp_mail( $to, $subject, $message, $headers );
			if ( $mail ) {
				$success = 'Check your email address for you new password.';
			} else {
				$error = 'System is unable to send you mail containg your new password.';
			}
		} else {
			$error = 'Oops! Something went wrong while updaing your account.';
		}
	}

	if ( ! empty( $error ) ) {
		wp_send_json_error( array(
			'loggedin' => false,
			'message'  => sprintf( '<p class="error wpp-al-error-message">%s</p>', __( $error ) )
		) );
	}

	if ( ! empty( $success ) ) {
		wp_send_json_success(
			array(
				'loggedin' => false,
				'message'  => sprintf(
					'<p class="success wpp-al-success-message">%s <a href="javascript:void(0)" title="" class="wpp-al-href" data-action="login">Login</a></p>', $success
				)
			)
		);
	}

	die();
}

add_action('wp_logout','wpp_unlog');

function wpp_unlog(){
	wp_redirect( home_url() );
	exit();
}