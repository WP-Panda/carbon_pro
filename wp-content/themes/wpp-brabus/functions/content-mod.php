<?php
	add_filter( 'the_content', 'wpp_br_the_content_filter', 10, 1 );

	function wpp_br_the_content_filter( $content ) {

		if ( is_singular( 'post' ) && has_post_thumbnail() ) {

			$img = get_the_post_thumbnail_url( $GLOBALS[ 'post' ]->ID, 'full' );
			$parags = explode( '</p>', $content );
			$parags[ 0 ] .= sprintf( '<img style="max-width:%s;" src="%s">', '100%', $img );

			$content_new = '';

			foreach ( $parags as $parag ) {
				$content_new .= $parag;
			}

			return $content_new;
		}

		return $content;

	}

	add_filter( 'excerpt_length', function() {
		if ( is_archive() || is_category() || is_home() || is_front_page() ) {
			return 20;
		}
	} );


	add_filter( 'excerpt_more', function( $more ) {
		if ( is_archive() || is_category()|| is_home() || is_front_page()  ) {
			return '...';
		}
		return $more;
	} );