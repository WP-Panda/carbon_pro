<?php
	/*
		Template Name: Projects
		Template Post Type: page
		*/
	defined( 'ABSPATH' ) || exit;
	get_header();

?>


<?php wpp_get_template_part( 'templates/globals/page-title', [] ); ?>

    <div class="content">

        <div id="object-data" class="neos-contentcollection wpp-mix-filter-wrap"
             data-id="<?php echo get_queried_object_id(); ?>">
			<?php
				$filters_data = wpp_br_sort_filter_args();
				wpp_get_template_part( 'templates/filters/progect-filter-new' );
			?>


            <section class="wpp-clear"></section>


			<?php $car_args = [
				'post_type' => 'project',
				'nopaging'  => true,
				'order'     => 'ASC',
			];
				$car_query  = new WP_Query( $car_args );

				$order_class = wpp_fr_user_is_admin() ? ' wpp-posts-ordering' : null;

				usort( $car_query->posts, function( $post_a, $post_b ) {

					$ordered = get_post_meta( get_queried_object_id(), 'posts_order', true );

					if ( ! empty( $ordered ) ) {

						$ordered = array_flip( $ordered );

						$a = $ordered[ $post_a->ID ];
						$b = $ordered[ $post_b->ID ];

						if ( $a == $b ) {
							return 0;
						}

						return ( $a < $b ) ? - 1 : 1;

					} else {
						return 0;
					}

				} );


				if ( $car_query->have_posts() ) : ?>

                    <section class="row wpp-fancy-box-gallery wpp-mix-item-wrap<?php echo $order_class; ?>">
						<?php while ( $car_query->have_posts() ) : $car_query->the_post();
							$id = get_the_ID();
							ob_start();

							$post_makers = wp_get_post_terms( $id, 'attach_makers', [ 'fields' => 'all' ] );
							$post_terms  = wp_get_post_terms( $id, 'product_cat', [ 'fields' => 'ids' ] );


							if ( ! empty( $post_makers[ 0 ]->term_id ) ) {
								$maker = $post_makers[ 0 ]->term_id;
							}


							$parents      = get_ancestors( $post_terms[ 0 ], 'product_cat', 'taxonomy' );
							$models       = array_unique( ( $parents + $post_terms ) );
							$models_class = 't_' . implode( ' t_', $models );
							?>
                            <div class="grid-teaser-details structured-content">
                                <h4 class="grid-headline-icon"><?php echo get_the_title( $post->ID ); ?></h4>
                                <p><?php echo get_post_meta( $post->ID, '_subtitle', true ); ?></p>
                            </div>
							<?php
							$htmls = ob_get_clean();

							$url    = get_the_post_thumbnail_url( '', 'full' );
							$params = [
								'wrap'  => '<div class="mix ' . $models_class . ' t_' . $maker . ' col-6 col-xs-6 col-sm-4 col-md-3 wpp-static-grid-item overlayed " aria-selected="true" data-prd_id="' . $id . '" data-car_brand="" data-car_model="" data-attach_brand="' . $maker . '"><a href="' . get_the_permalink( $id ) . '">%s' . $htmls . '</a></div>',
								'class' => 'wpp-fancy-gallery-thumb',
							];
							e_wpp_fr_image_html( $url, wpp_br_thumb_array( $params ) );

						endwhile; ?>
                        <div class="cd-fail-message"
                             style="display: none;"><?php e_wpp_br_lng( 'no_search_found' ); ?></div>
                    </section>
				<?php endif; ?>

        </div>
    </div>

