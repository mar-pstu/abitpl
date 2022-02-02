<?php


namespace abitpl;


use WP_Customize_Control;


if ( ! defined( 'ABSPATH' ) ) { exit; };


if ( class_exists( 'WP_Customize_Control' ) ) :


	class WP_Customize_Control_Gallery extends WP_Customize_Control {

		
		public $type;


		protected $textdomain;


		public function __construct( $manager, $id, $args = [] ) {
			parent::__construct( $manager, $id, $args );
			$this->type = 'gallery';
			if ( ! array_key_exists( 'textdomain', $args ) ) {
				$args[ 'textdomain' ] = 'WPCustomizeControlGallery';
			}
			$this->input_attrs = array_merge( $this->input_attrs, [
				'type'   => ( SCRIPT_DEBUG ) ? 'text' : 'hidden',
				'id'     => $this->id,
				'name'   => $this->id,
				'value'  => is_array( $this->value() ) ? implode( ',', $this->value() ) : $this->value(),
				'placeholder' => '',
			] );
			add_action( 'wp_ajax_' . 'thumbnails_data', [ get_class(), 'get_thumbnails_data' ] );
		}


		/**
		 * Возвращает данные изображения по AJAX для генерации превью галереи
		 */
		static public function get_thumbnails_data() {
			if ( array_key_exists( 'images', $_POST ) ) {
				$result = [];
				foreach ( wp_parse_id_list( $_POST[ 'images' ] ) as $image_id ) {
					$result[] = [
						'id'  => $image_id,
						'url' => wp_get_attachment_image_url( $image_id, 'thumbnail', false ),
					];
				}
				wp_send_json_success( $result, null );
			}
		}


		/**
		 * Подключаем скрипты и стили
		 */
		public function enqueue() {
			$suffix = ( SCRIPT_DEBUG ) ? '.min' : '';
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'customize-preview' );
			wp_enqueue_script( 'wp-i18n' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script(
				'control-gallery',
				get_theme_file_uri( 'scripts/control-gallery' . $suffix . '.js' ),
				[ 'jquery', 'customize-preview', 'wp-util' ],
				filemtime( get_theme_file_path( 'scripts/control-gallery' . $suffix . '.js' ) ),
				true
			);
			wp_set_script_translations( 'control-gallery', $this->textdomain );
			wp_localize_script( 'control-gallery', $this->id . 'Args', [
				'restUrl' => get_rest_url(),
			] );
			wp_add_inline_script(
				'control-gallery',
				"jQuery( document ).ready( function () { jQuery( '#customize-control-{$this->id}' ).WPCustomizeControlGallery( {$this->id}Args ); } );",
				'after'
			);
			wp_enqueue_style(
				'control-gallery',
				get_theme_file_uri( 'styles/control-gallery' . $suffix . '.css' ),
				[],
				filemtime( get_theme_file_path( 'styles/control-gallery' . $suffix . '.css' ) ),
				'all'
			);
		}

		public function render_content() {
			?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				</label>
				<input <?php $this->input_attrs(); ?> <?php echo $this->link(); ?> />
			<?php
		}


		/**
		 * Формирует html код аттрибутов элемента управления формы
		 * @param  array  $atts  ассоциативный массив аттрибут=>значение
		 * @return string        html-код
		 */
		public static function render_atts( array $atts = [] ) {
			$html = '';
			if ( ! empty( $atts ) ) {
				foreach ( $atts as $key => $value ) {
					$html .= ' ' . $key . '="' . $value . '"';
				}
			}
			return $html;
		}

	}


endif;