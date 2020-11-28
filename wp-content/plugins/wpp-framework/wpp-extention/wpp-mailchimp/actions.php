<?php
/**
 * Главная категория
 */
add_action( 'wp_ajax_add_parent_cat', 'wpp_mc_add_parent_cat' );
function wpp_mc_add_parent_cat() {
	check_ajax_referer( 'wpp-mc-special-string', 'security' );
	global $wpp_fr_mc;

	$data = [ 'title' => 'Cars', 'type' => 'dropdown' ];

	$out = $wpp_fr_mc->new_interest_category( $data );

	if ( ! empty( $out['error'] ) ) {
		wp_send_json_error( [ 'error' => $out['error'] ] );
	} else {
		wp_send_json_success( [ 'status' => 'OK' ] );
	}

}


add_action( 'wp_ajax_add_category_to_mailchimp', 'wpp_mc_add_category_to_mailchimp' );
/**
 * Добавление категории из мэйлчимп
 */
function wpp_mc_add_category_to_mailchimp() {

	check_ajax_referer( 'wpp-mc-special-string', 'security' );

	global $wpp_fr_mc, $wpdb;

	if ( empty( $_POST['id'] ) ) {
		wp_send_json_error( [ 'error' => 'Term ID is Empty!' ] );
	}

	$id   = (int) $_POST['id'];
	$name = get_term_parents_list( $id, 'product_cat', [ 'link' => false ] );

	if ( empty( $name ) ) {
		wp_send_json_error( [ 'error' => 'Category name not Found!' ] );
	}

	$table           = $wpdb->prefix . 'wpp_mc_intesrest';
	$intersest_group = $wpdb->get_results( "SELECT `interest_id` FROM $table LIMIT 0,1" );

	# Создание интереса
	$data_interes = [ 'name' => $name ];

	$interest_ID = $wpp_fr_mc->new_interest( $intersest_group[0]->interest_id, $data_interes );


	if ( empty( $interest_ID['id'] ) ) {
		wp_send_json_error( [ $interest_ID['error'], 'wpp-panda' => 'Не добавился Интерес' ] );
	}

	#Создание сегмента
	$data_segment = [
		'name'    => $name,
		'options' => (object) [
			'match'      => 'any',
			'conditions' => (array) [
				(object) [
					'condition_type' => 'Interests',
					"field"          => "interests-" . $intersest_group[0]->interest_id,
					"op"             => "interestcontains",
					"value"          => [ $interest_ID['id'] ]
				]
			]
		]
	];

	$out = $wpp_fr_mc->new_segment( $data_segment, $id, $interest_ID['id'], $intersest_group[0]->interest_id );


	if ( ! empty( $out['error'] ) ) {
		wp_send_json_error( $out );
	}

	if ( empty( $out['error'] ) ) {
		wp_send_json_success( $out );
	}

}

add_action( 'wp_ajax_remove_category_to_mailchimp', 'wpp_mc_remove_category_to_mailchimp' );

function wpp_mc_remove_category_to_mailchimp() {

	check_ajax_referer( 'wpp-mc-special-string', 'security' );

	global $wpp_fr_mc, $wpdb;

	if ( empty( $_POST['id'] ) ) {
		wp_send_json_error( [ 'error' => 'Term ID is Empty!' ] );
	}

	$id = (int) $_POST['id'];

	$table_seg = $wpdb->prefix . 'wpp_mc_segments';
	$table_cat = $wpdb->prefix . 'wpp_mc_category';
	$segments  = $wpdb->get_results( "SELECT * FROM $table_seg  WHERE  `term_id`=$id LIMIT 0,1" );

	if ( empty( $segments ) ) {
		wp_send_json_error( [ 'error' => 'Segment Not Found!' ] );
	}

	$wpp_fr_mc->segment_delete( $segments[0]->segment_id, $segments[0]->list_id );
	$wpp_fr_mc->interest_delete( $segments[0]->intersest_cat_id, $segments[0]->intersest_id, $segments[0]->list_id );

	$wpdb->delete( $table_seg, array( 'term_id' => $id ) );
	$wpdb->delete( $table_cat, array(
		'list_id'     => $segments[0]->list_id,
		'interest_id' => $segments[0]->intersest_cat_id,
		'category_id' => $segments[0]->intersest_id
	) );

	wp_send_json_success( [ 'status' => 'OK' ] );

}

