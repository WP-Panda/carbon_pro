<?php
/**
 * File Description
 *
 * @author  WP Panda
 *
 * @package Time, it needs time
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<form id="wpp-al-register-form" action="#" method="post">
<h3><?php _e( 'REGISTER', 'wpp-fr' ); ?></h3>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12"><input type="email" class="form-control" name="user_login" id="email" value=""
                                          placeholder="<?php _e( 'Your Email address...', 'wpp-fr' ); ?>"></div>
        </div>
    </div>

	<?php do_action( 'wpp_fr_register_add_fields' ); ?>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-12">
                <p>
                    <a href="javascript:void(0)" title="" class="wpp-al-href"
                       data-action="login"><?php _e( 'Login', 'wpp-fr' ); ?></a>
                    <a href="javascript:void(0)" title="" class="wpp-al-href"
                       data-action="lost"><?php _e( 'Lost a password?', 'wpp-fr' ); ?></a>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-offset-6 col-sm-6"><input type="submit" class="btn btn-black btn-inverse btn-block"
                                                         name="register" value="<?php _e( 'REGISTER', 'wpp-fr' ); ?>"
                                                         data-type="register"></div>
        </div>
    </div>

</form>