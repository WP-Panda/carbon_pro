<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$args = [
	'post_type' => 'sale_car',
	'posts_per_page' => 8,
	'title_key' => 'car4sale',
];

wpp_get_template_part('templates/media/wall-static', $args);
