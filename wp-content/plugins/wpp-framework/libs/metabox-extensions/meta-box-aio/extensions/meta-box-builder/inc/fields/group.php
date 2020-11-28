<?php

class MBB_Group extends MBB_Field
{
	public function __construct()
	{
		$group_setting = Meta_Box_Attribute::get_attribute_content( 'group_setting' );

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'collapsible' => array(
				'type'    => 'custom',
				'content' => $group_setting,
			),
			'clone' 		=> array(
				'type' 	=> 'checkbox',
			),
		);
		parent::__construct();
	}

    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Group;