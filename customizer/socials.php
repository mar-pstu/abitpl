<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


function customizer_register_socials( $wp_customize ) {

	$wp_customize->add_section(
		ABITPL_SLUG . '_socials',
		[
			'title'            => __( 'Социальные сети и контакты', ABITPL_TEXTDOMAIN ),
			'description'      => __( 'Используются в шапке и подвале сайта. В полях вводятся значения атрибут href.', ABITPL_TEXTDOMAIN ),
			'priority'         => 20,
		]
	); /**/

	foreach ( apply_filters( 'socials_list', [] ) as $key => $label ) {
		$wp_customize->add_setting(
			'socials' . $key . 'hrefattr',
			[
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_url_raw',
			]
		);
		$wp_customize->add_control(
			'socials' . $key . 'hrefattr',
			[
				'section'           => ABITPL_SLUG . '_socials',
				'label'             => $label,
				'type'              => 'text',
			]
		);
		$wp_customize->selective_refresh->add_partial( 'socials' . $key . 'hrefattr', [
			'selector'         => '.socials',
			'render_callback'  => '__return_false',
			'fallback_refresh' => true,
		] ); /**/
	}

}

add_action( 'customize_register', 'abitpl\customizer_register_socials', 10, 1 );


/**
 * 
 * */
function customizer_decor_socials() {
	?>

		<style>
			#accordion-section-<?php echo ABITPL_SLUG; ?>_socials .accordion-section-title::before {
				content: "\f237";
				display: inline;
				font: normal 20px/1 dashicons;
				vertical-align: middle;
			}
		</style>

	<?php
}

add_action( 'customize_controls_print_styles', 'abitpl\customizer_decor_socials' );