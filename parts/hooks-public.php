<?php


namespace abitpl;


use WP_Term;
use WP_Post;
use WP_Error;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Устанавливает префикс для архивов
 * */
function get_custom_archive_title_prefix( $prefix ){
	return get_theme_mod( 'archivetitleprefix' );
}

add_filter( 'get_the_archive_title_prefix', 'abitpl\get_custom_archive_title_prefix' );



/**
 * Поле для доп. скриптов и счетчиков
 * */
function print_scripts_head() {
	echo force_balance_tags( get_theme_mod( 'additionalscriptswphead' ) );
}

add_action( 'wp_head', 'abitpl\print_scripts_head', 99, 0 );


/**
 * Поле для доп. скриптов и счетчиков
 * */
function print_scripts_footer() {
	echo force_balance_tags( get_theme_mod( 'additionalscriptswpfooter' ) );
}

add_action( 'wp_footer', 'abitpl\print_scripts_footer', 99, 0 );


/**
 * Подключаем блок модалок
 * */
function render_part_modals() {
	get_template_part( 'parts/modals' );
}

add_action( 'wp_footer', 'abitpl\render_part_modals', 99, 0 );