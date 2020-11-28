<?php
	/*
Template Name: light
Template Post Type: page
*/

	if ( !defined( 'ABSPATH' ) ) {
		exit;
	}
	get_header();
?>

	    <?php wpp_get_template_part( 'templates/globals/page-title', [] ); ?>
        <div class="content">
            <div class="neos-contentcollection">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php
				endwhile;
				else :
				endif; ?>
            </div>
        </div>

<?php
	get_footer();