add_action( 'wp_ajax_wpp_mc_add_user', 'wpp_mc_wpp_mc_add_user' );
add_action( 'wp_ajax_nopriv_wpp_mc_add_user', 'wpp_mc_wpp_mc_add_user' );
function wpp_mc_wpp_mc_add_user() {
	global $wpp_fr_mc, $wpdb;

	check_ajax_referer( 'wpp-mc-string', 'security' );
	$error = [];

	if ( empty( $_POST['target'] ) ) {
		$error['mc-target'] = __( 'Target is Empty!', 'wpp-fr' );
	}

	if ( empty( $_POST['email'] ) ) {
		$error['mc-email'] = __( 'Email is Empty!', 'wpp-fr' );
	}

	if ( ! is_email( $_POST['email'] ) ) {
		$error['mc-email'] = __( 'E-mail is not Valid!', 'wpp-fr' );
	}

	if ( ! empty( $error ) ) {
		wp_send_json_error( [ 'form_error' => $error ] );
	}

	$table = $wpdb->prefix . $wpp_fr_mc::$table_users;

	$user = $wpdb->get_results( sprintf( "SELECT * FROM $table WHERE `email`='%s' LIMIT 0,1", $_POST['email'] ) );

	if ( empty( $user[0] ) ) {
		$data = [
			'members' => [
				[
					'email_address' => $_POST['email'],
					'status'        => 'subscribed',
					'interests'     => (object) [ $_POST['target'] => true ]
				]
			]
		];

		$result = $wpp_fr_mc->add_members_to_list( $data );

		wp_send_json_success( ['message'=>__('Subscribed','wpp-fr')] );
	} else {
		$result = $wpp_fr_mc->add_interest_to_user( $_POST['email'], $_POST['target'], $user[0] );

		if ( ! empty( $result['error'] ) ) {
			wp_send_json_error( [ $result['error'] ] );
		}

		$interest     = unserialize( $user[0]->interests );
		$interest[]   = $_POST['target'];
		$interest_new = serialize( $interest );

		$insert = $wpdb->update( $table,
			[ 'interests' => $interest_new ],
			[ 'email' => $_POST['email'] ]
		);

		if(empty($insert)){
			wp_send_json_error( [ 'error'=>__('Db Error','wpp-fr') ] );
        }

        wp_send_json_success(['message'=>__('Subscribed','wpp-fr')]);

		die();
	}

}

