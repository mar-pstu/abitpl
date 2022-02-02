<?php


namespace abitpl;


use WP_Customize_Control;


if ( ! defined( 'ABSPATH' ) ) { exit; };


if ( class_exists( 'WP_Customize_Control' ) ) :


	class WP_Customize_Control_list extends WP_Customize_Control {

		
		public $type = 'list';


		protected $textdomain;


		protected $controls;


		public function __construct( $manager, $id, $args = [] ) {
			parent::__construct( $manager, $id, $args );
			if ( ! array_key_exists( 'textdomain', $args ) ) {
				$args[ 'textdomain' ] = 'WPCustomizeControlList';
			}
			if ( ! array_key_exists( 'controls', $args ) ) {
				$args[ 'controls' ] = [];
			}
			$this->controls = array_merge( [
				'usedby' => [
					'type'  => 'checkbox',
					'label' => __( 'Использовать', $this->textdomain ),
				],
				'title'  => [
					'type'  => 'text',
					'label' => __( 'Заголовок', $this->textdomain ),
				],
			], $args[ 'controls' ] );
			$this->input_attrs = array_merge( $this->input_attrs, [
				'type'   => 'hidden',
				'id'     => $this->id,
				'name'   => $this->id,
				'value'  => htmlspecialchars( is_array( $this->value() ) ? wp_json_encode( $this->value() ) : $this->value(), ENT_QUOTES, 'UTF-8' ),
				'placeholder' => '',
			] );
		}


		/**
		 * Подключаем скрипты и стили
		 */
		public function enqueue() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'customize-preview' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'code-editor' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'wp-util' );
			wp_enqueue_script( 'wp-i18n' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'chosen', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js', [ 'jquery' ], '1.8.7', true );
			wp_enqueue_script( 'control-list', get_theme_file_uri( 'scripts/control-list.js' ), [ 'jquery', 'customize-preview', 'wp-util', 'jquery-ui-sortable', 'chosen', 'wp-color-picker', 'jquery-ui-datepicker' ], filemtime( get_theme_file_path( 'scripts/control-list.js' ) ), true );
			wp_set_script_translations( 'control-list', $this->textdomain );
			wp_localize_script( 'control-list', $this->id . 'Args', [
				'restUrl' => get_rest_url(),
			] );
			wp_add_inline_script( 'control-list', "jQuery( document ).ready( function () { jQuery( '#customize-control-{$this->id}' ).WPCustomizeControlList( {$this->id}Args ); } );", 'after' );
			wp_enqueue_style( 'chosen', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css', [], '1.8.7', 'all' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'code-editor' );
			wp_enqueue_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null );
			wp_enqueue_style( 'control-list', get_theme_file_uri( 'styles/control-list.css' ), [ 'chosen', 'jquery-ui' ], filemtime( get_theme_file_path( 'styles/control-list.css' ) ), 'all' );
		}

		public function render_content() {
			?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				</label>
				<input <?php $this->input_attrs(); ?> <?php echo $this->link(); ?> />
				<script type="text/html" id="tmpl-<?php echo $this->id; ?>-item">
					<li class="list__item item {{data.usedby}}" data-item-index="{{data.i}}">
						<span class="title">{{data.title}}</span>
						<button type="button" class="toggle dashicons-before" title="<?php _e( 'Открыть форму редатирования блока', $this->textdomain ); ?>">
							<snap class="sr-only"><?php _e( 'Редактировать блок', $this->textdomain ); ?></snap>
						</button>
					</li>
				</script>
				<script id="tmpl-<?php echo $this->id; ?>-form" type="text/html">
					<form class="form" data-item-index="{{data.i}}">
						<div class="form__inner inner">
							<button type="button" class="close dashicons-before dashicons-no-alt" title="<?php esc_attr_e( 'Закрыть форму', $this->textdomain ); ?>">
								<?php _e( 'Закрыть', $this->textdomain ); ?>
							</button>
			<?php

			foreach ( $this->controls as $name => $args ) {

				if ( ! array_key_exists( 'input_atts', $args ) || ! is_array( $args[ 'input_atts' ] ) ) {
					$args[ 'input_atts' ] = [];
				}

				if ( ! array_key_exists( 'label_atts', $args ) || ! is_array( $args[ 'label_atts' ] ) ) {
					$args[ 'label_atts' ] = [];
				}

				$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
					'id'   => $this->id . '{{data.i}}' . $name,
					'name' => $name,
				] );

				$args[ 'label_atts' ] = array_merge( $args[ 'label_atts' ], [
					'for'   => sprintf( '%1$s{{data.i}}%2$s', $this->id, $name ),
					'class' => sprintf( 'label label-%1$s label%2$s%3$s', $args[ 'type' ], $this->id, $name ),
				] );

				switch ( $args[ 'type' ] ) {

					case 'checkbox':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'type'  => 'checkbox',
							'value' => 'on',
						] );
						?>
							<div class="checkbox">
								<input <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> <# print( data.<?php echo $name; ?> ? 'checked' : '' ); #> />
								<label <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
									<?php echo $args[ 'label' ]; ?>
								</label>
							</div>
						<?php
						break;

					case 'checkboxes':
						if ( array_key_exists( 'choices', $args ) && is_array( $args[ 'choices' ] ) && ! empty( $args[ 'choices' ] ) ) :
							?>
								<div class="<?php echo $this->id . $name; ?>"><?php echo $args[ 'label' ]; ?></div>
								<ul>
									<?php foreach ( $args[ 'choices' ] as $v => $l ) : ?>
										<li>
											<label for="<?php echo $this->id; ?>{{data.i}}<?php echo $name . $v; ?>">
												<input
													id="<?php echo $this->id; ?>{{data.i}}<?php echo $name . $v; ?>"
													<# print( ( typeof ( data.<?php echo $name; ?> ) != 'undefined' && Array.isArray( data.<?php echo $name; ?> ) && data.<?php echo $name; ?>.includes( '<?php echo esc_attr( $v ); ?>' ) ) ? 'checked' : '' ); #>
													name="<?php echo $name; ?>[]"
													value="<?php echo esc_attr( $v ); ?>"
													type="checkbox"
												/>
												<?php echo $l; ?>
											</label>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php
						endif;
						break;

					case 'radio':
						if ( array_key_exists( 'choices', $args ) && is_array( $args[ 'choices' ] ) && ! empty( $args[ 'choices' ] ) ) :
							?>
								<div class="label <?php echo $this->id . $name; ?>"><?php echo $args[ 'label' ]; ?></div>
								<ul>
									<?php foreach ( $args[ 'choices' ] as $v => $l ) : ?>
										<li>
											<label for="<?php echo $this->id; ?>{{data.i}}<?php echo $name . $v; ?>">
												<input
													id="<?php echo $this->id; ?>{{data.i}}<?php echo $name . $v; ?>"
													<# print( ( typeof ( data.<?php echo $name; ?> ) != 'undefined' && data.<?php echo $name; ?> == '<?php echo esc_attr( $v ); ?>' ) ? 'checked' : '' ); #>
													name="<?php echo $name; ?>"
													value="<?php echo esc_attr( $v ); ?>"
													type="<?php echo $args[ 'type' ]; ?>"
												/>
												<?php echo $l; ?>
											</label>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php
						endif;
						break;

					case 'editor':
					case 'textarea':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'placeholder' => '',
							'class'       => '',
						] );
						$args[ 'input_atts' ][ 'class' ] .= ( $args[ 'type' ] == 'editor' ) ? ' editor' : '';
						if ( $args[ 'type' ] == 'editor' ) {
							$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-editor-paste-word';
						} else {
							$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-editor-paste-text';
						}
						?>
 							<label <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
								<?php echo $args[ 'label' ]; ?>
								<textarea <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> >{{data.<?php echo $name; ?>}}</textarea>
							</label>
						<?php
						break;

					case 'entries':
						$args[ 'input_atts' ][ 'data-post-type' ] = ( ! array_key_exists( 'post_type', $args ) || empty( $args[ 'post_type' ] ) ) ? $args[ 'post_type' ] : 'posts';
						?>
							<label <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
								<?php echo $args[ 'label' ]; ?>
								<select <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> >
									<option value="">-</option>
								</select>
							</label>
						<?php
						break;

					case 'image':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'type'  => 'hidden',
							'placeholder' => '',
						] );
						$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-format-image';
						?>
 							<div class="image">
 								<?php echo $args[ 'label' ]; ?>
								<input
									<?php echo $this->render_atts( $args[ 'input_atts' ] ); ?>
									value='<# try { print( JSON.stringify( data.<?php echo $name; ?> ).replace(/[\\"']/g, '\\$&') ); } catch ( error ) {} #>'
								/>
								<img src="" alt="<?php esc_attr_e( 'выбор изображения', $this->textdomain ); ?>">
								<button type="button" class="choice" title="<?php esc_attr_e( 'Выберите изображение', $this->textdomain ); ?>">
									<?php _e( 'Выберите изображение', $this->textdomain ); ?>
								</button>
								<button type="button" class="clear dashicons-before dashicons-editor-removeformatting" title="<?php esc_attr_e( 'Очистить', $this->textdomain ); ?>">
									<?php _e( 'Очистить изображение', $this->textdomain ); ?>
								</button>
							</div>
						<?php
						break;

					case 'select':
						if ( array_key_exists( 'choices', $args ) && is_array( $args[ 'choices' ] ) && ! empty( $args[ 'choices' ] ) ) :
							?>
								<label <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
									<?php echo $args[ 'label' ]; ?>
									<select <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> >
										<?php foreach ( $args[ 'choices' ] as $v => $l ) : ?>
											<option
												value="<?php echo esc_attr( $v ); ?>"
												<# print( data.<?php echo $name; ?> && data.<?php echo $name; ?> == '<?php echo esc_attr( $v ); ?>' ? 'selected' : '' ); #>
											>
												<?php echo $l; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</label>
							<?php
						endif;
						break;

					case 'gallery':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'type'  => 'hidden',
							'placeholder' => '',
						] );
						$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-format-gallery';
						?>
 							<div class="gallery">
								<?php echo $args[ 'label' ]; ?>
								<input
									<?php echo $this->render_atts( $args[ 'input_atts' ] ); ?>
									value='<# try { print( JSON.stringify( data.<?php echo $name; ?> ).replace(/[\\"']/g, '\\$&') ); } catch ( error ) {} #>'
								/>
								<button type="button" class="choice" title="<?php esc_attr_e( 'Выберите изображение', $this->textdomain ); ?>">
									<?php _e( 'Выберите изображения галереи', $this->textdomain ); ?>
								</button>
							</div>
						<?php
						break;

					case 'color':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'type'  => 'text',
							'value' => sprintf( '{{data.%1$s}}', $name ),
							'placeholder' => '',
							'class' => 'colorpicker',
						] );
						$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-art';
						?>
							<div <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
								<?php echo $args[ 'label' ]; ?>
								<input <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> />
							</div>
						<?php
						break;

					case 'date':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'type'  => 'text',
							'value' => sprintf( '{{data.%1$s}}', $name ),
							'placeholder' => '',
							'class' => 'datepicker',
						] );
						$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-calendar-alt';
						?>
							<div <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
								<?php echo $args[ 'label' ]; ?>
								<input <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> />
							</div>
						<?php
						break;

					default:
					case 'text':
						$args[ 'input_atts' ] = array_merge( $args[ 'input_atts' ], [
							'type'  => $args[ 'type' ],
							'value' => sprintf( '{{data.%1$s}}', $name ),
							'placeholder' => '',
						] );
						$args[ 'label_atts' ][ 'class' ] .= ' dashicons-before dashicons-editor-textcolor'
						?>
							<label <?php echo $this->render_atts( $args[ 'label_atts' ] ); ?> >
								<?php echo $args[ 'label' ]; ?>
								<input <?php echo $this->render_atts( $args[ 'input_atts' ] ); ?> />
							</label>
						<?php
						break;
				}
			}

			?>
							<button type="button" class="remove dashicons-before dashicons-trash" title="<?php esc_attr_e( 'Удалить блок', $this->textdomain ); ?>">
								<?php _e( 'Удалить', $this->textdomain ); ?>
							</button>
						</div>
					</form>
				</script>
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