function cbs_slider() {
	var initialslide = 7;
	if ( jQuery( '#initial-slide' ).length ) {
		initialSlide = jQuery( '#initial-slide' ).data( 'id' );
	} else {
		initialSlide = 0;
	}

	jQuery( '#course .slider' ).slick({
		dots: true,
		infinite: false,
		speed: 300,
		slidesToShow: 4,
		// slidesToScroll: 4,
		initialSlide: initialSlide,
		prevArrow: '<button type="button" class="slick-prev">‹</button>',
		nextArrow: '<button type="button" class="slick-next">›</button>',
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					// slidesToScroll: 3,
				}
			}, {
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					// slidesToScroll: 2
				}
			}, {
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					// slidesToScroll: 1
				}
			}
	    ]
	});
}

jQuery(document).ready(function() {
	cbs_slider();

	jQuery( document ).on( 'blur', '.livesearch-input', function( e ) {
		jQuery( this ).hide();
	});
});