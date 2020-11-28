<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	/**
	 * WPP_Bt Theme Customizer
	 *
	 * @package WPP_FR
	 */
	
	/**
	 * Class WPP__Customize
	 */
	class WPP_Customize {
		/**
		 * Customize settings
		 *
		 * @var array
		 */
		protected $config = array ();
		
		/**
		 * The class constructor
		 *
		 * @param array $config
		 */
		public function __construct( $config ) {
			$this->config = $config;
			
			if ( ! class_exists( 'Kirki' ) ) {
				return;
			}
			
			$this->register();
		}
		
		/**
		 * Register settings
		 */
		public function register() {
			/**
			 * Add the theme configuration
			 */
			if ( ! empty( $this->config[ 'theme' ] ) ) {
				Kirki::add_config(
					$this->config[ 'theme' ], array (
						                        'capability'  => 'edit_theme_options',
						                        'option_type' => 'theme_mod',
					                        )
				);
			}
			
			/**
			 * Add panels
			 */
			if ( ! empty( $this->config[ 'panels' ] ) ) {
				foreach ( $this->config[ 'panels' ] as $panel => $settings ) {
					Kirki::add_panel( $panel, $settings );
				}
			}
			
			/**
			 * Add sections
			 */
			if ( ! empty( $this->config[ 'sections' ] ) ) {
				foreach ( $this->config[ 'sections' ] as $section => $settings ) {
					Kirki::add_section( $section, $settings );
				}
			}
			
			/**
			 * Add fields
			 */
			if ( ! empty( $this->config[ 'theme' ] ) && ! empty( $this->config[ 'fields' ] ) ) {
				foreach ( $this->config[ 'fields' ] as $name => $settings ) {
					if ( ! isset( $settings[ 'settings' ] ) ) {
						$settings[ 'settings' ] = $name;
					}
					
					Kirki::add_field( $this->config[ 'theme' ], $settings );
				}
			}
		}
		
		/**
		 * Get config ID
		 *
		 * @return string
		 */
		public function get_theme() {
			return $this->config[ 'theme' ];
		}
		
		/**
		 * Get customize setting value
		 *
		 * @param string $name
		 *
		 * @return bool|string
		 */
		public function get_option( $name ) {
			$default = $this->get_option_default( $name );
			
			return get_theme_mod( $name, $default );
		}
		
		/**
		 * Get default option values
		 *
		 * @param $name
		 *
		 * @return mixed
		 */
		public function get_option_default( $name ) {
			if ( ! isset( $this->config[ 'fields' ][ $name ] ) ) {
				return false;
			}
			
			return isset( $this->config[ 'fields' ][ $name ][ 'default' ] ) ? $this->config[ 'fields' ][ $name ][ 'default' ] : false;
		}
	}
	
	/**
	 * This is a short hand function for getting setting value from customizer
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	function wpp_bt_get_option( $name ) {
		global $wpp_bt_customize;
		
		if ( empty( $wpp_bt_customize ) ) {
			return false;
		}
		
		if ( class_exists( 'Kirki' ) ) {
			$value = Kirki::get_option( $wpp_bt_customize->get_theme(), $name );
		} else {
			$value = $wpp_bt_customize->get_option( $name );
		}
		
		return apply_filters( 'wpp_bt_get_option', $value, $name );
	}
	
	/**
	 * Get default option values
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	function wpp_bt_get_option_default( $name ) {
		global $wpp_bt_customize;
		
		if ( empty( $wpp_bt_customize ) ) {
			return false;
		}
		
		return $wpp_bt_customize->get_option_default( $name );
	}
	
	/**
	 * Move some default sections to `general` panel that registered by theme
	 *
	 * @param object $wp_customize
	 */
	function wpp_bt_customize_modify( $wp_customize ) {
		$wp_customize->get_section( 'title_tagline' )->panel = 'general';
		$wp_customize->get_section( 'static_front_page' )->panel = 'general';
	}
	
	#add_action( 'customize_register', 'wpp_bt_customize_modify' );
	
	/**
	 * Register theme settings
	 *
	 * @return array
	 */
	function wpp_bt_customize_config() {
		
		$name = 'theme-name';
		$config = array (
			'theme' => apply_filters( 'wpp_fr_theme_name_for_customizer', $name ),
		);
		
		$panels = $sections = $fields = array ();
		$config[ 'panels' ] = apply_filters( 'wpp_fr_panels_for_customizer', $panels );
		$config[ 'sections' ] = apply_filters( 'wpp_fr_sections_for_customizer', $sections );
		$config[ 'fields' ] = apply_filters( 'wpp_fr_customize_fields', $fields );
		
		return $config;
	}
	
	function wpp_bt_customaizer_init() {
		global $wpp_bt_customize;
		$wpp_bt_customize = new WPP_Customize( wpp_bt_customize_config() );
	}
	add_action( 'init', 'wpp_bt_customaizer_init' );