<?php

class MBB_Date extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
		'inline' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide',
			'label' => 'inline_date',
		),
		'timestamp' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		),
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
		$label = 'Date picker options (<a href="http://api.jqueryui.com/datepicker">see documentation</a>)';
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

		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Date;