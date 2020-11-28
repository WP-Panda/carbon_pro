<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<section class="container-fluid responsive-gutter">
    <nav class="main-entry main-entry--bordered">
		<?php $nav = wp_nav_menu( [
			'theme_location' => 'top_' . get_locale(),
			'container'      => false,
			'echo'           => false,
			'add_a_class'    => 'main-entry__item',
			'fallback_cd' => '__return_empty_string',
			'items_wrap'     => '%3$s',
			'link_before'    => '<span class="main-entry--text">',
			'link_after'     => '</span>'
		] );

		echo strip_tags( $nav, '<a>,<span>' );
		?>
    </nav>
</section>