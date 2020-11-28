<?php
/**
 * Настройки плагинов
 *
 * @package WppFramework\WppMastHave\Helpers
 * @version 1.0.0
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'wpp_fr_hide_plugin_in_list' ) ) :

	/**
	 * Скрытие плагина из списка плагинов в админке
	 * @since   1.0.1
	 *
	 */

	function wpp_fr_hide_plugin_in_list() {
		global $wp_list_table;

		/**
		 * Массив со списком плагинов которые надо скрыть
		 * формат записи plugin-directory/plugin-file.php
		 */
		$hide_array = apply_filters( 'wpp_fr_plugins_hide', [] );

		if ( ! empty( $hide_array ) ) :

			$plugins = $wp_list_table->items;
			foreach ( $plugins as $key => $val ) {
				if ( in_array( $key, $hide_array ) ) {
					unset( $wp_list_table->items[ $key ] );
				}
			}

		endif;
	}

endif;
add_action( 'pre_current_active_plugins', 'wpp_fr_hide_plugin_in_list' );

if ( ! function_exists( 'wpp_fr_disable_plugin_updates' ) ) :

	/**
	 *  Скрытие обновления для плагинов
	 *
	 * @param $value
	 *
	 * @return mixed
	 */

	function wpp_fr_disable_plugin_updates( $value ) {

		/**
		 * Массив со списком плагинов которые надо скрыть
		 * формат записи plugin-directory/plugin-file.php
		 */
		$hide_array = apply_filters( 'wpp_fr_plugins_update_hide', [] );

		if ( ! empty( $hide_array ) ) :

			if ( isset( $value ) && is_object( $value ) ) {
				foreach ( $hide_array as $plugin ) :

					if ( isset( $value->response[ $plugin ] ) ) {
						unset( $value->response[ $plugin ] );
					}

				endforeach;
			}

		endif;

		return $value;
	}

	add_filter( 'site_transient_update_plugins', 'wpp_fr_disable_plugin_updates' );

endif;


if ( ! function_exists( 'wpp_fr_unset_deactivation_plugins' ) ) :
	/**
	 * hide plugin deativation link
	 *
	 * @param $actions
	 * @param $plugin_file
	 * @param $plugin_data
	 * @param $context
	 *
	 * @return mixed
	 */
	function wpp_fr_unset_deactivation_plugins( $actions, $plugin_file, $plugin_data, $context ) {

		if ( array_key_exists( 'edit', $actions ) ) {
			unset( $actions['edit'] );
		}

		/**
		 * Массив со списком плагинов которым надо скрыть деактивацию
		 * формат записи plugin-directory/plugin-file.php
		 */
		$hide_array = apply_filters( 'wpp_fr_plugins_deactivate_hide', [] );
		if ( ! empty( $hide_array ) ) :

			if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, $hide_array ) ) {
				unset( $actions['deactivate'] );
			}

		endif;

		return $actions;
	}

	add_filter( 'plugin_action_links', 'wpp_fr_unset_deactivation_plugins', 10, 4 );
endif;