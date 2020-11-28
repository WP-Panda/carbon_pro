<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpp_Posts_Settings {

	public static function init() {
		add_filter( 'wpp_fr_post_meta_boxes_args', [
			__CLASS__,
			'wpp_post_metaboxes'
		] );
		add_filter( 'wpp_fr_term_meta_boxes_args', [
			__CLASS__,
			'wpp_term_metaboxes'
		] );
	}

	public static function get_current_post_id() {
		$post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );

		return is_numeric( $post_id ) ? absint( $post_id ) : false;
	}


	public static function wpp_post_metaboxes( $meta_boxes ) {

		$id      = self::get_current_post_id();
		$product = wc_get_product( $id );
		if ( ! empty( $id ) && ! empty( $product ) ) :
			$type = $product->get_type();


			if ( 'bundle' === $type ) :

				$term_list = wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'ids' ) );

				$query_args = [
					'post_type' => 'product',
					'nopaging'  => true,
					'tax_query' => [
						[
							'taxonomy' => 'product_cat',
							'field'    => 'id',
							'terms'    => $term_list
						]
					]
				];

				$query = new WP_Query( $query_args );
				wp_reset_query();
				$options = [];
				if ( $query->found_posts > 1 ) {
					foreach ( $query->posts as $post_one ) {
						if ( $post_one->ID === $id ) {
							continue;
						}

						$options[ $post_one->ID ] = $post_one->post_title;

					}
				}
				$meta_boxes['packager'] = [
					'title'      => 'Набор деталей',
					'post_types' => [
						'product',
					],
					'context'    => 'normal',
					'priority'   => 'high',

					'fields' => [
						[
							'id'   => 'bundle_sale',
							'name' => 'Скидка на набор %',
							'type' => 'text',
						],
						[
							'id'          => 'bundle_package',
							'name'        => 'Детали из набора',
							'type'        => 'select_advanced',
							'multiple'    => true,
							'placeholder' => 'Выберите детали',
							'options'     => $options
						]
					]
				];

			endif;
		endif;

		/*$meta_boxes['anonses'] = [
			'title'      => 'Промо блок баннеров',
			'post_types' => [
				'page'
			],
			'include'    => [
				'template' => [ 'pages/main.php' ],
			],
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => [

				'it' => [
					'name'        => 'Блоки',
					'id'          => 'promo',
					'type'        => 'group',
					'clone'       => true,
					'sort_clone'  => true,
					'collapsible' => true,
					'save_state'  => true,
					'fields'      => [
						[
							'id'      => 'type',
							'name'    => 'Высота',
							'type'    => 'button_group',
							'options' => [
								0 => 'Низкий',
								1 => 'Высокий',
								2 => 'Высокий Высокий',
							],
							'inline'  => true,
						],
						[
							'id'        => 'width',
							'name'      => 'Ширина',
							'type'      => 'switch',
							'style'     => 'square',
							'off_label' => 'Полная ширина',
							'on_label'  => 'Два блока',
							'class'     => 'right_trigger'
						],

						[
							'name'             => 'Изображение',
							'id'               => 'img',
							'type'             => 'image_advanced',
							'force_delete'     => false,
							'max_file_uploads' => 2,
						],
					]
				]

			]
		];*/

		$meta_boxes['package'] = [
			'title'      => 'Характеристики Машины',
			'post_types' => [
				'sale_car',
			],
			'context'    => 'normal',
			'priority'   => 'high',

			'fields' => [
				[
					'id'   => '_subtitle',
					'name' => 'Подзаголовок',
					'type' => 'textarea',
				],
				[
					'id'   => '_sold',
					'name' => 'Продано',
					'type' => 'checkbox',
				],
				[
					'id'   => '_engine',
					'name' => 'Двигатель',
					'type' => 'text',
				],
				[
					'id'   => '_power',
					'name' => 'Мощьность',
					'type' => 'text',
				],
				[
					'id'   => '_mileage',
					'name' => 'Пробег',
					'type' => 'text',
				],
				[
					'id'   => '_speed_100',
					'name' => 'Разгон до 100',
					'type' => 'text',
				],
				[
					'id'   => '_speed_max',
					'name' => 'Максимальная скорость',
					'type' => 'text',
				],
				[
					'id'   => '_price',
					'name' => 'Цена',
					'type' => 'text',
				],
				[
					'id'   => '_price_nds',
					'name' => 'Цена без НДС',
					'type' => 'text',
				],
			]
		];


		$meta_boxes['package2'] = [
			'title'      => 'Характеристики Проекта',
			'post_types' => [
				'project'
			],
			'context'    => 'normal',
			'priority'   => 'high',

			'fields' => [
				[
					'id'   => '_subtitle',
					'name' => 'Подзаголовок',
					'type' => 'textarea',
				],
				[
					'id'   => '_engine',
					'name' => 'Двигатель',
					'type' => 'text',
				],
				[
					'id'   => '_power',
					'name' => 'Мощьность',
					'type' => 'text',
				],
				[
					'id'   => '_mileage',
					'name' => 'Пробег',
					'type' => 'text',
				],
				[
					'id'   => '_speed_100',
					'name' => 'Разгон до 100',
					'type' => 'text',
				],
				[
					'id'   => '_speed_max',
					'name' => 'Максимальная скорость',
					'type' => 'text',
				],
				[
					'id'   => '_price',
					'name' => 'Цена',
					'type' => 'text',
				],
				[
					'id'   => '_price_nds',
					'name' => 'Цена без НДС',
					'type' => 'text',
				],
			]
		];

		$meta_boxes['prd'] = [
			'title'      => 'Медиа заголовок страницы',
			'post_types' => [
				'product',
				'page',
                'sale_car'
			],
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => [
				[
					'id'        => 'hero_on',
					'name'      => 'Включить медиа заголовок',
					'type'      => 'switch',
					'style'     => 'square',
					'on_label'  => 'Выключить',
					'off_label' => 'Включить'
				],
				[
					'id'        => 'hero_format',
					'name'      => 'Формат медиа заголовка страницы',
					'type'      => 'switch',
					'style'     => 'square',
					'on_label'  => 'Слайдер',
					'off_label' => 'Статика',
					'hidden'    => [
						'hero_on',
						'!=',
						''
					]
				],
				[
					'id'        => 'hero_type',
					'name'      => 'Тип заголовка медиа заголовка страницы',
					'type'      => 'switch',
					'style'     => 'square',
					'on_label'  => 'Видео',
					'off_label' => 'Картинка',
					'hidden'    => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],

				[
					'name'             => 'Изображение',
					'id'               => 'hero_img',
					'type'             => 'image_advanced',
					'force_delete'     => false,
					'max_file_uploads' => 1,
					'hidden'           => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],
				[
					'name'             => 'Video',
					'id'               => 'hero_video',
					'type'             => 'video',
					'max_file_uploads' => 1,
					'force_delete'     => false,
					'max_status'       => false,
					'hidden'           => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],
				[
					'name'   => 'Заголовок',
					'id'     => 'hero_title',
					'type'   => 'text',
					'hidden' => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],
				[
					'name'   => 'Повзаголовок',
					'id'     => 'hero_sub_title',
					'type'   => 'text',
					'hidden' => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],
				[
					'name'   => 'Надпись на кнопке',
					'id'     => 'hero_btn_title',
					'type'   => 'text',
					'hidden' => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],
				[
					'name'   => 'Ссылка с кнопки',
					'id'     => 'hero_btn_link',
					'type'   => 'text',
					'hidden' => [
						[
							'hero_format',
							'!=',
							''
						]
					]
				],
				'hh' => [
					'name'        => 'Слайдер',
					'hidden'      => [
						[
							'hero_format',
							'=',
							''
						]
					],
					'id'          => 'hero',
					'type'        => 'group',
					'clone'       => true,
					'sort_clone'  => true,
					'collapsible' => true,
					'save_state'  => true,
					'fields'      => [
						[
							'id'        => 'type',
							'name'      => 'Тип слайда',
							'type'      => 'switch',
							'style'     => 'square',
							'on_label'  => 'Видео',
							'off_label' => 'Картинка',
						],
						[
							'name'             => 'Изображение',
							'id'               => 'img',
							'type'             => 'image_advanced',
							'force_delete'     => false,
							'max_file_uploads' => 1,
						],
						[
							'name'             => 'Video',
							'id'               => 'video',
							'type'             => 'video',
							'max_file_uploads' => 1,
							'force_delete'     => false,
							'max_status'       => false,
						]

					]
				]
			]
		];

		$meta_boxes['anonses']['fields']['it']['fields'][] = [
			'type'  => 'heading',
			'name'  => 'Левый блок',
			'desc'  => 'Эти настройки только для левого блока',
			'class' => 'show_right'
		];

		$langs = wpp_set_locate_args();
		if ( ! empty( $langs ) && is_array( $langs ) ) {

			foreach ( $langs as $lang => $val ) {
				$meta_boxes['prd']['fields']['hh']['fields'][] = [
					'name' => 'Заголовок ' . $val,
					'id'   => 'title' . $lang,
					'type' => 'text',
				];

				/*	$meta_boxes['prd']['fields']['hh']['fields'][] = [
						'name' => 'Повзаголовок ' . $val,
						'id'   => 'sub_title' . $lang,
						'type' => 'text',
					];*/

				$meta_boxes['prd']['fields']['hh']['fields'][] = [
					'name' => 'Надпись на кнопке ' . $val,
					'id'   => 'btn_title' . $lang,
					'type' => 'text',
				];


				$meta_boxes['prd']['fields']['hh']['fields'][] = [
					'name' => 'Ссылка с кнопки ' . $val,
					'id'   => 'btn_link' . $lang,
					'type' => 'text'
				];


				# ромо блок
				/*$meta_boxes['anonses']['fields']['it']['fields'][] = [
					'name' => 'Заголовок ' . $val,
					'id'   => 'title' . $lang,
					'type' => 'text'
				];

				$meta_boxes['anonses']['fields']['it']['fields'][] = [
					'name' => 'Надпись на кнопке ' . $val,
					'id'   => 'btn_title' . $lang,
					'type' => 'text'
				];

				$meta_boxes['anonses']['fields']['it']['fields'][] = [
					'name' => 'Ссылка с кнопки ' . $val,
					'id'   => 'btn_link' . $lang,
					'type' => 'text'
				];*/

			}

			/*
			$meta_boxes['anonses']['fields']['it']['fields'][] = [
				'type'  => 'heading',
				'name'  => 'Правый блок',
				'desc'  => 'Эти настройки только для правого блока',
				'class' => 'show_right'
			];

			foreach ( $langs as $lang => $val ) {

				$meta_boxes['anonses']['fields']['it']['fields'][] = [
					'name'  => 'Заголовок ' . $val,
					'id'    => 'title_2' . $lang,
					'type'  => 'text',
					'class' => 'show_right'
				];

				$meta_boxes['anonses']['fields']['it']['fields'][] = [
					'name'  => 'Надпись на кнопке ' . $val,
					'id'    => 'btn_title_2' . $lang,
					'type'  => 'text',
					'class' => 'show_right'
				];

				$meta_boxes['anonses']['fields']['it']['fields'][] = [
					'name'  => 'Ссылка с кнопки ' . $val,
					'id'    => 'btn_link_2' . $lang,
					'type'  => 'text',
					'class' => 'show_right'
				];
			}
			*/
		}


		return $meta_boxes;

	}

	public static function wpp_term_metaboxes( $meta_boxes ) {

		$meta_boxes[] = [
			'title'      => 'Дополнительно',
			'taxonomies' => 'product_cat',
			'fields'     => [
				[
					'name'        => 'Тип кузова',
					'id'          => 'body_type',
					'type'        => 'select',
					'placeholder' => 'Установите тип кузова',
					'options'     => wpp_woo_car_body_tipes(),
				],
				[
					'id'      => 'type',
					'name'    => 'Тип Элемента',
					'type'    => 'select',
					'options' => [
						'slider' => 'Слайдер',
						'wall'   => 'Стена'
					],
				],
				[
					'id'               => 'image_cat_gallery',
					'name'             => 'Галерея - Изображения',
					'type'             => 'image_advanced',
					'force_delete'     => false,
					'max_file_uploads' => 100,
					'max_status'       => 'true',
					'image_size'       => 'thumbnail',
				],
				[
					'id'          => 'standard',
					'type'        => 'group',
					'name'        => 'Галерея - Видео',
					'clone'       => true,
					'sort_clone'  => true,
					'collapsible' => true,
					'group_title' => array( 'field' => 'caption' ),
					'fields'      => [

						[
							'name'   => 'Ссылка на видео с Youtube',
							'id'     => 'video',
							'type'   => 'text',
						]

					]
				],
				[
					'id'               => 'logo',
					'name'             => 'Logo',
					'type'             => 'image_advanced',
					'force_delete'     => false,
					'max_file_uploads' => 1,
					'max_status'       => 'false',
					'image_size'       => 'thumbnail',
				]

			]
		];

		return $meta_boxes;

	}


}

