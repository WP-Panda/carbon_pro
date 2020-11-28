<?php

class MBB_Background extends MBB_Field {
    public function register_fields() {
        parent::register_fields();

		if ( isset( $this->basic['std'] ) ) {
			unset( $this->basic['std'] );
		}
    }
}

new MBB_Background;
