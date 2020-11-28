<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="header__brand" style="background-color: rgba(255, 255, 255, 0); color: rgba(0, 0, 0, 0);">
    <div class="brand">
		<?php if ( ! wp_is_mobile() ) : ?>
        <a class="brand__link" href="<?php echo get_home_url(); ?>">
			<?php $logo_img = '';


			$size = ' height="50px"';


			if ( defined( 'BR_DOMAIN' ) ) {
				if ( BR_DOMAIN === 'brabus.pro' ) {
					$size = '';
				}
			}
			$options = get_option( 'wpp_br' );
			if ( wp_is_mobile() && ! empty( $options['m_logo'][0] ) ) :
				$logo_img = wp_get_attachment_image_src( (int) $options['m_logo'][0], 'full' );
				$params   = array( 'width' => 40, 'crop' => true );
				$size     = ' height="40px"';
			else:
				if ( $custom_logo_id = get_theme_mod( 'custom_logo' ) ) {
					$logo_img = wp_get_attachment_image_src( $custom_logo_id, 'full' );
				}
				$params = array( 'width' => 75, 'crop' => true );
			endif;


			$logo_img = bfi_thumb( $logo_img[0], $params );
			if ( ! empty( $logo_img ) ) {
				printf( '<img class="brand__logo" src="%s"%s>', $logo_img, $size );
			} ?>
        </a>
    </div>
	<?php endif; ?>
</div>
