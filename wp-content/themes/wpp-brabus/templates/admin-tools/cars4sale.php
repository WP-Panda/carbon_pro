<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( wpp_fr_user_is_admin() && is_archive() && post_type_exists( 'sale_car' ) ):
	?>
    <div class="wpp-admin-car4sale-block">
        <div class="wpp-admin-car4sale wpp-tooltips" data-title='Добавить элемент к "Машине на продажу"'>
                    <span class="wpp-car-sale-img-wrap">
                        <img class="sale-car-image"
                             src="<?php echo get_template_directory_uri() . '/assets/img/icons/car-sales.svg'; ?>"
                             alt="">
                    </span>
        </div>
        <div class="wpp-car4sale-modal">
            <h5>Выбор машин в комплект которых входит продукт</h5>
            <hr>
            <div class="wpp-car4sale-close wpp-tooltips" data-title="Закрыть">
                <img class="sale-car-image"
                     src="<?php echo get_template_directory_uri() . '/assets/img/icons/cross.svg'; ?>" alt="">
            </div>
			<?php if ( empty( $args['cars_array'] ) ) :

				wpp_get_template_part( 'templates/part/info-block', [
					'type' => 'alert',
					'text' => 'Машины на продажу отсутствуют'
				] );

			else:
				?>
                <form class="sale-car-selector" data-id="<?php echo $args['id']; ?>">
					<?php $cars_package = get_post_meta( $args['id'], '_in_sale_cars', true );
					if ( empty( $cars_package ) ) {
						$cars_package = [];
					}
					?>
                    <select class="wpp-select-2" name="car4sale[]" multiple="multiple">
						<?php
						foreach ( $args ['cars_array'] as $id => $title ):
							printf( '<option value="%s"%s>%s - %s</option>', $id, in_array( $id, $cars_package ) ? ' selected="selected"' : '', $title, $id );
						endforeach;
						?>
                    </select>
                    <button type="submit" class="form--button-dark form--button--cta">Сохранить</button>
                </form>
			<?php endif; ?>
        </div>
    </div>
<?php endif;