add_action( 'admin_footer', 'wpp_mc_action_javascript' );
function wpp_mc_action_javascript() {
	$ajax_nonce = wp_create_nonce( "wpp-mc-special-string" );
	?>
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
    <style>
        #loader-wrapper {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10003;
            background-color: rgba(255, 255, 255, .6);
        }

        #loader {
            display: block;
            position: relative;
            left: 50%;
            top: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #3498db;
            -webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        #loader:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #e74c3c;
            -webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        #loader:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #f9c922;
            -webkit-animation: spin 1.5s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 1.5s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(0deg); /* IE 9 */
                transform: rotate(0deg); /* Firefox 16+, IE 10+, Opera */
            }
            100% {
                -webkit-transform: rotate(360deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(360deg); /* IE 9 */
                transform: rotate(360deg); /* Firefox 16+, IE 10+, Opera */
            }
        }

        @keyframes spin {
            0% {
                -webkit-transform: rotate(0deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(0deg); /* IE 9 */
                transform: rotate(0deg); /* Firefox 16+, IE 10+, Opera */
            }
            100% {
                -webkit-transform: rotate(360deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(360deg); /* IE 9 */
                transform: rotate(360deg); /* Firefox 16+, IE 10+, Opera */
            }
        }
    </style>

    <script>
        jQuery(function ($) {

            //Основная категория
            $(document).on('click', '.wpp-mc-create-cat', function (e) {

                e.preventDefault();

                $('#loader-wrapper').show();

                var $data = {
                    action: 'add_parent_cat',
                    security: '<?php echo $ajax_nonce; ?>',
                };

                $.post(ajaxurl, $data, function ($response) {

                    $('#loader-wrapper').hide();

                    if ($response.success) {
                        $.toast({
                            position: 'top-right',
                            text: 'Категория Создана',
                            showHideTransition: 'slide',
                            loader: true,
                            icon: 'success'
                        });
                    } else {
                        $.toast({
                            position: 'top-right',
                            text: $response.data.error,
                            showHideTransition: 'slide',
                            loader: true,
                            icon: 'error'
                        });
                    }

                });

            });
            //Удаление сегмента
            $(document).on('click', '.wpp-wc-remove', function (e) {
                e.preventDefault();
                $('#loader-wrapper').show();

                var $el = $(this),
                    $data = {
                        action: 'remove_category_to_mailchimp',
                        security: '<?php echo $ajax_nonce; ?>',
                        id: $el.data('id')
                    };

                $.post(ajaxurl, $data, function ($response) {

                    $('#loader-wrapper').hide();

                    if ($response.success) {
                        $.toast({
                            position: 'top-right',
                            text: 'Сегмент Удален',
                            showHideTransition: 'slide',
                            loader: true,
                            icon: 'success'
                        });
                        $el.removeClass('wpp-wc-remove').addClass('wpp-wc-add').html('Добавить');
                    } else {
                        $.toast({
                            position: 'top-right',
                            text: $response.data.error,
                            showHideTransition: 'slide',
                            loader: true,
                            icon: 'error'
                        });
                    }

                });


            });
            //Добавить сегмент
            $(document).on('click', '.wpp-wc-add', function (e) {

                e.preventDefault();

                $('#loader-wrapper').show();

                var $el = $(this),
                    $data = {
                        action: 'add_category_to_mailchimp',
                        security: '<?php echo $ajax_nonce; ?>',
                        id: $el.data('id')
                    };

                $.post(ajaxurl, $data, function ($response) {

                    $('#loader-wrapper').hide();

                    if ($response.success) {
                        $.toast({
                            position: 'top-right',
                            text: 'Сегмент Добавлен',
                            showHideTransition: 'slide',
                            loader: true,
                            icon: 'success'
                        });
                        $el.removeClass('wpp-wc-add').addClass('wpp-wc-remove').html('Удалить');
                    } else {
                        $.toast({
                            position: 'top-right',
                            text: $response.data.error,
                            showHideTransition: 'slide',
                            loader: true,
                            icon: 'error'
                        });
                    }

                });

            });

        });
    </script>
	<?php
}


add_action( 'wp_enqueue_scripts', 'wpp_mc_frontend_js', 100 );
function wpp_mc_frontend_js() {

	wp_enqueue_script( 'wpp-mc', wpp_fr()->plugin_url() . '/wpp-extention/wpp-mailchimp/wpp-mailcimp.js', [ 'jquery' ], '1.0.2', true );
	wp_localize_script( 'wpp-mc', 'WppMc', [
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'security'              => wp_create_nonce( 'wpp-mc-string' ),
			'mail_empty_message'    => __( 'Email is Empty!', 'wpp-fr' ),
			'target_empty_message'  => __( 'Target is Empty!', 'wpp-fr' ),
			'mail_validate_message' => __( 'E-mail is not Valid!', 'wpp-fr' )
		]
	);

}