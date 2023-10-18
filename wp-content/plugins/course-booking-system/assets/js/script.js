jQuery( document ).ready( function() {
	// WooCommerce Account
	jQuery( document ).on( 'click', '.woocommerce-MyAccount-navigation-link--delete a', function( e ) {
		return confirm( 'Are you sure you want to delete the account?' );
	});

	// Livesearch
	const livesearch = jQuery( '#livesearch' );
	const livesearch_waitlist = jQuery( '#livesearch-waitlist' );
	jQuery( document ).on( 'focus click', '.livesearch-input, .livesearch-input-waitlist',  function( e ) {
		let element = livesearch;
		if ( jQuery( this ).hasClass( 'livesearch-input-waitlist' ) )
			element = livesearch_waitlist;

		let offset = jQuery( this ).offset();
		let height = jQuery( this ).height();

		element.show();
		element.css( 'top', (offset.top + height)+'px' );
		element.css( 'left', offset.left+'px' );

		element.children().show();
		element.children().removeClass( 'selected' );
	});

	/* jQuery( '.livesearch-input' ).blur( function( e ) {
		livesearch.hide();
		livesearch_waitlist.hide();
	}); */

	jQuery( document ).on( 'click', '.livesearch > li > a',  function( e ) {
		livesearch.hide();
		livesearch_waitlist.hide();
	});

	let selected = 0;
	jQuery( document ).on( 'focus click keyup', '.livesearch-input, .livesearch-input-waitlist', function( e ) {
		let element = livesearch;
		if ( jQuery( this ).hasClass( 'livesearch-input-waitlist' ) )
			element = livesearch_waitlist;

		let search = jQuery( this ).val();
		let course_id = jQuery( this ).data( 'id' );
		let date = jQuery( this ).data( 'date' );

		let visible = [0];
		element.children().each( function() {
			jQuery( this ).hide();
			if ( jQuery( this ).text().search( new RegExp( search, 'i' ) ) >= 0 || jQuery( this ).hasClass( 'user-new' ) ) {
				jQuery( this ).show();

				let user_id = jQuery( this ).data( 'user' );
				visible.push( user_id );
			}

			jQuery( this ).children().attr( 'data-id', course_id );
			jQuery( this ).children().attr( 'data-date', date );
		});

		if ( e.keyCode == '38' ) { // Arrow key up
			if ( selected > 0 )
				selected--;

			element.children().removeClass( 'selected' );
			element.find( '.user-'+visible[selected] ).addClass( 'selected' )
		} else if ( e.keyCode == '40' ) { // Arrow key down
			if ( selected == 0 || selected == jQuery( '.livesearch > li:visible' ).length ) {
				selected = 1;
			} else {
				selected++;
			}
			element.children().removeClass( 'selected' );
			element.find( '.user-'+visible[selected] ).addClass( 'selected' )
		} else if ( e.key === 'Enter' && jQuery( '.livesearch > li.selected' ).length == 1 ) {
			jQuery( '.livesearch > li.selected' ).children().trigger( 'click' );
		}
	});
});