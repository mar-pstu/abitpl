<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_parts_footer( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_parts_footer',
		[
			'title'            => __( 'Подвал сайта', ABITPL_TEXTDOMAIN ),
			'priority'         => 30,
			'panel'            => 'template_parts',
		]
	); /**/

	$wp_customize->add_setting(
		'footercopytext',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);
	$wp_customize->add_control(
		'footercopytext',
		[
			'section'           => ABITPL_SLUG . '_parts_footer',
			'label'             => __( 'Копирайт', ABITPL_TEXTDOMAIN ),
			'type'              => 'text',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'footercopytext', [
		'selector'         => '#footer-copyright',
		'render_callback'  => function () { return customizer_get_text_theme_mod( 'footercopytext' ); },
		'fallback_refresh' => true,
	] ); /**/

}

add_action( 'customize_register', 'abitpl\customizer_register_parts_footer', 10, 1 );