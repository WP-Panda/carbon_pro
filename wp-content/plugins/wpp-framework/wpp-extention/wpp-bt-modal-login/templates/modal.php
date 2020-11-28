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
	
	add_action( 'wp_footer', 'wpp_bt_modal_login', 150 );
	
	function wpp_bt_modal_login() {
	 
		if ( ! is_user_logged_in() ):?>
            <div class="modal fade" id="loginRegisterModal" tabindex="-1" role="dialog">

                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content">
                        <div  class="wpp-al-loading" style="display:none;width:100%;height:100%;background-color: rgba(0,0,0,0.8);position:absolute;margin:0;padding:0;z-index:10;font-size:50px;text-align:center;color:#fff;"><?php _e('Loading Form...','wpp-fr'); ?></div>

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e('Close','wpp-fr'); ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="wpp-al-response-message"></div>
                                    <div class="wpp-al-response-form">
										<?php
											if ( ! empty( $_GET[ 'key' ] ) && ! empty( $_GET[ 'login' ] ) && ( ! empty( $_GET[ 'action' ] ) && $_GET[ 'action' ] === 'rp' )  ) {
												wpp_al_set_form();
											} else {
												wpp_al_login_form();
											}; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
		<?php endif;
	}