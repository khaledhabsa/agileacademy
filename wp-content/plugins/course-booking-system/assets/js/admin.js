// Single: Add Timeslot
jQuery( document ).ready( function() {

	jQuery( document ).on( 'click', '.add-timeslot', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		let post_id = parseInt( jQuery( '#post_ID' ).val() );
		let id = jQuery( 'input[name^=id]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let day = jQuery( 'select[name^=day]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let date = jQuery( 'input[name^=date]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let start = jQuery( 'input[name^=start]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let end = jQuery( 'input[name^=end]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let user_id = jQuery( 'select[name^=user_id]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_add_timetable',
				post_id: post_id,
				id: id,
				day: day,
				date: date,
				start: start,
				end: end,
				user_id: user_id
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#ajax-admin' ).html( data );
				jQuery( '#ajax-loader' ).hide();
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});

	jQuery( document ).on( 'click', '.delete-timeslot', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		let delete_id = parseInt( jQuery( this ).parent().parent().find( 'input.id' ).val() );
		let post_id = parseInt( jQuery( '#post_ID' ).val() );
		let id = jQuery( 'input[name^=id]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let day = jQuery( 'select[name^=day]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let date = jQuery( 'input[name^=date]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let start = jQuery( 'input[name^=start]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let end = jQuery( 'input[name^=end]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();
		let user_id = jQuery( 'select[name^=user_id]' ).map( function( index, element ) { return jQuery( element ).val(); }).get();

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_delete_timetable',
				delete_id: delete_id,
				post_id: post_id,
				id: id,
				day: day,
				date: date,
				start: start,
				end: end,
				user_id: user_id
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#ajax-admin' ).html( data );
				jQuery( '#ajax-loader' ).hide();
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});
});

// Settings: Automatic course cancellation
jQuery( '#course_booking_system_auto_cancel' ).change(function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-auto_cancel_number' ).show();
		jQuery( '#tr-auto_cancel_advance' ).show();
	} else {
		jQuery( '#tr-auto_cancel_number' ).hide();
		jQuery( '#tr-auto_cancel_advance' ).hide();
	}
});

// Settings: Referral
jQuery( '#course_booking_system_woocommerce_referral' ).change(function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-referral_price_level' ).show();
	} else {
		jQuery( '#tr-referral_price_level' ).hide();
	}
});

// Settings: Email cancel address
jQuery( '#course_booking_system_email_cancel' ).change(function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-email_cancel_address' ).show();
	} else {
		jQuery( '#tr-email_cancel_address' ).hide();
	}
});

// Settings: Email waitlist address
jQuery( '#course_booking_system_email_waitlist' ).change(function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-email_waitlist_address' ).show();
	} else {
		jQuery( '#tr-email_waitlist_address' ).hide();
	}
});

// Settings: Email expiry
jQuery( '#course_booking_system_email_expire' ).change(function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#div-email_expire' ).show();
	} else {
		jQuery( '#div-email_expire' ).hide();
	}
});

// Settings: Disable autofocus on inputs and textareas
jQuery( 'body.settings_page_course_booking_system input.regular-text' ).trigger( 'blur' );
jQuery( 'body.settings_page_course_booking_system textarea.large-text' ).trigger( 'blur' );

// Single: Output of input[type="range"]
if ( jQuery( '#price_level' ).length == 1 ) {
	var slider = document.getElementById( 'price_level' );
	var output = document.getElementById( 'output' );
	output.innerHTML = slider.value;

	slider.oninput = function() {
		output.innerHTML = this.value;
	}
}

// User: Card
Date.prototype.yyyymmdd = function() {
	var yyyy = this.getFullYear().toString();
	var mm = ( this.getMonth() + 1 ).toString();
	var dd = this.getDate().toString();
	return yyyy + '-' + ( mm[1] ? mm : '0' + mm[0] ) + '-' + ( dd[1] ? dd : '0' + dd[0] ); // padding
};

jQuery( '#card' ).change( function() {
	var date = new Date();
	var selectedDate = new Date( jQuery( '#expire' ).val() );
	if ( !jQuery( '#expire' ).val() || selectedDate < date ) {
		date.setMonth( date.getMonth() + 1 );
		jQuery( '#expire' ).val( date.yyyymmdd() );
	}
});

jQuery( '#card_2' ).change( function() {
	var date = new Date();
	var selectedDate = new Date( jQuery( '#expire_2' ).val() );
	if ( !jQuery( '#expire_2' ).val() || selectedDate < date ) {
		date.setMonth( date.getMonth() + 1 );
		jQuery( '#expire_2' ).val( date.yyyymmdd() );
	}
});

jQuery( '#card_3' ).change( function() {
	var date = new Date();
	var selectedDate = new Date( jQuery( '#expire_3' ).val() );
	if ( !jQuery( '#expire_3' ).val() || selectedDate < date ) {
		date.setMonth( date.getMonth() + 1 );
		jQuery( '#expire_3' ).val( date.yyyymmdd() );
	}
});

jQuery( '#card_4' ).change( function() {
	var date = new Date();
	var selectedDate = new Date( jQuery( '#expire_4' ).val() );
	if ( !jQuery( '#expire_4' ).val() || selectedDate < date ) {
		date.setMonth( date.getMonth() + 1 );
		jQuery( '#expire_4' ).val( date.yyyymmdd() );
	}
});

jQuery( '#card_5' ).change( function() {
	var date = new Date();
	var selectedDate = new Date( jQuery( '#expire_5' ).val() );
	if ( !jQuery( '#expire_5' ).val() || selectedDate < date ) {
		date.setMonth( date.getMonth() + 1 );
		jQuery( '#expire_5' ).val( date.yyyymmdd() );
	}
});

// User: Subscription
jQuery( '#abo' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-abo_course' ).show();
	} else {
		jQuery( '#tr-abo_course' ).hide();
	}
});

jQuery( '#abo_2' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-abo_course_2' ).show();
	} else {
		jQuery( '#tr-abo_course_2' ).hide();
		jQuery( '#abo_course_2 option:selected' ).removeAttr('selected');
	}
});

jQuery( '#abo_3' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-abo_course_3' ).show();
	} else {
		jQuery( '#tr-abo_course_3' ).hide();
		jQuery( '#abo_course_3 option:selected' ).removeAttr('selected');
	}
});

// User: Flatrate
jQuery( '#flat' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-flat_expire' ).show();
	} else {
		jQuery( '#tr-flat_expire' ).hide();
	}
});

jQuery( '#flat_2' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-flat_expire_2' ).show();
	} else {
		jQuery( '#tr-flat_expire_2' ).hide();
	}
});

jQuery( '#flat_3' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-flat_expire_3' ).show();
	} else {
		jQuery( '#tr-flat_expire_3' ).hide();
	}
});

jQuery( '#flat_4' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-flat_expire_4' ).show();
	} else {
		jQuery( '#tr-flat_expire_4' ).hide();
	}
});

jQuery( '#flat_5' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#tr-flat_expire_5' ).show();
	} else {
		jQuery( '#tr-flat_expire_5' ).hide();
	}
});

// User: Logs
jQuery( '#cbs-logs' ).click( function( e ) {
	e.preventDefault();

	jQuery( this ).hide();
	jQuery( 'h2.logs-headline' ).show();
	jQuery( 'table.logs-table' ).show();
});

// Single: Hide price level for free courses
jQuery( '#free' ).change( function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '#price_level_container' ).hide();
	} else {
		jQuery( '#price_level_container' ).show();
	}
});