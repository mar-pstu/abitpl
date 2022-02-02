<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_parts_sidebar( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_parts_sidebar',
		[
			'title'            => __( 'Сайдбар', ABITPL_TEXTDOMAIN ),
			'priority'         => 20,
			'panel'            => 'template_parts',
		]
	); /**/

	$wp_customize->add_setting(
		'sideabarlogousedby',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'abitpl\sanitize_checkbox',
		]
	);
	$wp_customize->add_control(
		'sideabarlogousedby',
		[
			'section'           => ABITPL_SLUG . '_parts_sidebar',
			'label'             => __( 'Показывать логотип сайта', ABITPL_TEXTDOMAIN ),
			'type'              => 'checkbox',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'sideabarlogousedby', [
		'selector'         => '#aside-logo',
		'render_callback'  => '__return_false',
		'fallback_refresh' => true,
	] ); /**/

	$wp_customize->add_setting(
		'sideabarsocialsusedby',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'abitpl\sanitize_checkbox',
		]
	);
	$wp_customize->add_control(
		'sideabarsocialsusedby',
		[
			'section'           => ABITPL_SLUG . '_parts_sidebar',
			'label'             => __( 'Показывать ссылки на иконки социальных сетей', ABITPL_TEXTDOMAIN ),
			'type'              => 'checkbox',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'sideabarsocialsusedby', [
		'selector'         => '#aside .socials',
		'render_callback'  => '__return_false',
		'fallback_refresh' => true,
	] ); /**/


}

add_action( 'customize_register', 'abitpl\customizer_register_parts_sidebar', 10, 1 );