<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Меняем картинку логотипа WP в админке
 * */
function brand_admin_logo() {
	$icon_url = get_site_icon_url( 32 );
	if ( is_url( $icon_url ) ) {
		echo css_array_to_css( [
			'#wp-admin-bar-site-name > a.ab-item::before' => [
				'content'      => '\'\' !important',
				'width'        => '16px',
				'height'       => 'inherit',
				'box-sizing'   => 'border-box',
				'display'      => 'inline-block',
				'padding'      => '0 !important',
				'background-image'    => 'url(' . $icon_url . ') !important',
				'background-repeat'   => 'no-repeat !important',
				'background-position' => 'center center',
				'background-size' => '16px 16px',
			],
		], [ 'container' => true ] );
	}
}

add_action( 'admin_head', 'abitpl\brand_admin_logo' );


/**
 * Меняем картинку логотипа WP на странице входа
 * */
function brand_login_logo() {
	$icon_url = get_site_icon_url();
	if ( is_url( $icon_url ) ) {
		echo css_array_to_css( [
			'body.login h1 a' => [
				'background-image'   => 'url( ' . $icon_url . ' )',
				'background-repeat'  => 'no-repeat',
				'width'              => '128px',
				'height'             => '128px',
				'background-size'    => '100px 100px',
				'background-position'=> 'center center',
				'margin'             => '0 auto'
			],
			'body.login h1::after' => [
				'content'            => '\'' . get_bloginfo( 'name', 'raw' ) . '\'',
				'display'            => 'block',
				'margin-bottom'      => '25px',
			],
		], [ 'container' => true ] );
	}
}

add_action( 'login_head', 'abitpl\brand_login_logo' );


/**
 * Ставим ссыллку с логотипа на сайт, а не на wordpress.org
 * */
add_filter( 'login_headerurl', 'get_home_url' );

/**
 *  убираем title в логотипе "сайт работает на wordpress"
 * */
add_filter( 'login_headertext', '__return_false' );