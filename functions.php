<?php


if ( ! defined( 'ABSPATH' ) ) { exit; };


define( 'ABITPL_URL', get_template_directory_uri() . '/' );
define( 'ABITPL_DIR', get_template_directory() . '/' );
define( 'ABITPL_TEXTDOMAIN', 'abitpl' );
define( 'ABITPL_SLUG', 'abitpl' );


get_template_part( 'includes/brand' );
get_template_part( 'includes/textdomain' );
get_template_part( 'includes/theme-functions' );
get_template_part( 'includes/sidebars' );
get_template_part( 'includes/theme-supports' );
get_template_part( 'includes/menus' );


if ( is_admin() ) {
	isset( $_GET[ 'activated' ] ) && 'themes.php' == $pagenow && get_template_part( 'includes/theme-activate' );
} else {
	get_template_part( 'includes/hooks-public' );
	get_template_part( 'includes/enqueue-public' );
	get_template_part( 'includes/search-result' );
}


if ( is_customize_preview() ) {
	get_template_part( 'customizer/control', 'separator' );
	get_template_part( 'customizer/control', 'list' );
	get_template_part( 'customizer/control', 'gallery' );
	get_template_part( 'customizer/control', 'editor' );
	get_template_part( 'customizer/panels' );
	get_template_part( 'customizer/scripts' );
	get_template_part( 'customizer/modals' );
	get_template_part( 'customizer/socials' );
	get_template_part( 'customizer/section', 'partners' );
	get_template_part( 'customizer/part', 'sidebar' );
	get_template_part( 'customizer/part', 'footer' );
	get_template_part( 'customizer/template', 'archive' );
	get_template_part( 'customizer/template', '404' );
}