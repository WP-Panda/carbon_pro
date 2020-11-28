<?php

class MBB_ButtonGroup extends MBB_Checkbox_List {

    public function register_fields() {
        parent::register_fields();
		$this->basic['multiple'] = 'checkbox';
    }
}

new MBB_ButtonGroup;
