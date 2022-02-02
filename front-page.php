<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


get_header();


$sections_keys = apply_filters( 'home_section_list', [
	'partners',
	'about',
	'experience',
	'advantages',
	'reviews',
	'offers',
	'subscribe',
] );

if ( is_array( $sections_keys ) && ! empty( $sections_keys ) ) {

	foreach ( $sections_keys as $key ) {
		if ( get_theme_mod( 'section' . $key . 'usedby' ) ) {
			get_template_part( 'parts/section', $key );
		}
	}

} else {

	include get_theme_file_path( 'index.php' );

}


get_footer();