( function () {

	var $mobilenav = jQuery( '#mobilenav' );
	var $body = jQuery( 'body' );

	function Toggle( event ) {
		event.preventDefault();
		if ( $mobilenav.hasClass( 'active' ) ) {
			$body.css( 'overflow', 'auto' );
			$mobilenav.removeClass( 'active' );
		} else {
			$body.css( 'overflow', 'hidden' );
			$mobilenav.addClass( 'active' );
		}
	}

	$body.on( 'click', '[data-mobilenav=toggle]', Toggle );

} )();