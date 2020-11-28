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
	}
	
	
	$user = check_password_reset_key( $_GET[ 'key' ], wp_unslash( $_GET[ 'login' ] ) );
	
	if ( isset( $_POST[ 'pass1' ] ) && ! hash_equals( $_GET[ 'key' ], $_POST[ 'rp_key' ] ) ) {
		$user = false;
	}
	
	
?>
<form id="wpp-al-set-form" action="#" method="post">
    <h3><?php _e( 'SET PASSWORD', 'wpp-fr' ); ?></h3>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12">
                <input type="text" class="form-control" name="pass1" id="pass1" value="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" placeholder="<?php _e( 'Enter Password', 'wpp-fr' ); ?>"
                       autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12">
                <p>
                    <a href="javascript:void(0)" title="" class="wpp-al-href" data-action="login"><?php _e( 'Login', 'wpp-fr' ); ?></a>
                    <a href="javascript:void(0)" title="" class="wpp-al-href" data-action="register"><?php _e( 'Register', 'wpp-fr' ); ?></a>
                </p>
            </div>
        </div>
    </div>
    <input type="hidden" name="rp_key" value="<?php echo esc_attr( $_GET[ 'key' ] ); ?>"/>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-offset-6 col-sm-6"><input type="submit" class="btn btn-black btn-inverse btn-block" name="reset_pass" value="<?php _e( 'SET PASSWORD', 'wpp-fr' ); ?>"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12"><br><br>
                <p class="text-center"><?php _e( 'Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols
                    like ! " ? $ % ^ & ).', 'wpp-fr' ); ?></p></div>
        </div>
    </div>
</form>
