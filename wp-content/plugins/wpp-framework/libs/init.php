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
	
	require_once 'meta-box/meta-box.php';
	require_once 'metabox-extensions/mb-frontend-submission/mb-frontend-submission.php';
	require_once 'image-optim/vendor/autoload.php';

	require_once 'metabox-extensions/mb-settings-page/mb-settings-page.php';
	#require_once 'metabox-extensions/mb-user-meta/mb-user-meta.php';
	require_once 'metabox-extensions/mb-user-profile/mb-user-profile.php';
	#require_once 'metabox-extensions/meta-box-builder/meta-box-builder.php';
	require_once 'metabox-extensions/meta-box-columns/meta-box-columns.php';
	require_once 'metabox-extensions/wpp-meta-box-custom-heading/meta-box-custom-heading.php';
	require_once 'metabox-extensions/meta-box-conditional-logic/meta-box-conditional-logic.php';
	require_once 'metabox-extensions/wpp-meta-box-fontawesome/meta-box-fontawesome.php';
	#require_once 'metabox-extensions/meta-box-geolocation/meta-box-geolocation.php';
	require_once 'metabox-extensions/meta-box-group/meta-box-group.php';
	require_once 'metabox-extensions/meta-box-include-exclude/meta-box-include-exclude.php';
	require_once 'metabox-extensions/meta-box-show-hide/meta-box-show-hide.php';
	require_once 'metabox-extensions/meta-box-tabs/meta-box-tabs.php';
	require_once 'metabox-extensions/meta-box-tooltip/meta-box-tooltip.php';
	require_once 'metabox-extensions/wpp-meta-box-image-heading/meta-box-image-heading.php';
	require_once 'metabox-extensions/term-meta/mb-term-meta.php';
	require_once 'metabox-extensions/mb-admin-columns/mb-admin-columns.php';

	//TODO: Ошибки при активации когда подключено это
	#require_once 'intuitive-custom-post-order/intuitive-custom-post-order.php';

	#когда подключаем pdf
	if( WPP_FR_PDF === true ) {
		require_once 'for-pdf/autoload.php';
	}

require_once 'specific-libraries/index.php';
	
	
	require_once 'kirki/kirki.php';