Wpp_Posts_Settings::init();


#add_action( 'init', 'setup_menu_custom_fields' );
function setup_menu_custom_fields() {

	$fields = [
		'_mycustom_field_1' => [
			'label'             => __( 'Custom field 1', 'domain' ),
			'element'           => 'input',
			'sanitize_callback' => 'sanitize_text_field',
			'attrs'             => [
				'type' => 'text',
			],
		],
		'_mycustom_field_2' => [
			'label'             => __( 'Custom field 2', 'domain' ),
			'element'           => 'select',
			'sanitize_callback' => 'sanitize_text_field',
			'options'           => [
				'option-1' => __( 'Option 1', 'domain' ),
				'option-2' => __( 'Option 2', 'domain' ),
			],
		],
	];

	// Menu Management custom fields.
	new WPP_Menu( $fields );
}

function get_3_first_attach( $post_ID ) {


	$items = get_posts( [
		'post_type'      => 'attachment',
		'posts_per_page' => 3,
		'post_parent'    => $post_ID
	] );

	return ! empty( $items ) ? $items : false;
}


function wpp_br_admin_scripts() { ?>
    <script>
        jQuery(function ($) {

            function WppShowPromo($el) {

                var $target = $el.find('input'),
                    $val = $target.is(":checked"),
                    $trigged = $el.parents('.rwmb-clone').find('.show_right');

                if ($val === true) {
                    $trigged.show()
                } else {
                    $trigged.hide()
                }
            }

            $('.right_trigger').each(function (e) {
                WppShowPromo($(this))
            });

            $(document).on('change', '.right_trigger', function (e) {
                WppShowPromo($(this))
            });

        });
    </script>
<?php }

add_action( 'admin_footer', 'wpp_br_admin_scripts' );

function ror() {
	add_meta_box( 'sale_car-product-images', __( 'Product gallery', 'woocommerce' ), 'Wpp_Fr_Post_Gallery::output', 'sale_car', 'side', 'low' );
	add_meta_box( 'sale_car-product-images', __( 'Product gallery', 'woocommerce' ), 'Wpp_Fr_Post_Gallery::output', 'project', 'side', 'low' );
}

add_action('add_meta_boxes','ror');