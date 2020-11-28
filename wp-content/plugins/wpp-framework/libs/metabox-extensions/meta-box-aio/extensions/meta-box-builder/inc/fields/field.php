<?php
class MBB_Field {
	public $basic = array( 'id', 'name', 'desc' );

	public $appearance = array();

	public $advanced = array();

	public function __construct() {
		$this->register_fields();

		echo '<ul class="ui-tabs">
			<li class="ui-tab-item {{ pane == \'general\' ? \'active\' : \'\' }}"><a role="button" href="#" ng-click="setActivePane(\'general\')">General</a></li>
			<li class="ui-tab-item {{ pane == \'appearance\' ? \'active\' : \'\' }}" ng-hide="field.type == \'hidden\' || field.type == \'tab\'"><a role="button" href="#" ng-click="setActivePane(\'appearance\')">Appearance</a></li>
			<li class="ui-tab-item {{ pane == \'advanced\' ? \'active\' : \'\' }}" ng-hide="field.type == \'tab\'"><a role="button" href="#" ng-click="setActivePane(\'advanced\')">Advanced</a></li>
		</ul>';

		echo '<div class="ui-pane pane-general" ng-show="pane == \'general\'">';
		echo $this->get_fields( $this->basic );
		echo '</div>';

		if ( ! is_array( $this->appearance ) ) {
			return;
		}

		echo '<div class="ui-pane pane-appearance" ng-show="pane == \'appearance\'">';
		echo $this->get_fields( $this->appearance );
		echo '</div>';

		if ( ! is_array( $this->advanced ) ) {
			return;
		}

		echo '<div class="ui-pane pane-advanced" ng-show="pane == \'advanced\'">';
		echo $this->get_fields( $this->advanced );
		echo '</div>';
	}

	protected function register_fields() {
		$attrs = Meta_Box_Attribute::get_attribute_content( 'key_value', 'attrs' );
		$basic_type = !empty( $this->basic['std']['type'] ) ? $this->basic['std']['type'] : '';
		$this->appearance['placeholder'] = 'placeholder';

		// Add a size section
		$this->appearance['size'] = array(
			'type' => 'number',
		);

		$this->appearance['label_description'] = array(
			'type' => 'textarea',
		);
		$this->appearance['before'] = array(
			'type' => 'textarea',
		);
		$this->appearance['after'] = array(
			'type' => 'textarea',
		);

		// Add a class section with full size
		$this->appearance['class'] = array(
			'size'  => 'wide',
			'label' => 'Custom CSS Class',
		);

		// Add a custom attribute section
		$this->advanced['attrs'] = array(
			'type'    => 'custom',
			'content' => $attrs,
		);

		// Add conditional logic section
		$conditional_logic = Meta_Box_Attribute::get_attribute_content( 'conditional_logic' );

		$this->advanced['conditional_logic'] = array(
			'type'    => 'custom',
			'content' => $conditional_logic,
		);

		// Add columns section
		$this->advanced['columns'] = array(
			'type'  => 'number',
			'attrs' => array(
				'min' => 1,
				'max' => 12,
			),
		);
		// Add conditional logic section
		$clone_setting = Meta_Box_Attribute::get_attribute_content( 'clone_setting' );

		$this->basic['clone_setting'] = array(
			'type'    => 'custom',
			'content' => $clone_setting,
		);

	}

	public function get_fields( $fields ) {
		$output = '';

		foreach ( $fields as $index => $field ) {
			// Clearfix
			if ( is_null( $index ) || is_null( $field ) ) {
				$output .= '<div class="clear"></div>';
				continue;
			}

			if ( is_numeric( $index ) ) {
				// Normal text field, normal size
				$output .= '<div class="description description-wide ' . $index . '">';
				$output .= Meta_Box_Attribute::text( $field );
				$output .= '</div>';

				continue;
			}

			if ( is_array( $field ) && ! empty( $field ) ) {
				$size  = isset( $field['size'] ) ? $field['size'] : 'wide';
				$label = isset( $field['label'] ) ? $field['label'] : null;
				$attrs = isset( $field['attrs'] ) ? $field['attrs'] : array();
				$type  = isset( $field['type'] ) ? $field['type'] : 'text';

				$output .= "<div class='description description-$size'>";

				if ( $type === 'custom' ) {
					$output .= $field['content'];
				} else {
					$output .= Meta_Box_Attribute::$type( $index, $label, $attrs );
				}
				$output .= '</div>';
			}
		}
		return $output;
	}
}
