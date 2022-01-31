( function () {

	let $sliders = jQuery( '#offers .slider-content' );
	let $heading = jQuery( '#offers .heading' );

	function Init( index, slider ) {
		jQuery( slider ).slick( {
			infinite: true,
			slidesToShow: 2,
			slidesToScroll: 1,
			dots: false,
			prevArrow: '#' + jQuery( slider ).attr( 'id' ) + '-prev',
			nextArrow: '#' + jQuery( slider ).attr( 'id' ) + '-next',
			responsive: [
				{
					breakpoint: 960,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			],
		} );
	}

	function Toggle( event ) {
		event.preventDefault();
		console.log( 'click' );
		let $button = jQuery( event.target );
		let $tab = jQuery( $button.attr( 'href' ) );
		$button.siblings().removeClass( 'active' );
		$button.addClass( 'active' );
		$tab.siblings().addClass( 'hide' );
		$tab.removeClass( 'hide' );
	}

	$sliders.each( Init );
	$heading.on( 'click', 'a[href^="#"]', Toggle );

} )();