<?php


namespace abitpl;


use WP_Customize_Control;


if ( ! defined( 'ABSPATH' ) ) { exit; };


if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Control_Tinymce_Editor' ) ) :


	class WP_Customize_Control_Tinymce_Editor extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 */
		public $type = 'tinymce_editor';


		public function __construct( $manager, $id, $args = [] ) {
			parent::__construct( $manager, $id, $args );
			$this->input_attrs = array_merge( $this->input_attrs, [
				'rows'  => '10',
				'id'    => esc_attr( $this->id ),
				'class' => 'control-editor',
			] );
		}

		/**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_editor();
			wp_enqueue_script( 'wp-i18n' );
			wp_enqueue_script( 'customize-preview' );
			wp_enqueue_script(
				'control-editor',
				get_theme_file_uri( 'scripts/control-editor.js' ),
				[ 'jquery', 'customize-preview' ],
				filemtime( get_theme_file_path( 'scripts/control-editor.js' ) ),
				true
			);
			wp_add_inline_script(
				'control-editor',
				'jQuery( document ).ready( function () { jQuery( \'#customize-control-' . $this->id . '\' ).WPCustomizeTinymceEditor(); } );',
				'after'
			);
		}


		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			?>
				<div class="tinymce-control">
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php endif; ?>
					<textarea <?php echo $this->render_atts( $this->input_attrs ); ?> <?php $this->link(); ?> >
						<?php echo esc_attr( $this->value() ); ?>
					</textarea>
				</div>
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