<?php wp_reset_query();
	/**
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
?>
    <script src="https://codyhouse.co/demo/content-filter/js/jquery.mixitup.min.js"></script>
    <script>
        jQuery(function ($) {

            function wppgetstr() {
                var $args = [];
                var $string = '';
                $('#wpp-project-filter').find('select').each(function (e) {
                    let $val = $(this).val().toString();
                    if ($val !== '') {
                        $args[$(this).attr('name')] = $val;
                        $string += '[data-' + $(this).attr('name') + '="' + $val + '"]';
                    }
                });

                return $string;
            }

            function wppget_txt() {

                var $str = $('.wpp_pr_searh').val(),
                    $string = '';

                if ('' !== $str) {
                    $string = ':contains("' + $str + '")'
                }

                return $string

            }


            function onlyUnique(value, index, self) {
                return self.indexOf(value) === index;
            }


            $(document).ready(function () {
                /************************************
                 MitItUp filter settings
                 More details:
                 https://mixitup.kunkalabs.com/
                 or:
                 http://codepen.io/patrickkunka/
                 *************************************/

                buttonFilter.init();
                $('.wpp-mix-item-wrap').mixItUp({
                    controls: {
                        enable: true
                    },
                    callbacks: {
                        onMixStart: function () {
                            $('.cd-fail-message').fadeOut(200);


                        },
                        onMixEnd: function () {
                            var opt_array = [];
                            $('.wpp-static-grid-item:visible').each(function (i) {
                                let ele = $(this);

                                opt_array = $.merge(opt_array, ele.attr('class').split(' '));


                            });
                            var unique = opt_array.filter(onlyUnique); // returns ['a', 1, 2, '1']

                            $('option').each(function (i) {

                                let val = $(this).val();


                                if (!unique.includes(val.substr(1))) {
                                    //$(this).attr('disabled', 'disabled');
                                } else {
                                    //$(this).removeAttr('disabled');
                                }
                            })

                        },
                        onMixFail: function () {
                            $('.cd-fail-message').fadeIn(200);
                        }
                    }
                });

                //search filtering
                //credits http://codepen.io/edprats/pen/pzAdg
                var inputText;
                var $matching = $();

                var delay = (function () {
                    var timer = 0;
                    return function (callback, ms) {
                        clearTimeout(timer);
                        timer = setTimeout(callback, ms);
                    };
                })();

                $(".wpp_pr_searh").keyup(function () {
                    // Delay function invoked to make sure user stopped typing
                    delay(function () {
                        inputText = $(".wpp_pr_searh").val().toLowerCase();
                        // Check to see if input field is empty
                        if ((inputText.length) > 0) {
                            $('.mix').each(function () {
                                var $this = $(this);

                                console.log($this.text());
                                console.log($this.html());
                                // add item to be filtered out if input text matches items inside the title
                                if ($this.html().toLowerCase().match(inputText)) {
                                    $matching = $matching.add(this);
                                } else {
                                    // removes any previously matched item
                                    $matching = $matching.not(this);
                                }
                            });
                            $('.wpp-mix-item-wrap').mixItUp('filter', $matching);
                        } else {
                            // resets the filter to show all item if input is empty
                            $('.wpp-mix-item-wrap').mixItUp('filter', 'all');
                        }
                    }, 200);
                });
            })
            /*****************************************************
             MixItUp - Define a single object literal
             to contain all filter custom functionality
             *****************************************************/
            var buttonFilter = {
                // Declare any variables we will need as properties of the object
                $filters: null,
                groups: [],
                outputArray: [],
                outputString: '',

                // The "init" method will run on document ready and cache any jQuery objects we will need.
                init: function () {
                    var self = this; // As a best practice, in each method we will asign "this" to the variable "self" so that it remains scope-agnostic. We will use it to refer to the parent "buttonFilter" object so that we can share methods and properties between all parts of the object.

                    self.$filters = $('.wpp-mix-filter-wrap');
                    self.$container = $('.wpp-mix-item-wrap');

                    self.$filters.find('.wpp-filers-wrap').each(function (i) {
                        var $this = $(this);

                        self.groups.push({
                            $inputs: $this.find('.wpp-mix-filter'),
                            active: '',
                            tracker: false
                        });
                    });



                    console.log(self.groups);
                    self.bindHandlers();
                },

                // The "bindHandlers" method will listen for whenever a button is clicked.
                bindHandlers: function () {
                    var self = this;

                /*    $(document).on('click','.filter-drop',function () {
                        console.log('CC')
                        self.parseFilters();
                    })*/

                    self.$filters.on('change', function () {
                        console.log('kC')
                        self.parseFilters();
                    });
                },

                parseFilters: function () {
                    var self = this;

                    // loop through each filter group and grap the active filter from each one.
                    for (var i = 0, group; group = self.groups[i]; i++) {
                        group.active = [];
                        group.$inputs.each(function () {
                            var $this = $(this);
                            if ($this.is('input[type="radio"]') || $this.is('input[type="checkbox"]')) {
                                if ($this.is(':checked')) {
                                    group.active.push($this.attr('data-filter'));
                                }
                            } else if ($this.is('select')) {
                                group.active.push($this.val());
                            } else if ($this.find('.selected').length > 0) {
                                group.active.push($this.attr('data-filter'));
                            } else if ($this.is('input[type="hidden"]')) {
                                if ($this.val() !== '') {
                                   var $string_val = $this.val().split(',');
                                    $.each($string_val,function (i,v) {
                                        group.active.push(v);
                                    });
                                }
                            }
                        });
                        console.log(group.active)
                    }
                    self.concatenate();
                },

                concatenate: function () {
                    var self = this;

                    self.outputString = ''; // Reset output string

                    for (var i = 0, group; group = self.groups[i]; i++) {
                        self.outputString += group.active;
                    }
                    console.log(self.outputString);
                    // If the output string is empty, show all rather than none:
                    !self.outputString.length && (self.outputString = 'all');

                    // Send the output string to MixItUp via the 'filter' method:
                    if (self.$container.mixItUp('isLoaded')) {
                        self.$container.mixItUp('filter', self.outputString);
                    }
                }
            };



            /**
             * ыпадающий список
             */
            $(document).on('click', '.filter-select button', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation()
                let $_this = $(this),
                    $_parent = $_this.parents('.wpp-filers-wrap'),
                    $_drop = $_parent.find('.filter-drop'),
                    $_input = $_this.next('input.filter-display');

                $_parent.addClass('show-drop');
                $_drop.slideDown();
                $_input.attr('type', 'text').focus();

            })


            /**
             * Закрываем список
             */
            $(document).click(function (event) {


                if (!$(event.target).is(".wpp-filers-wrap, .filter-drop, .filter-drop *")) {
                    $('.wpp-filers-wrap').removeClass('show-drop');
                    //if($('.wpp-filers-wrap').find('input.filter-display').val() == '') {
                        $('.wpp-filers-wrap').find('input.filter-display').attr('type', 'hidden');
                        $('.wpp-filers-wrap').find('button').attr('show');
                    //}
                    $('.filter-drop').slideUp();
                }
            });


           /* $(document).on('focusout', '.filter-select input', function (e) {
				e.preventDefault();
				e.stopImmediatePropagation()
				let $_this = $(this),
					$_parent = $_this.parents('.wpp-filers-wrap'),
					$_drop = $_parent.find('.filter-drop');


				$_this.attr('type', 'hidden');

			})*/

            $(document).on('click', '.filter-drop li', function (e) {

                e.preventDefault();
                e.stopImmediatePropagation()

                let $_this = $(this),
                    $_display_input = $_this.parents('.wpp-filers-wrap').find('input.filter-display'),
                    $_model_input = $_this.parents('.wpp-filers-wrap').find('input.filter-values'),
                    $_value_input = $_display_input.val(),
                    $_value_model = $_model_input.val(),
                    $_model= $_this.data('val'),
                    $_text = $_this.text();

                if (!$_this.hasClass('check-filter')) {
                    $_this.addClass('check-filter');

                    var $new_val = '' == $_value_input ? $_text : $_value_input + ',' + $_text;
                    var $new_models = '' == $_value_model ? $_model: $_value_model + ',' + $_model;

                } else {
                    $_this.removeClass('check-filter')
                    var $vals_array = $_value_input.split(','),
                        $diff_array = $vals_array.splice($.inArray($_text, $vals_array), 1),
                        $new_val = $vals_array.join(','),
                        $models_array = $_value_model.split(','),
                        $diff_model_array = $models_array .splice($.inArray($_model, $models_array ), 1),
                        $new_models = $models_array.join(',');

                }

               var  $text = $new_val !== '' ? $new_val : 'Все модели';
                $_this.parents('.wpp-filers-wrap').find('button').text($text);
                $_this.parents('.wpp-filers-wrap').find('input.filter-display').focus().val($new_val);
                $_this.parents('.wpp-filers-wrap').find('input.filter-values').focus().val($new_models).trigger("change");


            })

            $('.filter-drop').hover(
                function () {
                    $(this).parents('.wpp-filers-wrap').find('input.filter-display').focus();
                },
                function () {}

            );


            $(document).on('click', '.filter-drop', function (e) {
                $(this).parents('.wpp-filers-wrap').find('input.filter-display').focus();
            })

            $(document).on('click', '.trigger', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation()
                let $_this = $(this),
                    $_list = $_this.parent().next('ul');

                if (!$_this.hasClass('opened')) {
                    $_list.show();
                    $_this.addClass('opened');
                } else {
                    $_list.hide();
                    $_this.removeClass('opened');
                }

            })




        })
    </script>
<?php
	/*   $(document).on('change', '#wpp-project-filter select', function (e) {

					  $('.wpp-static-grid-item').hide();
					  $('.wpp-static-grid-item' + wppgetstr() + wppget_txt()).fadeIn();

				  });

				  $('.wpp_pr_searh').keyup(function () {

					  $('.wpp-static-grid-item').hide();
					  $('.wpp-static-grid-item' + wppgetstr() + wppget_txt()).fadeIn();

				  });*/
	get_footer();