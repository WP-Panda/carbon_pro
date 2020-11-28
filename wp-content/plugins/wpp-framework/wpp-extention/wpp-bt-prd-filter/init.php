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
	
	require_once 'helpers.php';
	require_once 'db.php';
	require_once 'pre_get_posta.php';
	require_once 'option-page.php';
	require_once 'widget.php';
	require_once 'filter-list.php';
	
	function wpp_pf_front_assets(){
		$replace = str_replace( ABSPATH, get_home_url() . '/', __DIR__ );
		wp_register_script( 'wpp-pf-front', $replace . '/assets/wpp-pf-front.js', array('jquery'),'1.0.0');
	}
	
	
	add_action('wp_enqueue_scripts','wpp_pf_front_assets');