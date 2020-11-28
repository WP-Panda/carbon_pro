<?php
/*
Plugin Name: Meta Box Geolocation
Plugin URI: https://www.metabox.io/plugins/meta-box-geolocation
Description: Powerful tool to interact with Google Maps API and save location data
Version: 1.1.1
Author: tanng
Author URI: https://giga.ai
License: GPL2+
*/

//Prevent loading this file directly
defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'meta_box_geolocation_load' ) ) {
    /**
     * Hook to 'init' with priority 5 to make sure all actions are registered before Meta Box 4.9.0 runs
     */
    add_action( 'init', 'meta_box_geolocation_load', 5 );

    /**
     * Load plugin files after Meta Box is loaded
     */
    function meta_box_geolocation_load() {

        if ( ! defined( 'RWMB_VER' ) || class_exists( 'Meta_Box_Geolocation' ) ) {
            return;
        }

        require dirname( __FILE__ ) . '/inc/class-meta-box-geolocation.php';

        new Meta_Box_Geolocation;

        // Example Meta Box. You can edit and uncomment this line below
        // require dirname( __FILE__ ). '/examples/example.php';
    }
}
