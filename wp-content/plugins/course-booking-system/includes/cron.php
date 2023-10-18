<?php
// Quarterly
function cbs_add_cron_interval( $schedules ) { 
	$schedules['quarterly'] = array(
		'interval' => 15 * MINUTE_IN_SECONDS,
		'display'  => esc_html__( 'Every 15 Minutes' ), );
	return $schedules;
}
add_filter( 'cron_schedules', 'cbs_add_cron_interval' );

if ( !wp_next_scheduled( 'cbs_cron_quarterly' ) ) {
	wp_schedule_event( time(), 'quarterly', 'cbs_cron_quarterly' );
}
add_action( 'cbs_cron_quarterly', 'cbs_send_invitation_links' );
add_action( 'cbs_cron_quarterly', 'cbs_mark_woocommerce_order_paid' );

function cbs_send_invitation_links() {
	global $wpdb;
	$current_time = current_time( 'timestamp' );

	$date = date( 'Y-m-d' );
	$holiday_start = get_option( 'course_booking_system_holiday_start' );
	$holiday_end   = get_option( 'course_booking_system_holiday_end' );

	$blog_title  = get_bloginfo( 'name' );
	$admin_email = get_option( 'admin_email' );
	$time_format = get_option( 'time_format' );
	$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'#bookings';

	$courses = cbs_get_courses( array(
		'day' => date( 'N' ),
		'date' => $date
	) );
	foreach ( $courses as $course ) {
		$invitation_link          = get_post_meta( $course->post_id, 'invitation_link', true );
		$invitation_link_password = get_post_meta( $course->post_id, 'invitation_link_password', true );

		if ( $_SERVER['HTTP_HOST'] == 'dynamicartsfreiburg.com' )
			$account_url = $invitation_link;

		$subject = get_option( 'course_booking_system_email_invitation_subject' );
		$content = get_option( 'course_booking_system_email_invitation_content' );

		$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

		if ( !empty( $invitation_link ) && ( $current_time + 25 * MINUTE_IN_SECONDS ) >= strtotime( date( 'Y-m-d '.$course->start ) ) && ( $current_time + 10 * MINUTE_IN_SECONDS ) <= strtotime( date( 'Y-m-d '.$course->start ) ) ) {
			$abos = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE (meta_key = 'abo_course' AND meta_value = $course->id) OR (meta_key = 'abo_course_2' AND meta_value = $course->id) OR (meta_key = 'abo_course_3' AND meta_value = $course->id)" );
			foreach ( $abos as $abo ) {
				$user_id = $abo->user_id;
				$abo_start = get_the_author_meta( 'abo_start', $user_id );
				$abo_expire = get_the_author_meta( 'abo_expire', $user_id );
				$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
				$abo_alternate = explode( ',', $abo_alternate );
				if ( $abo_start <= $date && $abo_expire >= $date && !in_array( $date, $abo_alternate ) && !cbs_is_holiday( date( 'd' ), date( 'm' ), date( 'Y' ) ) && ( $date < $holiday_start || $date > $holiday_end ) ) {
					$user_info = get_userdata( $user_id );
					$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
					$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your course starts right away. You are registered for the course', 'course-booking-system' ).' "<strong>'.$course->post_title.'</strong>" '.__( 'at', 'course-booking-system' ).' <strong>'.date( $time_format, strtotime( $course->start ) ).'</strong> '.__( 'o\'clock', 'course-booking-system' ).'. '.$content.' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p>'; $body .= !empty( $invitation_link_password ) ? '<p style="margin: 0 0 16px;">'.__( 'The following password is required to access the online course:', 'course-booking-system' ).' <strong>'.$invitation_link_password.'</strong></p>' : ''; $body .= '<p style="margin: 0 0 16px;">'.__( 'We look forward to you.', 'course-booking-system' ).' '.__( 'See you soon!', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
					$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

					wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
				}
			}

			$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course->id AND date = '$date'" );
			foreach ( $bookings as $booking ) {
				$user_id = $booking->user_id;
				$user_info = get_userdata( $user_id );
				$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your course starts right away. You are registered for the course', 'course-booking-system' ).' "<strong>'.$course->post_title.'</strong>" '.__( 'at', 'course-booking-system' ).' <strong>'.date( $time_format, strtotime( $course->start ) ).'</strong> '.__( 'o\'clock', 'course-booking-system' ).'. '.$content.' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p>'; $body .= !empty($invitation_link_password) ? '<p style="margin: 0 0 16px;">'.__( 'The following password is required to access the online course:', 'course-booking-system' ).' <strong>'.$invitation_link_password.'</strong></p>' : ''; $body .= '<p style="margin: 0 0 16px;">'.__( 'We look forward to you.', 'course-booking-system' ).' '.__( 'See you soon!', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}
		}
	}
}

