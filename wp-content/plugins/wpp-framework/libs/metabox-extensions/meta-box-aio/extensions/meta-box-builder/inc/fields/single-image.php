<?php

class MBB_SingleImage extends MBB_Field {
    public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'force_delete' => array(
			'type' 	=> 'checkbox',
			// 'label' => 'Force Delete?'
		),

		'required' => array(
			'type' => 'checkbox',
		),
		'clone'	=> array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		),

	);

	public function register_fields() {
        parent::register_fields();
		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_SingleImage;
