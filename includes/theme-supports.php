<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * 
 * */
function theme_supports() {
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', [ 'script', 'style' ] );
	add_filter( 'widget_text', 'do_shortcode' );
}

add_action( 'after_setup_theme', 'abitpl\theme_supports' );


/**
 * 
 * */
function theme_image_sizes() {
	add_image_size( 'archive', 585, 160, true );
}

add_action( 'after_setup_theme', 'abitpl\theme_image_sizes' );


/**
 * Возвращает список социальных сетей
 * */
function get_list_of_socials( $items = [] ) {
	return array_merge( $items, [
		'facebook'      => __( 'Facebook', ABITPL_TEXTDOMAIN ),
		'instagram'     => __( 'Instaram', ABITPL_TEXTDOMAIN ),
		'twitter'       => __( 'Twitter', ABITPL_TEXTDOMAIN ),
		'youtube'       => __( 'YouTube', ABITPL_TEXTDOMAIN ),
	] );
}

add_filter( 'socials_list', 'abitpl\get_list_of_socials', 10, 1 );