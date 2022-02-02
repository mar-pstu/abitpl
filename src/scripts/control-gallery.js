( function( $ ) {


	$.fn.WPCustomizeControlGallery = function( args ) {


		const { __, _x, _n, _nx } = wp.i18n;
	

		const textdomain = 'WPCustomizeControlGallery';


		const MediaLibrary = window.wp.media( {
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


		/**
			Действие при выборе галереи изображений
		*/
		function SelectImages( event ) {
			var selectedImages = MediaLibrary.state().get( 'selection' ).toJSON();
			var images = [];
			selectedImages.map( function ( imageData ) {
				if ( typeof( imageData.id ) != 'undefined' && Number.isInteger( imageData.id ) ) {
					images.push( imageData.id );
				}
			} );
			SetValue( MediaLibrary.galleRyparamS, images );
			Build( MediaLibrary.galleRyparamS );
			MediaLibrary.galleRyparamS = null;
		}


		/**
			Добавляет уже выбранные изображения в окно загрузчика галереи
		*/
		function AddSelectedImages() {
			var attachments = [];
			var galleryData = GetData( MediaLibrary.galleRyparamS.id );
			if ( Array.isArray( galleryData ) ) {
				galleryData.map( function ( id ) {
					attachments.push( wp.media.attachment( id ) );
				} );
			}
			if ( attachments.length > 0 ) {
				MediaLibrary.state().get( 'selection' ).add( attachments );
			}
		}


		/**
		 * 
		 * */
		function SetValue( galleryParams, images ) {
			wp.customize.value( galleryParams.id ).set( images );
		}


		/**
			Открывает окно загрузчика изображений галереи
		*/
		function OpenMediaLibrary( event ) {
			event.preventDefault();
			if ( typeof ( MediaLibrary.galleRyparamS ) == 'undefined' ) {
				Object.assign( MediaLibrary, { galleRyparamS: null } );
			}
			MediaLibrary.galleRyparamS = event.data;
			MediaLibrary.open();
		}


		function GetData( id ) {
			try {
				return wp.customize.value( id ).get();
			} catch ( error ) {
				return [];
			}
		}


		function Build( galleryParams ) {
			var request = {
				action: 'thumbnails_data',
				images: GetData( galleryParams.id ),
			};
			galleryParams.$container.empty();
			jQuery.post( ajaxurl, request, function( response ) {
				if ( typeof ( response.success ) && response.success && typeof ( response.data ) && Array.isArray( response.data ) ) {
					jQuery( response.data ).each( function ( i, image ) {
						if ( typeof ( image.id ) != 'undefined' && typeof ( image.url ) != 'undefined' ) {
							jQuery( '<img />', {
								'src': image.url,
								'data-image-id': image.id,
							} ).appendTo( galleryParams.$container );
						}
					} );
					galleryParams.$container.sortable( {
						out: function ( event, ui ) {
							var images = [];
							// console.log( galleryParams.$container.find( 'img' ).not( '.ui-sortable-placeholder' ) );
							galleryParams.$container.find( 'img' ).not( '.ui-sortable-placeholder' ).each( function ( index, img ) {
								images.push( jQuery( img ).attr( 'data-image-id' ) );
							} );
							SetValue( galleryParams, images );
						},
					} );
				} else {
					console.log( response );
				}
			} );
		}


		/**
			Запускает всё
		*/
		return this.each( function( index, element ) {
			var id = jQuery( element ).attr( 'id' ).replace( /customize-control-/i, '' );
			var $container = jQuery( '<div / >' ).addClass( 'container' ).appendTo( element );
			var $buttonEdit = jQuery( '<button />', {
				class: 'choice dashicons-format-gallery dashicons-before',
				type: 'button',
				title: __( 'Редактировать', textdomain ),
			} ).appendTo( element );
			var params = { id: id, $container: $container, $buttonEdit: $buttonEdit };
			Build( params );
			$buttonEdit.on( 'click', params, OpenMediaLibrary );
			MediaLibrary.on( 'select', SelectImages );
			MediaLibrary.on( 'open', AddSelectedImages );
		} );


	};


} )( jQuery );