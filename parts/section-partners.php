<?php


namespace abitpl;


global $post;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$title = trim( get_theme_mod( 'sectionpartnerstitle' ) );
$entries = wp_parse_id_list( get_theme_mod( 'sectionpartners' ) );
$content = '';


if ( is_array( $entries ) && ! empty( $entries ) ) {

	ob_start();

	foreach ( $entries as $post ) {

		setup_postdata( $post );

		include get_theme_file_path( 'views/entry-partner.php' );

	}

	wp_reset_postdata();

	$content = ob_get_contents();

	ob_end_clean();

	if ( ( bool ) apply_filters( 'section_enqueue', true, 'partners', 'slick' ) ) {
		
		wp_enqueue_script( 'slick' );

		add_action( 'get_footer', function () {
			wp_enqueue_style( 'slick' );
		}, 10, 0 );

		$init_file_path = get_theme_file_path( 'scripts/init-partners.js' );
		
		if ( file_exists( $init_file_path ) ) {
			wp_add_inline_script( 'slick', file_get_contents( $init_file_path ), 'after' );
		}

	}

}


include get_theme_file_path( 'views/section-partners.php' );