<?php

class MBB_Textarea extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std' => array(
			'type' 	=> 'textarea',
			'size'	=> 'wide'
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		)
	);
    public function register_fields() {
        parent::register_fields();

		$this->appearance['rows'] = array(
			'label' => 'Rows',
		);
		$this->appearance['cols'] = array(
			'label' => 'Columns',
		);
		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}
    }
}

new MBB_Textarea;