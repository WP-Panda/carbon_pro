<?php

class MBB_ImageAdvanced extends MBB_Field {

	public function __construct()
	{
		$image_thumb  = get_intermediate_image_sizes();
		$image_sizes = '<label for="{{field.id}}_image_size">Image size <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Image size that displays in the edit page"></b></span></label>
		<select name="image_size" ng-model="field.image_size" id="{{field.id}}_image_size" class="form-control">';
		foreach ( $image_thumb as $size_name ) :
		    $image_sizes .= '<option value="' . $size_name . '">' . $size_name . '</option>';
		endforeach;
		$image_sizes .= '</select>';
		$status_field = Meta_Box_Attribute::get_attribute_content( 'status_field' );

	   	$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'max_file_uploads' => array(
				'type'	=> 'number',
				'label' => 'Maximum number of files',
				'attrs' => array( 'min' => 0, 'max' => 99 )
			),
			'image_size'  => array(
				'type'    => 'custom',
				'content' => $image_sizes,
			),
			'force_delete' => array(
				'type' 	=> 'checkbox',
				// 'label' => 'Force Delete?'
			),
			'max_status' => array(
				'type'    => 'custom',
				'content' => $status_field,
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone'	=> array(
				'type' 	=> 'checkbox',
				'size'	=> 'wide'
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

new MBB_ImageAdvanced;
