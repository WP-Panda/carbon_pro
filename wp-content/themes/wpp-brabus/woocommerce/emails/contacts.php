<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
    <p><?php printf( esc_html__( 'Name -  %s,', 'wpp-fr' ), esc_html( $data['name'] ) ); ?></p>
    <p><?php printf( esc_html__( 'Email -  %s,', 'wpp-fr' ), esc_html( $data['email'] ) ); ?></p>
<?php if ( ! empty( $data['country'] ) ) { ?>
    <p><?php printf( esc_html__( 'Country -  %s,', 'wpp-fr' ), esc_html( $data['country'] ) ); ?></p>
<?php } ?>
    <p><?php printf( esc_html__( '%s,', 'wpp-fr' ), esc_html( $data['message'] ) ); ?></p>
<?php do_action( 'woocommerce_email_footer', $email );