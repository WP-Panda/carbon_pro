<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
extract( $args );
if ( ! empty( $video ) ) :
	?>
    <video class="picture-teaser__media" loop autoplay muted playsinline>
        <source src="<?php echo $video; ?>" type="video/mp4"/>
        Your browser does not support the video tag.
    </video>
<?php endif;