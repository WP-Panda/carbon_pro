<?php

class MBB_Switch extends MBB_Checkbox {

    public function register_fields() {
        parent::register_fields();

		$style = '<p class="description description-thin">
							<label for="{{field.id}}_style">Style <br />
								<select ng-model="field.style" class="form-control" id="{{field.id}}_style">
									<option value="rounded">Rounded</option>
									<option value="square">Square</option>
								</select>
							</label>
						</p>';

		$this->basic['style'] = array(
            'type'    => 'custom',
            'content' => $style,
        );

        $this->basic[] = 'on_label';
        $this->basic[] = 'off_label';
    }
}

new MBB_Switch;
