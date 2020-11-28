<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1.2.00 - Алгоритм формирования слайдера версия карусель - 6
 */

$params = [
	'wrap' => '<div class="carousel-cell carousel-color-">%s</div>',
    'lazy' => false
];


?>
</section>

<div class="carousel <?php echo wpp_fr_file_class( __FILE__, 'wpp_' ); ?>">
	<?php
	foreach ( $args['imgs'] as $img_ID ) {
		$url = wp_get_attachment_image_src( $img_ID, 'full' );
		$img = e_wpp_fr_image_html( $url[0], wpp_br_slider_array( $params ) );
	}
	?>
</div>

<section>

    