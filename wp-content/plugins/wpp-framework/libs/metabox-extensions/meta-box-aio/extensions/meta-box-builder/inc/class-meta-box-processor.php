<?php
/**
 * The meta box processor that parse and save meta box settings.
 *
 * @package    Meta Box
 * @subpackage Meta Box Builder
 * @author     Tan Nguyen <tan@fitwp.com>
 */

/**
 * Parse JSON to Meta Box array
 *
 * @package    Meta Box
 * @subpackage Meta Box Builder
 * @author     Tan Nguyen <tan@fitwp.com>
 */
class Meta_Box_Processor {
	/**
	 * Store the meta box to be parsed.
	 *
	 * @var array
	 */
	private $meta_box = array();

	/**
	 * Construct is also main method
	 *
	 * @param string $meta_box Meta Box Json to prepare to parse.
	 */
	public function __construct( $meta_box ) {
		$this->meta_box = $meta_box;
		$this->parse();
	}

	/**
	 * Get Meta Box to save after parsed
	 *
	 * @return array This Meta Box
	 */
	public function get_meta_box() {
		return is_array( $this->meta_box ) ? $this->meta_box : array();
	}

	/**
	 * Convert JSON which stored from post_excerpt to array to store on post_content
	 */
	private function parse() {
		// By default, when get json form raw post data. It will have backslashes.
		// so remember to add stripslahses before decode.
		$this->meta_box = json_decode( stripslashes( $this->meta_box ), true );
		if ( ! is_array( $this->meta_box ) ) {
			$this->meta_box = array();
			return;
		}

		$this->normalize_field( $this->meta_box )
			->parse_attrs( $this->meta_box )
			->parse_settings()
			->normalize_conditional_logic( $this->meta_box );

		// Normalize include exclude show hide.
		$this->normalize_include_exclude_show_hide();

		if ( ! isset( $this->meta_box['fields'] ) || ! is_array( $this->meta_box['fields'] ) ) {
			return;
		}

		$this->set_fields_tab();

		$this->move_tabs_to_meta_box();

		$this->meta_box = $this->normalize_loop( $this->meta_box );

		// Allows user define multi-dimensional array by dot(.) notation.
		$this->meta_box = array_unflatten( $this->meta_box );
	}

	/**
	 * Normalize a group field.
	 *
	 * @param array $field Field settings.
	 *
	 * @return array
	 */
	private function normalize_loop( $field ) {
		$sub_fields = array();

		foreach ( $field['fields'] as $index => $sub_field ) {
			$this->normalize_field( $sub_field )
				->parse_attrs( $sub_field )
				->normalize_conditional_logic( $sub_field );

			if ( ! empty( $sub_field['fields'] ) ) {
				$sub_field = $this->normalize_loop( $sub_field );
			}

			// Remove tabs fields from Meta Box.
			if ( isset( $sub_field['type'] ) && 'tab' === $sub_field['type'] ) {
				continue;
			}

			if ( isset( $sub_field['type'] ) && 'group' === $sub_field['type'] && empty( $sub_field['fields'] ) ) {
				continue;
			}

			$sub_fields[] = $sub_field;
		}

		$field['fields'] = $sub_fields;

		return $field;
	}

	/**
	 * Normalize a field.
	 *
	 * @param array $field Field settings.
	 *
	 * @return $this
	 */
	private function normalize_field( &$field ) {
		if ( ! is_array( $field ) ) {
			return $this;
		}

		array_walk_recursive( $field, array( $this, 'normalize_value' ) );

		foreach ( $field as $key => $value ) :

			// Handle some key / value pairs.
			if ( in_array( $key, array( 'options', 'js_options', 'query_args' ), true ) && is_array( $value ) ) :
				// Options aren't affected with taxonomies.
				$tmp_array = array();
				$tmp_std   = array();

				foreach ( $value as $arr ) :
					// $skip = empty($arr['key']);
					$tmp_array[ $arr['key'] ] = $arr['value'];
					if ( isset( $arr['selected'] ) && $arr['selected'] ) {
						$tmp_std[] = $arr['key'];
					}

					// Push default value to std on Text List.
					if ( isset( $arr['default'] ) && ! empty( $arr['default'] ) ) {
						if ( 'fieldset_text' === $field['type'] ) {
							$tmp_std[ $arr['value'] ] = $arr['default'];
						} else {
							$tmp_std[] = $arr['default'];
						}
					}
				endforeach;

				$field[ $key ] = $tmp_array;

				if ( ! empty( $tmp_std ) ) {
					$field['std'] = $tmp_std;
				}

			endif;
			// Remember unset the empty value on the last.
			if ( empty( $value ) && ! in_array( $key, array( 'max_status' ) ) ) {
				unset( $field[ $key ] );
			}
		endforeach;

		unset( $field['$$hashKey'] );

		if ( empty( $field['datalist']['id'] ) ) {
			unset( $field['datalist'] );
		}

		return $this;
	}

