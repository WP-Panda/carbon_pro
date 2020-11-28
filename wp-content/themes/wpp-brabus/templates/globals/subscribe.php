<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="container wpp-mc-row">
    <div class="row">
        <div class="col-sm-3">
            <img class="global-shipping" src="<?php echo get_template_directory_uri() . '/assets/img/icons/shipping.png'; ?>" alt="">
            <span class="global-shipping-label">Global Shipping</span>
        </div>
        <div class="col-sm-9">
            <form id="wpp-mc-subscribe"
                  action="https://carbon.us3.list-manage.com/subscribe/post?u=9443394dff87edb774d351185&amp;id=140242053c"
                  method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                  class="row"
                  target="_blank" novalidate>
                <div style="position: absolute; left: -5000px;" aria-hidden="true">
                    <input type="text" name="b_9443394dff87edb774d351185_140242053c" tabindex="-1" value="">
                </div>
                <div class="wpp-mc-fr form--row col-md-7">
                   <!-- <label for="mc-email" class="form--label"><?php /*_e( 'E-mail', 'wpp-br' ); */?> </label>-->
                    <input type="email" value="" name="EMAIL" placeholder="<?php _e( 'E-mail', 'wpp-br' ); ?>" class="email form--text form--border-bottom"
                           id="mce-EMAIL" required>
                </div>

                <div class="wpp-mc-fr form--row in col-md-5">
                    <button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe"
                            class="form--button-dark form--button--cta"><?php echo wpp_br_lng( 'subscribe' ); ?></button>
                </div>
        </div>
        </form>
    </div>
</div>
