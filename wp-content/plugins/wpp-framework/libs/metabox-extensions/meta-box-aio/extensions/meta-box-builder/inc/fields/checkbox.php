<?php

class MBB_Checkbox extends MBB_Field {
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		null,
		'std'	=> array(
			'type' 	=> 'checkbox',
			'label' => 'Checked by default?',
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
		),
	);
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Checkbox;