function cbs_mark_woocommerce_order_paid() {
	$payment_methods = array( 'bacs', 'cheque', 'cod', 'invoice' );

	if ( array_key_exists( 'wc-paid', wc_get_order_statuses() ) ) {
		$args = array(
			'type' => 'shop_order',
			'limit' => -1,
			'status' => array( 'wc-completed' )
		);
		$orders = wc_get_orders( $args );

		foreach ( $orders AS $order ) {
			$downloads = wc_get_customer_available_downloads( $order->get_customer_id() );
			if ( !in_array( $order->get_payment_method(), $payment_methods ) && count( $downloads ) == 0 ) {
				$order->update_status( 'paid' );
			}
		}
	}
}

// Hourly
if ( !wp_next_scheduled( 'cbs_cron_hourly' ) ) {
	wp_schedule_event( time(), 'hourly', 'cbs_cron_hourly' );
}
add_action( 'cbs_cron_hourly', 'cbs_auto_cancel' );
add_action( 'cbs_cron_hourly', 'cbs_auto_redeem' );

function cbs_auto_cancel() {
	$auto_cancel         = get_option( 'course_booking_system_auto_cancel' );
	$auto_cancel_number  = get_option( 'course_booking_system_auto_cancel_number' );
	$auto_cancel_advance = get_option( 'course_booking_system_auto_cancel_advance' );

	if ( !$auto_cancel )
		return;

	global $wpdb;
	$current_time = current_time( 'timestamp' );
	$timestamp = $current_time + $auto_cancel_advance * HOUR_IN_SECONDS;

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$date = date( 'Y-m-d', $timestamp );
	$holiday_start = get_option( 'course_booking_system_holiday_start' );
	$holiday_end   = get_option( 'course_booking_system_holiday_end' );

	$blog_title = get_bloginfo( 'name' );
	$admin_email = get_option( 'admin_email' );
	$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

	$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

	$courses = cbs_get_courses( array(
		'day' => date( 'N', $timestamp ),
		'date' => $date
	) );
	foreach ( $courses as $course ) {
		$attendance           = get_post_meta( $course->post_id, 'attendance', true );
		$free                 = get_post_meta( $course->post_id, 'free', true );
		$price_level          = get_post_meta( $course->post_id, 'price_level', true );
		$timetable_custom_url = get_post_meta( $course->post_id, 'timetable_custom_url', true );

		if ( !empty( $timetable_custom_url ) || ( !empty( $course->date ) && $course->date != $date ) )
			continue;

		$substitute_id = cbs_get_substitute_id( $course->id, $date );
		$user_fullnames = array();

		$attendance_count = cbs_get_attendance_abo( $course->id, $date ) + cbs_get_attendance_booking( $course->id, $date );
		if ( $attendance_count == 0 && $substitute_id != 99999 && !cbs_is_holiday( date( 'd', $timestamp ), date( 'm', $timestamp ), date( 'Y', $timestamp ) ) && ( $date < $holiday_start || $date > $holiday_end ) && ( $current_time + 12 * HOUR_IN_SECONDS ) >= strtotime( $date.' '.$course->start ) && $current_time < strtotime( $date.' '.$course->start ) ) {
			// Substitute
			$wpdb->delete(
				$wpdb->prefix.'cbs_substitutes',
				array( 'course_id' => $course->id, 'date' => $date ),
				array( '%d', '%s')
			);

			$wpdb->insert(
				$wpdb->prefix.'cbs_substitutes',
				array( 'course_id' => $course->id, 'date' => $date, 'user_id' => 99999 ),
				array( '%d', '%s', '%d' )
			);

			// Email to trainer
			$user_id = !empty( $substitute_id ) ? $substitute_id : $course->user_id;
			$user_info = get_userdata( $user_id );
			$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
			$subject = __( 'Your booked course', 'course-booking-system' ).' '.__( 'has been cancelled', 'course-booking-system' );
			$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your booked course', 'course-booking-system' ).' "<strong>'.$course->post_title.'</strong>" '.__( 'from', 'course-booking-system' ).' <strong>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</strong> '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).' '.__( 'did not reach the minimum number of participants and is therefore cancelled', 'course-booking-system' ).'.</p><p style="margin: 0 0 16px;">'.__( 'No customers were registered for the course.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
			$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Cc: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
		} else if ( $attendance > $auto_cancel_number && $attendance_count < $auto_cancel_number && $substitute_id != 99999 && !cbs_is_holiday( date( 'd', $timestamp ), date( 'm', $timestamp ), date( 'Y', $timestamp ) ) && ( $date < $holiday_start || $date > $holiday_end ) && $timestamp >= strtotime( $date.' '.$course->start ) ) {
			foreach ( $abos as $abo ) {
				$user_id = $abo->user_id;
				$abo_start = get_the_author_meta( 'abo_start', $user_id );
				$abo_expire = get_the_author_meta( 'abo_expire', $user_id );
				$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
				$abo_alternate = explode( ',', $abo_alternate );
				if ( $abo_start <= $date && $abo_expire >= $date && !in_array( $date, $abo_alternate ) ) {
					$user_info = get_userdata( $user_id );
					$user_fullnames[] = $user_info->first_name.' '.$user_info->last_name;
					$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
					$subject = __( 'Your booked course', 'course-booking-system' ).' '.__( 'has been cancelled', 'course-booking-system' );
					$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your booked course', 'course-booking-system' ).' "<strong>'.$course->post_title.'</strong>" '.__( 'from', 'course-booking-system' ).' <strong>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</strong> '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).' '.__( 'did not reach the minimum number of participants and is therefore cancelled', 'course-booking-system' ).'.</p><p style="margin: 0 0 16px;">'.__( 'The booking was reversed successfully. A credit has been added to the card.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Please book another course through your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'We look forward to you.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
					$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

					wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
				}
			}

			$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course->id AND date = '$date'" );
			foreach ( $bookings as $booking ) {
				$booking_id = $booking->booking_id;
				$user_id    = $booking->user_id;

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

				// Add credit
				if ( !$free && !( $flat && date( 'Y-m-d', strtotime( $flat_expire ) ) >= $date ) ) {
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
					cbs_log( $user_id, $card_name, $card, $course->id, __FUNCTION__ );

					if ( $expire_extend && get_current_user_id() != $user_id ) {
						$expire = date( 'Y-m-d', strtotime( $expire.' +1 week' ) );
						update_user_meta( $user_id, $expire_name, $expire );
					}
				}

				// Delete booking
				$wpdb->delete(
					$wpdb->prefix.'cbs_bookings',
					array( 'booking_id' => $booking_id, 'course_id' => $course->id, 'date' => $date, 'user_id' => $user_id ),
					array( '%d', '%d', '%s', '%d' )
				);

				// Email
				$user_info = get_userdata( $user_id );
				$user_fullnames[] = $user_info->first_name.' '.$user_info->last_name;
				$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
				$subject = __( 'Your booked course', 'course-booking-system' ).' '.__( 'has been cancelled', 'course-booking-system' );
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your booked course', 'course-booking-system' ).' "<strong>'.$course->post_title.'</strong>" '.__( 'from', 'course-booking-system' ).' <strong>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</strong> '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).' '.__( 'did not reach the minimum number of participants and is therefore cancelled', 'course-booking-system' ).'.</p><p style="margin: 0 0 16px;">'.__( 'The booking was reversed successfully. A credit has been added to the card.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Please book another course through your account:', 'course-booking-system' ).' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.__( 'We look forward to you.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}

			// Substitute
			$wpdb->delete(
				$wpdb->prefix.'cbs_substitutes',
				array( 'course_id' => $course->id, 'date' => $date ),
				array( '%d', '%s')
			);

			$wpdb->insert(
				$wpdb->prefix.'cbs_substitutes',
				array( 'course_id' => $course->id, 'date' => $date, 'user_id' => 99999 ),
				array( '%d', '%s', '%d' )
			);

			// Email to trainer
			$user_id = !empty( $substitute_id ) ? $substitute_id : $course->user_id;
			$user_info = get_userdata( $user_id );
			$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
			$subject = __( 'Your booked course', 'course-booking-system' ).' '.__( 'has been cancelled', 'course-booking-system' );
			$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.__( 'Your booked course', 'course-booking-system' ).' "<strong>'.$course->post_title.'</strong>" '.__( 'from', 'course-booking-system' ).' <strong>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</strong> '.__( 'o\'clock', 'course-booking-system' ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $date ) ).' '.__( 'did not reach the minimum number of participants and is therefore cancelled', 'course-booking-system' ).'.</p><p style="margin: 0 0 16px;">'.__( 'The following customers were registered for the course:', 'course-booking-system' ).'</p><ul style="margin: 0 0 16px;">'; foreach ( $user_fullnames as $user_fullname ) { $body .= '<li>'.$user_fullname.'</li>'; } $body .= '</ul><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
			$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Cc: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
		}
	}
}

function cbs_auto_redeem() {
	$args = array(
		'type' => 'shop_order',
		'limit' => 10,
		'status' => array( 'wc-completed', 'wc-paid' ),
		'orderby' => 'date',
		'order' => 'DESC'
	);
	$orders = wc_get_orders( $args );

	foreach ( $orders AS $order ) :
		cbs_woocommerce_download_file_redeem( $order );
	endforeach;
}

// Daily
if ( !wp_next_scheduled( 'cbs_cron_daily' ) ) {
	wp_schedule_event( time(), 'daily', 'cbs_cron_daily' );
}
add_action( 'cbs_cron_daily', 'cbs_abo_control' );
add_action( 'cbs_cron_daily', 'cbs_card_expire_email' );
add_action( 'cbs_cron_daily', 'cbs_flat_expire_email' );
add_action( 'cbs_cron_daily', 'cbs_birthday_email' );

function cbs_abo_control() {
	$users = get_users( array( 'meta_query' => array( array( 'key' => 'abo_course', 'value' => 0, 'compare' => '>' ) ) ) );
	foreach ( $users as $user ) {
		$user_id         = $user->ID;
		$abo             = get_the_author_meta( 'abo', $user_id );
		$abo_expire_date = get_the_author_meta( 'abo_expire', $user_id );
		$abo_expire      = get_option( 'course_booking_system_abo_expire' );
		$abo_period      = get_option( 'course_booking_system_abo_period' );

		if ( $abo && date( 'Y-m-d', strtotime( $abo_expire_date.' -'.$abo_period.' weeks' ) ) < date( 'Y-m-d' ) ) { // Extend subscription
			update_user_meta( $user_id, 'abo_expire', date( 'Y-m-d', strtotime( $abo_expire_date.' +'.$abo_expire.' months' ) ) );

			$abo_alternate = explode( ',', get_the_author_meta( 'abo_alternate', $user_id ) );
			foreach ( $abo_alternate as $key => $value ) {
				if ( date( 'Y-m-d', strtotime( $value.' +'.$abo_expire.' months' ) ) < date( 'Y-m-d' ) ) {
					unset( $abo_alternate[$key] );
				}
			}
			update_user_meta( $user_id, 'abo_alternate', implode( ',', $abo_alternate ) );
		} else if ( !$abo && date( 'Y-m-d', strtotime( $abo_expire_date ) ) < date( 'Y-m-d' ) ) { // Clean database
			update_user_meta( $user_id, 'abo_course', '' );
			update_user_meta( $user_id, 'abo_alternate', '' );
		}
	}
}

function cbs_card_expire_email() {
	$email_expire = get_option( 'course_booking_system_email_expire' );
	if ( $email_expire ) {
		$blog_title  = get_bloginfo( 'name' );
		$admin_email = get_option( 'admin_email' );

		$subject = get_option( 'course_booking_system_email_expire_subject' );
		$content = get_option( 'course_booking_system_email_expire_content' );
		$content_2 = get_option( 'course_booking_system_email_expire_content_2' );

		$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

		$date = date( 'Y-m-d', strtotime( '+1 week' ) );
		$users = get_users(
			array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'expire',
						'value' => $date
					),
					array(
						'key' => 'expire_2',
						'value' => $date
					),
					array(
						'key' => 'expire_3',
						'value' => $date
					)
				)
			)
		);

		foreach ( $users as $user ) {
			$user_id  = $user->ID;
			$card     = get_the_author_meta( 'card', $user_id );
			$expire   = get_the_author_meta( 'expire', $user_id );
			$card_2   = get_the_author_meta( 'card_2', $user_id );
			$expire_2 = get_the_author_meta( 'expire_2', $user_id );
			$card_3   = get_the_author_meta( 'card_3', $user_id );
			$expire_3 = get_the_author_meta( 'expire_3', $user_id );
			$card_4   = get_the_author_meta( 'card_4', $user_id );
			$expire_4 = get_the_author_meta( 'expire_4', $user_id );
			$card_5   = get_the_author_meta( 'card_5', $user_id );
			$expire_5 = get_the_author_meta( 'expire_5', $user_id );

			if ( ( $card > 0 && $expire == $date ) || ( $card_2 > 0 && $expire_2 == $date ) || ( $card_3 > 0 && $expire_3 == $date ) || ( $card_4 > 0 && $expire_4 == $date ) || ( $card_5 > 0 && $expire_5 == $date ) ) {
				$user_info = get_userdata( $user_id );
				$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
				$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.$content.' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.$content_2.'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
				$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
			}
		}
	}
}

