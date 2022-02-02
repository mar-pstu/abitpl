<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$list = [];
$format = apply_filters( 'socials_item_format', '<li><a class="$1%s" href="$2%s"><span class="sr-only">$3%s</span></a></li>' );


foreach ( apply_filters( 'socials_list', [] ) as $key => $label ) {
	$href_attr = trim( get_theme_mod( 'socials' . $key . 'hrefattr', '' ) );
	if ( ! empty( $href_attr ) ) {
		array_push( $list, sprintf( $format, $key, $href_attr, $label ) );
	}
}


if ( ! empty( $list ) ) {
	echo apply_filters( 'socials_list_before', '<ul id="socials" class="socials list-unstyled d-flex">' ), implode( PHP_EOL, $list ), apply_filters( 'socials_list_after', '</ul>' );