<?php
	/**
	 * @package carbon.coms
	 * @author  WP_Panda
	 * @version 1.0.0
	 */

	defined( 'ABSPATH' ) || exit;
	$filters_data = wpp_br_sort_filter_args();
?>

<div class="container">

	<form id="wpp-project-filter" class="wpp-form-filter row">

		<?php
			$html = '';
			foreach ( $filters_data as $one_filter_key => $one_filter_item ) :

				if ( 'car_brand' === $one_filter_key ) {
					$option = 'select_maker';
				} elseif ( 'car_model' === $one_filter_key ) {
					$option = 'select_model';
				} else {
					$option = 'tuning';
				}

				$html_s = '<option value="">' . wpp_br_lng( $option ) . '</option>';
				foreach ( $one_filter_item as $one_key => $one_name ) {
					$html_s .= sprintf( '<option value=\'.t_%s\' >%s</option>', (int) $one_key, $one_name );
				}

				$html .= sprintf( '<div class="col-12 col-sm-3 col-lg-3 wpp-form-row wpp-filers-wrap"><select name="%s"
                              class="wpp-mix-filter form--text form--border-bottom dirty filter-news-cats">%s</select></div>', $one_filter_key, $html_s );
			endforeach;

			echo $html; ?>
		<div class="col-12 col-sm-3 col-lg-3 wpp-form-row">
			<input type="text"
			       class="wpp_pr_searh form--text form--border"
			       name="wpp_pr_searh" value="" placeholder="<?php e_wpp_br_lng( 'search' ); ?>"></div>
	</form>

</div>
