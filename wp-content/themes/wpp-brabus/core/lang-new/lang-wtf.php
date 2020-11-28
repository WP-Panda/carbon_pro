<?php
	/**
	 * @package taxo.coms
	 * @author  WP_Panda
	 * @version 1.0.0
	 */

	defined( 'ABSPATH' ) || exit;

	/**
	 * @since 3.2.8 Copies the data needed for qtranxf_buildURL and qtranxf_url_set_language
	 */
	function wpp_fr_copy_url_info( $urlinfo ) {
		$r = [];
		if ( isset( $urlinfo[ 'scheme' ] ) ) {
			$r[ 'scheme' ] = $urlinfo[ 'scheme' ];
		}
		if ( isset( $urlinfo[ 'user' ] ) ) {
			$r[ 'user' ] = $urlinfo[ 'user' ];
		}
		if ( isset( $urlinfo[ 'pass' ] ) ) {
			$r[ 'pass' ] = $urlinfo[ 'pass' ];
		}
		if ( isset( $urlinfo[ 'host' ] ) ) {
			$r[ 'host' ] = $urlinfo[ 'host' ];
		}
		if ( isset( $urlinfo[ 'path-base' ] ) ) {
			$r[ 'path-base' ] = $urlinfo[ 'path-base' ];
		}
		if ( isset( $urlinfo[ 'path-base-length' ] ) ) {
			$r[ 'path-base-length' ] = $urlinfo[ 'path-base-length' ];
		}
		if ( isset( $urlinfo[ 'wp-path' ] ) ) {
			$r[ 'wp-path' ] = $urlinfo[ 'wp-path' ];
		}
		if ( isset( $urlinfo[ 'query' ] ) ) {
			$r[ 'query' ] = $urlinfo[ 'query' ];
		}
		if ( isset( $urlinfo[ 'fragment' ] ) ) {
			$r[ 'fragment' ] = $urlinfo[ 'fragment' ];
		}
		if ( isset( $urlinfo[ 'query_amp' ] ) ) {
			$r[ 'query_amp' ] = $urlinfo[ 'query_amp' ];
		}

		return $r;
	}


	function wpp_fr_language_neutral_path( $path ) {
		//qtranxf_dbg_log('qtranxf_language_neutral_path: path='.$path);
		//if(preg_match("#^(wp-comments-post.php|wp-login.php|wp-signup.php|wp-register.php|wp-cron.php|wp-admin/)#", $path)) return true;
		if ( empty( $path ) ) {
			return false;
		}
		static $language_neutral_path_cache;
		if ( isset( $language_neutral_path_cache[ $path ] ) ) {
			//qtranxf_dbg_log('qtranxf_language_neutral_path: cached='.$language_neutral_path_cache[$path].': path='.$path);
			return $language_neutral_path_cache[ $path ];
		}
		//if(preg_match('#^/(wp-.*\.php|wp-admin/|xmlrpc.php|.*sitemap.*|robots.txt|oauth/)#', $path)){//sitemap.hml works ok without it
		if ( preg_match( '#^/(wp-.*\.php|wp-login/|wp-admin/|xmlrpc.php|robots.txt|oauth/)#', $path ) ) {
			$language_neutral_path_cache[ $path ] = true;

			//qtranxf_dbg_log('qtranxf_language_neutral_path: preg_match: path='.$path);
			return true;
		}
		if ( qtranxf_ignored_file_type( $path ) ) {
			$language_neutral_path_cache[ $path ] = true;

			//qtranxf_dbg_log('qtranxf_language_neutral_path: file_type: path='.$path);
			return true;
		}
		$language_neutral_path_cache[ $path ] = false;

		return false;
	}


	function wpp_get_lang_preff() {

	}