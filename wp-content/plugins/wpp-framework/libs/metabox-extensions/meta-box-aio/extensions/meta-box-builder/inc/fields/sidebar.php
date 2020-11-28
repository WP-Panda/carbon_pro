<?php

class MBB_Sidebar extends MBB_Select {

    public function register_fields() {
    	unset( $this->basic['select_all_none'] );
    	unset( $this->basic['multiple'] );
    	unset( $this->basic['required'] );
    	unset( $this->basic['clone'] );

        $field_type = '<div class="description description-thin">
					<label for="{{field.sidebarid}}_field_type">Field Type <br />
						<select ng-model="field.field_type" class="form-control" id="{{field.sidebarid}}_field_type">
							<option value="select">Select</option>
                            <option value="select_advanced">Select Advanced</option>
                            <option value="checkbox_list">Checkbox List</option>
                            <option value="radio_list">Radio list</option>
						</select>
					</label>
				</div>';

		$this->basic['field_type'] = array(
			'type'  => 'custom',
			'content' => $field_type,
		);
		$this->basic['required'] = array(
			'type'  => 'checkbox',
		);
		$this->basic['clone'] = array(
			'type'  => 'checkbox',
			'size' => 'wide',
		);
        parent::register_fields();

		if ( isset( $this->basic['options'] ) ) {
			unset( $this->basic['options'] );
		}
		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}

    }
}

new MBB_Sidebar;
