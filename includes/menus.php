<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Регистрация меню
 */
function register_theme_nav_menus() {
	register_nav_menus( [
		'main'          => __( 'Главное меню', ABITPL_TEXTDOMAIN ),
	] );
}

add_action( 'after_setup_theme', 'abitpl\register_theme_nav_menus' );