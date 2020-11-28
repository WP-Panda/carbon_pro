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
<form id="wpp-al-lost-form" action="#" method="post">
    <h3><?php _e( 'RESET PASSWORD', 'wpp-fr' ); ?></h3>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12"><input type="text" class="form-control" name="user_login" id="user_login" value="" placeholder="<?php _e( 'Your Email address...', 'wpp-fr' ); ?> Email"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12">
                <p>
                    <a href="javascript:void(0)" title="" class="wpp-al-href" data-action="login"><?php _e( 'Login', 'wpp-fr' ); ?></a>
                    <a href="javascript:void(0)" title="" class="wpp-al-href" data-action="register"<?php _e( 'Register', 'wpp-fr' ); ?>></a>
                </p>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-offset-6 col-sm-6"><input type="submit" class="btn btn-black btn-inverse btn-block" name="reset_pass" value="<?php _e( 'RESET PASSWORD', 'wpp-fr' ); ?>" data-type="lost"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12"><br><br>
                <p class="text-center"><?php _e( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'wpp-fr' ); ?></p></div>
        </div>
    </div>
</form>
