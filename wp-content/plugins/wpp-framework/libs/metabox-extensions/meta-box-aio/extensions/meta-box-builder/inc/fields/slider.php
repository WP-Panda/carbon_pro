<?php

class MBB_Slider extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		)
	);

	public function __construct()
	{
		$label = 'jQuery UI slider options. (<a href="https://api.jqueryui.com/slider">see documentation</a>)';
		$js_options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'js_options', $label );

		$this->advanced['js_options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $js_options,
			'size'		=> 'wide'
		);

		$this->appearance[] = 'prefix';
		$this->appearance[] = 'suffix';

		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();
		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Slider;