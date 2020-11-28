<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

function e_wpp_br_lng( $key ) {
	echo wpp_br_lng( $key );
}

function wpp_br_lng( $key ) {

	$locate = get_locale();


	$array = [
		'email' => [
			'ru_RU' => 'Почта',
			'en_US' => 'Email'
		],
		'share' => [
			'ru_RU' => 'Поделиться',
			'en_US' => 'Share'
		],
		'copy_to_clipboard' => [
			'ru_RU' => 'Скопировать в буфер обмена',
			'en_US' => 'Copy to clipboard'
		],
		'copy_link'   => [
			'ru_RU' => 'Копировать ссылку',
			'en_US' => 'Copy Link'
		],
		'saved_cart_link'   => [
			'ru_RU' => 'Ссылка на эту корзину',
			'en_US' => 'Link to this cart'
		],
		'add_all_project'   => [
			'ru_RU' => 'СЛОЖИТЬ ВСЕ ТОВАРЫ В КОРЗИНУ',
			'en_US' => ' Move all products to cart'
		],
		'bundle_sale'       => [
			'ru_RU' => 'Пакетом дешевле на %s',
			'en_US' => 'Package is %s cheaper'
		],
		'no_search_found'   => [
			'ru_RU' => 'Ничего не найдено',
			'en_US' => 'No results found'
		],
		'package'           => [
			'ru_RU' => 'КОМПЛЕКТ',
			'en_US' => 'PACKAGE'
		],
		'package_inc'       => [
			'ru_RU' => 'Комплект включает в себя следующие части:',
			'en_US' => 'PACKAGE includes following parts:'
		],
		'package_in'        => [
			'ru_RU' => 'Из пакета: ',
			'en_US' => 'Package: '
		],
		'package_as'        => [
			'ru_RU' => 'Установка комплекта: ',
			'en_US' => 'Installation Package: '
		],
		'package_pa'        => [
			'ru_RU' => 'Покраска пакета: ',
			'en_US' => 'Paint Package: '
		],
		'package_total'     => [
			'en_US' => 'Package Total:',
			'ru_RU' => 'Итого за Комплект:'
		],
		'assem'             => [
			'ru_RU' => 'Установка',
			'en_US' => 'Installation'
		],
		'paint'             => [
			'ru_RU' => 'Покраска',
			'en_US' => 'Paint'
		],
		'varnish_select'    => [
			'ru_RU' => 'Выберите лак',
			'en_US' => 'Choose clear coat'
		],
		'wave_select'       => [
			'ru_RU' => 'Выберите плетение карбона',
			'en_US' => 'Please Choose Carbon Pattern'
		],
		'select_maker'      => [
			'ru_RU' => 'Производитель',
			'en_US' => 'Select make'
		],
		'tuning'            => [
			'ru_RU' => 'Тюнинг',
			'en_US' => 'Tuning'
		],
		'select_model'      => [
			'ru_RU' => 'Модель',
			'en_US' => 'Select model'
		],
		'all_models'        => [
			'ru_RU' => 'Все модели',
			'en_US' => 'All models'
		],
		'cart_link'         => [
			'en_US' => 'Cart Link',
			'ru_RU' => 'Ссылка на Корзину'
		],
		'save_pdf'          => [
			'en_US' => 'Save as PDF',
			'ru_RU' => 'Сохранить в PDF'
		],
		'remove_addit'      => [
			'en_US' => 'Remove Installation & Paint',
			'ru_RU' => 'Удалить Установку'
		],
		'clear_cart'        => [
			'en_US' => 'Clear Cart',
			'ru_RU' => 'Очистить Корзину'
		],
		'subscribe'         => [
			'en_US' => 'Subscribe',
			'ru_RU' => 'Подписаться'
		],
		'search'            => [
			'en_US' => 'Search text...',
			'ru_RU' => 'Найти...'
		],
		'valid_email'       => [
			'en_US' => 'Email is not Valid',
			'ru_RU' => 'Электронная почта не действительна',
		],
		'learn_more'        => [
			'en_US' => 'Learn More',
			'ru_RU' => 'Смотреть Еще',
		],
		'learn_hide'        => [
			'en_US' => 'Hide',
			'ru_RU' => 'Свернуть',
		],
		'new_products'      => [
			'en_US' => 'New Products',
			'ru_RU' => 'Новинки'
		],
		'all_new'           => [
			'en_US' => 'Show All New Products',
			'ru_RU' => 'Смотреть Все Новинки'
		],
		'clear_filter'      => [
			'en_US' => 'Clear Filter',
			'ru_RU' => 'Очистить Фильтр'
		],
		'standard_features' => [
			'en_US' => 'Standard Features',
			'ru_RU' => 'Стандартные характеристики'
		],
		'tuning_features'   => [
			'en_US' => 'Tuning Features',
			'ru_RU' => 'Дополнительное оборудование'
		],
		'_engine'           => [
			'ru_RU' => 'Двигатель',
			'en_US' => 'Engine',
		],
		'_power'            => [
			'ru_RU' => 'Мощьность',
			'en_US' => 'Power',
		],
		'_mileage'          => [
			'ru_RU' => 'Пробег',
			'en_US' => 'Mileage',
		],
		'_speed_100'        => [
			'ru_RU' => 'Разгон до 100',
			'en_US' => '0-100 km/h',
		],
		'_speed_max'        => [
			'ru_RU' => 'Максимальная скорость',
			'en_US' => 'V max',
		],
		'_price'            => [
			'ru_RU' => 'Цена',
			'en_US' => 'Gross (inc. VAT)',
		],
		'_price_nds'        => [
			'ru_RU' => 'Цена без НДС',
			'en_US' => 'Export (excl. VAT)',
		],
		'technical_data'    => [
			'ru_RU' => 'Техническая Информация',
			'en_US' => 'Technical Data',
		],
		'gross'             => [
			'ru_RU' => 'Стоимость',
			'en_US' => 'Price',
		],
		'share'             => [
			'ru_RU' => 'Поделиться',
			'en_US' => 'Share',
		],
		'request'           => [
			'ru_RU' => 'Запросить',
			'en_US' => 'Request now',
		],
		'neus'              => [
			'ru_RU' => 'НОВАЯ/БУ',
			'en_US' => 'NEW/USED',
		],
		'in_vat'            => [
			'ru_RU' => 'c НДС',
			'en_US' => 'VAT included',
		],
		'details'           => [
			'ru_RU' => 'Подробнее',
			'en_US' => 'Details',
		],
		'sold'              => [
			'ru_RU' => 'Продано',
			'en_US' => 'Sold',
		],
		'car4sale'          => [
			'ru_RU' => 'Машины на Продажу',
			'en_US' => 'Cars4Sale',
		],
		'car4sale_all'      => [
			'ru_RU' => 'Все Машины на Продажу',
			'en_US' => 'Show All Cars4Sale',
		],
		'projects'          => [
			'ru_RU' => 'Последние проекты',
			'en_US' => 'Latest projects',
		],
		'project_for'       => [
			'ru_RU' => 'Наши проекты для %s %s',
			'en_US' => 'Our projects for %s %s',
		]
	];


	return $array[ $key ][ $locate ];

}