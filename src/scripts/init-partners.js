( function () {

	let $sliders = jQuery( '#partners .slider-content' );
	let content = [];

	function Init( index, slider ) {
		if ( ! jQuery( slider ).hasClass( 'slick-initialized' ) ) {
			content[ index ] = jQuery( slider ).clone();
			jQuery( slider ).slick( {
				infinite: true,
				dots: false,
				slidesToShow: 2,
				slidesToScroll: 1,
				prevArrow: '#' + jQuery( slider ).attr( 'id' ) + '-prev',
				nextArrow: '#' + jQuery( slider ).attr( 'id' ) + '-next',
				responsive: [
					{
						breakpoint: 540,
						settings: {
							slidesToShow: 1,
						}
					},
				],
			} );
		}
	}

	function Destroy( index, slider ) {
		if ( jQuery( slider ).hasClass( 'slick-initialized' ) ) {
			jQuery( slider ).slick( 'unslick' );
			if ( ! typeof content[ index ] == 'undefined' ) {
				jQuery( slider ).replaceWith( content[ index ] );
			}
		}
	}

	function Toggle( event ) {
		if ( jQuery( window ).width() > 720 ) {
			$sliders.each( Destroy );
		} else {
			$sliders.each( Init );
		}
	}

	jQuery( window ).resize( Toggle );
	jQuery( document ).ready( Toggle );

} )();