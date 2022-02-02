( function( $ ) {


	$.fn.WPCustomizeControlList = function( args ) {


		const { __, _x, _n, _nx } = wp.i18n;
	

		const textdomain = 'WPCustomizeControlList';


		const settings = jQuery.extend( {
			restUrl: '',
		}, args );


		const MediaLibraryForSingleImage = window.wp.media( {
			frame: 'select',
			title: __( 'Выберите изображение', textdomain ),
			multiple: false,
			library: {
				order: 'DESC',
				orderby: 'date',
				type: 'image',
				search: null,
				uploadedTo: null
			},
			button: {
				text: __( 'Выбрать', textdomain ),
			}
		} );


		const MediaLibraryForGallery = window.wp.media( {
			frame: 'select',
			title: __( 'Выберите изображение', textdomain ),
			multiple: true,
			library: {
				order: 'DESC',
				orderby: 'date',
				type: 'image',
				search: null,
				uploadedTo: null
			},
			button: {
				text: __( 'Выбрать', textdomain ),
			}
		} );


		function StripSlashes( str ) {
			return str.replace( /\\(.?)/g, function ( s, n1 ) {
				switch ( n1 ) {
					case '\\':
						return '\\'
					case '0':
						return '\u0000'
					case '':
						return ''
					default:
						return n1
				}
			} );
		}


		/**
			Получает данные элемента управления по идентификатору
		*/
		function GetData( id ) {
			var value = wp.customize.value( id ).get();
			if ( Array.isArray( value ) ) {
				return value;
			} else {
				try {
					return JSON.parse( value );
				} catch ( error ) {
					return [];
				}
			}
		}

		/**
			Записывает данные в элемент управления
		*/
		function SetData( id, data ) {
			wp.customize.value( id ).set( JSON.stringify( data ) );
		}


		/**
			Строит список
		*/
		function Build( data, Item, $list ) {
			$list.empty();
			jQuery( data ).each( function ( index, value ) {
				if ( typeof ( value ) != 'object' || value == null ) {
					value = {};
				}
				value.i = index;
				$list.append( Item( value ) );
			} );
		}


		/**
			Добавляем новую запись
		*/
		function AddItem( event ) {
			event.preventDefault();
			var data = GetData( event.data.id );
			data.push( {} );
			SetData( event.data.id, data );
			Build( data, event.data.Item, event.data.$list );
		}


		/**
			Открывает модальное окно для редактирования элемента формы
		*/
		function FormToggle( event ) {
			event.preventDefault();
			if ( event.data.$modal.hasClass( 'hide' ) ) {
				
				var $item = jQuery( event.target ).closest( '.item' );
				var data = GetData( event.data.id );
				var index = $item.closest( 'ol' ).find( 'li' ).not( '.ui-sortable-placeholder' ).index( $item );
				data[ index ].i = index;
				event.data.$modal.empty();
				event.data.$modal.append( jQuery( '<div />' ).addClass( 'bg' ) );
				event.data.$modal.append( event.data.Form( data[ index ] ) );
				event.data.$modal.removeClass( 'hide' );
				event.data.$modal.find( 'textarea.editor' ).each( WPEditorInit );
				event.data.$modal.find( 'select' ).each( function ( index, select ) { SelectInit( select, data ); } );
				event.data.$modal.find( '.image' ).each( ImageInit );
				event.data.$modal.find( '.gallery' ).each( GalleryInit );
				event.data.$modal.find( 'input.colorpicker[name]' ).each( ColorPickerInit );
				event.data.$modal.find( 'input.datepicker[name]' ).each( DatePickerInit );
			} else {
				event.data.$modal.addClass( 'hide' );
				event.data.$modal.find( 'textarea.editor' ).each( WPEditorRemove );
				event.data.$modal.empty();
			}
		}


		function DatePickerInit( index, input ) {
			var $input = jQuery( input );
			$input.datepicker( {
				dateFormat : $input.is( '[data-format]' ) ? $input.attr( 'data-format' ) : 'yy-mm-dd',
				onSelect: function( dateText) {
					$input.trigger( 'keyup' );
				}
			} );
		}


		function ColorPickerInit( index, input ) {
			jQuery( input ).wpColorPicker( {
				change: function( event, ui ) {
					jQuery( input ).trigger( 'keyup' );
				},
			} );
		}


		function GetGalleryData( gallery ) {
			try {
				return JSON.parse( StripSlashes( jQuery( gallery ).find( '[name]' ).val() ) );
			} catch ( e ) {
				return [];
			}
		}


		function SetGalleryData( gallery, galleryData ) {
			var $control = jQuery( gallery ).find( '[name]' );
			$control.val( JSON.stringify( galleryData ) );
			$control.trigger( 'change' );
		}


		function GalleryInit( index, gallery ) {
			var $gallery = jQuery( gallery );
			var $container = jQuery( '<div />' ).addClass( 'container' );
			var galleryData = GetGalleryData( gallery );
			$gallery.find( '.container' ).remove();
			$container.appendTo( $gallery );
			if ( Array.isArray( galleryData ) ) {
				jQuery( galleryData ).each( function ( i, imageData ) {
					jQuery( '<img />', {
						'src': imageData.url,
						'data-item-index': i,
					} ).appendTo( $container );
				} );
				if ( galleryData.length > 0 ) {
					jQuery( '<button />' ).addClass( 'clear' ).attr( 'type', 'button' ).text( __( 'Очистить галерею', textdomain ) ).appendTo( $gallery );
				}
				$container.sortable( {
					stop: function ( event, ui ) {
						var galleryData = GetGalleryData( gallery );
						var oldPosition = jQuery( ui.item ).attr( 'data-item-index' );
						var newPosition = jQuery( ui.item ).closest( '.container' ).find( 'img' ).not( '.ui-sortable-placeholder' ).index( jQuery( ui.item ) )

						if ( oldPosition != newPosition ) {
							galleryData[ newPosition ] = galleryData.splice( oldPosition, 1, galleryData[ newPosition ] )[ 0 ];
							SetGalleryData( gallery, galleryData );
						}
					},
					start: function ( event, ui ) {
						jQuery( ui.item ).closest( '.container' ).find( 'img' ).not( '.ui-sortable-placeholder' ).each( function ( index, img ) {
							jQuery( img ).attr( 'data-item-index', index );
						} );
					},
				} );
			}
		}


		function ImageInit( index, image ) {
			var $image = jQuery( image );
			var value = $image.find( '[name]' ).val();
			var imageData;
			try {
				imageData = value.length ? JSON.parse( StripSlashes( value ) ) : {};
			} catch ( error ) {
				console.log( error );
				imageData = false;
			}
			$image.find( 'img' ).attr( 'src', ( imageData && typeof ( imageData.url ) !== 'undefined' ) ? imageData.url : '' );
		}


		/**
			Получает записи и устанавливает значение для select
		*/
		function SelectInit( select, data ) {
			var $select = jQuery( select );
			var index = $select.closest( 'form' ).attr( 'data-item-index' );
			var name = $select.attr( 'name' );
			if ( $select.is( '[data-post-type]' ) ) {
				jQuery.ajax( {
					async: false,
					type: 'GET',
					url: settings[ 'restUrl' ] + 'wp/v2/' + $select.attr( 'data-post-type' ) + '/',
					error: function ( jqXHR, textStatus, errorThrown ) {
						console.log( textStatus );
						if ( $select.firstChild ) {
							$select.hide( 0 );
						}
					},
					success: function ( result, textStatus, jqXHR ) {
						try {
							result.forEach( function ( entry ) {
								$select.append( jQuery( '<option>', {
									value: entry.id,
									text: entry.title.rendered,
								} ) );
							} );
						} catch ( error ) {
							console.log( error );
							$select.hide( 0 );
						}
					}
				} );
			}
			if ( data[ index ][ name ] instanceof Array ) {
				data[ index ][ name ].forEach( function ( value ) {
					$select.find( '[value=' + id + ']' ).attr( 'selected', 'selected' );
				} );
			} else {
				$select.val( data[ index ][ name ] );
			}
			$select.chosen();
		}


		/**
			Устанавливает автовысоту многострочного текстового поля при его редактировании
		*/
		function AutoSizeTextareaHeight( event ) {
			if ( ! event.target.classList.contains( 'editor' ) ) {
				if ( ( event.target.scrollHeight - 10 ) > event.target.clientHeight ) {
					event.target.style.cssText = 'height:auto; overflow-y: hidden;';
					event.target.style.cssText = 'height:' + ( event.target.scrollHeight + 10 ) + 'px';
				}
			}
		}


		/**
			Инициализирует редактор
		*/
		function WPEditorInit( index, textarea ) {
			wp.editor.initialize( textarea.id, {
				tinymce: {
					wpautop: false,
					tadv_noautop: false,
					verify_html: true,
					cleanup_on_startup: false,
					cleanup: false,
					validate_children: false,
				},
				quicktags: true,
				mediaButtons: true,
			} );
		}


		/**
			Удаляет редактор
		*/
		function WPEditorRemove( index, textarea ) {
			wp.editor.remove( textarea.id );
		}


		function WPEditorTriggerChange( event, editor ) {
			editor.on( 'change', function( e ) {
				jQuery( '#' + editor.id ).val( editor.getContent( { format: 'raw' } ) ).trigger( 'change' );
			} );
		}


		/**
			Удаляет запись
		*/
		function RemoveItem( event ) {
			event.preventDefault();
			if ( confirm( __( 'Вы уверены?', textdomain ) ) ) {
				var data = GetData( event.data.id );
				var index = jQuery( event.target ).closest( '.form' ).attr( 'data-item-index' );
				data.splice( index, 1 );
				SetData( event.data.id, data );
				event.data.$list.find( '.item' ).eq( index ).remove();
				event.data.$modal.addClass( 'hide' );
				event.data.$modal.empty();
				Build( data, event.data.Item, event.data.$list );
			}
		}


		/**
			Сохраняет запись
		*/
		function SaveItem( event ) {
			event.preventDefault();
			var data = GetData( event.data.id );
			var $form = jQuery( event.target ).closest( '.form' );
			var index = $form.attr( 'data-item-index' );
			data[ index ] = {};
			$form.serializeArray().map( function( item ) {
				var value;
				if ( item.value.length ) {
					try {
						value = JSON.parse( StripSlashes( item.value ) );
					} catch ( error ) {
						console.log( error );
						value = item.value;
					}
				} else {
					value = item.value;
				}
				if ( item.name.match( /\[\]$/ ) ) {
					item.name = item.name.replace( /\[\]$/, '' );
					if ( typeof ( data[ index ][ item.name ] ) == 'undefined' ) {
						data[ index ][ item.name ] = [];
					}
					data[ index ][ item.name ].push( value );
				} else {
					data[ index ][ item.name ] = value;
				}
			} );
			SetData( event.data.id, data );
			Build( data, event.data.Item, event.data.$list );
		}


		/**
			Действие при выборе одного изображения в медиагалереи
		*/
		function SelectImageForSingleImage( event ) {
			var selectedImages = MediaLibraryForSingleImage.state().get( 'selection' ).toJSON();
			var $control = MediaLibraryForSingleImage.targetListItemImageObject.find( '[name]' );
			var $image = MediaLibraryForSingleImage.targetListItemImageObject.find( 'img' );
			if ( typeof( selectedImages[ 0 ].sizes.full.url ) != "undefined" && selectedImages[ 0 ].sizes.full.url !== null ) {
				$control.val( JSON.stringify( {
					id: selectedImages[ 0 ].id,
					url: selectedImages[ 0 ].sizes.full.url,
				} ) );
				$image.attr( 'src', selectedImages[ 0 ].sizes.full.url );
			} else {
				$control.val( '' );
				$image.attr( 'src', '' );
			}
			MediaLibraryForSingleImage.targetListItemImageObject = null;
			$control.trigger( 'change' );
		}


		/**
			Действие при выборе галереи изображений
		*/
		function SelectImageForGallery( event ) {
			var selectedImages = MediaLibraryForGallery.state().get( 'selection' ).toJSON();
			var galleryData = [];
			MediaLibraryForGallery.targetListItemGalleryObject.find( 'img' ).remove();
			selectedImages.map( function ( imageData ) {
				if ( typeof( imageData.sizes.full.url ) != "undefined" && imageData.sizes.full.url !== null ) {
					galleryData.push( {
						id: imageData.id,
						url: imageData.sizes.full.url,
					} );
				}
			} );
			SetGalleryData( MediaLibraryForGallery.targetListItemGalleryObject, galleryData );
			GalleryInit( null, MediaLibraryForGallery.targetListItemGalleryObject );
			MediaLibraryForGallery.targetListItemGalleryObject = null;
		}


		/**
			Добавляет уже выбранное изображение в окно загрузчика
		*/
		function AddSelectedItemForSingleImage() {
			try {
				var attachment;
				var value = MediaLibraryForSingleImage.targetListItemImageObject.find( '[name]' ).val();
				var imageData = value.length > 0 ? JSON.parse( StripSlashes( value ) ) : {};
				if ( typeof ( imageData.id ) != 'undefined' ) {
					attachment = wp.media.attachment( imageData.id );
					MediaLibraryForSingleImage.state().get( 'selection' ).add( attachment );
				}
			} catch ( error ) {
				console.log( error );
			}
		}


		/**
			Добавляет уже выбранные изображения в окно загрузчика галереи
		*/
		function AddSelectedItemForGallery() {
			var attachments = [];
			var galleryData = [];
			try {
				galleryData = JSON.parse( StripSlashes( MediaLibraryForGallery.targetListItemGalleryObject.find( '[name]' ).val() ) );
				if ( Array.isArray( galleryData ) ) {
					galleryData.map( function ( imageData ) {
						if ( typeof ( imageData.id ) != 'undefined' ) {
							attachments.push( wp.media.attachment( imageData.id ) );
						}
					} );
				}
				if ( attachments.length > 0 ) {
					MediaLibraryForGallery.state().get( 'selection' ).add( attachments );
				}
			} catch ( error ) {
				console.log( error );
			}
		}


		/**
			Открывает окно загрузчика изображений
		*/
		function OpenMediaLibraryForSingleImage( event ) {
			event.preventDefault();
			if ( typeof ( MediaLibraryForSingleImage.targetListItemImageObject ) == 'undefined' ) {
				Object.assign( MediaLibraryForSingleImage, { targetListItemImageObject: null } );
			}
			MediaLibraryForSingleImage.targetListItemImageObject = jQuery( event.target ).closest( '.image' );
			MediaLibraryForSingleImage.open();
		}


		/**
			Открывает окно загрузчика изображений галереи
		*/
		function OpenMediaLibraryGallery( event ) {
			event.preventDefault();
			if ( typeof ( MediaLibraryForGallery.targetListItemGalleryObject ) == 'undefined' ) {
				Object.assign( MediaLibraryForGallery, { targetListItemGalleryObject: null } );
			}
			MediaLibraryForGallery.targetListItemGalleryObject = jQuery( event.target ).closest( '.gallery' );
			MediaLibraryForGallery.open();
		}


		/**
			Очищает элемент формы с выбранным изрображением
		*/
		function ClearMediaForSingleImage( event ) {
			var $image = jQuery( event.target ).closest( '.image' );
			$image.find( '[name]' ).val( '' ).trigger( 'change' );
			$image.find( 'img' ).attr( 'src', '' );
			MediaLibraryForSingleImage.targetListItemImageObject = null;
		}


		function ClearGallery( event ) {
			var $button = jQuery( event.target );
			var $gallery = $button.closest( '.gallery' );
			if ( confirm( __( 'Вы уверены?', textdomain ) ) ) {
				$gallery.find( '[name]' ).val( '' ).trigger( 'change' );
				$gallery.find( '.container' ).remove();
				$button.remove();
			}
		}


		/**
			Запускает всё
		*/
		return this.each( function( index, element ) {
			var id = jQuery( element ).attr( 'id' ).replace( /customize-control-/i, '' );
			var $list = jQuery( '<ol / >' ).addClass( 'list' ).appendTo( element );
			var $modal = jQuery( '<div />' ).attr( 'id', 'modal-' + id ).addClass( 'customize-control-list-modal' ).addClass( 'hide' ).appendTo( 'body' );
			var $buttonAdd = jQuery( '<button />', {
				class: 'add button-primary dashicons-plus dashicons-before',
				type: 'button',
				text: __( 'Добавить', textdomain ),
			} ).appendTo( element );
			var Item = wp.template( id + '-item' );
			var Form = wp.template( id + '-form' );
			var params = { id: id, Item: Item, $list: $list, Form: Form, $modal: $modal };
			Build( GetData( id ), Item, $list );
			$buttonAdd.on( 'click', params, AddItem );
			$list.sortable( {
				stop: function ( event, ui ) {
					var data = GetData( id );
					var oldPosition = jQuery( ui.item ).attr( 'data-item-index' );
					var newPosition = jQuery( ui.item ).closest( '.list' ).find( '.item' ).not( '.ui-sortable-placeholder' ).index( jQuery( ui.item ) )
					if ( oldPosition != newPosition ) {
						data[ newPosition ] = data.splice( oldPosition, 1, data[ newPosition ] )[ 0 ];
						SetData( id, data );
					}
				},
				start: function ( event, ui ) {
					jQuery( ui.item ).closest( 'ol' ).find( 'li' ).not( '.ui-sortable-placeholder' ).each( function ( index, item ) {
						jQuery( item ).attr( 'data-item-index', index );
					} );
				},
			} );
			$list.on( 'click', 'button.toggle', params, FormToggle );
			$modal.on( 'click', 'button.close', params, FormToggle );
			$modal.on( 'click', '.bg', params, FormToggle );
			$modal.on( 'click', '[data-item-index] button.remove', params, RemoveItem );
			MediaLibraryForSingleImage.on( 'select', SelectImageForSingleImage );
			MediaLibraryForSingleImage.on( 'open', AddSelectedItemForSingleImage );
			$modal.on( 'click', '.form .image button.choice', params, OpenMediaLibraryForSingleImage );
			$modal.on( 'click', '.form .image img', params, OpenMediaLibraryForSingleImage );
			$modal.on( 'click', '.form .image button.clear', params, ClearMediaForSingleImage );

			$modal.on( 'change', '.form select[name]', params, SaveItem );
			$modal.on( 'change', '.form [name][type=checkbox]', params, SaveItem );
			$modal.on( 'change', '.form [name][type=radio]', params, SaveItem );
			$modal.on( 'change', '.form [name][type=hidden]', params, SaveItem );
			$modal.on( 'change', '.form textarea[name]', params, SaveItem );
			$modal.on( 'keyup', '.form [name][type=text]', params, SaveItem );
			$modal.on( 'keyup', '.form [name][type=number]', params, SaveItem );
			$modal.on( 'keyup', '.form [name][type=url]', params, SaveItem );
			$modal.on( 'keyup', '.form [name][type=tel]', params, SaveItem );
			$modal.on( 'keyup', '.form [name][type=password]', params, SaveItem );
			$modal.on( 'keyup', '.form [name][type=email]', params, SaveItem );

			$modal.on( 'keydown', '.form textarea', {}, AutoSizeTextareaHeight );
			jQuery( document ).on( 'tinymce-editor-setup', WPEditorTriggerChange );
			MediaLibraryForGallery.on( 'select', SelectImageForGallery );
			MediaLibraryForGallery.on( 'open', AddSelectedItemForGallery );
			$modal.on( 'click', '.form .gallery button.choice', params, OpenMediaLibraryGallery );
			$modal.on( 'click', '.form .gallery button.clear', params, ClearGallery );
		} );


	};


} )( jQuery );