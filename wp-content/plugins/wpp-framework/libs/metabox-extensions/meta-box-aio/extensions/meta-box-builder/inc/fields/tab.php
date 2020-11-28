<?php

class MBB_Tab extends MBB_Field
{
	public function __construct()
	{
		$dashicons = mbb_get_dashicons();

		$select_icon = '<div class="icon-panel">';

		foreach ( $dashicons as $icon )
		{
			$select_icon .= '<label class="icon-single {{active.icon == \'' . $icon . '\'}}">
				<i class="wp-menu-image dashicons-before '.$icon.'"></i>
				<input type="radio" ng-model="active.icon" value="' . $icon . '" class="hidden">
			</label>';
		}

		$select_icon .= '</div>';

		$tab_style = '<br/><label for="{{field.id}}_tab_style"><span>Tab style </span>
				<select ng-model="field.tab_style" class="form-control" id="{{field.id}}_tab_style">
					<option value="default">Default </option>
					<option value="box">Box</option>
					<option value="left">Left</option>
				</select>
			</label>';

		$this->basic = array(
			'id',
			'label',
			'icon' => array(
				'type' 	=> 'text',
				'label' => 'Icon',
			),
			null,

			'select_icon' => array(
				'type' => 'custom',
				'content' => $select_icon,
				'size'	=> 'wide'
			),
			'tab_style' => array(
				'type' => 'custom',
				'content' => $tab_style,
				'size'	=> 'wide'
			),
			'tab_wrapper' => array(
				'type' => 'checkbox',
			),
		);

		parent::__construct();
	}
}

new MBB_Tab;