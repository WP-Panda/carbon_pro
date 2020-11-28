<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
$flag = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;
$loc = !empty($flag) ? strtolower( get_locale() ) : 'ru_ru';

$title     = ! empty( block_value( 'text_' . $loc ) ) ? block_value( 'text_' . $loc ) : 'Контент пуст';
?>

<div class="text-box-container text-box-tiny-container">
	<div class="text-box-row">
		<div class="text-box text-box-tiny text-center"><?php echo
			htmlspecialchars_decode($title); ?></div>
	</div>
</div>
