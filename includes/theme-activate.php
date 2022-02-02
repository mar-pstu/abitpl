<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Добавляет опции темы по умолчанию при её активации
 **/
function setup_default_mods( $old_name ) {
	$theme_slug = get_option( 'stylesheet' );
	$mods = get_theme_mods();
	if ( ! is_array( $mods ) ) {
		$mods = [];
	}
	update_option( 'theme_mods_' . $theme_slug, array_merge( [

		// подвал сайта
		'footercopytext'               => __( '&copy; Sellzee, %s', ABITPL_TEXTDOMAIN ),

		// модалки
		'modals'                       => [],

		// поля для доп. скриптов
		'additionalscriptswphead'      => '',
		'additionalscriptswpfooter'    => '',

		// соцсети
		'socialsfacebookhrefattr'      => '',
		'socialsinstagramhrefattr'     => '',
		'socialstwitterhrefattr'       => '',
		'socialsyoutubehrefattr'       => '',

		// сайдбар
		'sideabarlogousedby'           => false,
		'sideabarsocialsusedby'        => false,

		'sectionpartnersusedby'        => false,
		'sectionpartnerstitle'         => __( 'Партнёры', ABITPL_TEXTDOMAIN ),
		'sectionpartners'              => [],

		// архив
		'archivetitleprefix'           => '',

		// 404
		'error404title'                => __( 'Страница не найдена', ABITPL_TEXTDOMAIN ),
		'error404description'          => __( 'Проверьте правильность написания адреса или перейдите на главную страницу', ABITPL_TEXTDOMAIN ),

	], $mods ) );
}

add_action( 'after_switch_theme', 'abitpl\setup_default_mods' );