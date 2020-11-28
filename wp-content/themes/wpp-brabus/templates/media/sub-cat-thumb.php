<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

/**
 * 1.2.00 - Вывод картнки новым алгоритмом
 */

defined( 'ABSPATH' ) || exit;

$category = esc_attr( $args['category']->name );


$params = [
	'class' => 'img-fluid',
];

?>
<div class="grid-teaser-image <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>">
	<?php e_wpp_fr_image_html( $args['img'], wpp_br_thumb_array( $params ) ); ?>
</div>

