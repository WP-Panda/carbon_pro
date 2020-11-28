<?php
	/**
	 * The select field.
	 *
	 * @package Meta Box
	 */
	
	/**
	 * Select field class.
	 * fontawesome
	 */
	class RWMB_Fontawesome_Field extends RWMB_Field {
		
		/**
		 * Enqueue plugin scripts
		 */
		public function enqueue() {
			list( , $url ) = RWMB_Loader::get_path( dirname( __FILE__ ) );
			wp_enqueue_style( 'font-awesome', $url . 'assets/font-awesome/css/font-awesome.css"' );
		}
		
		public static function array_delete( $array, $element ) {
			return ( is_array( $element ) ) ? array_values( array_diff( $array, $element ) ) : array_values( array_diff( $array, array ( $element ) ) );
		}
		
		public static function fontawesome_icon_list() {
			$icons_file = __DIR__ . "/assets/font-awesome/css/font-awesome.css";
			$parsed_file = file_get_contents( $icons_file );
			$iconses = [];
			
			#preg_match_all( "/fa\-([a-zA-z0-9\-]+[^\:\.\,\s\{\>])/", $parsed_file, $matches );
			preg_match_all( "/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*\"\\\\(.+)\";\s+}/", $parsed_file, $matches, PREG_SET_ORDER );
			
			foreach ( $matches as $match ) {
				$iconses[ $match[ 1 ] ] = $match[ 2 ];
			}
			
			$exclude_icons = array (
				"fa-lg",
				"fa-2x",
				"fa-3x",
				"fa-4x",
				"fa-5x",
				"fa-ul",
				"fa-li",
				"fa-fw",
				"fa-border",
				"fa-pulse",
				"fa-rotate-90",
				"fa-rotate-180",
				"fa-rotate-270",
				"fa-spin",
				"fa-flip-horizontal",
				"fa-flip-vertical",
				"fa-stack",
				"fa-stack-1x",
				"fa-stack-2x",
				"fa-inverse"
			);
			
			ksort( $iconses );
			
			//$icons = (object) array ( "icons" => self::array_delete( $matches[ 0 ], $exclude_icons ) );
			
			
			return $iconses;
		}
		
		public static function html( $meta, $field ) {
			$options = '<option>Select Icon</option>';
			$icons = self::fontawesome_icon_list();
			#wpp_dump( $icons );
			
			foreach ( $icons as $key => $val ) {
				$options .= sprintf( '<option value="%1$s"%3$s><i class="fa %1$s"></i>&#x%2$s; %1$s</option>', $key, $val, selected( $key, $meta, false ) );
			}
			
			return sprintf(
				'<select name="%s" id="%s" class="wpp-fa-select" data-value="%s">%s</select><style>select.wpp-fa-select{font-family:\'FontAwesome\' , \'arial\'}</style>',
				$field[ 'field_name' ], $field[ 'id' ], $meta, $options
			);
		}
		
	}
