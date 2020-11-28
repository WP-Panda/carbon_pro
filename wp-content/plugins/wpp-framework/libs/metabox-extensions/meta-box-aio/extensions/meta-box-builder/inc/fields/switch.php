<?php

class MBB_Switch extends MBB_Checkbox {

    public function register_fields() {
        parent::register_fields();
        unset( $this->basic['std'] );
        unset( $this->basic['clone_setting'] );
        unset( $this->basic['required'] );
        unset( $this->basic['clone'] );
		$style = '<div class="description description-thin">
							<label for="{{field.id}}_style">Style <br />
								<select ng-model="field.style" class="form-control" id="{{field.id}}_style">
									<option value="rounded">Rounded</option>
									<option value="square">Square</option>
								</select>
							</label>
						</div>';

		$this->basic['style'] = array(
            'type'    => 'custom',
            'content' => $style,
        );

        $this->basic[] = 'on_label';
        $this->basic[] = 'off_label';
		$this->basic['required'] = array(
			'type' => 'checkbox',
		);
		$this->basic['clone'] = array(
			'type' => 'checkbox',
			'size' => 'wide'
		);
		$clone_setting = Meta_Box_Attribute::get_attribute_content( 'clone_setting' );
		$this->basic['clone_setting'] = array(
			'type'    => 'custom',
			'content' => $clone_setting,
		);
    }
}

new MBB_Switch;
