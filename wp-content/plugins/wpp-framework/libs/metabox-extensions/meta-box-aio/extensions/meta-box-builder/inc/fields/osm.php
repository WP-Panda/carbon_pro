<?php

class MBB_OSM extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
	);

	public function __construct()
	{
		$address_field = Meta_Box_Attribute::get_attribute_content( 'address_field' );

		$language_code  = 'ar,bg,bn,ca,cs,da,de,el,en,en-AU,en-GB,es,eu,eu,fa,fi,fil,fr,gl,gu,hi,hr,hu,id,it,iw,ja,kn,ko,lt,lv,ml,mr,nl,no,pl,pt,pt-BR,pt-PT,ro,ru,sk,sl,sr,sv,ta,te,th,tl,tr,uk,vi,zh-CN,zh-TW';
		$language_name = 'Arabic,Bulgarian,Bengali,Catalan,Czech,Danish,German,Greek,English,English (Australian),English (Great Britain),Spanish,Basque,Basque,Farsi,Finnish,Filipino,French,Galician,Gujarati,Hindi,Croatian,Hungarian,Indonesian,Italian,Hebrew,Japanese,Kannada,Korean,Lithuanian,Latvian,Malayalam,Marathi,Dutch,Norwegian,Polish,Portuguese,Portuguese (Brazil),Portuguese (Portugal),Romanian,Russian,Slovak,Slovenian,Serbian,Swedish,Tamil,Telugu,Thai,Tagalog,Turkish,Ukrainian,Vietnamese,Chinese (Simplified),Chinese (Traditional)';


		$language_code_item = explode( ",", $language_code );
		$language_name_item = explode( ",", $language_name );
		$language = array_combine( $language_code_item, $language_name_item );

		$map_language = '<label for="{{field.id}}_language">Language</label>
		<select name="map_language" ng-model="field.language" id="{{field.id}}_language" class="form-control">';
		foreach ( array_unique( $language ) as $code => $name ) :
		    $map_language .= '<option value="' . $code . '">' . $name . '</option>';
		endforeach;
		$map_language .= '</select>';

		$this->basic['address_field'] = array(
			'type'    => 'custom',
			'content' => $address_field,
		);
		$this->basic['language'] = array(
			'type'    => 'custom',
			'content' => $map_language,
		);
		$this->basic['region'] = array(
			'type' => 'text',
		);
		$this->basic['required'] = array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		);
		$this->basic['clone'] = array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		);

		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_OSM;
