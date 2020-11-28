<?php

/**
 * 1.2.00 - Алгоритм формирования галереи версия стена - 3
 */
$html = '';
$a    = 1;
$device_count = wp_is_mobile() ? 6 : 12;
?>
</section>
<section class="row wpp-fancy-box-gallery <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>">
	<?php
	wpp_get_template_part( 'templates/media/part/wall-img-items', [
		'img'          => ! empty( $args['imgs'] ) ? $args['imgs'] : [],
		'a'            => $a,
		'device_count' => $device_count
	] );
	?>
</section>
<section>