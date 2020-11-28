<?php

/**
 * Render Html for Attribute
 */
class Meta_Box_Attribute {
	/**
	 * Define labels for displaying on the builder.
	 * We just have to define some special labels, the remaining is auto generate by str_title function.
	 *
	 * @var array
	 */
	static $labels = array(
		'id'                => 'ID<span class="required">*</span><span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Must be unique, will be used as meta key when saving to the database. Recommended to use only lowercase letters, numbers, and underscores."></b></span>',
		'attrs'             => 'Custom attributes (<a href="https://docs.metabox.io/extensions/meta-box-builder/#custom-attributes" target="_blank">see documentation</a>)',
		'std'               => 'Default value',
		'query_args'        => 'Query arguments <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Query arguments for getting post objects. Use the same arguments as WP_Query."></b></span>',
		'options.args'      => 'Option arguments',
		'options'           => 'Options',
		'size'              => 'Size of the input box <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Enter a number here. The bigger value, the longer input box. Normal size is around 30."></b></span>',
		'name'              => 'Label <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Optional field label. If empty, the field input is 100% width."></b></span>',
		'label_description' => 'Label description<span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Optional label description, displayed below the field label"></b></span>',
		'placeholder'       => 'Placeholder text',
		'clone'             => 'Cloneable <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Make field clonable (repeatable)"></b></span>',
		'columns'           => 'Columns (requires <a href="https://metabox.io/plugins/meta-box-columns/" target="_blank">Meta Box Columns</a>)',
		'before'            => 'Custom HTML displayed before field output',
		'after'             => 'Custom HTML displayed after field output',
		'mime_type'         => 'MIME types<span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="This is a filter for items in Media Library popup, it does not restrict file types when upload."></b></span>',
		'force_delete'      => 'Force delete?<span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Delete files from the Media Library when deleting them from post meta"></b></span>',
		'alpha_channel'     => 'Allow to select opacity',
		'address_field'     => 'Address field<span class="required">*</span> <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="The ID of address field. For multiple address fields, enter field IDs separated by comma."></b></span>',
		'desc'              => 'Description',
		'max_status'        => 'Show status <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Display how many files uploaded/remaining"></b></span>',
		'select_all_none'   => 'Display "Select All | None" button',
		'required'          => 'Required',
		'inline'            => 'Display choices in a single line',
		'multiple'          => 'Allow to select multiple choices',
		'on_label'          => 'Label for ON status <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Optional label when the switch is ON. Text and HTML are allowed. Leave empty to use iOS style or use HTML to display custom icon like Dashicons."></b></span>',
		'off_label'         => 'Label for OFF status <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Optional label when the switch is OFF. Text and HTML are allowed. Leave empty to use iOS style or use HTML to display custom icon like Dashicons."></b></span>',
		'min'               => 'Minimum value',
		'max'               => 'Maximum value',
		'timestamp'         => 'Save the date in the Unix timestamp format ',
		'max_file_uploads'  => 'Maximum number of files (leave empty for unlimited files)',
		'parent'            => 'Set the selected post as the parent for the current being edited post',
		'prefix'            => 'Prefix <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Text displayed before the field value"></b></span>',
		'suffix'            => 'Suffix <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Text displayed after the field value"></b></span>',
		'raw'               => 'Save data in raw format',
		'step'              => 'Step <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Set the increments at which a numeric value can be set. It can be the string "any" (for floating numbers) or a positive float number or integer. If this attribute is not set to "any", the control accepts only values at multiples of the step value greater than the minimum"></b></span>',
		'inline_date'       => 'Display the date picker inline with the input <span class="tooltip right"><span class="dashicons dashicons-editor-help"></span><b title="Do not require to click the input field to trigger the date picker"></b></span>',
		'image_size'        => 'Image size <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Image size that displays in the edit page"></b></span>',
		'region'            => 'Region code <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="The region code, specified as a country code top-level domain. This parameter returns autocompleted address results influenced by the region (typically the country) from the address field."></b></span>',
	);

	/**
	 * Generate Input Content
	 *
	 * @param  string $name  Name of the input.
	 * @param  string $label Label of the input.
	 * @param  array  $attrs Other attributes.
	 * @param  string $type  input type.
	 *
	 * @return string html
	 */
	public static function input( $name, $label = null, $attrs = array(), $type = 'text' ) {
		// Turn key => value array to key="value" html output.
		$attrs = self::build_attributes( $attrs );

		// If label is not defined.
		$label = ( null !== $label ) ? $label : $name;

		// Because field name is not Capitalized, so we have to convert it.
		if ( array_key_exists( $label, self::$labels ) ) {
			$label = self::$labels[ $label ];
		}

		$output = '
			<label for="{{field.id}}_' . $name . '">' . $label . '<br>
				<input type="' . $type . '" ng-model="field.' . $name . '" id="{{field.id}}_' . $name . '" class="form-control field-' . $name . '"' . $attrs . ' />
			</label>
		';

		if ( 'checkbox' === $type ) {
			$output = '
				<label for="{{field.id}}_' . $name . '">
					<input type="' . $type . '" ng-model="field.' . $name . '" id="{{field.id}}_' . $name . '" ' . $attrs . ' /> ' . $label . '
				</label>
			';
		}

		return $output;
	}

	public static function text( $name, $label = null, $attrs = array() ) {
		return self::input( $name, $label, $attrs );
	}

	public static function email( $name, $label = null, $attrs = array() ) {
		return self::input( $name, $label, $attrs, 'email' );
	}

	public static function number( $name, $label = null, $attrs = array() ) {
		return self::input( $name, $label, $attrs, 'number' );
	}

	public static function range( $name, $label = null, $attrs = array() ) {
		return self::input( $name, $label, $attrs, 'range' );
	}

	public static function checkbox( $name, $label = null, $attrs = array() ) {
		$attrs['ng-true-value']  = 1;
		$attrs['ng-false-value'] = 0;

		return self::input( $name, $label, $attrs, 'checkbox' );
	}

	public static function build_attributes( $attrs = array() ) {
		$attributes = '';

		if ( ! empty( $attrs ) ) {
			foreach ( $attrs as $k => $v ) {
				$attributes .= " {$k}=\"{$v}\"";
			}
		}

		return $attributes;
	}

	public static function textarea( $name, $label = null, $attrs = array() ) {
		$attributes = self::build_attributes( $attrs );

		$label = null === $label ? $name : $label;

		if ( array_key_exists( $label, self::$labels ) ) {
			$label = self::$labels[ $label ];
		}

		$output = '
			<label for="{{field.id}}_' . $name . '">' . $label . '</label>
			<textarea ng-model="field.' . $name . '" id="{{field.id}}_' . $name . '" class="form-control"' . $attributes . '></textarea>';

		return $output;
	}

	public static function get_attribute_content( $attribute, $replace = '', $label = '' ) {
		return mbb_get_attribute_content( $attribute, $replace, $label );
	}
}
