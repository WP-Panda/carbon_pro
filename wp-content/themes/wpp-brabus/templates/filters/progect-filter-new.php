<?php
	/**
	 * @package carbon.coms
	 * @author  WP_Panda
	 * @version 1.0.0
	 */

	defined( 'ABSPATH' ) || exit;
	$filters_data = wpp_br_sort_filter_args();



?>
<style>
    .filter-select {
        width: 100%;
        border: 1px solid #afafaf;
        padding: 10px 5px 7px;
    }

    .show-drop .filter-select {
        border: none;
        padding: 0;
    }

    .filter-select button {
        width: 100%;
        background-color: #fff;
        border: none;
        text-align: left;
        font-size: 16px;
        color: #353535;
        position: relative;
    }

    .filter-select button:focus {
        border: none;
        outline: none;
    }

    .show-drop .filter-select button {
        display: none;
    }

    button:after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 32px;
        height: auto;
        content: "";
        opacity: .543;
        background: url("data:image/svg+xml;charset=utf-8,<svg width='24' height='24' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M15.483 9.297l-3.9 3.9-3.9-3.9a.99.99 0 00-1.4 1.4l4.593 4.593a1 1 0 001.414 0l4.593-4.593a.99.99 0 10-1.4-1.4z'/></svg>") 50% 50% no-repeat;
        transition: transform .1s ease-out;
    }

    .filter-drop {
        display: none;
        position: absolute;
        z-index: 99;
        background-color: #fff;
        width: calc(100% - 21px);
        border: 1px solid #d2d2d2;
        box-sizing: border-box;
        max-height: 400px;
        overflow-y: auto;
    }

    .filter-select input:focus {
        outline: none;
        border: 1px solid #000;
    }

    .filter-select input {
        width: 100%;
        padding: 10px 5px 7px;
        border: 1px solid #eee;
        border-radius: 0;
        box-shadow: none;
        box-sizing: border-box;
    }

    .filter-drop ul {
        padding: 10px 10px 10px 35px;
        list-style: none;
        font-size: 18px;
    }

    .filter-drop ul ul {
        padding: 0 0 0 15px;
        display: none;
    }

    li .check-filter:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        left: 7px;
        background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='10'><path d='M.295 5.477a.951.951 0 010-1.378l-.033.031a1.042 1.042 0 011.437.004l2.88 2.77L11.464.282a1.045 1.045 0 011.431.001l-.033-.031a.949.949 0 01-.004 1.382L5.3 8.902a1.053 1.053 0 01-1.438.005L.295 5.477z'/></svg>") 50% 50% no-repeat;
    }

    li.filter-sublavel .trigger:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        background: url("data:image/svg+xml;charset=utf-8,<svg width='25' height='25' xmlns='http://www.w3.org/2000/svg'><path fill='currentColor' d='M12.857 11.143h4.286a.857.857 0 010 1.714h-4.286v4.286a.857.857 0 01-1.714 0v-4.286H6.857a.857.857 0 010-1.714h4.286V6.857a.857.857 0 011.714 0v4.286z'></path></svg>");

    }

    li.filter-sublavel .trigger.opened:after {
        content: '';
        width: 25px;
        height: 25px;
        position: absolute;
        background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 384 512' ><path fill='currentColor' d='M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z' class=''></path></svg>");
        background-repeat: no-repeat;
        background-position: 50%;
    }

    .filter-drop li {
        padding-top: 10px;
        cursor: pointer;
    }

    .filter-drop li .filter-item:hover {
        border-bottom: 1px solid #d2d2d2;
    }

    span.trigger {
        cursor: pointer;
        border: 1px solid;
        width: 27px;
        height: 27px;
        position: absolute;
        right: 7px;
        border-radius: 5px;
    }

    span.trigger:hover {
        background-color: #eee;
        border-color: #eee;
    }
</style>

<div class="container">

    <form id="wpp-project-filter" class="wpp-form-filter row">

		<?php
			$html = '';
			foreach ( $filters_data as $one_filter_key => $one_filter_item ) :

				if ( 'car_brand' === $one_filter_key || 'car_model' === $one_filter_key) {
					continue;
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
			wpp_f_s();
			echo $html; ?>
        <div class="col-12 col-sm-3 col-lg-3 wpp-form-row">
            <input type="text"
                   class="wpp_pr_searh form--text form--border"
                   name="wpp_pr_searh" value="" placeholder="<?php e_wpp_br_lng( 'search' ); ?>"></div>
    </form>

</div>