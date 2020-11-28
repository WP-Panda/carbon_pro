<?php
$options = get_option( 'wpp_br' );

$address = ! empty( $options ) && ! empty( $options['wpp_mc_address'] ) ? $options['wpp_mc_address'] : null;
$phone   = ! empty( $options ) && ! empty( $options['wpp_mc_phone'] ) ? $options['wpp_mc_phone'] : null;
?>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">


                <section class="container-fluid responsive-gutter section-padding-medium teaser-dark" id="contact">
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <p class="copy">
								<?php if ( ! empty( $address ) ) {
									printf( '<strong>%s</strong><br>', $address );
								} ?>
								<?php if ( ! empty( $phone ) && function_exists( 'wpp_phone_link' ) ) {
									echo wpp_phone_link( $phone );
								} ?>
                            </p>
                        </div>
                    </div>
                </section>


                <section class="container-fluid responsive-gutter section-padding-medium">
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <div class="ok-for">
                                <form enctype="multipart/form-data" method="post" id="contact" action="">
                                    <h3 class="font-size-small light headline-padding-small">
										<?php _e( 'We look forward to your message', 'wpp-brabus' ); ?>
                                    </h3>

                                    <div class="form--row">
                                        <label for="contact-name"
                                               class="form--label"><?php _e( 'Name', 'wpp-brabus' ); ?>
                                            *</label>
                                        <input type="text" class="form--text form--border" id="contact-name"
                                               name="name" value="">
                                    </div>

                                    <div class="form--row">
                                        <label for="contact-email"
                                               class="form--label"><?php _e( 'Mail', 'wpp-brabus' ); ?>
                                            *</label>
                                        <input type="email" class="form--text form--border " id="contact-email"
                                               name="email" value="">

                                    </div>

                                    <div class="form--row">
                                        <label for="contact-country" class="form--select-label">
                                            <select id="contact-country" name="country" class="form--select">
                                                <option class="select__option" value="" disabled=""
                                                        selected=""><?php _e( 'Country  selection', 'wpp-fr' ); ?>
                                                </option>
												<?php $options = wpp_fr_geo_array_country( 'ru_RU' );
												foreach ( $options as $option ) {
													printf( '<option class="select__option" value="%1$s">%1$s</option>', $option );
												}
												?>

                                            </select>
                                        </label>
                                    </div>

                                    <div class="form--row">
                                        <label for="contact-message"
                                               class="form--label"><?php _e( 'Message', 'wpp-brabus' ); ?>
                                            *</label>
                                        <textarea class="form--text form--border xxlarge" id="contact-message"
                                                  name="message"></textarea>
                                    </div>

                                    <div class="form--row">
                                        <div class="form--checkbox form--border-none">
                                            <input data-prevent-submit="true" id="contact-tac" type="checkbox"
                                                   name="tac" value="1">
                                            <label for="contact-tac" class="form--label__multiLine">
												<?php printf( __( 'I confirm that I have read and agree to the %s. I agree that the data entered will be used to answer my request.', 'wpp-brabus' ), sprintf( '<a href="%s">%s</a>', get_privacy_policy_url(), __( 'privacy policy', 'wpp-brabus' ) ) ); ?>
                                            </label>
                                        </div>

                                    </div>

                                    <div class="actions form--row" id="contact-actions">
                                        <button formnovalidate="formnovalidate" type="submit"
                                                class="form--button-light form--button-border auto">
											<?php _e( 'Send', 'wpp-brabus' ); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="form-sends"></div>
                        </div>
                    </div>
                </section>


            </div>
        </div>
    </div>
</div>