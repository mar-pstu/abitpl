<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Редирект на запись со страницы поиска, если найдена всего одна запись
 */
function single_result_redirect() {
	global $wp_query;
	if ( $wp_query->is_search() ) {
		if ( $wp_query->post_count == 1 ) {
			wp_redirect( get_permalink( reset( $wp_query->posts )->ID ) );
			die;
		}
	}
}

add_action( 'template_redirect', 'abitpl\single_result_redirect' );


/**
 * Подсветка результатов поиска
 **/
function search_backlight( $text ) {
	if ( in_the_loop() && ( is_search() || ( is_singular() && array_key_exists( 's', $_GET ) ) ) ) {
		$query_terms = get_query_var( 'search_terms' );
		if ( empty( $query_terms ) )
			$query_terms = array_filter( (array) get_query_var( 's' ) );
		if ( empty( $query_terms ) )
			return $text;
		foreach( $query_terms as $term ){
			$term = preg_quote( $term, '/' );
			$text = preg_replace_callback( "/$term/iu", function( $match ) {
				return '<span class="pl-1 pr-1 bg-primary">'. $match[ 0 ] .'</span>';
			}, $text );
		}
	}
	return $text;
}

add_filter( 'the_content', 'abitpl\search_backlight' );

add_filter( 'get_the_excerpt', 'abitpl\search_backlight' );

add_filter( 'the_title', 'abitpl\search_backlight' );


/**
 * Добавляет ключ поиска в ссылку поста для подсветки на странице записи
 * */
function add_search_key_for_backlight( $permalink, $post_id ) {
	if ( is_search() && in_the_loop() ) {
		$permalink = esc_url( add_query_arg( [ 's' => get_search_query() ], $permalink ) );
	}
	return $permalink;
}

add_filter( 'the_permalink', 'abitpl\add_search_key_for_backlight', 10, 2 );