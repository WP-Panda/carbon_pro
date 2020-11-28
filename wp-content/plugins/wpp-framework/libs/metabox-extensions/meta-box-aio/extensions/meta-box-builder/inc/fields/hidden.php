<?php

class MBB_Hidden extends MBB_Field
{
	public function __construct() {
		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'std',
			'clone'	=> array(
				'type' 	=> 'checkbox',
				// 'label' => 'Force Delete?'
			),

		);

		parent::__construct();
	}

    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['label_description'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Hidden;