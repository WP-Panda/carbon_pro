<?php
class MBB_Heading extends MBB_Field {
	public function __construct() {
		$this->basic = array(
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),

		);

		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}
new MBB_Heading;