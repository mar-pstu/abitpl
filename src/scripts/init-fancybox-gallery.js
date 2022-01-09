jQuery( document ).ready( function ($) {

	jQuery( '.fancybox' ).fancybox();

	if ( jQuery( '.wp-block-gallery' ) ) {

		jQuery( '.blocks-gallery-item' ).click( function() {

			var galleryImages = $(this).parent().find( 'a' );
			var gallery = [];

			galleryImages.each(function( index, galleryItem ) {

				var caption = $(this).parent().find('figcaption') ?  $(this).find('img').attr('alt') : $(this).parent().find('figcaption')  ;

				gallery.push( {
					src : galleryItem.href,
					opts : {
						caption: caption
					}
				})
			} );

			$.fancybox.open( gallery, { loop: false }, $(this).index() );

			return false;
		} );
	}

} );
