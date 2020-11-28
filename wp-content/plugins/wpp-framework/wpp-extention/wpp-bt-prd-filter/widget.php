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
	function wpp_fp_load_widget() {
		register_widget( 'WPP_PR_Filter_Widget' );
	}
	
	add_action( 'widgets_init', 'wpp_fp_load_widget' );
	
	/**
	 * Core class used to implement the Navigation Menu widget.
	 *
	 * @since 3.0.0
	 *
	 * @see   WP_Widget
	 */
	class WPP_PR_Filter_Widget extends WP_Widget {
		
		/**
		 * Sets up a new Navigation Menu widget instance.
		 *
		 * @since 3.0.0
		 */
		public function __construct() {
			$widget_ops = array (
				'description'                 => __( 'WPP Product filter widget' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'wpp_pr_filter', __( 'Wpp PR Filter' ), $widget_ops );
		}
		
		/**
		 * Outputs the content for the current Navigation Menu widget instance.
		 *
		 * @since 3.0.0
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Navigation Menu widget instance.
		 */
		public function widget( $args, $instance ) {
			wp_enqueue_script( 'wpp-pf-front' );
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpp_prdf_filter';
			$larst_pref = ' AND stock_status NOT LIKE (\'outofstock\')';
			$last_last = '';
			
			$term = get_queried_object();
			if ( ! empty( $term ) && ! is_shop() ) {
				$larst_pref .= ' AND ' . wpp_bt_convert_atts( $term->taxonomy ) . ' LIKE ' . '\'%' . $term->term_id . '%\'';
			}
			
			$wpp_pr_filter = ! empty( $instance[ 'wpp_pr_filter' ] ) ? $instance[ 'wpp_pr_filter' ] : false;
			if ( empty( $wpp_pr_filter ) ) {
				return;
			}
			
			if ( $wpp_pr_filter === 'keywords' ) {
				return;
			} elseif ( $wpp_pr_filter === 'product_cat' || $wpp_pr_filter === 'product_tag' || $wpp_pr_filter === 'price' ) {
			
			} else {
				$wpp_pr_filter = 'pa_' . wpp_bt_convert_atts( $wpp_pr_filter );
			}
			
			if ( ! empty( $_GET ) ) {
				foreach ( $_GET as $key => $val ) {
				    if( $key === 'min' || $key === 'max'){
					    if( $key === 'min' ) {
					        $min_price = (int)$val;
                        }
					    if( $key === 'max' ) {
						    $max_price = (int)$val;
					    }
				        continue;
                    }
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $key ) ) ) :
						$vals = explode( ',', $val );
						foreach ( $vals as $one ) {
							$last_last .= ' AND pa_' . wpp_bt_convert_atts( $key ) . ' LIKE ' . '\'%' . $one . '%\'';
						}
					endif;
				}
			}
			
			$min_price = !empty($min_price) ? $min_price : 0;
			$max_price = !empty($max_price) ? $max_price : 1000000000000;
			$last_last .= " AND `price` BETWEEN $min_price AND $max_price";
			
			$results = $wpdb->get_results(
				"SELECT DISTINCT $wpp_pr_filter FROM $table_name WHERE $wpp_pr_filter NOT LIKE ('') $larst_pref  $last_last"
			);
			if ( empty( $results ) ) {
				return;
			}
			
			$out = [];
			foreach ( $results as $result ) {
				
				$sub_dump = explode( ',', $result->{$wpp_pr_filter} );
				
				foreach ( $sub_dump as $one ) {
					$out[] = $one;
				}
				
			}
			
			if ( ! $wpp_pr_filter ) {
				return;
			}
			
			$title = ! empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			echo $args[ 'before_widget' ];
			$rnd = rand( 10, 10000000 );
			if ( $title ) {
				echo $args[ 'before_title' ] . '<h4 class="option-title"><a href="#' . $wpp_pr_filter . $rnd . '" title role="button" data-toggle="collapse" aria-expanded="true" aria-controls="test-2s" class>' . $title . '</a></h4>' . $args[ 'after_title' ];
			}
			$dropdown = ! empty( $instance[ 'dropdown' ] ) ? ' in' : '';
			
			if ( $wpp_pr_filter === 'price' ) {
				$resultsa = $wpdb->get_results(
					"SELECT min(price) as min_price, max(price) as max_price FROM $table_name WHERE price NOT LIKE ('') $larst_pref  $last_last AND price NOT LIKE ('0')"
				);
				echo '<div class="collapse' . $dropdown . '" id="' . $wpp_pr_filter . $rnd . '" aria-expanded="true" style="">';
				
				    $min = !empty($_GET['min']) ? (int)$_GET['min'] : '';
				    $max = !empty($_GET['max']) ? (int)$_GET['max'] : '';
				?>

                <div class="row">
                    <div class="col-xs-6 text-left">
                        <div class="form-group">
                            <label for="min">Min</label>
                            <input type="number" class="form-control" name="min" id="min" value="<?php echo $min ?>">
                        </div>
                    </div>
                    <div class="col-xs-6 text-right">
                        <div class="form-group">
                            <label for="max">Max</label>
                            <input type="number" class="form-control" name="max" id="max" value="<?php echo $max; ?>">

                        </div>
                    </div>
                    <div class="col-xs-12">
                        <input type="button" name="submit" id="wpp-bt-fs" class="btn btn-orange btn-block" value="OK">
                    </div>
                </div>
				
				<?php echo '</div>';
			} elseif ( $wpp_pr_filter === 'product_cat' || $wpp_pr_filter === 'product_tag' ) {
				return;
			} elseif ( $wpp_pr_filter === 'keywords' ) {
				return;
			} else {
				
				echo '<div class="row"> <div class="collapse' . $dropdown . '" id="' . $wpp_pr_filter . $rnd . '" aria-expanded="true" style="">';
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $instance[ 'wpp_pr_filter' ] ) ) ) :
					$array_terms = array (
						'taxonomy'   => wc_attribute_taxonomy_name( $instance[ 'wpp_pr_filter' ] ),
						'hide_empty' => 1,
						'orderby'    => 'name',
						'include'    => $out
					
					);
					$terms = get_terms( $array_terms );
					$count = count( $terms );
					$n = 1;
					foreach ( $terms as $term ) {
						$hidden = $n > 5 ? ' hidden' : null;
						$checked = null;
						if ( ! empty( $_GET[ $instance[ 'wpp_pr_filter' ] ] ) ) {
							$valls = explode( ',', $_GET[ $instance[ 'wpp_pr_filter' ] ] );
							if ( in_array( $term->term_id, $valls ) ) {
								$checked = ' checked="checked"';
							}
						}
						$count_post = (int) $wpdb->get_var(
							"SELECT COUNT(`post_id`) FROM $table_name WHERE $wpp_pr_filter NOT LIKE ('') $larst_pref  $last_last AND $wpp_pr_filter LIKE " . "'%" . $term->term_id . "%'"
						);
						
						echo '<div class="checkbox' . $hidden . '"><input type="checkbox" name="' . $instance[ 'wpp_pr_filter' ] . '[]" id="' . $instance[ 'wpp_pr_filter' ] . '-' . $term->term_id . '" value="' . $term->term_id . '"' . $checked . '><label for="' . $instance[ 'wpp_pr_filter' ] . '-' . $term->term_id . '">' . $term->name . ' <span>' . $count_post . '</span></label></div>';
						$n ++;
					}
				endif;
				if ( $count > 5 ) {
					echo '<a href="javascript:void(0)" title="" class="toggle-checkboxes">See ' . ( $count - 5 ) . ' more</a>';
				}
				echo '</div>';
			} ?>
		
			<?php
			/**
			 * Filters the arguments for the Navigation Menu widget.
			 *
			 * @since 4.2.0
			 * @since 4.4.0 Added the `$instance` parameter.
			 *
			 * @param array        $nav_menu_args {
			 *                                    An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
			 *
			 * @type callable|bool $fallback_cb   Callback to fire if the menu doesn't exist. Default empty.
			 * @type mixed         $menu          Menu ID, slug, or name.
			 * }
			 *
			 * @param WP_Term      $wpp_pr_filter Nav menu object for the current menu.
			 * @param array        $args          Display arguments for the current widget.
			 * @param array        $instance      Array of settings for the current widget.
			 */
			
			echo $args[ 'after_widget' ];
		}
		
		/**
		 * Handles updating settings for the current Navigation Menu widget instance.
		 *
		 * @since 3.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 *
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array ();
			if ( ! empty( $new_instance[ 'title' ] ) ) {
				$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
			}
			if ( ! empty( $new_instance[ 'wpp_pr_filter' ] ) ) {
				$instance[ 'wpp_pr_filter' ] = esc_attr( $new_instance[ 'wpp_pr_filter' ] );
			}
			
			$instance[ 'dropdown' ] = $new_instance[ 'dropdown' ] ? 1 : 0;
			
			return $instance;
		}
		
		/**
		 * Outputs the settings form for the Navigation Menu widget.
		 *
		 * @since 3.0.0
		 *
		 * @param array                 $instance Current settings.
		 *
		 * @global WP_Customize_Manager $wp_customize
		 */
		public function form( $instance ) {
			
			$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
			$wpp_pr_filter = isset( $instance[ 'wpp_pr_filter' ] ) ? $instance[ 'wpp_pr_filter' ] : '';
			$dropdown = ! empty( $instance[ 'dropdown' ] ) ? 1 : 0;
			
			$array = array (
				'product_cat',
				'product_tag',
				'price',
				'keywords'
			);
			
			$options = sprintf( '<option value="0">%s</option>', __( '&mdash; Select &mdash;' ) );
			$options .= sprintf( '<option value="product_cat">%s</option>', __( 'Product Cat' ) );
			$options .= sprintf( '<option value="product_tag">%s</option>', __( 'Product Tag' ) );
			$options .= sprintf( '<option value="price" %s>%s</option>', selected( $wpp_pr_filter, 'price', false ), __( 'Price' ) );
			$options .= sprintf( '<option value="keywords">%s</option>', __( 'Keywords' ) );
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			$count = count( $attribute_taxonomies );
			if ( 0 !== $count ) {
				foreach ( $attribute_taxonomies as $one_tax ) {
					$options .= sprintf(
						'<option value="%s"%s>%s</option>', $one_tax->attribute_name, selected( $wpp_pr_filter, $one_tax->attribute_name, false ), $one_tax->attribute_label
					);
				}
			}
			
			?>
            <div class="nav-menu-widget-form-controls">
                <p>
                    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
                           value="<?php echo esc_attr( $title ); ?>"/>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'wpp_pr_filter' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
                    <select id="<?php echo $this->get_field_id( 'wpp_pr_filter' ); ?>" name="<?php echo $this->get_field_name( 'wpp_pr_filter' ); ?>">
						<?php echo $options; ?>
                    </select>
                </p>
                <p>
                    <input class="checkbox" type="checkbox"<?php checked( $instance[ 'dropdown' ] ); ?> id="<?php echo $this->get_field_id( 'dropdown' ); ?>"
                           name="<?php echo $this->get_field_name( 'dropdown' ); ?>"/> <label for="<?php echo $this->get_field_id( 'dropdown' ); ?>"><?php _e(
							'Check for Default Opened'
						); ?></label>
                </p>
            </div>
			<?php
		}
		
	}