function cbs_flat_expire_email() {
	$email_expire = get_option('course_booking_system_email_expire');
	if ( $email_expire ) {
		$blog_title  = get_bloginfo( 'name' );
		$admin_email = get_option( 'admin_email' );

		$subject = get_option( 'course_booking_system_email_flat_subject' );
		$content = get_option( 'course_booking_system_email_flat_content' );
		$content_2 = get_option( 'course_booking_system_email_flat_content_2' );

		$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$woocommerce_email_base_color = get_option( 'woocommerce_email_base_color' );

		$date = date( 'Y-m-d', strtotime( '+1 week' ) );
		$users = get_users(
			array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'flat_expire',
						'value' => $date
					),
					array(
						'key' => 'flat_expire_2',
						'value' => $date
					),
					array(
						'key' => 'flat_expire_3',
						'value' => $date
					)
				)
			)
		);

		foreach ( $users as $user ) {
			$user_id  = $user->ID;
			$flat_expire   = get_the_author_meta( 'flat_expire', $user_id );
			$flat_expire_2 = get_the_author_meta( 'flat_expire_2', $user_id );
			$flat_expire_3 = get_the_author_meta( 'flat_expire_3', $user_id );

			$user_info = get_userdata( $user_id );
			$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
			$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.$content.' <a class="link" href="'.$account_url.'" style="font-weight: normal; text-decoration: underline; color: '.$woocommerce_email_base_color.';">'.$account_url.'</a></p><p style="margin: 0 0 16px;">'.$content_2.'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
			$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
		}
	}
}

