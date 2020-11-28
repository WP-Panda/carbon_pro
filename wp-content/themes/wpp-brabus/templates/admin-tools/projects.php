<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( wpp_fr_user_is_admin() && is_archive() && post_type_exists( 'project' ) ):
	?>
    <div class="wpp-admin-project-block">
        <div class="wpp-admin-project wpp-tooltips" data-title='Добавить элемент к "Проектам"'>
                    <span class="wpp-car-sale-img-wrap">
                        <img class="sale-car-image"
                             src="<?php echo get_template_directory_uri() . '/assets/img/icons/tuning.svg'; ?>"
                             alt="">
                    </span>
        </div>
        <div class="wpp-project-modal">
            <h5>Выбор Проектов в комплект которых входит продукт</h5>
            <hr>
            <div class="wpp-project-close wpp-tooltips" data-title="Закрыть">
                <img class="sale-car-image"
                     src="<?php echo get_template_directory_uri() . '/assets/img/icons/cross.svg'; ?>" alt="">
            </div>
			<?php if ( empty( $args['project_array'] ) ) :

				wpp_get_template_part( 'templates/part/info-block', [
					'type' => 'alert',
					'text' => 'Проекты отсутствуют'
				] );

			else:
				?>
                <form class="project-car-selector" data-id="<?php echo $args['id']; ?>">
					<?php $cars_package = get_post_meta( $args['id'], '_in_project_cars', true );
					if ( empty( $cars_package ) ) {
						$cars_package = [];
					}
					?>
                    <select class="wpp-select-2" name="car4sale[]" multiple="multiple">
						<?php
						foreach ( $args ['project_array'] as $id => $title ):
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