<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
wpp_get_template_part( 'templates/home/hero', [] );
wpp_get_template_part( 'templates/globals/page-title', [] ); ?>
	<div class="content">
		<div class="neos-contentcollection">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<section class="text-box-container text-box-white">
					<div class="text-box-row">
						<div class="text-box">
							<h3 class="headline-padding-small">
								<strong><?php the_title(); ?></strong>
							</h3>
							<?php the_content(); ?>
						</div>
					</div>
				</section>
			<?php
			endwhile;
			else :
			endif; ?>
		</div>
	</div>
<?php
get_footer();