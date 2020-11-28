<?php
class MBB_Divider extends MBB_Field {
	public function __construct() {
		$this->basic = array(
			'name',
		);
		parent::__construct();
	}
	public function register_fields() {
		parent::register_fields();
		unset( $this->appearance['placeholder'] );
		unset( $this->appearance['size'] );
		unset( $this->appearance['label_description'] );
		unset( $this->appearance['class'] );
	}
}

new MBB_Divider;