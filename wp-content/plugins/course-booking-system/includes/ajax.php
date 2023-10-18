<?php
function cbs_action_booking( $custom_user_id ) {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$user_id   = empty( $custom_user_id ) ? sanitize_text_field( $_REQUEST['user_id'] ) : $custom_user_id;

	$courses = cbs_get_courses( array(
		'id' => $course_id
	) );
	foreach ( $courses as $course ) {
		$course_post_title = $course->post_title;
		$day = $course->day;
		$start = $course->start;
		$end = $course->end;
		$substitute_id = cbs_get_substitute_id( $course_id, $date );
		$course_user_id = !empty( $substitute_id ) ? $substitute_id : $course->user_id;

		$attendance  = get_post_meta( $course->post_id, 'attendance', true );
		$free        = get_post_meta( $course->post_id, 'free', true );
		$price_level = get_post_meta( $course->post_id, 'price_level', true );
	}

	if ( $price_level == 5 ) {
		$flat = get_the_author_meta( 'flat_5', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_5', $user_id );
	} else if ( $price_level == 4 ) {
		$flat = get_the_author_meta( 'flat_4', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_4', $user_id );
	} else if ( $price_level == 3 ) {
		$flat = get_the_author_meta( 'flat_3', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_3', $user_id );
	} else if ( $price_level == 2 ) {
		$flat = get_the_author_meta( 'flat_2', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_2', $user_id );
	} else {
		$flat = get_the_author_meta( 'flat', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire', $user_id );
	}

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$blog_title  = get_bloginfo( 'name' );
	$admin_email = get_option( 'admin_email' );
	$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

	$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

	$email_booking = get_option( 'course_booking_system_email_booking' );
	if ( $attendance <= 2 ) // Send booking emails always if the attendance is 2 or lower
		$email_booking = 1;

	if ( $free || ( $flat && date( 'Y-m-d', strtotime( $flat_expire ) ) >= $date ) ) {
		if ( is_user_logged_in() && !empty( $user_id ) && ( get_current_user_id() == $user_id || !empty( $custom_user_id ) || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
			$wpdb->insert(
				$wpdb->prefix.'cbs_bookings',
				array( 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
				array( '%d', '%s', '%d' )
			);

			if ( empty( $custom_user_id ) ) {
				echo '<div class="woocommerce-message">'.__( 'Thank you for booking the course.', 'course-booking-system' ).' <a href="'.get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'">» '.get_the_title( get_option( 'woocommerce_myaccount_page_id' ) ).'</a></div>';
			} else {
				return TRUE;
			}

			// Email for course booking: Send an email if a customer books a course
			if ( $email_booking ) {
				$user_info = get_userdata( $user_id );
				$trainer_info = get_userdata( $course_user_id );

				$subject = __( 'Course booking on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

				// Email to trainer
				$to = $trainer_info->first_name.' '.$trainer_info->last_name.' <'.$trainer_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$trainer_info->first_name.' '.$trainer_info->last_name.',</p><p style="margin: 0 0 16px;">'.$user_info->first_name.' '.$user_info->last_name.' '.__( 'booked the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );

				// Email to user
				$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'You booked the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'You can see the courses you have booked in your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}
		}
	} else {
		$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );

		if ( $price_level == 5 ) {
			$card_name = 'card_5';
			$expire_name = 'expire_5';

			$card = get_the_author_meta( $card_name, $user_id );
			$expire = get_the_author_meta( $expire_name, $user_id );
		} else if ( $price_level == 4 ) {
			$card_name = 'card_4';
			$expire_name = 'expire_4';

			$card = get_the_author_meta( $card_name, $user_id );
			$expire = get_the_author_meta( $expire_name, $user_id );

			if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_5';
				$expire_name = 'expire_5';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			}
		} else if ( $price_level == 3 ) {
			$card_name = 'card_3';
			$expire_name = 'expire_3';

			$card = get_the_author_meta( $card_name, $user_id );
			$expire = get_the_author_meta( $expire_name, $user_id );

			if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_4';
				$expire_name = 'expire_4';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_5';
				$expire_name = 'expire_5';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			}
		} else if ( $price_level == 2 ) {
			$card_name = 'card_2';
			$expire_name = 'expire_2';

			$card = get_the_author_meta( $card_name, $user_id );
			$expire = get_the_author_meta( $expire_name, $user_id );

			if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_3';
				$expire_name = 'expire_3';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_4';
				$expire_name = 'expire_4';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_5';
				$expire_name = 'expire_5';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			}
		} else {
			$card_name = 'card';
			$expire_name = 'expire';

			$card = get_the_author_meta( $card_name, $user_id );
			$expire = get_the_author_meta( $expire_name, $user_id );

			if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_2';
				$expire_name = 'expire_2';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_3';
				$expire_name = 'expire_3';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_4';
				$expire_name = 'expire_4';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
				$card_name = 'card_5';
				$expire_name = 'expire_5';

				$card = get_the_author_meta( $card_name, $user_id );
				$expire = get_the_author_meta( $expire_name, $user_id );
			}
		}

		if ( is_user_logged_in() && !empty( $user_id ) && ( get_current_user_id() == $user_id || !empty( $custom_user_id ) || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
			if ( $card > 0 && $expire >= $date ) {
				$card--;
				update_user_meta( $user_id, $card_name, $card );
				cbs_log( $user_id, $card_name, $card, $course_id, 'cbs_action_booking ('.get_current_user_id().')' );

				$wpdb->insert(
					$wpdb->prefix.'cbs_bookings',
					array( 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
					array( '%d', '%s', '%d' )
				);

				if ( empty( $custom_user_id ) ) {
					echo '<div class="woocommerce-message">'.__( 'Thank you for booking the course.', 'course-booking-system' ).' <a href="'.get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'">» '.get_the_title( get_option( 'woocommerce_myaccount_page_id' ) ).'</a></div>';
				} else {
					return TRUE;
				}

				// Email for course booking: Send an email if a customer books a course
				if ( $email_booking ) {
					$user_info = get_userdata( $user_id );
					$trainer_info = get_userdata( $course_user_id );

					$subject = __( 'Course booking on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

					// Email to trainer
					$to = $trainer_info->first_name.' '.$trainer_info->last_name.' <'.$trainer_info->user_email.'>';
					$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$trainer_info->first_name.' '.$trainer_info->last_name.',</p><p style="margin: 0 0 16px;">'.$user_info->first_name.' '.$user_info->last_name.' '.__( 'booked the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p>';
					$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

					wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );

					// Email to user
					$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
					$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'You booked the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'You can see the courses you have booked in your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
					$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

					wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
				}
			} else {
				echo '<div class="woocommerce-error">'.__( 'No valid card available.', 'course-booking-system' ).'</div>';
			}
		}
	}

	cbs_action_waitlist_delete();

	wp_die();
}
add_action( 'wp_ajax_cbs_action_booking', 'cbs_action_booking' );
add_action( 'wp_ajax_nopriv_cbs_action_booking', 'cbs_action_booking' );

function cbs_action_booking_delete() {
	global $wpdb;

	$booking_id = sanitize_text_field( $_REQUEST['booking_id'] );
	$course_id  = sanitize_text_field( $_REQUEST['course_id'] );
	$date       = sanitize_text_field( $_REQUEST['date'] );
	$user_id    = sanitize_text_field( $_REQUEST['user_id'] );

	if ( !empty( $_REQUEST['goodwill'] ) ) {
		$goodwill = sanitize_text_field( $_REQUEST['goodwill'] );
	} else {
		$goodwill = false;
	}

	$posts = $wpdb->get_results( "SELECT post_id FROM ".$wpdb->prefix."cbs_data WHERE id = $course_id LIMIT 1" );
	foreach ( $posts as $post ) {
		$id = $post->post_id;
	}

	$custom      = get_post_custom( $id );
	$attendance  = $custom['attendance'][0];
	$free        = $custom['free'][0];
	$price_level = $custom['price_level'][0];

	if ( $price_level == 5 ) {
		$flat = get_the_author_meta( 'flat_5', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_5', $user_id );
	} else if ( $price_level == 4 ) {
		$flat = get_the_author_meta( 'flat_4', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_4', $user_id );
	} else if ( $price_level == 3 ) {
		$flat = get_the_author_meta( 'flat_3', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_3', $user_id );
	} else if ( $price_level == 2 ) {
		$flat = get_the_author_meta( 'flat_2', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire_2', $user_id );
	} else {
		$flat = get_the_author_meta( 'flat', $user_id );
		$flat_expire = get_the_author_meta( 'flat_expire', $user_id );
	}

	$courses = $wpdb->get_results( "SELECT post_title, day, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id WHERE ".$wpdb->prefix."cbs_data.id = $course_id LIMIT 1" );
	foreach ( $courses as $course ) {
		$course_post_title = $course->post_title;
		$start = $course->start;
		$end = $course->end;
		$day = $course->day;
		$course_user_id = $course->user_id;
	}

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$blog_title  = get_bloginfo( 'name' );
	$admin_email = get_option( 'admin_email' );
	$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

	$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

	$deleting_in_advance    = get_option( 'course_booking_system_deleting_in_advance' );
	$expire_extend          = get_option( 'course_booking_system_expire_extend' );
	$waitlist_auto_booking  = get_option( 'course_booking_system_waitlist_auto_booking' );
	$email_deleting         = get_option( 'course_booking_system_email_deleting' );
	$email_booking          = get_option( 'course_booking_system_email_booking' );
	$email_cancel           = get_option( 'course_booking_system_email_cancel' );
	$email_cancel_address   = get_option( 'course_booking_system_email_cancel_address' );
	$email_waitlist         = get_option( 'course_booking_system_email_waitlist' );
	$email_waitlist_address = get_option( 'course_booking_system_email_waitlist_address' );

	if ( $attendance <= 2 ) // Send booking emails always if the attendance is 2 or lower
		$email_booking = 1;

	if ( is_user_logged_in() && !empty( $user_id ) && ( get_current_user_id() == $user_id || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
		if ( $goodwill || ( !$free && !( $flat && date( 'Y-m-d', strtotime($flat_expire) ) >= $date ) && ( strtotime( $date.' '.$start ) - $deleting_in_advance * HOUR_IN_SECONDS ) > current_time( 'timestamp' ) ) ) {
			if ( $price_level == 5 ) {
				$card_name = 'card_5';
				$expire_name = 'expire_5';
			} else if ( $price_level == 4 ) {
				$card_name = 'card_4';
				$expire_name = 'expire_4';
			} else if ( $price_level == 3 ) {
				$card_name = 'card_3';
				$expire_name = 'expire_3';
			} else if ( $price_level == 2 ) {
				$card_name = 'card_2';
				$expire_name = 'expire_2';
			} else {
				$card_name = 'card';
				$expire_name = 'expire';
			}

			$card = get_the_author_meta( $card_name, $user_id );
			$expire = get_the_author_meta( $expire_name, $user_id );

			$card++;
			update_user_meta( $user_id, $card_name, $card );
			cbs_log( $user_id, $card_name, $card, $course_id, 'cbs_action_booking_delete ('.get_current_user_id().')' );

			if ( empty( $expire ) || $expire < $date ) { // Fallback if course was booked with a higher card and the new price level card has no expiry date yet
				update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+1 month' ) ) );
			} else if ( $expire_extend && get_current_user_id() != $user_id ) {
				$expire = date( 'Y-m-d', strtotime( $expire.' +1 week' ) );
				update_user_meta( $user_id, $expire_name, $expire );
			}

			echo '<div class="woocommerce-message">'.__( 'The booking was reversed successfully. A credit has been added to the card.', 'course-booking-system' ).'</div>';

			// Email for course booking: Send an email if a customer books a course
			if ( $email_booking ) {
				$user_info = get_userdata( $user_id );
				$trainer_info = get_userdata( $course_user_id );

				$subject = __( 'Course reversion on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

				// Email to trainer
				$to = $trainer_info->first_name.' '.$trainer_info->last_name.' <'.$trainer_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$trainer_info->first_name.' '.$trainer_info->last_name.',</p><p style="margin: 0 0 16px;">'.$user_info->first_name.' '.$user_info->last_name.' '.__( 'reversed the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'The booking was reversed successfully. A credit has been added to the card.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );

				// Email to user
				$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'You reversed the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'The booking was reversed successfully. A credit has been added to the card.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Please book another course through your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}
		} else {
			echo '<div class="woocommerce-message">'.__( 'Thank you for canceling the course.', 'course-booking-system' ).'</div>';

			$wpdb->insert(
				$wpdb->prefix.'cbs_cancellations',
				array( 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
				array( '%d', '%s', '%d' )
			);

			// Email for course booking: Send an email if a customer books a course
			if ( $email_booking ) {
				$user_info = get_userdata( $user_id );
				$trainer_info = get_userdata( $course_user_id );

				$subject = __( 'Course cancellation on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

				// Email to trainer
				$to = $trainer_info->first_name.' '.$trainer_info->last_name.' <'.$trainer_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$trainer_info->first_name.' '.$trainer_info->last_name.',</p><p style="margin: 0 0 16px;">'.$user_info->first_name.' '.$user_info->last_name.' '.__( 'cancelled the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );

				// Email to user
				$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'You cancelled the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'Thank you for canceling the course.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}

			// Email in case of course cancellation: Send an email if a customer cancels a course
			if ( $email_cancel ) {
				$user_info = get_userdata( $user_id );
				$trainer_info = get_userdata( $course_user_id );

				$subject = __( 'Course cancellation on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

				$to = $blog_title.' <'.$email_cancel_address.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$blog_title.',</p><p style="margin: 0 0 16px;">'.$user_info->first_name.' '.$user_info->last_name.' '.__( 'cancelled the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p>';
				$headers = array( 'Cc: '.$trainer_info->first_name.' '.$trainer_info->last_name.' <'.$trainer_info->user_email.'>', 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}
		}

		$wpdb->delete(
			$wpdb->prefix.'cbs_bookings',
			array( 'booking_id' => $booking_id, 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
			array( '%d', '%d', '%s', '%d' )
		);
	}

	// Email for course reversion: Send an email to the customer if an admin reverses or cancels a course
	if ( $email_deleting && get_current_user_id() != $user_id && ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
		$user_info = get_userdata( $user_id );

		$subject = __( 'Course cancellation on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

		$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
		$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your booked course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).' '.__( 'has been cancelled', 'course-booking-system' ).'.</p><p style="margin: 0 0 16px;">'.__( 'Please book another course through your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
		$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
	}

	// Email waitlist
	$waitlists = $wpdb->get_results( "SELECT waitlist_id, user_id FROM ".$wpdb->prefix."cbs_waitlists WHERE course_id = $course_id AND date = '$date'" );
	foreach ( $waitlists as $waitlist ) {
		$subject = get_option( 'course_booking_system_email_waitlist_subject' );
		$content = get_option( 'course_booking_system_email_waitlist_content' );

		$booked = FALSE;
		if ( $waitlist_auto_booking && ( strtotime( $date.' '.$start ) - $deleting_in_advance * HOUR_IN_SECONDS ) ) {
			$content = __( 'The course was automatically booked for you. You can see the booked course in your account:', 'course-booking-system' );

			$booked = cbs_action_booking( $waitlist->user_id );
			if ( !$booked )
				continue;
		}

		$user_info = get_userdata( $waitlist->user_id );
		$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
		$subject = $subject.' '.date_i18n( $date_format, strtotime( $date ) );
		$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->display_name.',</p><p style="margin: 0 0 16px;">'.__( 'We are happy to inform you that a place has become available in the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.$content.' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'We look forward to you.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
		$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

		if ( $email_waitlist && !empty( $email_waitlist_address ) )
			$headers[] = 'Bcc: '.$email_waitlist_address;

		wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );

		if ( $booked ) {
			$wpdb->delete(
				$wpdb->prefix.'cbs_waitlists',
				array( 'waitlist_id' => $waitlist->waitlist_id, 'course_id' => $course_id, 'date' => $date, 'user_id' => $waitlist->user_id ),
				array( '%d', '%d', '%s', '%d' )
			);

			break;
		}
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_booking_delete', 'cbs_action_booking_delete' );
add_action( 'wp_ajax_nopriv_cbs_action_booking_delete', 'cbs_action_booking_delete' );

function cbs_action_abo_delete() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$user_id   = sanitize_text_field( $_REQUEST['user_id'] );

	if ( !empty( $_REQUEST['goodwill'] ) ) {
		$goodwill = sanitize_text_field( $_REQUEST['goodwill'] );
	} else {
		$goodwill = false;
	}

	$posts = $wpdb->get_results( "SELECT post_id FROM ".$wpdb->prefix."cbs_data WHERE id = $course_id LIMIT 1" );
	foreach ( $posts as $post ) {
		$id = $post->post_id;
	}

	$custom      = get_post_custom( $id );
	$free        = $custom['free'][0];
	$price_level = $custom['price_level'][0];

	$courses = $wpdb->get_results( "SELECT post_title, day, start, end FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id WHERE ".$wpdb->prefix."cbs_data.id = $course_id LIMIT 1" );
	foreach ( $courses as $course ) {
		$course_post_title = $course->post_title;
		$start = $course->start;
		$end = $course->end;
		$day = $course->day;
	}

	if ( !$free ) {
		if ( $price_level == 5 ) {
			$card_name = 'card_5';
			$expire_name = 'expire_5';
		} else if ( $price_level == 4 ) {
			$card_name = 'card_4';
			$expire_name = 'expire_4';
		} else if ( $price_level == 3 ) {
			$card_name = 'card_3';
			$expire_name = 'expire_3';
		} else if ( $price_level == 2 ) {
			$card_name = 'card_2';
			$expire_name = 'expire_2';
		} else {
			$card_name = 'card';
			$expire_name = 'expire';
		}

		$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
		$card = get_the_author_meta( $card_name, $user_id );
		$expire = get_the_author_meta( $expire_name, $user_id );
	}

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$blog_title  = get_bloginfo( 'name' );
	$admin_email = get_option( 'admin_email' );
	$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

	$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

	$email_deleting = get_option( 'course_booking_system_email_deleting' );
	$email_cancel = get_option( 'course_booking_system_email_cancel' );
	$email_cancel_address = get_option( 'course_booking_system_email_cancel_address' );
	$email_waitlist = get_option( 'course_booking_system_email_waitlist' );
	$email_waitlist_address = get_option( 'course_booking_system_email_waitlist_address' );

	if ( is_user_logged_in() && !empty( $user_id ) && ( get_current_user_id() == $user_id || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
		if ( empty( $abo_alternate ) ) {
			$abo_alternate = $date;
		} else {
			$abo_alternate .= ','.$date;
		}
		update_user_meta( $user_id, 'abo_alternate', $abo_alternate );

		$abo_alternate        = explode( ',', $abo_alternate );
		$deleting_in_advance  = get_option( 'course_booking_system_deleting_in_advance' );
		$abo_alternate_option = get_option( 'course_booking_system_abo_alternate' );
		if ( $goodwill || ( $_SERVER['HTTP_HOST'] != 'studio-a-pilates.de' && !$free && ( strtotime( $date.' '.$start ) - $deleting_in_advance * HOUR_IN_SECONDS ) > current_time( 'timestamp' ) && ( empty( $abo_alternate_option ) || count( $abo_alternate ) <= $abo_alternate_option ) ) ) {
			$card++;
			update_user_meta( $user_id, $card_name, $card );
			cbs_log( $user_id, $card_name, $card, $course_id, __FUNCTION__ );

			if ( empty( $expire ) || $expire < date( 'Y-m-d' ) ) { // Fallback if expiry date is expired or to short
				update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+1 week' ) ) );
			}

			echo '<div class="woocommerce-message">'.__( 'The booking was reversed successfully. A credit has been added to the card.', 'course-booking-system' ).'</div>';
		} else {
			echo '<div class="woocommerce-message">'.__( 'Thank you for canceling the course.', 'course-booking-system' ).'</div>';

			// Email in case of course cancellation: Send an email if a customer cancels a course
			if ( $email_cancel ) {
				$user_info = get_userdata( $user_id );

				$subject = __( 'Course cancellation on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

				$to = $blog_title.' <'.$email_cancel_address.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$blog_title.',</p><p style="margin: 0 0 16px;">'.$user_info->first_name.' '.$user_info->last_name.' '.__( 'cancelled the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.__( 'This is an automatically generated email from the website.', 'course-booking-system' ).'</p>';
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}
		}
	}

	// Email for course reversion: Send an email to the customer if an admin reverses or cancels a course
	if ( $email_deleting && get_current_user_id() != $user_id ) {
		$user_info = get_userdata( $user_id );

		$subject = __( 'Course cancellation on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) );

		$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
		$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your booked course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).' '.__( 'has been cancelled', 'course-booking-system' ).'.</p><p style="margin: 0 0 16px;">'.__( 'Please book another course through your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
		$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
	}

	// Email waitlist
	$subject = get_option( 'course_booking_system_email_waitlist_subject' ).' '.date_i18n( $date_format, strtotime( $date ) );
	$content = get_option( 'course_booking_system_email_waitlist_content' );

	$waitlists = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."cbs_waitlists WHERE course_id = $course_id AND date = '$date'" );
	foreach ( $waitlists as $waitlist ) {
		$user_info = get_userdata( $waitlist->user_id );
		$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
		$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->display_name.',</p><p style="margin: 0 0 16px;">'.__( 'We are happy to inform you that a place has become available in the course', 'course-booking-system' ).' "'.$course_post_title.'" '.__( 'from', 'course-booking-system' ).' '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).'.</p><p style="margin: 0 0 16px;">'.$content.' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'We look forward to you.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title.'</p>';
		$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

		if ( $email_waitlist && !empty( $email_waitlist_address ) )
			$headers[] = 'Bcc: '.$email_waitlist_address;

		wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_abo_delete', 'cbs_action_abo_delete' );
add_action( 'wp_ajax_nopriv_cbs_action_abo_delete', 'cbs_action_abo_delete' );

function cbs_action_waitlist() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$user_id   = sanitize_text_field( $_REQUEST['user_id'] );

	if ( is_user_logged_in() && !empty( $user_id ) && ( get_current_user_id() == $user_id || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
		$wpdb->insert(
			$wpdb->prefix.'cbs_waitlists',
			array( 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
			array( '%d', '%s', '%d' )
		);

		echo '<div class="woocommerce-message">'.__( 'Thank you for your interest in this course. You have successfully registered on the waiting list. As soon as a place is available you will be notified by email.', 'course-booking-system' ).' <a href="'.get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'">» '.get_the_title( get_option( 'woocommerce_myaccount_page_id' ) ).'</a></div>';
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_waitlist', 'cbs_action_waitlist' );
add_action( 'wp_ajax_nopriv_cbs_action_waitlist', 'cbs_action_waitlist' );

function cbs_action_waitlist_delete() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$user_id   = sanitize_text_field( $_REQUEST['user_id'] );

	if ( is_user_logged_in() && !empty( $user_id ) && ( get_current_user_id() == $user_id || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
		$waitlists = $wpdb->delete(
			$wpdb->prefix.'cbs_waitlists',
			array( 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
			array( '%d', '%s', '%d' )
		);

		if ( $waitlists > 0 ) {
			echo '<div class="woocommerce-message">'.__( 'Thank you for unsubscribing from the waiting list.', 'course-booking-system' ).'</div>';
		}
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_waitlist_delete', 'cbs_action_waitlist_delete' );
add_action( 'wp_ajax_nopriv_cbs_action_waitlist_delete', 'cbs_action_waitlist_delete' );

function cbs_action_course() {
	require plugin_dir_path( __FILE__ ) . 'ajax/single-course.php';

	wp_die();
}
add_action( 'wp_ajax_cbs_action_course', 'cbs_action_course' );
add_action( 'wp_ajax_nopriv_cbs_action_course', 'cbs_action_course' );

function cbs_action_account() {
	include plugin_dir_path( __FILE__ ) . 'woocommerce/myaccount/dashboard-status.php';

	wp_die();
}
add_action( 'wp_ajax_cbs_action_account', 'cbs_action_account' );
add_action( 'wp_ajax_nopriv_cbs_action_account', 'cbs_action_account' );

function cbs_action_event() {
	require plugin_dir_path( __FILE__ ) . 'ajax/single-event.php';

	wp_die();
}
add_action( 'wp_ajax_cbs_action_event', 'cbs_action_event' );
add_action( 'wp_ajax_nopriv_cbs_action_event', 'cbs_action_event' );

function cbs_action_substitute() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$user_id   = sanitize_text_field( $_REQUEST['user_id'] );

	if ( is_user_logged_in() && !empty( $user_id ) && current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) {
		$wpdb->delete(
			$wpdb->prefix.'cbs_substitutes',
			array( 'course_id' => $course_id, 'date' => $date ),
			array( '%d', '%s')
		);

		// Check if substitute is not actual user of course
		$courses = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."cbs_data WHERE id = $course_id LIMIT 1" );
		foreach ( $courses as $course ) {
			if ( $course->user_id != $user_id ) {
				$wpdb->insert(
					$wpdb->prefix.'cbs_substitutes',
					array( 'course_id' => $course_id, 'date' => $date, 'user_id' => $user_id ),
					array( '%d', '%s', '%d' )
				);
			}
		}

		echo '<div class="woocommerce-message">'.__( 'Trainer saved successfully.', 'course-booking-system' ).'</div>';
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_substitute', 'cbs_action_substitute' );
add_action( 'wp_ajax_nopriv_cbs_action_substitute', 'cbs_action_substitute' );

function cbs_action_attendance() {
	global $wpdb;

	$course_id  = sanitize_text_field( $_REQUEST['course_id'] );
	$date       = sanitize_text_field( $_REQUEST['date'] );
	$attendance = sanitize_text_field( $_REQUEST['attendance'] );

	if ( is_user_logged_in() && current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) {
		$wpdb->delete(
			$wpdb->prefix.'cbs_attendances',
			array( 'course_id' => $course_id, 'date' => $date ),
			array( '%d', '%s')
		);

		// Check if attendance is not actual attendance of course
		$events = $wpdb->get_results( "SELECT post_id FROM ".$wpdb->prefix."cbs_data WHERE id = $course_id LIMIT 1" );
		foreach ( $events as $event ) {
			$id = $event->post_id;
		}
		$custom = get_post_custom( $id );
		$custom_attendance = $custom['attendance'][0];
		if ( $custom_attendance != $attendance ) {
			$wpdb->insert(
				$wpdb->prefix.'cbs_attendances',
				array( 'course_id' => $course_id, 'date' => $date, 'attendance' => $attendance ),
				array( '%d', '%s', '%d' )
			);
		}

		echo '<div class="woocommerce-message">'.__( 'The attendance was updated successfully.', 'course-booking-system' ).'</div>';
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_attendance', 'cbs_action_attendance' );
add_action( 'wp_ajax_nopriv_cbs_action_attendance', 'cbs_action_attendance' );

function cbs_livesearch() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$search    = sanitize_text_field( $_REQUEST['search'] );

	if ( strlen($search) >= 3 ) {
		$date_format = get_option( 'date_format' );
		$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );

		$posts = $wpdb->get_results( "SELECT post_id FROM ".$wpdb->prefix."cbs_data WHERE id = $course_id LIMIT 1" );
		foreach ( $posts as $post ) {
			$id = $post->post_id;
		}

		$custom      = get_post_custom( $id );
		$free        = $custom['free'][0];
		$price_level = $custom['price_level'][0];

		$wp_user_query = new WP_User_Query(
			array(
				'search' => "*{$search}*",
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
				),

			)
		);
		$users = $wp_user_query->get_results();

		$wp_user_query_2 = new WP_User_Query(
			array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'first_name',
						'value' => $search,
						'compare' => 'LIKE'
					),
					array(
						'key' => 'last_name',
						'value' => $search,
						'compare' => 'LIKE'
					)
				)
			)
		);
		$users_2 = $wp_user_query_2->get_results();

		$user_results_duplicates = array_merge( $users, $users_2 );
		$user_results = array_unique( $user_results_duplicates, SORT_REGULAR );

		if ( empty( $user_results ) ) :
			?>

			<li><a href="<?= admin_url( 'user-new.php' ) ?>" target="_blank">+ <?php _e( 'Add new customer', 'course-booking-system' ); ?></a></li>

			<?php
			wp_die();
		endif;

		foreach ( $user_results AS $user_result ) :

			if ( $price_level == 5 ) {
				$card_name = 'card_5';
				$expire_name = 'expire_5';

				$flat_name = 'flat_5';
				$flat_expire_name = 'flat_expire_5';

				$card = get_the_author_meta( $card_name, $user_result->ID );
				$expire = get_the_author_meta( $expire_name, $user_result->ID );
			} else if ( $price_level == 4 ) {
				$card_name = 'card_4';
				$expire_name = 'expire_4';

				$flat_name = 'flat_4';
				$flat_expire_name = 'flat_expire_4';

				$card = get_the_author_meta( $card_name, $user_result->ID );
				$expire = get_the_author_meta( $expire_name, $user_result->ID );

				if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_5';
					$expire_name = 'expire_5';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				}
			} else if ( $price_level == 3 ) {
				$card_name = 'card_3';
				$expire_name = 'expire_3';

				$flat_name = 'flat_3';
				$flat_expire_name = 'flat_expire_3';

				$card = get_the_author_meta( $card_name, $user_result->ID );
				$expire = get_the_author_meta( $expire_name, $user_result->ID );

				if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_4';
					$expire_name = 'expire_4';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_5';
					$expire_name = 'expire_5';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				}
			} else if ( $price_level == 2 ) {
				$card_name = 'card_2';
				$expire_name = 'expire_2';

				$flat_name = 'flat_2';
				$flat_expire_name = 'flat_expire_2';

				$card = get_the_author_meta( $card_name, $user_result->ID );
				$expire = get_the_author_meta( $expire_name, $user_result->ID );

				if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_3';
					$expire_name = 'expire_3';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_4';
					$expire_name = 'expire_4';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_5';
					$expire_name = 'expire_5';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				}
			} else {
				$card_name = 'card';
				$expire_name = 'expire';

				$flat_name = 'flat';
				$flat_expire_name = 'flat_expire';

				$card = get_the_author_meta( $card_name, $user_result->ID );
				$expire = get_the_author_meta( $expire_name, $user_result->ID );

				if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_2';
					$expire_name = 'expire_2';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_3';
					$expire_name = 'expire_3';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_4';
					$expire_name = 'expire_4';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
					$card_name = 'card_5';
					$expire_name = 'expire_5';

					$card = get_the_author_meta( $card_name, $user_result->ID );
					$expire = get_the_author_meta( $expire_name, $user_result->ID );
				}
			}

			$flat = get_the_author_meta( $flat_name, $user_result->ID );
			$flat_expire = get_the_author_meta( $flat_expire_name, $user_result->ID );

			if ( $free ) {
				echo '<li><a href="#" class="action-booking" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_result->ID.'">'.$user_result->first_name.' '.$user_result->last_name.'</a></li>';
			} else if ( $flat && $flat_expire >= $date ) {
				echo '<li><a href="#" class="action-booking" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_result->ID.'">'.$user_result->first_name.' '.$user_result->last_name.' (<strong>'.__( 'Flatrate', 'course-booking-system' ).'</strong><span class="expiry"> '.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $flat_expire ) ).'</span>)</a></li>';
			} else if ( $card > 0 && $expire >= $date ) {
				echo '<li><a href="#" class="action-booking" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_result->ID.'">'.$user_result->first_name.' '.$user_result->last_name.' (<strong>'.$card.'</strong><span class="expiry"> '.__( 'valid until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $expire ) ).'</span>)</a></li>';
			} else {
				echo '<li><a href="#">'.$user_result->first_name.' '.$user_result->last_name.' - '.__( 'No valid card available.', 'course-booking-system' ).'</a></li>';
			}
		endforeach;
	} else {
		?>
		<li><span><?php _e( 'At least enter 3 characters', 'course-booking-system' ); ?></span></li>
		<?php
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_livesearch', 'cbs_livesearch' );
add_action( 'wp_ajax_nopriv_cbs_livesearch', 'cbs_livesearch' );

function cbs_livesearch_waitlist() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$search    = sanitize_text_field( $_REQUEST['search'] );

	if ( strlen($search) >= 3 ) {
		$date_format = get_option( 'date_format' );
		$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );

		$wp_user_query = new WP_User_Query(
			array(
				'search' => "*{$search}*",
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
				),

			)
		);
		$users = $wp_user_query->get_results();

		$wp_user_query_2 = new WP_User_Query(
			array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'first_name',
						'value' => $search,
						'compare' => 'LIKE'
					),
					array(
						'key' => 'last_name',
						'value' => $search,
						'compare' => 'LIKE'
					)
				)
			)
		);
		$users_2 = $wp_user_query_2->get_results();

		$user_results_duplicates = array_merge( $users, $users_2 );
		$user_results = array_unique( $user_results_duplicates, SORT_REGULAR );

		foreach ( $user_results AS $user_result) :
			echo '<li><a href="#" class="action-waitlist" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_result->ID.'">'.$user_result->first_name.' '.$user_result->last_name.'</a></li>';
		endforeach;
	} else {
		?>
		<li><span><?php _e( 'At least enter 3 characters', 'course-booking-system' ); ?></span></li>
		<?php
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_livesearch_waitlist', 'cbs_livesearch_waitlist' );
add_action( 'wp_ajax_nopriv_cbs_livesearch_waitlist', 'cbs_livesearch_waitlist' );

function cbs_note() {
	global $wpdb;

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$note      = sanitize_text_field( $_REQUEST['note'] );

	if ( is_user_logged_in() && ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
		$wpdb->delete(
			$wpdb->prefix.'cbs_notes',
			array( 'course_id' => $course_id, 'date' => $date ),
			array( '%d', '%s')
		);

		if ( !empty( $note ) ) {
			$wpdb->insert(
				$wpdb->prefix.'cbs_notes',
				array( 'course_id' => $course_id, 'date' => $date, 'note' => $note ),
				array( '%d', '%s', '%s' )
			);
		}

		echo '<div class="woocommerce-message">'.__( 'Note saved successfully.', 'course-booking-system' ).'</div>';
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_note', 'cbs_note' );
add_action( 'wp_ajax_nopriv_cbs_note', 'cbs_note' );

function cbs_action_week() {
	global $wpdb;
	$category  = sanitize_text_field( $_REQUEST['category'] );
	$date      = sanitize_text_field( $_REQUEST['date'] );
	$direction = sanitize_text_field( $_REQUEST['direction'] );

	$atts = array();
	if ( !empty( $category ) )
		$atts['category'] = $category;

	if ( $direction == 'prev' ) {
		$date = date( 'Y-m-d', strtotime( $date.' -1 week' ) );
	} else {
		$date = date( 'Y-m-d', strtotime( $date.' +1 week' ) );
	}

	echo cbs_shortcode_timetable( $atts, $date );

	wp_die();
}
add_action( 'wp_ajax_cbs_action_week', 'cbs_action_week' );
add_action( 'wp_ajax_nopriv_cbs_action_week', 'cbs_action_week' );

function cbs_action_subscription() {
	global $wpdb;

	$abo_course = sanitize_text_field( $_REQUEST['abo_course'] );

	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'abo_course', sanitize_text_field( $_POST['abo_course'] ) );

		echo '<div class="woocommerce-message">'.__( 'Subscription course saved successfully.', 'course-booking-system' ).'</div>';
	}

	wp_die();
}
add_action( 'wp_ajax_cbs_action_subscription', 'cbs_action_subscription' );
add_action( 'wp_ajax_nopriv_cbs_action_subscription', 'cbs_action_subscription' );