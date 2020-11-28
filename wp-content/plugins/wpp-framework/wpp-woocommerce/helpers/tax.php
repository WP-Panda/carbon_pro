<?php

	function wpp_fr_get_attributes_tax(){
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$taxonomy_terms = [];

		if ( $attribute_taxonomies ) :
			foreach ( $attribute_taxonomies as $tax ) :
				#wpp_dump( $tax );
				/*if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) :
					$taxonomy_terms[$tax->attribute_name] = get_terms( wc_attribute_taxonomy_name($tax->attribute_name), 'orderby=name&hide_empty=0' );
				endif;*/
			endforeach;
		endif;

		var_dump( $taxonomy_terms );
	}