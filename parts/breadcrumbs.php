<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


echo apply_filters( 'breadcrumbs_before', '<div class="breadcrumbs mb-3 small" id="bredcrumbs">' );

$link_format = apply_filters( 'breadcrumbs_link_format', '<a href="%s">%s</a> » ' );

if ( function_exists( 'yoast_breadcrumb' ) ) {

	yoast_breadcrumb();

} else {

	printf( $link_format, home_url(), __( 'Главная', ABITPL_TEXTDOMAIN ) );
	
	if ( is_category() || is_single() ) {
		the_category( ', ' );
	}

	if ( is_single() || is_page() ) {
		the_title();
	}
}

echo apply_filters( 'breadcrumbs_after', '</div>' );