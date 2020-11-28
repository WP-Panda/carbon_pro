<?php

class MBB_Sidebar extends MBB_Select {

    public function register_fields() {
        parent::register_fields();

		if ( isset( $this->basic['options'] ) ) {
			unset( $this->basic['options'] );
		}
    }
}

new MBB_Sidebar;
