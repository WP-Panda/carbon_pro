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
<form id="wpp-al-login-form" action="#" method="post">

    <h3><?php _e( 'LOGIN', 'wpp-fr' ); ?></h3>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-12">
                <input type="text" class="form-control" name="log" id="user_login" value="" placeholder="<?php _e( 'Username or Email', 'wpp-fr' ); ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-12">
                <input type="password" class="form-control" name="pwd" id="user_pass" value=""  placeholder="<?php _e( 'Enter a password', 'wpp-fr' ); ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-12"><p><a href="javascript:void(0)" title="" class="wpp-al-href" data-action="register"><?php _e( 'Register', 'wpp-fr' ); ?></a><a href="javascript:void(0)" title="" class="wpp-al-href" data-action="lost"><?php _e( 'Lost a password?', 'wpp-fr' ); ?></a></p></div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <input type="submit" class="btn btn-black btn-inverse btn-block" name="login" value="<?php _e( 'LOGIN', 'wpp-fr' ); ?>"
                           data-type="login">
                </div>
            </div>
        </div>
    </div>
</form>
