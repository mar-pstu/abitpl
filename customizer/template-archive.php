<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_archive( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_archive',
		[
			'title'            => __( 'Архив', ABITPL_TEXTDOMAIN ),
			'description'      => __( 'Страницы архива, категорий (шаблон index.php)', ABITPL_TEXTDOMAIN ),
			'priority'         => 10,
			'panel'            => 'page_templates',
		]
	); /**/

	$wp_customize->add_setting(
		'archivetitleprefix',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);
	$wp_customize->add_control(
		'archivetitleprefix',
		[
			'section'           => ABITPL_SLUG . '_archive',
			'label'             => __( 'Префикс', ABITPL_TEXTDOMAIN ),
			'description'       => __( 'Текст перед заголовком архива', ABITPL_TEXTDOMAIN ),
			'type'              => 'text',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'archivetitleprefix', [
		'render_callback'  => '__return_false',
		'fallback_refresh' => true,
	] ); /**/

}

add_action( 'customize_register', 'abitpl\customizer_register_archive', 10, 1 );