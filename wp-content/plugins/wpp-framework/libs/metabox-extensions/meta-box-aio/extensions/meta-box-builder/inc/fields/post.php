<?php

class MBB_Post extends MBB_Field {

	public function register_fields() {
		$field_select_all = '<span class="field-inline-post">
			<label ng-show="field.field_type == \'select\' || field.field_type == \'select_advanced\' || field.field_type == \'checkbox_list\'">
				<input type="checkbox" ng-model="field.select_all_none" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Display "Select All | None" button
			</label></span>
		';
		$field_multiple = '<span>
			<label ng-show="field.field_type == \'select\' || field.field_type == \'select_advanced\'">
				<input type="checkbox" ng-model="field.multiple"  0="ng-change" 1="toggleMultiple()" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Allow to select multiple choices
			</label></span>
		';
		$field_inline = '<span class="field-inline">
			<label ng-show="field.field_type == \'radio_list\' || field.field_type == \'checkbox_list\'">
				<input type="checkbox" ng-model="field.inline" ng-true-value="true" ng-false-value="false" class="ng-pristine ng-untouched ng-valid ng-empty"> Display choices in a single line
			</label></span>
		';

		$field_type = '<div class="description description-thin">
			<label for="{{field.id}}_field_type"><span>Field Type</span>
				<select ng-model="field.field_type" class="form-control" id="{{field.id}}_field_type">
					<option value="select">Select</option>
					<option value="select_tree">Select tree</option>
					<option value="select_advanced">Select advanced</option>
					<option value="checkbox_list">Checkbox list</option>
					<option value="checkbox_tree">Checkbox tree</option>
					<option value="radio_list">Radio list</option>
				</select>
			</label>
		</div>';

		$post_types = mbb_get_post_types();
		$field_post_type = '';
		if ( ! empty( $post_types ) ) {
			$field_post_type = '<label for="{{field.id}}_post_type">Post Type</label>
				<select multiple="multiple" ng-model="field.post_type" class="form-control" id="{{field.id}}_post_type">';
			foreach ( $post_types as $post_type ) {
				$field_post_type .= '<option value="' . esc_attr( $post_type['slug'] ) . '"> ' . esc_html( $post_type['name'] ) . '</option>';
			}
			$field_post_type .= '</select></label>';
		}

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type'  => 'textarea',
			),
			'post_type' => array(
				'type'    => 'custom',
				'content' => $field_post_type,
			),
			'field_type' => array(
				'type'    => 'custom',
				'content' => $field_type,
			),
			// 'placeholder',
			'select_all_none' => array(
				'type' => 'custom',
				'content' => $field_select_all,
			),
			 'inline' => array(
				'type' => 'custom',
				'content' => $field_inline,
			),
			'multiple' => array(
				'type' => 'custom',
				'content' => $field_multiple,
			),
			'parent' => array(
				'type' => 'checkbox',
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone' => array(
				'type' => 'checkbox',
			),
		);

		$label = 'Query arguments <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Query arguments for getting post objects. Use the same arguments as WP_Query."></b></span> (<a href="https://codex.wordpress.org/Class_Reference/WP_Query">see documentation</a>)';
		$query_args = Meta_Box_Attribute::get_attribute_content( 'key_value', 'query_args',  $label );
		$this->advanced['query_args'] = array(
			'type'    => 'custom',
			'size'    => 'wide',
			'content' => $query_args,
		);

		parent::register_fields();
		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}
	}
}

new MBB_Post;
