<?php


namespace abitpl;


use WP_Customize_Control;


if ( ! defined( 'ABSPATH' ) ) { exit; };


if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Control_Separator' ) ) :


	class WP_Customize_Control_Separator extends WP_Customize_Control {

		public $type = 'separator';

		/**
		 * Подключаем скрипты и стили
		 */
		public function enqueue() {
			wp_enqueue_style( 'control-separator', get_theme_file_uri( 'styles/control-separator.css' ), [], filemtime( get_theme_file_path( 'styles/control-separator.css' ) ), 'all' );
		}

		public function render_content(){
			?>
				<div>
					<div class="customize-control-title"><?php echo esc_html( $this->label ); ?></div>
					<hr>
					<?php if ( ! empty( $this->description ) ) : ?>
						<div class="customize-control-description"><?php echo esc_html( $this->description ); ?></div>
					<?php endif; ?>
				</div>
			<?php
		}

	}


endif;