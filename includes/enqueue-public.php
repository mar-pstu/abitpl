<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Подключение скриптов
 */
function scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'public', get_theme_file_uri( 'scripts/public.min.js' ), [ 'jquery' ], filemtime( get_theme_file_path( 'scripts/public.min.js' ) ), true );
	wp_enqueue_script( 'superembed', get_theme_file_uri( 'scripts/superembed.min.js' ), [ 'jquery' ], '3.1', true );
	wp_register_script( 'slick', get_theme_file_uri( 'scripts/slick.min.js' ), [ 'jquery' ], '1.8.0', true );
	wp_enqueue_script( 'fancybox', get_theme_file_uri( 'scripts/fancybox.min.js' ), [ 'jquery' ], '3.3.5', true );
	$init_fancybox_script_path = get_theme_file_path( 'scripts/init-gallery.min.js' );
	if ( file_exists( $init_fancybox_script_path ) ) {
		wp_add_inline_script( 'fancybox', file_get_contents( $init_fancybox_script_path ), 'after' );
	}
}

add_action( 'wp_enqueue_scripts', 'abitpl\scripts' );


/**
 * Добавляет предварительную загрузку шрифтов
 * */
function add_preload_resource() {
	foreach ( apply_filters( 'preload_resource_part', [
		// path => font
	] ) as $file_path => $type ) {
		$file_uri = get_theme_file_uri( $file_path );
		if ( $file_uri ) {
			echo '<link rel="preload" href="' . $file_uri . '" as="' . $type . '" crossorigin="anonymous">';
		}
	}
}

add_action( 'wp_head', 'abitpl\add_preload_resource' );


/**
 * Подключение стилей
 */
function styles() {
	wp_enqueue_style( 'public', get_theme_file_uri( 'styles/public.css' ), [], filemtime( get_theme_file_path( 'styles/public.css' ) ), 'all' );
	wp_enqueue_style( 'fancybox', get_theme_file_uri( 'styles/fancybox.min.css' ), [], '3.3.5', 'all' );
	wp_register_style( 'slick', get_theme_file_uri( 'styles/slick.min.css' ), [], '1.8.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'abitpl\styles', 10, 0 );


/**
 * Подключение стилей инлайн для более быстрой отрисовки страницы
 * */
function ctitical_styles() {
	$critical_file_path = get_theme_file_path( "styles/critical.min.css" );
	if ( file_exists( $critical_file_path ) ) {
		echo '<style type="text/css">' . file_get_contents( $critical_file_path ) . '</style>';
	}
}

add_action( 'wp_head', 'abitpl\ctitical_styles', 10, 0 );


/**
 * Удаляет аттрибуты для style и script, которые были добавленны в обход wp
 * */
function remove_type_attr_start() {
	ob_start();
}

function remove_type_attr_end() {
    echo preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', ob_get_clean() );
}

add_action( 'get_header', 'abitpl\remove_type_attr_start', 5, 0 );
add_action( 'wp_footer', 'abitpl\remove_type_attr_end', 99, 0 );