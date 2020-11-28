<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! empty( $args['content'] ) ) :
	$size = wpp_fr_rnd_string();
	?>
    <section class="container-fluid responsive-gutter section-padding-large accordion">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div>
                    <div class="neos-contentcollection">
                        <div class="accordion__panel">
                            <div class="accordion__header" role="tab" data-toggle="collapse"
                                 href="#w_<?php echo $size; ?>" aria-expanded="true">
                                <h3><?php echo $args['head']; ?></h3>
                                <span class="icon icon-plus"></span>
                            </div>
                            <div class="accordion__body collapse show" id="w_<?php echo $size; ?>" role="tabpanel"
                                 style="">
                                <div class="neos-contentcollection">
                                    <section class="structured-content">
                                        <div class="row">
                                            <div class="col-lg-12">
												<?php echo $args['content']; ?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;