<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_singular( [ 'sale_car', 'project' ] ) ) {

	echo '</div></div>';
}
?>
    </section>
    <div class="carousel <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>">
		<?php
		foreach ( $args['imgs'] as $img_ID ) {
			$url = wp_get_attachment_image_src( $img_ID, 'full' );
			e_wpp_fr_image_html( $url[0], wpp_br_slider_array() );
		} ?>
    </div>
<?php

if ( ! is_singular( [ 'sale_car', 'project'] ) ) {
	echo '<section class="text-box-container text-box-white"><div class="text-box-row"><div class="text-box">';
} else {
	echo '<section>';
}
