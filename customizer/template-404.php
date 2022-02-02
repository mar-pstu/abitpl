<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_error404( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_error404',
		[
			'title'            => __( '404', ABITPL_TEXTDOMAIN ),
			'description'      => __( 'Страница ошибки 404 (шаблон 404.php)', ABITPL_TEXTDOMAIN ),
			'priority'         => 20,
			'panel'            => 'page_templates',
		]
	); /**/

	$wp_customize->add_setting(
		'error404title',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);
	$wp_customize->add_control(
		'error404title',
		[
			'section'           => ABITPL_SLUG . '_error404',
			'label'             => __( 'Заголовок', ABITPL_TEXTDOMAIN ),
			'type'              => 'text',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'error404title', [
		'selector'         => '#error404-title',
		'render_callback'  => function () { return customizer_get_text_theme_mod( 'error404title' ); },
		'fallback_refresh' => true,
	] ); /**/

	$wp_customize->add_setting(
		'error404description',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'wp_kses_post',
		]
	);
	$wp_customize->add_control(
		new WP_Customize_Control_Tinymce_Editor(
			$wp_customize,
			'error404description',
			[
				'label'                 => __( 'Описание', ABITPL_TEXTDOMAIN ),
				'section'               => ABITPL_SLUG . '_error404',
				'settings'              => 'error404description'
			]
		)
	);
	$wp_customize->selective_refresh->add_partial( 'error404description', [
		'selector'         => '#error404-description',
		'render_callback'  => function () { return customizer_get_editor_theme_mod( 'error404description' ); },
		'fallback_refresh' => true,
	] ); /**/

}

add_action( 'customize_register', 'abitpl\customizer_register_error404', 10, 1 );