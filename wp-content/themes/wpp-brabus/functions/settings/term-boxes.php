<?php
	/**
	 * Created by PhpStorm.
	 * User: WP_Panda
	 * Date: 16.07.2019
	 * Time: 18:13
	 */

	/*
 * Meta Box Removal
 */
	function rudr_post_tags_meta_box_remove() {

		$id = 'tagsdiv-brands'; // you can find it in a page source code (Ctrl+U)
		$post_type = 'product'; // remove only from post edit screen
		$position = 'side';

		remove_meta_box( $id, $post_type, $position );

		$id = 'tagsdiv-as_options'; // you can find it in a page source code (Ctrl+U)
		$post_type = 'product'; // remove only from post edit screen
		$position = 'side';

		remove_meta_box( $id, $post_type, $position );

	}
	add_action( 'admin_menu', 'rudr_post_tags_meta_box_remove');

	/*
 * Add
 */
	function rudr_add_new_tags_metabox(){
		$id = 'tagsdiv-as_options'; // it should be unique
		$heading = 'AS Makers'; // meta box heading
		$callback = 'wpp_metabox_content'; // the name of the callback function
		$post_type = 'product';
		$position = 'side';
		$pri = 'high'; // priority, 'default' is good for us
		add_meta_box( $id, $heading, $callback, $post_type, $position, $pri );


		$id = 'tagsdiv-brands'; // it should be unique
		$heading = 'Brands'; // meta box heading
		$callback = 'rudr_metabox_content'; // the name of the callback function
		$post_type = 'product';
		$position = 'side';
		$pri = 'default'; // priority, 'default' is good for us
		add_meta_box( $id, $heading, $callback, $post_type, $position, $pri );

	}
	add_action( 'admin_menu', 'rudr_add_new_tags_metabox');

	/*
	 * Fill
	 */
	function rudr_metabox_content($post) {

		// get all blog post tags as an array of objects
		$all_tags = get_terms( array('taxonomy' => 'brands', 'hide_empty' => 0) );

		// get all tags assigned to a post
		$all_tags_of_post = get_the_terms( $post->ID, 'brands' );

		// create an array of post tags ids
		$ids = array();
		if ( $all_tags_of_post ) {
			foreach ($all_tags_of_post as $tag ) {
				$ids[] = $tag->term_id;
			}
		}

		// HTML
		echo '<div id="taxonomy-post_tag" class="categorydiv">';
		echo '<input type="hidden" name="tax_input[brands][]" value="0" />';
		echo '<ul>';
		foreach( $all_tags as $tag ){
			// unchecked by default
			$checked = "";
			// if an ID of a tag in the loop is in the array of assigned post tags - then check the checkbox
			if ( in_array( $tag->term_id, $ids ) ) {
				$checked = " checked='checked'";
			}
			$id = 'brands-' . $tag->term_id;
			echo "<li id='{$id}'>";
			echo "<label><input type='checkbox' name='tax_input[brands][]' id='in-$id'". $checked ." value='$tag->slug' /> $tag->name</label><br />";
			echo "</li>";
		}
		echo '</ul></div>'; // end HTML
	}

	/*
	 * Fill
	 */
	function wpp_metabox_content($post) {

		// get all blog post tags as an array of objects
		$all_tags = get_terms( array('taxonomy' => 'as_options', 'hide_empty' => 0) );

		// get all tags assigned to a post
		$all_tags_of_post = get_the_terms( $post->ID, 'as_options' );

		// create an array of post tags ids
		$ids = array();
		if ( $all_tags_of_post ) {
			foreach ($all_tags_of_post as $tag ) {
				$ids[] = $tag->term_id;
			}
		}

		// HTML
		echo '<div id="taxonomy-post_tag" class="categorydiv">';
		echo '<input type="hidden" name="tax_input[as_options][]" value="0" />';
		echo '<ul>';
		foreach( $all_tags as $tag ){
			// unchecked by default
			$checked = "";
			// if an ID of a tag in the loop is in the array of assigned post tags - then check the checkbox
			if ( in_array( $tag->term_id, $ids ) ) {
				$checked = " checked='checked'";
			}
			if($tag->term_id == 85 || $tag->term_id == 81 ) {
				continue;
			}
			$id = 'brands-' . $tag->term_id;
			echo "<li id='{$id}'>";
			echo "<label><input type='checkbox' name='tax_input[as_options][]' id='in-$id'". $checked ." value='$tag->slug' /> $tag->name</label><br />";
			echo "</li>";
		}
		echo '</ul></div>'; // end HTML
	}