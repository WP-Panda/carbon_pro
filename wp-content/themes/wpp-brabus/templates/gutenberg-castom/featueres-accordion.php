<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


$block_content = str_replace(
	[
		'<table>',
		'<tbody>',
		'wp-block-table'
	],
	[
		'<table class="structured-table ">',
		'<tbody class="neos-contentcollection">',
		'wpp-block-table'
	],
	$args['html']
);

echo wpp_get_template_part( 'templates/part/accordion', [
	'content' => $block_content,
	'head'    => wpp_br_lng( 'standard_features' )
] );
