<?php

class MBB_SingleImage extends MBB_ImageAdvanced {

	public function register_fields() {
        parent::register_fields();

		if ( isset( $this->basic['max_status'] ) ) {
			unset( $this->basic['max_status'] );
		}

		if ( isset( $this->basic['max_file_uploads'] ) ) {
			unset( $this->basic['max_file_uploads'] );
		}
    }
}

new MBB_SingleImage;
