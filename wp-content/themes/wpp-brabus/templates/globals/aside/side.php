<section class="flyout-container navbar-light">

    <div class="flyout-navigation-toggle" id="navbarSupportedContent">

		<?php $search_action = wpp_fr_get_page_id( 'search' );
		if ( ! empty( $search_action ) ) :
			$action = get_the_permalink( $search_action );
			if ( ! empty( $action ) ): ?>
                <form class="wpp-s-form" action="<?php echo $action ?>">
                    <div class="form--row">
                        <label for="product_search"
                               class="form--label"><?php echo wpp_br_lng( 'search' ); ?></label>
                        <input type="text" class="form--text form--border" id="product_search" name="product_search"
                               value="">
                        <button type="submit" id="wpp-send-product-search"
                                style="background-image: url('<?php echo get_template_directory_uri() . '/assets/img/icons/search.svg' ?>')"></button>
                    </div>
                </form>
			<?php endif;
		endif;
		?>

        <div class="flyout-body">
			<?php
			wp_nav_menu( [
				'theme_location' => 'main_' . get_locale(),
				'container'      => '',
				'menu_class'     => 'flyout-navigation',
				'add_li_class'   => 'flyout-item',
				'walker'         => new IBenic_Walker()
			] );

			wpp_lng_swither();
			wpp_currency_swither();
			?>
        </div>
    </div>
</section>