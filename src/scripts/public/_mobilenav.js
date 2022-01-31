( function () {

	let $menu = jQuery( '#menu' );
	let $burger = jQuery( '#burger' );

	function Toggle( event ) {
		event.preventDefault();
		if ( $burger.hasClass( 'active' ) ) {
			$menu.removeClass( 'active' );
			$burger.removeClass( 'active' );
		} else {
			$menu.addClass( 'active' );
			$burger.addClass( 'active' );
		}
	}

	$burger.on( 'click', Toggle );

} )();