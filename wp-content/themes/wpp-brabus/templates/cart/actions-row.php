<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
wp_enqueue_script( 'clipboard' );
?>
<div class="container justify-content-center">
    <div class="wpp-cart-saved-url"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row copy-clipboard-row">
                <div class="col-sm-12 text-center"><?php e_wpp_br_lng( 'saved_cart_link' ); ?>: <span
                            class="saved-link"></span>
                    <span class="wpp-copy-btn wpp-tooltips-copy"
                          data-title='<?php e_wpp_br_lng( 'copy_to_clipboard' ); ?>' data-clipboard-text="">
                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/copy.svg" alt="">
                    </span>
                </div>
            </div>


			<?php
			$text = 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Nulla eget tellus nunc. Maecenas id suscipit nisl. Sed vitae volutpat lectus et';
			$lng  = ! empty( $_SESSION['wpp_lang_new'] ) && 'en' !== strtolower( $_SESSION['wpp_lang_new'] ) ? 'ru.' : '';
			$cur  = ! empty( $_SESSION['wpp_currency_new'] ) ? strtolower( $_SESSION['wpp_currency_new'] ) . '/' : '';

			$url       = WPP_PROTOCOL . $lng . WPP_DOMAIN . '/cart/' . $cur . is_saved_cart();
			$title     = 'Carbon pro Cart';
			$hash_tags = 'Carbon.pro,tuning';

			$args = [
				'facebook'  => 'http://www.facebook.com/sharer.php?u=' . $url,
				'email'     => 'mailto:?subject=' . $title . '&body=' . $text,
				'linkedin'  => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
				'pinterest' => 'http://pinterest.com/pin/create/button/?url=' . $url,
				'skype'     => 'https://web.skype.com/share?url=' . $url . '&text=' . $text,
				'telegram'  => 'https://t.me/share/url?url=' . $url . '&text=' . $text,
				'twitter'   => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $text . '&via=' . $title . '&hashtags=' . $hash_tags,
				'vk'        => 'http://vk.com/share.php?url=' . $url . '&title=' . $title . '&comment=' . $desc,
				'whatsapp'  => 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url,
			];

			?>
            <meta property="og:url" content="<?php echo $url; ?>"/>
            <meta property="og:type" content="article"/>
            <meta property="og:title" content="<?php echo $title; ?>"/>
            <meta property="og:description" content="<?php echo $text; ?>"/>
            <!--<meta property="og:image" content="/wp-content/uploads/2019/08/MMLogoKreis_vector.svg"/>-->
            <div class="row">
                <ul class="wpp-al-row">


                    <li class="vpp-cart-action wpp-share-cart-li">
                        <a href="javascript:void(0);" class="get_share" title="">
                            <span class="wpp-ca-li-icon"
                                  style="background-image: url(/wp-content/themes/wpp-brabus/assets/img/icons/share.svg)"></span>
                            <b><?php echo wpp_br_lng( 'share' ); ?></b>
                        </a>
                        <div class="modal-share-box">
                            <ul>

                                <li>
                                    <span class="share-target-icon">
                                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/copy.svg" alt="">
                                    </span>
                                    <span class="wpp-copy-btn" data-clipboard-text="">
                                        <?php e_wpp_br_lng( 'copy_link' ); ?>
                                    </span>
                                </li>

                                <li>
                                    <a id="facebook" target="popup" class="wpp-share-modal-href"
                                       href="<?php echo $args['facebook']; ?>" title="">
                                        <span class="share-target-icon">
                                            <img src="/wp-content/themes/wpp-brabus/assets/img/icons/facebook.svg"
                                                 alt="">
                                        </span>
                                        Facebook
                                    </a>
                                </li>
                                <li>
                                    <a id="linkedin" target="popup" class="wpp-share-modal-href"
                                       href="<?php echo $args['linkedin']; ?>" title="">
                                        <span class="share-target-icon">
                                            <img src="/wp-content/themes/wpp-brabus/assets/img/icons/linkedin.svg"
                                                 alt="">
                                        </span>
                                        LinkedIn
                                    </a>
                                </li>
                                <li>
                                    <a id="twitter" target="popup" class="wpp-share-modal-href"
                                       href="<?php echo $args['twitter']; ?>" title="">
                                        <span class="share-target-icon">
                                            <img src="/wp-content/themes/wpp-brabus/assets/img/icons/twitter.svg"
                                                 alt="">
                                        </span>
                                        Twitter
                                    </a>
                                </li>
                                <li>
                                    <a id="vk" target="popup" class="wpp-share-modal-href"
                                       href="<?php echo $args['vk']; ?>" title="">
                                    <span class="share-target-icon">
                                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/vk.svg" alt="">
                                    </span>
                                        VK
                                    </a>
                                </li>
                                <li>
                                    <a id="email" target="popup" href="<?php echo $args['email']; ?>" title="">
                                    <span class="share-target-icon">
                                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/mail.svg" alt="">
                                    </span>
										<?php e_wpp_br_lng( 'email' ); ?>
                                    </a>
                                </li>
                                <li>
                                    <a id="whatsapp" target="popup" class="wpp-share-modal-href"
                                       href="<?php echo $args['whatsapp']; ?>" title="">
                                    <span class="share-target-icon">
                                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/whatsapp.svg" alt="">
                                    </span>
                                        WhatsApp
                                    </a>
                                </li>
                                <li>
                                    <a id="telegramm" target="popup" class="wpp-share-modal-href"
                                       href="<?php echo $args['telegram']; ?>" title="">
                                    <span class="share-target-icon">
                                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/telegram.svg" alt="">
                                    </span>
                                        Telegramm
                                    </a>
                                </li>
                                <li>
                                    <a id="skype" target="popup" href="<?php echo $args['skype']; ?>" title="">
                                    <span class="share-target-icon">
                                        <img src="/wp-content/themes/wpp-brabus/assets/img/icons/skype.svg" alt="">
                                    </span>
                                        Skype
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>


                    <!-- <li class="vpp-cart-action wpp-cart-link-li col-xl-offset-1 col-lg-offset-1">

                        <a href="javascript:void(0);" class="wpp-cart-save" title="">
                    <span class="wpp-ca-li-icon"
                          style="background-image: url(<?php /*echo get_home_url(); */ ?>/wp-content/themes/wpp-brabus/assets/img/icons/lists-icons.svg)"></span>
                            <b><?php /*echo wpp_br_lng( 'cart_link' ); */ ?></b>
                        </a>
                    </li>-->
                    <!-- <li class="vpp-cart-action wpp-save-as-pdf-li">
                        <a class="wpp-cart-share"
                           href="<?php /*echo esc_url( wp_nonce_url( add_query_arg( [ 'cart-pdf' => '1' ], wc_get_cart_url() ), 'cart-pdf' ) ); */ ?>"
                           title="">
                            <span class="wpp-ca-li-icon"
                                  style="background-image: url(<?php /*echo get_home_url(); */ ?>/wp-content/themes/wpp-brabus/assets/img/icons/pdf.svg)"></span>
                            <b><?php /*echo wpp_br_lng( 'save_pdf' ); */ ?></b>
                        </a>
                    </li>-->
                    <li class="vpp-cart-action wpp-remove-a-p-li">
                        <a href="javascript:void(0);" class="dell_ass" title="">
					<span class="wpp-ca-li-icon"
                          style="background-image: url(/wp-content/themes/wpp-brabus/assets/img/icons/trash2.svg)"></span>
                            <b><?php echo wpp_br_lng( 'remove_addit' ); ?></b>
                        </a>
                    </li>
                    <li class="vpp-cart-action wpp-clear-cart-li">
                        <a class="wpp-clear-cart" href="javascript:void(0);" title="">
					<span class="wpp-ca-li-icon"
                          style="background-image: url(/wp-content/themes/wpp-brabus/assets/img/icons/cross.svg)"></span>
                            <b><?php echo wpp_br_lng( 'clear_cart' ); ?></b>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>