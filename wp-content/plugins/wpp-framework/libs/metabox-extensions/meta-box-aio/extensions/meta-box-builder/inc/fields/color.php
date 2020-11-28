<?php

class MBB_Color extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
		'alpha_channel'	=> array(
			'type' 	=> 'checkbox',
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
		),
	);

	public function __construct()
	{
		$label = 'Color picker options. (<a href="https://automattic.github.io/Iris">see documentation</a>)';
		$js_options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'js_options', $label );

		$this->advanced['js_options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $js_options,
			'size'		=> 'wide'
		);

		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Color;