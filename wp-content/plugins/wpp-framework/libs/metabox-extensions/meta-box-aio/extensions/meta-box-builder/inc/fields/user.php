<?php

class MBB_User extends MBB_Field
{
	public function __construct()
	{
		$field_select_all = '<span class="field-inline">
			<label ng-show="field.field_type == \'select\' || field.field_type == \'select_advanced\' || field.field_type == \'checkbox_list\'">
				<input type="checkbox" ng-model="field.select_all_none" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Display "Select All | None" button
			</label></span>
		';
		$field_multiple = '<span class="field-inline">
			<label ng-show="field.field_type == \'select\' || field.field_type == \'select_advanced\'">
				<input type="checkbox" ng-model="field.multiple"  0="ng-change" 1="toggleMultiple()" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Allow to select multiple choices
			</label></span>
		';
		$field_inline = '<span>
			<label ng-show="field.field_type == \'radio_list\' || field.field_type == \'checkbox_list\'">
				<input type="checkbox" ng-model="field.inline" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Display choices in a single line
			</label></span>
		';
		$field_type = '<label for="{{field.id}}_field_type">Field Type <br />
					<select ng-model="field.field_type" class="form-control" id="{{field.id}}_field_type">
						<option value="select">Select</option>
						<option value="select_tree">Select Tree</option>
						<option value="select_advanced">Select Advanced</option>
						<option value="checkbox_list">Checkbox List</option>
						<option value="checkbox_tree">Checkbox Tree</option>
						<option value="radio_list">Radio list</option>
					</select>
				</label>';

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'field_type' => array(
				'type' => 'custom',
				'content' => $field_type
			),
			'select_all_none' => array(
				'type' => 'custom',
				'content' => $field_select_all,
			),
			'multiple' => array(
				'type' => 'custom',
				'content' => $field_multiple,
			),
			'inline' => array(
				'type' => 'custom',
				'content' => $field_inline,
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone' => array(
				'type' 	=> 'checkbox',
				'size'	=> 'wide'
			),
		);

		$label = 'Query arguments <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Query arguments for getting users. Use the same arguments as get_users()."></b></span> (<a href="https://codex.wordpress.org/Function_Reference/get_users">see documentation</a>)';
		$query_args = Meta_Box_Attribute::get_attribute_content( 'key_value', 'query_args',  $label );
        $this->advanced['query_args'] = array(
            'type'    => 'custom',
            'size'    => 'wide',
            'content' => $query_args,
        );
		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();

		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}
    }
}

new MBB_User;