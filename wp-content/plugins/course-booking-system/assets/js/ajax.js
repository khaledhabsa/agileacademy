// AJAX
function cbs_action_course() {
	if ( jQuery( '#course' ).length == 1 ) { // #course exists only on single pages
		jQuery( '#course-loader' ).show();

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_course',

				course_id: course_id,
				date: date,
				user_id: user_id
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#course' ).html( data );
				jQuery( '#course-loader' ).hide();

				cbs_slider();

				var offset = jQuery( '#ajax' ).offset();
				jQuery( 'body, html' ).animate({
					scrollTop: offset.top - 50 - course_booking_system_ajax.offset
				}, 300);
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#course-loader' ).hide();
			}
		});
	} else if ( jQuery( '#account' ).length == 1 ) { // #account exists only on account pages
		if ( typeof booking_id != 'undefined' ) {
			jQuery( '#booking-id-'+booking_id ).hide();
		} else if ( typeof waitlist_id != 'undefined' ) {
			jQuery( '#waitlist-id-'+waitlist_id ).hide();
		} else if ( typeof date != 'undefined' ) {
			jQuery( '#abo-date-'+date ).hide();
		}

		jQuery( '#account-loader' ).show();

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_account'
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#account' ).html( data );
				jQuery( '#account-loader' ).hide();

				var offset = jQuery( '#ajax' ).offset();
				jQuery( 'body, html' ).animate({
					scrollTop: offset.top - 50 - course_booking_system_ajax.offset
				}, 300);
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#account-loader' ).hide();
			}
		});
	} else if ( jQuery( '#event' ).length == 1 ) { // #event with id attribute exists only on column pages (weekdays and special dates)
		jQuery( '#event-loader' ).show();

		id = jQuery( '#event' ).data( 'id' );
		date = jQuery( '#date' ).val();

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_event',

				id: id,
				date: date
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#event' ).html( data );
				jQuery( '#event-loader' ).hide();

				var offset = jQuery( '#ajax' ).offset();
				jQuery( 'body, html' ).animate({
					scrollTop: offset.top - 50 - course_booking_system_ajax.offset
				}, 300);
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#event-loader' ).hide();
			}
		});
	} else {
		if ( typeof booking_id != 'undefined' ) {
			jQuery( '#booking-id-'+booking_id ).hide();
		} if ( typeof waitlist_id != 'undefined' ) {
			jQuery( '#waitlist-id-'+waitlist_id ).hide();
		}

		var offset = jQuery( '#ajax' ).offset();
		jQuery( 'body, html' ).animate({
			scrollTop: offset.top - 50 - course_booking_system_ajax.offset
		}, 300);
	}
}

function cbs_action_substitute( course_id, date, user_id ) {
	jQuery( '#ajax-loader' ).show();

	course_id = course_id;
	date = date;
	user_id = user_id;

	jQuery.ajax({
		type: 'POST',
		url: course_booking_system_ajax.ajaxurl,
		data: {
			action: 'cbs_action_substitute',

			course_id: course_id,
			date: date,
			user_id: user_id
		}, success: function( data, textStatus, XMLHttpRequest ) {
			jQuery( '#ajax' ).html( data );
			jQuery( '#ajax-loader' ).hide();

			var offset = jQuery( '#ajax' ).offset();
			jQuery( 'body, html' ).animate({
				scrollTop: offset.top - 50 - course_booking_system_ajax.offset
			}, 300);
		}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
			alert( errorThrown );
			jQuery( '#ajax-loader' ).hide();
		}
	});
}

function cbs_note( course_id, date, note ) {
	jQuery( '#ajax-loader' ).show();

	course_id = course_id;
	date = date;
	note = note;

	jQuery.ajax({
		type: 'POST',
		url: course_booking_system_ajax.ajaxurl,
		data: {
			action: 'cbs_note',

			course_id: course_id,
			date: date,
			note: note
		}, success: function( data, textStatus, XMLHttpRequest ) {
			jQuery( '#ajax' ).html( data );
			jQuery( '#ajax-loader' ).hide();

			var offset = jQuery( '#ajax' ).offset();
			jQuery( 'body, html' ).animate({
				scrollTop: offset.top - 50 - course_booking_system_ajax.offset
			}, 300);
		}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
			alert( errorThrown );
			jQuery( '#ajax-loader' ).hide();
		}
	});
}

