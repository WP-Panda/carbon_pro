<?php

class MBB_Video extends MBB_ImageAdvanced {

	public function register_fields() {
		unset( $this->basic['max_status'] );
		unset( $this->basic['required'] );
		unset( $this->basic['image_size'] );
		unset( $this->basic['clone'] );

        $status_field = Meta_Box_Attribute::get_attribute_content( 'status_field' );

		$this->basic['max_status'] = array(
			'type'    => 'custom',
			'content' => $status_field,
		);
		$this->basic['required'] = array(
			'type' => 'checkbox',
		);
		$this->basic['clone'] = array(
			'type' => 'checkbox',
		);
		parent::register_fields();
    }
}

new MBB_Video;
