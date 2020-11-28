<?php
/**
 * Проверка сегмента в Категории
 *
 * @param $term_ID
 *
 * @return array|null|object
 */
function wpp_fr_check_cat_in_segments( $term_ID ) {
	global $wpdb, $wpp_fr_mc;

	$table = $wpdb->prefix . $wpp_fr_mc::$table_segment;
	return $wpdb->get_results( "SELECT * FROM $table WHERE `term_id`=$term_ID" );
}

/**
 * Получение всех сегментов
 * @return array|null|object
 */
function wpp_fr_mc_get_all_segmets() {
	global $wpdb, $wpp_fr_mc;

	$table = $wpdb->prefix . $wpp_fr_mc::$table_segment;
	return $wpdb->get_results( "SELECT `intersest_id`,`name` FROM $table" );
}