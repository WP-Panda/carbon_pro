<?php

class MBB_Video extends MBB_ImageAdvanced {

	public function register_fields() {
        parent::register_fields();

		if ( isset( $this->basic['max_status'] ) ) {
			unset( $this->basic['max_status'] );
		}
    }
}

new MBB_Video;
