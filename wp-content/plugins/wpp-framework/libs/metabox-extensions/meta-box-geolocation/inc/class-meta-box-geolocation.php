<?php

class Meta_Box_Geolocation
{
    /**
     * Contains data to set to div[data-geo="JSON"]
     *
     * @var array|JSON
     */
    public $data_geo = array();

    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));

        add_action('rwmb_before', array($this, 'meta_box_geo_data'));

        add_filter('rwmb_wrapper_html', array($this, 'insert_field_geo_binding'), 10, 3);
    }

    /**
     * Enqueue Geo Location JS and allows users set custom query string for Gmap API URL
     *
     * @return void
     */
    public function enqueue()
    {
        // Allows users set API key
        $params = apply_filters('gmap_api_params', array());

        $query_string = '';

        if (is_array($params) && !empty($params))
            $query_string = '&' . http_build_query($params);

        list( , $url ) = RWMB_Loader::get_path( dirname( dirname( __FILE__ ) ) );

        // backward compatibility
        $url = defined( 'MBC_JS_URL' ) ? MBC_JS_URL : $url . 'assets/js/';

        wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?libraries=places' . $query_string,
            array(), '4.2.2', true);

        wp_register_script( 'mb-geo-location', $url . 'mb-geo.js', array('google-maps'), '1.0.0', true );

        wp_enqueue_script('mb-geo-location');
    }

    /**
     * Create div[data-geo] element which stores geo configuration for current Meta Box
     *
     * @param $obj
     */
    public function meta_box_geo_data($obj)
    {
        if (empty($obj->meta_box) || empty($obj->meta_box['geo']))
            return;

        $meta_box = $obj->meta_box;

        if (isset($meta_box['geo'])) {

            $meta_box['geo'] = is_array( $meta_box['geo'] ) ? $meta_box['geo'] : array();

            if ( ! isset( $meta_box['geo']['types'] ) )
                $meta_box['geo']['types'] = ['address'];

            $this->data_geo = $meta_box['geo'];

            $this->data_geo = esc_attr(json_encode($this->data_geo));

            echo '<div style="display: none; visibility: hidden" class="data-geo" data-geo="' . $this->data_geo .
                '"></div>';
        }
    }

    /**
     * Create div[data-binding] element which stores which address component to bind to current field
     *
     * @param $begin
     * @param $field
     * @param $meta
     * @return string
     */
    public function insert_field_geo_binding($begin, $field, $meta)
    {
        $data_binding = isset($field['binding']) ? $field['binding'] : $this->guessBindingField($field['id']);

        if (!$data_binding)
            return $begin;

        $data_binding = esc_attr($data_binding);

        $begin .= '<div style="display: none; visibility: hidden" class="rwmb-geo-binding" data-binding="' . $data_binding
            . '"></div>';

        return $begin;
    }

    /**
     * Get the supported fields
     *
     * @param $field
     * @return bool|string
     */
    private function guessBindingField($field)
    {
        $availableFields = array(
            'address', 'street_address', 'route', 'intersection', 'political', 'country',
            'administrative_area_level_1', 'administrative_area_level_2', 'administrative_area_level_3',
            'colloquial_area', 'locality', 'sublocality', 'neighborhood', 'premise', 'subpremise',
            'postal_code', 'natural_feature', 'airport', 'park', 'point_of_interest', 'post_box',
            'street_number', 'floor', 'room', 'lat', 'lng', 'viewport', 'location',
            'formatted_address', 'location_type', 'bounds', 'id', 'name', 'place_id',
            'reference', 'url', 'vicinity', 'geometry'
        );

        foreach ($availableFields as $gmap_field) {

            if ($field === $gmap_field . '_short')
                return 'short:' . $gmap_field;

            if ($field === $gmap_field)
                return $field;
        }

        return false;
    }
}