<?php

class MBB_ButtonGroup extends MBB_Checkbox_List {

    public function register_fields() {
    	unset( $this->basic['clone'] );
    	unset( $this->basic['required'] );
    	unset( $this->basic['select_all_none'] );

		$this->basic['multiple'] = array(
			'type'  => 'checkbox',
		);
		$this->basic['inline'] = array(
			'type'  => 'checkbox',
		);

		$this->basic['required'] = array(
			'type' => 'checkbox',
		);

		$this->basic['clone'] = array(
			'type' => 'checkbox',
			'size' => 'wide'
		);

        parent::register_fields();

		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}
    }
}

new MBB_ButtonGroup;
