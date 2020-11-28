<?php

class MBB_Text extends MBB_Field {

	public $basic = array(
		'id',
		'name',
		'desc',
		'std',
		'placeholder',
		'size',
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		)
	);

	protected function register_fields() {
		parent::register_fields();

		$datalist = Meta_Box_Attribute::get_attribute_content( 'datalist' );
		$this->advanced['datalist'] = array(
			'type' 		=> 'custom',
			'content' 	=> $datalist,
			'size'		=> 'wide',
		);
	}
}

new MBB_Text;