jQuery( document ).ready( function() {

	jQuery( document ).on( 'click', '.action-booking', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		course_id = jQuery( this ).data( 'id' );
		// date = jQuery( this ).data( 'date' );
		date = jQuery( this ).attr( 'data-date' );
		user_id = jQuery( this ).data( 'user' );
		confirm_message = jQuery( this ).data( 'confirm' );

		if ( confirm_message === undefined || confirm( confirm_message ) ) {
			jQuery.ajax({
				type: 'POST',
				url: course_booking_system_ajax.ajaxurl,
				data: {
					action: 'cbs_action_booking',

					course_id: course_id,
					date: date,
					user_id: user_id
				}, success: function( data, textStatus, XMLHttpRequest ) {
					jQuery( '#ajax' ).html( data );
					jQuery( '#ajax-loader' ).hide();

					cbs_action_course();
				}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
					alert( errorThrown );
					jQuery( '#ajax-loader' ).hide();
				}
			});
		} else {
			jQuery( '#ajax-loader' ).hide();
		}
	});

	jQuery( document ).on( 'click', '.action-booking-delete', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		course_id = jQuery( this ).data( 'id' );
		date = jQuery( this ).data( 'date' );
		user_id = jQuery( this ).data( 'user' );
		booking_id = jQuery( this ).data( 'booking' );
		confirm_message = jQuery( this ).data( 'confirm' );

		goodwill = 0;
		if ( jQuery( this ).data( 'goodwill' ) ) {
			goodwill = jQuery( this ).data( 'goodwill' );
		}

		if ( confirm_message === undefined || confirm( confirm_message ) ) {
			jQuery.ajax({
				type: 'POST',
				url: course_booking_system_ajax.ajaxurl,
				data: {
					action: 'cbs_action_booking_delete',

					course_id: course_id,
					date: date,
					user_id: user_id,
					booking_id: booking_id,
					goodwill: goodwill
				}, success: function( data, textStatus, XMLHttpRequest ) {
					jQuery( '#ajax' ).html( data );
					jQuery( '#ajax-loader' ).hide();

					cbs_action_course();
				}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
					alert( errorThrown );
					jQuery( '#ajax-loader' ).hide();
				}
			});
		} else {
			jQuery( '#ajax-loader' ).hide();
		}
	});

	jQuery( document ).on( 'click', '.action-abo-delete', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		course_id = jQuery( this ).data( 'id' );
		date = jQuery( this ).data( 'date' );
		user_id = jQuery( this ).data( 'user' );
		confirm_message = jQuery( this ).data( 'confirm' );

		if ( jQuery( this ).data( 'goodwill' ) ) {
			goodwill = jQuery( this ).data( 'goodwill' );
		} else {
			goodwill = 0;
		}

		if ( confirm_message === undefined || confirm( confirm_message ) ) {
			jQuery.ajax({
				type: 'POST',
				url: course_booking_system_ajax.ajaxurl,
				data: {
					action: 'cbs_action_abo_delete',

					course_id: course_id,
					date: date,
					user_id: user_id,
					goodwill: goodwill
				}, success: function( data, textStatus, XMLHttpRequest ) {
					jQuery( '#ajax' ).html( data );
					jQuery( '#ajax-loader' ).hide();

					cbs_action_course();
				}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
					alert( errorThrown );
					jQuery( '#ajax-loader' ).hide();
				}
			});
		} else {
			jQuery( '#ajax-loader' ).hide();
		}
	});

	jQuery( document ).on( 'click', '.action-waitlist', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		course_id = jQuery( this ).data( 'id' );
		date = jQuery( this ).data( 'date' );
		user_id = jQuery( this ).data( 'user' );

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_waitlist',

				course_id: course_id,
				date: date,
				user_id: user_id
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#ajax' ).html( data );
				jQuery( '#ajax-loader' ).hide();

				cbs_action_course();
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});

	jQuery( document ).on( 'click', '.action-waitlist-delete', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		course_id = jQuery( this ).data( 'id' );
		date = jQuery( this ).data( 'date' );
		user_id = jQuery( this ).data( 'user' );
		waitlist_id = jQuery( this ).data( 'waitlist' );

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_waitlist_delete',

				course_id: course_id,
				date: date,
				user_id: user_id,
				waitlist_id: waitlist_id
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#ajax' ).html( data );
				jQuery( '#ajax-loader' ).hide();

				cbs_action_course();
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});

	jQuery( document ).on( 'click', '.action-attendance', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		course_id = jQuery( this ).data( 'id' );
		date = jQuery( this ).data( 'date' );
		user_id = jQuery( this ).data( 'user' );
		attendance = jQuery( this ).data( 'attendance' );

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_attendance',

				course_id: course_id,
				date: date,
				attendance: attendance
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#ajax' ).html( data );
				jQuery( '#ajax-loader' ).hide();

				cbs_action_course();
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});

	var booking_in_advance = 0;
	jQuery( document ).on( 'click', '.action-week', function( e ) {
		e.preventDefault();
		container = jQuery( this ).parent().next(); // AJAX
		loader = jQuery( this ).parent().next().next(); // AJAX Loader
		loader.show(); // AJAX Loader

		category = jQuery( this ).data( 'category' );
		design = jQuery( this ).data( 'design' );
		date = container.find( '.cbs-timetable .cbs-timetable-column:first-child h4 time' ).attr( 'datetime' );
		direction = jQuery( this ).data( 'direction' );
		container.find( '.cbs-timetable' ).addClass( 'animate-slide-'+direction );

		jQuery( '.action-week' ).show();
		if ( direction == 'next' ) {
			booking_in_advance++;

			if ( booking_in_advance == jQuery( '#booking_in_advance' ).data( 'id' ) )
				jQuery( '.action-week.cbs-week-next' ).hide();
		} else {
			booking_in_advance--;

			if ( booking_in_advance == 0 )
				jQuery( '.action-week.cbs-week-prev' ).hide();
		}

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_week',

				category: category,
				design: design,
				date: date,
				direction: direction
			}, success: function( data, textStatus, XMLHttpRequest ) {
				container.html( data );
				loader.hide();
				jQuery( '.cbs-timetable' ).removeClass( 'animate-slide-prev' );
				jQuery( '.cbs-timetable' ).removeClass( 'animate-slide-next' );
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				loader.hide();
			}
		});
	});

	jQuery( document ).on( 'change', '.woocommerce-account .woocommerce-message #_subscription_course', function( e ) {
		e.preventDefault();
		jQuery( '#ajax-loader' ).show();

		abo_course = jQuery( this ).val();

		jQuery.ajax({
			type: 'POST',
			url: course_booking_system_ajax.ajaxurl,
			data: {
				action: 'cbs_action_subscription',

				abo_course: abo_course
			}, success: function( data, textStatus, XMLHttpRequest ) {
				jQuery( '#ajax' ).html( data );
				jQuery( '#ajax-loader' ).hide();

				location.reload();
			}, error: function( XMLHttpRequest, textStatus, errorThrown ) {
				alert( errorThrown );
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});

});