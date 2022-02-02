( function( $ ) {


	$.fn.WPCustomizeTinymceEditor = function( args ) {


		/**
			Запускает всё
		*/
		return this.each( function( index, element ) {
			var id = jQuery( element ).find( 'textarea' ).attr( 'id' ).replace( /customize-control-/i, '' );
			wp.editor.initialize( id, {
				tinymce: {
					wpautop: false,
					tadv_noautop: false,
					verify_html: true,
					cleanup_on_startup: false,
					cleanup: false,
					validate_children: false,
					setup: function( editor ) {
						editor.on( 'init', function( e ) {
							editor.setContent( wp.customize.value( id ).get() );
						} );
						editor.on( 'change', function( e ) {
							wp.customize.value( id ).set( editor.getContent() );
						} );
				  	}
				},
				quicktags: true,
				mediaButtons: true,
			} );
		} );


	};


} )( jQuery );