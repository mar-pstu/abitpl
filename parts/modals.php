<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$modals = get_theme_mod( 'modals' );
$content = '';

if ( is_string( $modals ) ) {
	$modals = json_decode( $modals, true );
}

if ( is_array( $modals ) && ! empty( $modals ) ) {
	ob_start();
	foreach ( $modals as $entry ) {
		include get_theme_file_path( 'views/entry-modal.php' );
	}
	$content = ob_get_contents();
	ob_end_clean();
}

include get_theme_file_path( 'views/modals.php' );