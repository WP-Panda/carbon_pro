<?php

class MBB_SelectAdvanced extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			// 'placeholder',
			// 'size' => 'number',
			'options' => array(
				'type' => 'textarea',
				'label'	=> 'Options <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Enter each choice on a new line.
					For more control, you may specify both a value and label like this:
					red: Red"></b></span>',
				// 'type' => 'custom',
				// 'content' => $options,
				// 'size'	=> 'wide'
			),
			'select_all_none' => array(
				'type' => 'checkbox',
			),
			'multiple' => array(
				'type' => 'checkbox',
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone' => array(
				'type' => 'checkbox',
			),

		);
		$label = 'Advanced select2 options (<a href="https://select2.org/configuration">see documentation</a>)';
		$js_options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'js_options', $label );

		$this->advanced['js_options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $js_options,
			'size'		=> 'wide',
		);
		parent::__construct();
	}

    public function register_fields() {
        parent::register_fields();

		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}
    }

}

new MBB_SelectAdvanced;