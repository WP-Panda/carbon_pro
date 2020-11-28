<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

?>

<?php wpp_get_template_part( 'templates/home/hero', [] ); ?>
<?php wpp_get_template_part( 'templates/globals/page-title', [] ); ?>
    <div class="content">
        <div class="neos-contentcollection">

            <section class="container-fluid responsive-gutter section-padding-medium">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="form--row">
                            <label class="form--select-label">
								<?php
								$categories = get_categories( [
									'taxonomy'     => 'category',
									'type'         => 'post',
									'child_of'     => 0,
									'parent'       => '',
									'orderby'      => 'name',
									'order'        => 'ASC',
									'hide_empty'   => 1,
									'hierarchical' => 1,
								] );
								?>
                                <select id="node-e816ea21-b081-410f-858c-b404276a7bcd" name="carlist"
                                        class="form--select">
                                    <option selected="selected" disabled="disabled" value="">- Please select -
                                    </option>
									<?php foreach ( $categories as $category ) {
										$option = '<option value="' . $category->cat_name . '"  data-href="' . get_term_link( $category->term_id, 'category' ) . '">';
										$option .= $category->cat_name;
										$option .= '</option>';
										echo $option;
									} ?>

                                </select>
                                <script type="application/javascript">
                                    document.getElementById('node-e816ea21-b081-410f-858c-b404276a7bcd').onchange = function () {
                                        if (this.selectedIndex != -1) {
                                            window.location.href = this.options[this.selectedIndex].getAttribute('data-href');
                                        }
                                    };
                                </script>
                            </label>
                        </div>
                    </div>
                </div>
            </section>

			<?php if ( have_posts() ) : ?>
                <section class="container-fluid responsive-gutter section-padding-medium col-min-height">
                    <div class="row">
						<?php while ( have_posts() ) : the_post(); ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">

                                <a href="<?php echo get_the_permalink(); ?>">

                                    <div class="grid-teaser">
                                        <div class="grid-teaser-image">
											<?php $params = [
												'class' => 'img-fluid',
												'sizes' => [
													BR_THUMB_SIZE,
													[ 'width' => 420, 'height' => 280 ]
												]
											];
											e_wpp_fr_image_html( wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' ), $params );
											?>

                                        </div>
                                        <h4>
											<?php the_title(); ?>
                                        </h4>
                                        <p>
                                            <strong><?php echo get_the_date( 'd.m.Y' ); ?></strong>
											<?php the_excerpt(); ?>
                                        </p>
                                    </div>

                                </a>

                            </div>
						<?php
						endwhile; ?>
                    </div>
                </section>
			<?php else :

			endif; ?>
        </div>
    </div>

<?php
get_footer();