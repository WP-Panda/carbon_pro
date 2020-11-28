<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container text-uppercase text-center">
	<?php
	$nav = wp_nav_menu( [
		'theme_location' => 'footer_' . get_locale(),
		'container'      => '',
		'menu_class' => 'row wpp-footer-nav',
		'echo'           => false,
		'add_li_class' => 'col-12 col-md-6 col-lg-3'
	] );

	echo $nav;


	?>
</div>
