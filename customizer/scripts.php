<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_scripts( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_scripts',
		[
			'title'            => __( 'Дополнительные скрипты', ABITPL_TEXTDOMAIN ),
			'priority'         => 10,
		]
	); /**/

	$wp_customize->add_setting(
		'additionalscriptswphead',
		[
			'transport'         => 'postMessage',
		]
	);
	$wp_customize->add_control(
		'additionalscriptswphead',
		[
			'section'           => ABITPL_SLUG . '_scripts',
			'label'             => __( 'Выводится в тэге HEAD', ABITPL_TEXTDOMAIN ),
			'type'              => 'textarea',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'scriptswphead', [
		'render_callback'       => '__return_false',
		'fallback_refresh'      => true,
	] ); /**/

	$wp_customize->add_setting(
		'additionalscriptswpfooter',
		[
			'transport'         => 'postMessage',
		]
	);
	$wp_customize->add_control(
		'additionalscriptswpfooter',
		[
			'section'           => ABITPL_SLUG . '_scripts',
			'label'             => __( 'Выводится в подвале сайта перед закрывающим тегом BODY', ABITPL_TEXTDOMAIN ),
			'type'              => 'textarea',
		]
	);
	$wp_customize->selective_refresh->add_partial( 'scriptswpfooter', [
		'render_callback'       => '__return_false',
		'fallback_refresh'      => true,
	] ); /**/

}

add_action( 'customize_register', 'abitpl\customizer_register_scripts', 10, 1 );


/**
 * 
 * */
function customizer_decor_scripts() {
	?>

		<style>
			#accordion-section-<?php echo ABITPL_SLUG; ?>_scripts .accordion-section-title::before {
				content: "\f14b";
				display: inline;
				font: normal 20px/1 dashicons;
				vertical-align: middle;
			}
			#accordion-section-custom_css .accordion-section-title::before {
				content: "\f540";
				display: inline;
				font: normal 20px/1 dashicons;
				vertical-align: middle;
			}
		</style>

	<?php
}

add_action( 'customize_controls_print_styles', 'abitpl\customizer_decor_scripts' );