	/**
	 * Normalize some special values.
	 *
	 * @param $value Input value.
	 *
	 * @return mixed Normalized value.
	 */
	private function normalize_value( &$value ) {
		// Boolean.
		if ( in_array( $value, array( 'true', 'false' ), true ) ) {
			$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		}

		// Make sure numeric values are not converted to string.
		if ( is_numeric( $value ) ) {
			$value = $value + 0;
		}
	}

	/**
	 * Move tabs from field to Meta Box array
	 */
	private function move_tabs_to_meta_box() {
		foreach ( $this->meta_box['fields'] as $field ) {
			if ( isset( $field['type'] ) && 'tab' === $field['type'] ) {

				if ( ! isset( $this->meta_box['tabs'] ) ) {
					$this->meta_box['tabs'] = array();
				}

				$label = isset( $field['label'] ) ? $field['label'] : '';
				$icon  = isset( $field['icon'] ) ? $field['icon'] : '';

				$this->meta_box['tabs'][ $field['id'] ] = compact( 'label', 'icon' );
			}
		}

		if ( empty( $this->meta_box['tabs'] ) ) {
			unset( $this->meta_box['tab_style'] );
			unset( $this->meta_box['tab_wrapper'] );
		}
	}

	/**
	 * Parse field attributes.
	 *
	 * @param array $field Field settings.
	 *
	 * @return $this
	 */
	private function parse_attrs( &$field ) {
		if ( ! isset( $field['attrs'] ) ) {
			return $this;
		}

		foreach ( $field['attrs'] as $attr ) {
			if ( in_array( $attr['value'], array( 'true', 'false' ), true ) ) {
				$attr['value'] = filter_var( $attr['value'], FILTER_VALIDATE_BOOLEAN );
			}

			// Try parse Json on value if its Json.
			$json = json_decode( stripslashes( $attr['value'] ), true );

			if ( is_array( $json ) ) {
				$attr['value'] = $json;
			}

			$field[ $attr['key'] ] = $attr['value'];
		}

		unset( $field['attrs'] );

		return $this;
	}

	/**
	 * Parse meta box settings.
	 *
	 * @return $this
	 */
	private function parse_settings() {
		$settings = &$this->meta_box;

		$objects = array( 'post_types', 'taxonomies', 'settings_pages', 'user', 'comment', 'attachments' );
		$for     = ! empty( $settings['for'] ) ? $settings['for'] : 'post_types';
		$fields  = ! empty( $settings['fields'] ) ? $settings['fields'] : array();

		$count_fields = count( $fields );
		for ( $i = 0; $i < $count_fields; $i++ ) {
			$groupfield = ! empty( $fields[ $i ]['groupfield'] ) ? $fields[ $i ]['groupfield'] : '';
			$type       = ! empty( $fields[ $i ]['type'] ) ? $fields[ $i ]['type'] : '';
			$field_type = ! empty( $fields[ $i ]['field_type'] ) ? $fields[ $i ]['field_type'] : '';

			if ( 'subfield' === $groupfield ) {
				$group_title = ! empty( $fields[ $i ]['group_title'] ) ? $fields[ $i ]['group_title'] : array();
				$sub_field   = implode( ',', $group_title );
				$title       = array(
					'field' => $sub_field,
				);
				$group_title = $title;
			}

			if ( ! in_array( $field_type, array( 'select', 'select_advanced' ), true ) ) {
				unset( $fields[ $i ]['select_all_none'] );
				unset( $fields[ $i ]['multiple'] );

			} elseif ( ! in_array( $field_type, array( 'checkbox_tree', 'checkbox_list' ), true ) ) {
				unset( $fields[ $i ]['inline'] );
			}
		}

		$settings['fields'] = array_filter( (array) $fields );
		foreach ( $objects as $object ) {
			if ( $for !== $object ) {
				unset( $settings[ $object ] );
			}
		}

		if ( 'post_types' !== $for ) {
			unset( $settings['context'] );
			unset( $settings['priority'] );
			unset( $settings['style'] );
			unset( $settings['default_hidden'] );
		}

		if ( in_array( $for, array( 'post_types', 'taxonomies' ), true ) && isset( $settings[ $for ] ) ) {
			$settings[ $for ] = wp_list_pluck( $settings[ $for ], 'slug' );
		}

		if ( in_array( $for, array( 'user', 'comment' ), true ) ) {
			unset( $settings[ $for ] );
			$settings['type'] = $for;
		}
		if ( 'attachments' === $for ) {
			$settings['post_types'] = 'attachment';
			unset( $settings['attachments'] );
		} else {
			unset( $settings['media_modal'] );
		}

		if ( ! empty( $settings['showhide'] ) ) {
			$settings['showhide'] = array_filter( (array) $settings['showhide'] );
		}

		return $this;
	}

