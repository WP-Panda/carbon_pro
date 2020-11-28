<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		the_content();

	endwhile;
endif;