( function () {

	let $sliders = jQuery( '#reviews .slider-content' );

	function Init ( index, slider ) {
		jQuery( slider ).slick( {
			dots: false,
			fade: false,
			autoplay: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			prevArrow: '#reviews-prev',
			nextArrow: '#reviews-next',
		} );
	}

	$sliders.each( Init );

} )();