<?php
/**
 * Created by PhpStorm.
 * User: WP_Panda
 * Date: 02.08.2019
 * Time: 18:37
 */

/**
 * Кнопка очистки корзины
 */
function wpp_contats_send() {

	$errors = $mail = [];
	if ( ! empty( $_POST['data'] ) ) {
		parse_str( $_POST['data'], $form );
	} else {
		wp_send_json_error( [ 'message' => __( 'Form is Empty', 'wpp-brabus' ) ] );
	}

	if ( ! empty( $form['name'] ) ) {
		$mail['name'] = esc_attr( $form['name'] );
		$len          = mb_strlen( $mail['name'] );
		if ( $len < 4 ) {
			$errors['name'] = __( 'Minimum name length is 4 characters', 'wpp-brabus' );
		}
	} else {
		$errors['name'] = __( 'Field Name is empty', 'wpp-brabus' );
	}

	if ( ! empty( $form['email'] ) ) {
		$mail['email'] = esc_attr( $form['email'] );

		if ( ! is_email( $mail['email'] ) ) {
			$errors['email'] = __( 'Email is not Valid', 'wpp-brabus' );
		}

	} else {
		$errors['email'] = __( 'Field Mail is empty', 'wpp-brabus' );
	}

	if ( ! empty( $form['message'] ) ) {
		$mail['message'] = esc_attr( $form['message'] );
		$len             = mb_strlen( str_replace( ' ', '', $mail['message'] ) );
		if ( $len < 30 ) {
			$errors['message'] = __( 'Minimum name length is 30 characters', 'wpp-brabus' );
		}
	} else {
		$errors['message'] = __( 'Field Message is empty', 'wpp-brabus' );
	}

	if ( empty( $form['tac'] ) || (int) $form['tac'] !== 1 ) {
		$errors['tac'] = __( 'You must agree to the privacy policy', 'wpp-brabus' );

	}

	if ( ! empty( $form['country'] ) ) {
		$mail['country'] = esc_attr( $form['country'] );
	}

	// отправка ошибок
	if ( ! empty( $errors ) ) {
		wp_send_json_error( [ 'errors' => $errors ] );
	}


	$options = get_option( 'wpp_mc' );
	$mailer  = WC()->mailer();
//format the email
	$recipient = ! empty( $options ) && ! empty( $options['wpp_mc_email'] ) ? $options['wpp_mc_email'] : 'yoowordpress@yandex.ru,info@carbon.pro';
	$subject   = sprintf( __( "New message by %s!", 'wpp-brabus' ), get_home_url() );
	$content   = get_custom_email_html( $mail, $subject, $mailer );
	$headers   = "Content-Type: text/html\r\n";
//send the email through wordpress
	$send = $mailer->send( $recipient, $subject, $content, $headers );

	if ( ! empty( $send ) ) {
		wp_send_json_success( [ 'send'    => $send,
		                        'message' => __( 'The email has been sent, expect a reply', 'wpp-brabus' )
		] );
	} else {
		wp_send_json_error( [ 'send' => __( 'An unexpected error occurred try again later', 'wpp-brabus' ) ] );
	}


}

add_action( 'wp_ajax_wpp_contats_send', 'wpp_contats_send' );
add_action( 'wp_ajax_nopriv_wpp_contats_send', 'wpp_contats_send' );

/**
 * Пересчет верхней корзины
 */
function wpp_recalculate_cart() {
	WC()->cart->calculate_totals();
	wp_send_json_success( [] );
}

add_action( 'wp_ajax_wpp_recalculate_cart', 'wpp_recalculate_cart' );
add_action( 'wp_ajax_nopriv_wpp_recalculate_cart', 'wpp_recalculate_cart' );