function cbs_birthday_email() {
	$blog_title     = get_bloginfo( 'name' );
	$admin_email    = get_option( 'admin_email' );
	$birthday_email = get_option( 'course_booking_system_woocommerce_birthday_email' );

	if ( !$birthday_email )
		return;

	$subject = get_option( 'course_booking_system_email_birthday_subject' );
	$content = get_option( 'course_booking_system_email_birthday_content' );

	$users = get_users();
	foreach ( $users as $user ) {
		$user_id  = $user->ID;
		$birthday = get_the_author_meta( 'birthday', $user_id );

		if ( !empty( $birthday ) && strtotime( $birthday ) && date( 'm-d' ) == date( 'm-d', strtotime( $birthday ) ) ) {
			$user_info = get_userdata( $user_id );
			$to = $user_info->first_name.' '.$user_info->last_name.' <'.$user_info->user_email.'>';
			$body = '<p style="margin: 0 0 16px;">'.__( 'Dear', 'course-booking-system' ).' '.$user_info->first_name.' '.$user_info->last_name.',</p><p style="margin: 0 0 16px;">'.$content.'</p><p style="margin: 0 0 16px;">'.__( 'We look forward to your next course with us.', 'course-booking-system' ).'</p><p style="margin: 0 0 16px;">'.__( 'Your team from', 'course-booking-system' ).' '.$blog_title;
			$headers = array( 'From: '.$blog_title.' <'.$admin_email.'>', 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $to, $subject, cbs_email_template( $subject, $body ), $headers );
		}
	}
}

