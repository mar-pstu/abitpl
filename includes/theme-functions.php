<?php


namespace abitpl;


use DOMDocument;


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**
 * Проверяет данные поля чекбокс
 * @return   bool
 * */
function sanitize_checkbox( $checked = false ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}


/**
 * Проверяет является ли переданная строка валидным URL
 * @param  string  $url исходная строка
 * @return boolean      результат проверки
 */
function is_url( $url = '' ) {
	$result = false;
	if ( is_string( $url ) ) {
		$path = parse_url( $url, PHP_URL_PATH );
		$encoded_path = array_map( 'urlencode', explode( '/', $path ) );
		$url = str_replace( $path, implode( '/', $encoded_path ), $url );
		$result = filter_var( $url, FILTER_VALIDATE_URL) ? true : false;
	}
	return $result;
}


/**
 * Функция для очистки массива параметров
 * @param  array $default           расзерённые парметры и стандартные значения
 * @param  array $args              неочищенные параметры
 * @param  array $sanitize_callback одномерный массив с именами функция, с помощью поторых нужно очистить параметры
 * @param  array $required          обязательные параметры
 * @param  array $not_empty         параметры которые не могут быть пустыми
 * @return array                    возвращает ощиченный массив разрешённых параметров
 * */
function parse_only_allowed_args( $default, $args, $sanitize_callback = [], $required = [], $not_empty = [] ) {
	$args = ( array ) $args;
	$result = [];
	$count = 0;
	while ( ( $value = current( $default ) ) !== false ) {
		$key = key( $default );
		if ( array_key_exists( $key, $args ) ) {
			$result[ $key ] = $args[ $key ];
			if ( isset( $sanitize_callback[ $count ] ) && ! empty( $sanitize_callback[ $count ] ) ) {
				$result[ $key ] = $sanitize_callback[ $count ]( $result[ $key ] );
			}
		} elseif ( in_array( $key, $required ) ) {
			return null;
		} else {
			$result[ $key ] = $value;
		}
		if ( empty( $result[ $key ] ) && in_array( $key, $not_empty ) ) {
			return null;
		}
		$count = $count + 1;
		next( $default );
	}
	return $result;
}


/**
 * Очищает массив с данные вложения
 * @param  array      исходные данные вложения (id, url)
 * @return array      очищенный результат
 */
function sanitize_attachment_data( $data = [] ) {
	return ( is_array( $data ) ) ? parse_only_allowed_args( [ 'id' => '', 'url' => '' ], $data, [ 'absint', 'esc_url_raw' ], [ 'id', 'url' ] ) : [];
}


/**
 * Конвертер ассоциативного массива в css правила
 * @param    array    $rules   массив параметров, где ключ это селекторы
 * @param    array    $args    дополнительные аргумаенты для преобразования
 * @return   string
 * */
function css_array_to_css( $rules, $args = [] ) {
	$args = array_merge( [
		'indent'     => 0,
		'container'  => false,
	], $args );
	$css = '';
	$prefix = str_repeat( '  ', $args[ 'indent' ] );
	foreach ($rules as $key => $value ) {
		if ( is_array( $value ) ) {
			$selector = $key;
			$properties = $value;
			$css .= $prefix . "$selector {\n";
			$css .= $prefix . css_array_to_css( $properties, [
				'indent'     => $args[ 'indent' ] + 1,
				'container'  => false,
			] );
			$css .= $prefix . "}\n";
		} else {
			$property = $key;
			if ( is_url( $value ) ) {
				$value = 'url(' . $value . ')';
			}
			$css .= $prefix . "$property: $value;\n";
		}
	}
	return ( $args[ 'container' ] ) ? "\n<style>\n" . $css . "\n</style>\n" : $css;
}


/**
 * Удаление размера изображения из url вложения
 * @param    string   $url   url изображения, который нужно очистить
 * @return   string          очищенный url
 * */
function removing_image_size_from_url( $url = '' ) {
	return preg_replace( '~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $url );
}


/**
 * Возвращает и очищает текстовую настройку для использования в превью Customizer API
 * */
function customizer_get_text_theme_mod( $setting_name ) {
	$result = nl2br( trim( esc_html( get_theme_mod( $setting_name ) ) ) );
	return ( empty( $result ) ) ? false : $result;
}

/**
 * Возвращает и очищает html настройку для использования в превью Customizer API
 * */
function customizer_get_editor_theme_mod( $setting_name ) {
	$result = nl2br( trim( force_balance_tags( wp_kses_post( get_theme_mod( $setting_name ) ) ) ) );
	return ( empty( $result ) ) ? false : $result;
}


/**
 * Возвращает html-код блоков темы по их идентификатору
 * @param    string    $slug         идентификатор блока темы для ф-ции get_template_part
 * @param    string    $name         имя блока темы для ф-ции get_template_part
 * @param    string    $args         дополнительные параметры для ф-ции get_template_part
 * @param    string    $element_id   идентификатор DOM елемента, который нужно найти
 * @return   string|bool             HTML-код или FALSE если ничего не удалось сформировать
 * */
function customizer_render_parts_element_by_id( $slug, $name = null, $args = [], $element_id = '' ) {
	ob_start();
	get_template_part( $slug, $name, $args );
	$result = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $element_id ) ) {
		$DOM = new \DOMDocument();
		$DOM->loadHTML( '<?xml encoding="' . get_bloginfo( 'charset' ) . '" ?>' . $result );
		$element = $DOM->getElementById( $element_id );
		$result = $element ? $DOM->saveHTML( $element ) : '';
	}
	return ( empty( $result ) ) ? false : $result;
}


/**
 * Используется со списками "записей" созданными классом WP_Customize_Control_list.
 * Проверяет активен ли элемент списка
 * */
function is_entry_usedby( $entry ) {
	return is_array( $entry ) && ! empty( $entry ) && array_key_exists( 'usedby', $entry ) && $entry[ 'usedby' ] && array_key_exists( 'title', $entry );
}