<?php

class MBB_Taxonomy extends MBB_Field
{
	public function __construct()
	{
		$label = 'Query arguments <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Query arguments for getting taxonomy terms. Use the same arguments as get_terms()."></b></span> (<a href="https://developer.wordpress.org/reference/functions/get_terms/">see documentation</a>)';
		$query_args = Meta_Box_Attribute::get_attribute_content( 'key_value', 'query_args',  $label );

		$field_select_all = '<span class="field-inline">
			<label ng-show="field.field_type == \'select\' || field.field_type == \'select_advanced\' || field.field_type == \'checkbox_list\'">
				<input type="checkbox" ng-model="field.select_all_none" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Display "Select All | None" button
			</label></span>
		';
		$field_multiple = '<span class="field-inline">
			<label ng-show="field.field_type == \'select\' || field.field_type == \'select_advanced\'">
				<input type="checkbox" ng-model="field.multiple" 0="ng-change" 1="toggleMultiple()" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Allow to select multiple choices
			</label></span>
		';
		$field_inline = '<span>
			<label ng-show="field.field_type == \'radio_list\' || field.field_type == \'checkbox_list\'">
				<input type="checkbox" ng-model="field.inline" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Display choices in a single line
			</label></span>
		';
		$taxonomy = '
			<label for="{{field.id}}_taxonomy">Taxonomy</label>
			<select ng-model="field.taxonomy" class="form-control field-taxonomy" id="{{field.id}}_taxonomy" ng-change="setCategory()">';

		foreach ( mbb_get_taxonomies() as $tax => $value ) :
			$taxonomy .= '<option value="' . $value['slug'] . '">'. $value['name'] . '</option>';
		endforeach;

		$taxonomy .= '</select>';

		$field_type = '<label for="{{field.id}}_type"><span>Field type</span>
				<select ng-model="field.field_type" class="form-control" id="{{field.id}}_type">
					<option value="select">Select</option>
					<option value="select_tree">Select tree</option>
					<option value="select_advanced">Select advanced</option>
					<option value="checkbox_list">Checkbox list</option>
					<option value="checkbox_tree">Checkbox tree</option>
					<option value="radio_list">Radio list</option>
				</select>
			</label>';

		$field_category = '<label for="{{field.id}}_type" ><span>Default value</span>
			<select ng-hide="field.multiple == true" ng-model="field.std" class="form-control" id="{{field.id}}_type">
			<option ng-repeat="term in field.terms" ng-value="{{term.id}}">{{term.name}}</option>
			</select>
			<select multiple ng-hide="field.multiple == false" ng-model="field.std" class="form-control" id="{{field.id}}_type">
			<option ng-repeat="term in field.terms" ng-value="{{term.id}}">{{term.name}}</option>
			</select>
		</label>';
		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'taxonomy' => array(
				'type'    => 'custom',
				'content' => $taxonomy,
			),
			'field_type'  => array(
				'type'    => 'custom',
				'content' => $field_type,
			),
			'std' => array(
				'type'    => 'custom',
				'content' => $field_category,
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
				'type' => 'checkbox',
			),
		);

		$this->advanced['query_args'] = array(
			'type'    => 'custom',
			'content' => $query_args,
			'size'    => 'wide',
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

new MBB_Taxonomy;