	/**
	 * Set field to correct tab
	 */
	private function set_fields_tab() {
		$tab = ! empty( $this->meta_box['fields'][0]['type'] ) ? $this->meta_box['fields'][0]['type'] : '';
		if ( 'tab' !== $tab ) {
			return $this;
		}

		$previous_tab = 0;

		foreach ( $this->meta_box['fields'] as $index => $field ) {
			if ( 'tab' === $field['type'] ) {
				$previous_tab = $index;
			} else {
				$this->meta_box['fields'][ $index ]['tab'] = $this->meta_box['fields'][ $previous_tab ]['id'];
			}
		}

		return $this;
	}

	/**
	 * Normalize field conditional logic.
	 *
	 * @param array $field Field settings.
	 *
	 * @return $this
	 */
	private function normalize_conditional_logic( &$field ) {
		if ( empty( $field['logic'] ) || ! isset( $field['logic'] ) ) {
			return $this;
		}

		$logic = $field['logic'];

		$visibility = 'visible' === $logic['visibility'] ? 'visible' : 'hidden';
		$relation   = 'and' === $logic['relation'] ? 'and' : 'or';

		foreach ( $logic['when'] as $index => $condition ) {
			if ( empty( $condition[0] ) ) {
				unset( $logic['when'][ $index ] );
			}

			if ( ! isset( $condition[2] ) || is_null( $condition[2] ) ) {
				$condition[2] = '';
			}

			if ( strpos( $condition[2], ',' ) !== false ) {
				$logic['when'][ $index ][2] = array_map( 'trim', explode( ',', $condition[2] ) );
			}
		}

		if ( ! empty( $logic['when'] ) ) {
			$field[ $visibility ] = array(
				'when'     => $logic['when'],
				'relation' => $relation,
			);
		}

		unset( $field['logic'] );

		return $this;
	}

	/**
	 * Normalize for Include Exclude, Show Hide extensions.
	 */
	private function normalize_include_exclude_show_hide() {
		// Clean Show / Hide, Include / Exclude.
		$cleans = array( 'showhide', 'includeexclude' );

		unset( $this->meta_box['show'], $this->meta_box['hide'], $this->meta_box['include'], $this->meta_box['exclude'] );

		foreach ( $cleans as $clean ) {

			// Skip if users don't use either show hide or include exclude.
			if ( ! isset( $this->meta_box[ $clean ] ) ) {
				continue;
			}

			// Skip if users use show hide or include exclude but set it to off.
			if ( isset( $this->meta_box[ $clean ] ) && 'off' === $this->meta_box[ $clean ]['type'] ) {
				unset( $this->meta_box[ $clean ] );
				continue;
			}

			// $action can be: show, hide, include, exclude
			$action = $this->meta_box[ $clean ]['type'];

			$this->meta_box[ $action ] = $this->meta_box[ $clean ];

			unset( $this->meta_box[ $clean ] );

			// Todo: Check this if it compatibility with PHP7.
			unset( $this->meta_box[ $action ]['type'] );

			// Now we have $meta_box[$action] with raw data.
			foreach ( $this->meta_box[ $action ] as $key => $value ) {

				if ( empty( $value ) ) {
					continue;
				}

				if ( is_string( $value ) && strpos( $value, ',' ) !== false ) {
					$value = array_map( 'trim', explode( ',', $value ) );
				}

				$this->meta_box[ $action ][ $key ] = $value;
			}
		}
	}
}
