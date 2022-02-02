<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Регистрация "сайдбаров"
 */
function register_sidebars() {
	register_sidebar( [
		'name'             => __( 'Сайдбар подвала', ABITPL_TEXTDOMAIN ),
		'id'               => 'basement',
		'description'      => '',
		'class'            => '',
		'before_widget'    => '<div id="%1$s" class="widget %2$s">',
		'after_widget'     => '</div>',
		'before_title'     => '<h3 class="widget__title title">',
		'after_title'      => '</h3>',
	] );
}

add_action( 'widgets_init', 'abitpl\register_sidebars' );