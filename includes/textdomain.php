<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Загрузка "переводов"
 */
function load_textdomain() {
	load_theme_textdomain( ABITPL_TEXTDOMAIN, ABITPL_DIR . 'languages/' );
}
add_action( 'after_setup_theme', 'abitpl\load_textdomain' );