// Weekly
if ( !wp_next_scheduled( 'cbs_cron_weekly' ) ) {
	wp_schedule_event( time(), 'daily', 'cbs_cron_weekly' );
}
add_action( 'cbs_cron_weekly', 'cbs_clean_database' );

function cbs_clean_database() {
	global $wpdb;

	// Delete old entries to keep databases small and clean
	$date = date( 'Y-m-d', strtotime( '-36 months' ) );
	$wpdb->get_results( "DELETE FROM ".$wpdb->prefix."cbs_bookings WHERE date < '$date'" );
	$wpdb->get_results( "DELETE FROM ".$wpdb->prefix."cbs_cancellations WHERE date < '$date'" );
	$wpdb->get_results( "DELETE FROM ".$wpdb->prefix."cbs_notes WHERE date < '$date'" );
	$wpdb->get_results( "DELETE FROM ".$wpdb->prefix."cbs_substitutes WHERE date < '$date'" );
	$wpdb->get_results( "DELETE FROM ".$wpdb->prefix."cbs_waitlists WHERE date < '$date'" );

	$timestamp = date( 'Y-m-d', strtotime( '-3 months' ) );
	$wpdb->get_results( "DELETE FROM ".$wpdb->prefix."cbs_logs WHERE timestamp < '$date'" );
}
