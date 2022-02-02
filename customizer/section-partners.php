<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_section_partners( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_section_partners',
		[
			'title'            => sprintf( '%s - %s', __( 'Главная страница', ABITPL_TEXTDOMAIN ),  __( 'Партнёры', ABITPL_TEXTDOMAIN ) ),
			'priority'         => 40,
			'panel'            => 'template_parts',
		]
	); /**/

	$wp_customize->add_setting(
		'sectionpartnersusedby',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'abitpl\sanitize_checkbox',
		]
	);
	$wp_customize->add_control(
		'sectionpartnersusedby',
		[
			'section'           => ABITPL_SLUG . '_section_partners',
			'label'             => __( 'Использовать секцию', ABITPL_TEXTDOMAIN ),
			'type'              => 'checkbox',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'sectionpartnersusedby', [
		'render_callback'  => '__return_false',
		'fallback_refresh' => true,
	] ); /**/

	$wp_customize->add_setting(
		'sectionpartnerstitle',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);
	$wp_customize->add_control(
		'sectionpartnerstitle',
		[
			'section'           => ABITPL_SLUG . '_section_partners',
			'label'             => __( 'Заголовок &lt;H2&gt;', ABITPL_TEXTDOMAIN ),
			'description'       => __( 'Заголовок скрыт', ABITPL_TEXTDOMAIN ),
			'type'              => 'text',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'sectionpartnerstitle', [
		'selector'         => '#technologes-title',
		'render_callback'  => function () { return customizer_get_text_theme_mod( 'sectionpartnerstitle' ); },
		'fallback_refresh' => true,
	] ); /**/

	$wp_customize->add_setting(
		'sectionpartners',
		[
			'transport'         => 'postMessage',
			'sanitize_callback' => 'wp_parse_id_list',
		]
	);
	$wp_customize->add_control(
		new WP_Customize_Control_Gallery(
			$wp_customize,
			'sectionpartners',
			[
				'label'      => __( 'Логотипы', ABITPL_TEXTDOMAIN ),
				'section'    => ABITPL_SLUG . '_section_partners',
			]
		)
	);
	$wp_customize->selective_refresh->add_partial( 'sectionpartners', [
		'selector'         => '#technologes-wrap',
		'render_callback'  => '__return_false',
		'fallback_refresh' => true,
	] ); /**/

}

add_action( 'customize_register', 'abitpl\customizer_register_section_partners', 10, 1 );