<?php


	function wpp_br_switcher_wrap( $args ) {
		$args[ 'class' ] = [
			'flyout-navigation',
			'language-navigation'
		];
		$args[ 'in_before' ] = '<li class="flyout-item">';
		$args[ 'in_after' ] = '</li>';

		return $args;
	}

	add_filter( 'wpp_fr_lng_html_wrap', 'wpp_br_switcher_wrap' );

	function wpp_br_switcher_item( $args ) {
		$args['skip_li'] = true;
	}

	add_filter( 'wpp_fr_lng_html_item', 'wpp_br_switcher_item' );