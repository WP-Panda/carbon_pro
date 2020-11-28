<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
$rnd  = rand( 10, 100000000 );
$imgs = ! empty( block_value( 'sep_img' ) ) ? wp_get_attachment_image_url( block_value( 'sep_img' ), 'full' ) : '';
if(!empty($imgs)) {
	$params = array(

		'sizes' => [
			[ 'width' => 260 ],
		]

	);

	$img = wpp_fr_image_bundle( $imgs, $params );

	$url = $img['thumb']['260'];
} else {
   $url =  get_home_url() .'/wp-content/themes/wpp-brabus/assets/img/logo.svg';
}
?>
<style>
    .separating-element.hr-<?php echo $rnd; ?>::after {
        background-image: url(<?php echo $url; ?>);
        content: 'C';
        background-size: auto;
        width: 100%;
        background-repeat: no-repeat;
        background-position: 50%;
        color: transparent;
    }
</style>
<hr class="separating-element hr-<?php echo $rnd; ?>">