<?php
	add_filter( 'post_gallery', 'my_post_gallery', 10, 2 );
	function my_post_gallery( $output, $attr ) {
		global $post, $wp_locale;

		static $instance = 0;
		$instance++;

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr[ 'orderby' ] ) ) {
			$attr[ 'orderby' ] = sanitize_sql_orderby( $attr[ 'orderby' ] );
			if ( !$attr[ 'orderby' ] )
				unset( $attr[ 'orderby' ] );
		}

		extract( shortcode_atts( [
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		], $attr ) );

		$id = intval( $id );
		if ( 'RAND' == $order )
			$orderby = 'none';

		if ( !empty( $include ) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( [
				'include'        => $include,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $order,
				'orderby'        => $orderby
			] );

			$attachments = [];
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( !empty( $exclude ) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( [
				'post_parent'    => $id,
				'exclude'        => $exclude,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $order,
				'orderby'        => $orderby
			] );
		} else {
			$attachments = get_children( [
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $order,
				'orderby'        => $orderby
			] );
		}

		if ( empty( $attachments ) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			return $output;
		}

		$selector = "gallery-{$instance}";
		$output .= '</div></div></section>';
		$output .= sprintf( '<div id="%s" class="gallery galleryid-%s carousel flickity-enabled is-draggable">', $selector, $id );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$link = isset( $attr[ 'link' ] ) && 'file' == $attr[ 'link' ] ? wp_get_attachment_link( $id, 'full', false, false ) : wp_get_attachment_link( $id, 'full', true, false );
			$selected = $i === 1 ? 'true' : 'false';
			$output .= sprintf( '<div class="carousel-cell carousel-color-" aria-selected="%s">', $selected );
			$output .= sprintf( '<picture><img src="%s"></picture>', $link );
			$output .= "</div>";
		}
		$output .= '</div><section class="text-box-container text-box-white">
                        <div class="text-box-row">
                            <div class="text-box">';

		return $output;
	}