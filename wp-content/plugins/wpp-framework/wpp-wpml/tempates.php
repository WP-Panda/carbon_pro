<?php
	add_action( 'wpp_lng_switch', 'wpp_fr_wpml_lng_switch' );
	function wpp_fr_wpml_lng_switch() {
		$items = $lng_html = '';

		// get languages
		$languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );


		$lng_html_wrap_default = <<<WRAP
			%3\$s<%1\$s class="%2\$s">%5\$s%7\$s%6\$s</%1\$s>%4\$s
WRAP;

		$args_default = [
			'tag'        => 'ul',
			//тэг
			'class'      => [ 'lng_html_wrap_lng_switch' ],
			// классы
			'out_before' => '',
			//до свитчера
			'out_after'  => '',
			//после свитчера
			'in_before'  => '',
			// внутри до элементов
			'in_after'   => ''
			//внутри после элементов
		];
		$args = (object)apply_filters( 'wpp_fr_lng_html_wrap', $args_default );


		$lng_html_item_default = <<<ITEM
			<%1\$s class="%2\$s"><a href="%3\$s" title="%4\$s">%5\$s</a></%1\$s>
ITEM;

		$lng_html_item_default_wo_li = <<<ITEM
			<a href="%3\$s" title="%4\$s">%5\$s</a>
ITEM;

		$items_args_default = [
			'tag'     => 'li',
			//тэг
			'class'   => [ 'lng_html_wrap_lng_switch_swith flyout-navigation language-navigation' ],
			'skip_li' => false //если убрать li
		];

		$items_args = (object)apply_filters( 'wpp_fr_lng_html_item', $items_args_default );


		if ( !empty( $languages ) ) {

			foreach ( $languages as $language ) {
				#if ( !$language[ 'active' ] ) {
				// flag with native name
				$items .= sprintf( !empty($items_args->skip_li ) ? $lng_html_item_default : $lng_html_item_default_wo_li , $items_args->tag, ( is_array( $items_args->class ) ? implode( ' ', $items_args->class ) : esc_attr( $items_args->class ) ), $language[ 'url' ], $language[ 'language_code' ], $language[ 'native_name' ] );
				#}
			}

			$lng_html = sprintf( $lng_html_wrap_default, $args->tag, ( is_array( $args->class ) ? implode( ' ', $args->class ) : esc_attr( $args->class ) ), $args->out_before, $args->out_after, $args->in_before, $args->in_after, $items );

		}


		echo $lng_html;
	}