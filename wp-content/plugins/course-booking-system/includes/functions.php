<?php
function cbs_is_licensed() {
	$license = get_option( 'course_booking_system_license' );

	$wpdb2 = new WPDB( 'cbs_slave', 'o748oo3#U', 'course-booking-system-license', '81.169.149.115:3306');
	$licenses = $wpdb2->get_results( "SELECT * FROM licenses WHERE license = '$license' LIMIT 1" );

	if ( ( $licenses && count( $licenses ) > 0 ) || $license == '8XNZF-S6YLZ-70NGF-01SPR' )
		return true;

	return false;
}

function cbs_is_holiday( $day, $month, $year ) {
	// Always use standard format with two numbers for days and months
	if ( strlen( $day ) == 1 ) :
		$day = "0$day";
	endif;
	if ( strlen( $month ) == 1 ) :
		$month = "0$month";
	endif;

	// Fixed holidays
	if ( get_option( 'course_booking_system_holiday_new_year' ) ) {
		$holidays[] = '01.01.'; // New Year
	} if ( get_option( 'course_booking_system_holiday_epiphany' ) ) {
		$holidays[] = '06.01.'; // Epiphany
	} if ( get_option( 'course_booking_system_holiday_labor_day' ) ) {
		$holidays[] = '01.05.'; // Labor Day
	} if ( get_option( 'course_booking_system_holiday_national_holiday' ) ) {
		$holidays[] = '03.10.'; // National Holiday (Germany)
	} if ( get_option( 'course_booking_system_holiday_national_holiday_austria' ) ) {
		$holidays[] = '26.10.'; // National Holiday (Austria)
	} if ( get_option( 'course_booking_system_holiday_reformation_day' ) ) {
		$holidays[] = '31.10.'; // Reformation Day
	} if ( get_option( 'course_booking_system_holiday_all_saints_day' ) ) {
		$holidays[] = '01.11.'; // All Saints' Day
	} if ( get_option( 'course_booking_system_holiday_christmas_eve' ) ) {
		$holidays[] = '24.12.'; // Christmas Eve
	} if ( get_option( 'course_booking_system_holiday_christmas_day_1' ) ) {
		$holidays[] = '25.12.'; // 1. Christmas Day
	} if ( get_option( 'course_booking_system_holiday_christmas_day_2' ) ) {
		$holidays[] = '26.12.'; // 2. Christmas Day
	} if ( get_option( 'course_booking_system_holiday_new_years_eve' ) ) {
		$holidays[] = '31.12.'; // New Year's Eve
	}

	// Calculate moving holidays
	if ( function_exists( 'easter_date' ) && !empty( $year ) && $year >= 1970 && $year <= 2037 ) {
		date_default_timezone_set( get_option( 'timezone_string' ) );
		$easter_date = easter_date( $year );
		if ( get_option( 'course_booking_system_holiday_good_friday' ) ) {
			$holidays[] = date( 'd.m.', $easter_date - 2 * DAY_IN_SECONDS );  // Good Friday
		} if ( get_option( 'course_booking_system_holiday_easter_sunday' ) ) {
			$holidays[] = date( 'd.m.', $easter_date );					   // Easter Sunday
		} if ( get_option( 'course_booking_system_holiday_easter_monday' ) ) {
			$holidays[] = date( 'd.m.', $easter_date + 1 * DAY_IN_SECONDS );  // Easter Monday
		} if ( get_option( 'course_booking_system_holiday_ascension' ) ) {
			$holidays[] = date( 'd.m.', $easter_date + 39 * DAY_IN_SECONDS ); // Ascension
		} if ( get_option( 'course_booking_system_holiday_whit_sunday' ) ) {
			$holidays[] = date( 'd.m.', $easter_date + 49 * DAY_IN_SECONDS ); // Whit Sunday
		} if ( get_option( 'course_booking_system_holiday_whit_monday' ) ) {
			$holidays[] = date( 'd.m.', $easter_date + 50 * DAY_IN_SECONDS ); // Whit Monday
		} if ( get_option( 'course_booking_system_holiday_corpus_christi' ) ) {
			$holidays[] = date( 'd.m.', $easter_date + 60 * DAY_IN_SECONDS ); // Corpus Christi
		}
		date_default_timezone_set( 'UTC' );
	}

	// Check if set date is a holiday
	$code = $day.'.'.$month.'.';
	if ( !empty( $holidays ) && in_array( $code, $holidays ) ) :
		return true;
	else :
		return false;
	endif;
}

function cbs_get_courses( $args = array() ) {
	global $wpdb;

	if ( ( is_admin() && !wp_doing_ajax() ) || ( array_key_exists( 'post_status', $args ) && $args['post_status'] == 'any' ) ) :
		$post_status = "post_status = post_status";
	else :
		$post_status = "post_status = 'publish'";
	endif;

	if ( empty( $args ) ) :
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id WHERE ".$post_status." ORDER BY day" );
		return $courses;
	endif;

	$category = '';
	if ( array_key_exists( 'category', $args ) && !empty( $args['category'] ) )
		$category = "AND term_taxonomy_id IN (".$args['category'].")";

	$time = '';
	if ( array_key_exists( 'start', $args ) && array_key_exists( 'end', $args ) && !empty( $args['start'] ) && !empty( $args['end'] ) )
		$time = "AND start >= '".$args['start']."' AND start <= '".$args['end']."'";

	if ( array_key_exists( 'day', $args ) && array_key_exists( 'date', $args ) ) :
		$day = $args['day'];
		$date = $args['date'];
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id LEFT JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id WHERE ".$post_status." AND (day = $day OR date = '$date') ".$category." ".$time." GROUP BY id ORDER BY start" );
		return $courses;
	endif;

	if ( array_key_exists( 'id', $args ) ) :
		$id = $args['id'];
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id LEFT JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id WHERE ".$post_status." AND ".$wpdb->prefix."cbs_data.id = $id ".$category." ".$time." GROUP BY id ORDER BY day,start" );
	endif;

	if ( array_key_exists( 'post_id', $args ) ) :
		$post_id = $args['post_id'];
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id LEFT JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id WHERE ".$post_status." AND ".$wpdb->prefix."cbs_data.post_id = $post_id ".$category." ".$time." GROUP BY id ORDER BY day,date,start" );
	endif;

	if ( array_key_exists( 'day', $args ) ) :
		$day = $args['day'];
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id LEFT JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id WHERE ".$post_status." AND day = $day ".$category." ".$time." GROUP BY id ORDER BY start" );
	endif;

	if ( array_key_exists( 'date', $args ) ) :
		$date = $args['date'];
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id LEFT JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id WHERE ".$post_status." AND date = '$date' ".$category." ".$time." GROUP BY id ORDER BY start" );
	endif;

	if ( array_key_exists( 'user_id', $args ) ) :
		$user_id = $args['user_id'];
		$courses = $wpdb->get_results( "SELECT ".$wpdb->prefix."cbs_data.id AS id, post_id, post_title, day, date, start, end, user_id FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id LEFT JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id WHERE ".$post_status." AND user_id = $user_id ".$category." ".$time." GROUP BY id ORDER BY day" );
	endif;

	return $courses;
}

function cbs_has_courses( $args ) {
	$courses = cbs_get_courses( $args );
	if ( empty( $courses ) )
		return false;

	/* $date = date( 'Y-m-d' );
	if ( array_key_exists( 'date', $args ) )
		$date = $args['date'];

	foreach ( $courses AS $course ) :
		$substitute_id = cbs_get_substitute_id( $course->id, $date );
		if ( $substitute_id != 99999 && ( empty( $course->date ) || $course->date < $date ) )
			return true;
	endforeach;

	return false; */

	return true;
}

function cbs_get_weekday( $course_day ) {
	$weekday = strtotime( 'Sunday +'.$course_day.' days' );
	return date_i18n( 'l', $weekday );
}

function cbs_get_weekday_permalink( $course_day, $date = '' ) {
	$permalink = site_url( '/course/?weekday='.$course_day );

	if ( !empty( $date ) )
		$permalink .= '&date='.$date;

	return $permalink;
}

function cbs_get_substitute_id( $course_id, $date ) {
	global $wpdb;

	$return = 0;
	$substitutes = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_substitutes WHERE course_id = $course_id AND date = '$date' LIMIT 1" );
	foreach ( $substitutes AS $substitute ) {
		$return = $substitute->user_id;
	}

	return $return;
}

function cbs_get_attendance_abo( $course_id, $date ) {
	global $wpdb;

	$return = 0;
	$abos = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE (meta_key = 'abo_course' AND meta_value = $course_id) OR (meta_key = 'abo_course_2' AND meta_value = $course_id) OR (meta_key = 'abo_course_3' AND meta_value = $course_id)" );
	foreach ( $abos as $abo ) {
		$abo_start = get_the_author_meta( 'abo_start', $abo->user_id );
		$abo_expire = get_the_author_meta( 'abo_expire', $abo->user_id );
		$abo_alternate = get_the_author_meta( 'abo_alternate', $abo->user_id );
		$abo_alternate = explode( ',', $abo_alternate );
		if ( $abo_start <= $date && $abo_expire >= $date && !in_array( $date, $abo_alternate ) ) {
			$return++;
		}
	}

	return $return;
}

function cbs_get_attendance_booking( $course_id, $date ) {
	global $wpdb;
	$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course_id AND date = '$date'" );

	return count( $bookings );
}

function cbs_get_attendance( $course_id, $date ) {
	global $wpdb;

	$return = 0;
	$attendances = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_attendances WHERE course_id = $course_id AND date = '$date' LIMIT 1" );
	foreach ( $attendances AS $attendance ) {
		$return = $attendance->attendance;
	}

	return $return;
}

function cbs_attendance( $course_id, $date, $echo = true ) {
	global $wpdb;

	$posts = $wpdb->get_results( "SELECT post_id FROM ".$wpdb->prefix."cbs_data WHERE id = $course_id LIMIT 1" );
	foreach ( $posts as $post ) {
		$id = $post->post_id;
	}

	$custom	 = get_post_custom( $id );
	$attendance = $custom['attendance'][0];
	$attendance_count = 0;

	if ( cbs_get_attendance_abo( $course_id, $date ) ) {
		$attendance_count += cbs_get_attendance_abo( $course_id, $date );
	}

	if ( cbs_get_attendance_booking( $course_id, $date ) ) {
		$attendance_count += cbs_get_attendance_booking( $course_id, $date );
	}

	if ( cbs_get_attendance( $course_id, $date ) ) {
		$attendance = cbs_get_attendance( $course_id, $date );
	}

	if ( ( intval( $attendance ) - intval( $attendance_count ) ) > 5 ) {
		$content = __( 'Availability:', 'course-booking-system' ).' '.sprintf( __( 'more than 5', 'course-booking-system' ), ( intval( $attendance ) - intval( $attendance_count ) ) );
	} else {
		$content = __( 'Availability:', 'course-booking-system' ).' '.( intval( $attendance ) - intval( $attendance_count ) );
	}
	$content .= '<time datetime="'.$date.'">'.$date.'</time>';

	if ( $echo )
		echo $content;

	return $content;
}

function cbs_get_note( $course_id, $date ) {
	global $wpdb;

	$return = 0;
	$notes = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_notes WHERE course_id = $course_id AND date = '$date' LIMIT 1" );
	foreach ( $notes AS $note ) {
		$return = $note->note;
	}

	return $return;
}

function cbs_log( $user_id, $card_name, $card, $course_id, $action ) {
	global $wpdb;
	$table_name = $wpdb->prefix.'cbs_logs';
	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

	if ( $wpdb->get_var( $query ) == $table_name ) {
		$wpdb->insert( $table_name,
			array( 'user_id' => $user_id, 'card_name' => $card_name, 'card' => $card, 'course_id' => $course_id, 'action' => $action ),
			array( '%d', '%s', '%d', '%d', '%s' )
		);
	}
}

function cbs_email_template( $subject, $body ) {
	$blog_title = get_bloginfo( 'name' );
	$woocommerce_email_header_image = !empty( get_option( 'woocommerce_email_header_image' ) ) ? get_option( 'woocommerce_email_header_image' ) : '';
	$woocommerce_email_base_color = !empty( get_option( 'woocommerce_email_base_color' ) ) ? get_option( 'woocommerce_email_base_color' ) : '#96588a';
	$woocommerce_email_background_color = !empty( get_option( 'woocommerce_email_background_color' ) ) ? get_option( 'woocommerce_email_background_color' ) : '#f7f7f7';
	$woocommerce_email_body_background_color = !empty( get_option( 'woocommerce_email_body_background_color' ) ) ? get_option( 'woocommerce_email_body_background_color' ) : '#ffffff';
	$woocommerce_email_text_color = !empty( get_option( 'woocommerce_email_text_color' ) ) ? get_option( 'woocommerce_email_text_color' ) : '#3c3c3c';

	if ( !empty( $woocommerce_email_header_image ) ) {
		$woocommerce_email_header_image = '
			<p style="margin-top: 0;">
				<img src="'.$woocommerce_email_header_image.'" alt="Logo" style="border: none; display: inline-block; font-size: 14px; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; vertical-align: middle; margin-left: 0; margin-right: 0;">
			</p>';
	}

	$email_template = '
		<!DOCTYPE html><html lang="de-DE">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<meta content="width=device-width, initial-scale=1.0" name="viewport">
				<title>'.$blog_title.'</title>
				<style type="text/css">@media screen and (max-width: 600px){#header_wrapper{padding: 27px 36px !important; font-size: 24px;}#body_content table > tbody > tr > td{padding: 10px !important;}#body_content_inner{font-size: 10px !important;}}</style>
			</head>
			<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color: #f7f7f7; padding: 0; text-align: center;" bgcolor="#f7f7f7">
				<table width="100%" id="outer_wrapper" style="background-color: '.$woocommerce_email_background_color.';" bgcolor="'.$woocommerce_email_background_color.'"><tr>
					<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
					<td width="600">
						<div id="wrapper" dir="ltr" style="margin: 0 auto; padding: 70px 0; width: 100%; max-width: 600px; -webkit-text-size-adjust: none;" width="100%">
							<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
								<tr>
									<td align="center" valign="top">
										<div id="template_header_image">'.$woocommerce_email_header_image.'</div>
										<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" style="background-color: '.$woocommerce_email_body_background_color.'; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,.1); border-radius: 3px;" bgcolor="'.$woocommerce_email_body_background_color.'">
											<tr>
												<td align="center" valign="top">
													<!-- Header -->
													<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: '.$woocommerce_email_base_color.'; color: #fff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: Helvetica Neue,Helvetica,Roboto,Arial,sans-serif; border-radius: 3px 3px 0 0;" bgcolor="'.$woocommerce_email_base_color.'"><tr>
														<td id="header_wrapper" style="padding: 36px 48px; display: block;">
															<h1 style="font-family: Helvetica Neue,Helvetica,Roboto,Arial,sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #fff; background-color: inherit;" bgcolor="inherit">'.$subject.'</h1>
														</td>
													</tr></table>
													<!-- End Header -->
												</td>
											</tr>
											<tr>
												<td align="center" valign="top">
													<!-- Body -->
													<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body"><tr>
														<td valign="top" id="body_content" style="background-color: '.$woocommerce_email_body_background_color.';" bgcolor="'.$woocommerce_email_body_background_color.'">
															<!-- Content -->
															<table border="0" cellpadding="20" cellspacing="0" width="100%"><tr>
																<td valign="top" style="padding: 48px 48px 32px;">
																	<div id="body_content_inner" style="color: #636363; font-family: Helvetica Neue,Helvetica,Roboto,Arial,sans-serif; font-size: 14px; line-height: 150%; text-align: left;" align="left">'.$body.'</div>
																</td>
															</tr></table>
															<!-- End Content -->
														</td>
													</tr></table>
													<!-- End Body -->
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align="center" valign="top">
										<!-- Footer -->
										<table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer"><tr>
											<td valign="top" style="padding: 0; border-radius: 6px;">
												<table border="0" cellpadding="10" cellspacing="0" width="100%"><tr>
													<td colspan="2" valign="middle" id="credit" style="border-radius: 6px; border: 0; color: #8a8a8a; font-family: Helvetica Neue,Helvetica,Roboto,Arial,sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;" align="center">
														<p style="margin: 0 0 16px;">'.$blog_title.'</p>
													</td>
												</tr></table>
											</td>
										</tr></table>
										<!-- End Footer -->
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
				</tr></table>
			</body>
		</html>';

	return $email_template;
}

if ( !function_exists( 'woocommerce_wp_multi_select' ) ) {
	function woocommerce_wp_multi_select( $field, $variation_id = 0 ) {
		global $thepostid, $post;

		if ( $variation_id == 0 )
			$the_id = empty( $thepostid ) ? $post->ID : $thepostid;
		else
			$the_id = $variation_id;

		$field['class']		    = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['name']		    = isset( $field['name'] ) ? $field['name'] : $field['id'];

		$meta_data			    = maybe_unserialize( get_post_meta( $the_id, $field['id'], true ) );
		$meta_data			    = $meta_data ? $meta_data : array() ;

		$field['value'] = isset( $field['value'] ) ? $field['value'] : $meta_data;

		echo '<p class="form-field '.esc_attr( $field['id'] ).'_field '.esc_attr( $field['wrapper_class'] ).'"><label for="'.esc_attr( $field['id'] ).'">'.wp_kses_post( $field['label'] ).'</label><select id="'.esc_attr( $field['id'] ).'" name="'.esc_attr( $field['name'] ).'" class="'.esc_attr( $field['class'] ).'" multiple="multiple">';

		foreach ( $field['options'] as $key => $value ) {
			echo '<option value="'.esc_attr( $key ).'" '.( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ).'>'.esc_html( $value ).'</option>';
		}
		echo '</select>';
		echo '<span class="description"><a href="javascript:void(0);" onclick="jQuery( \'#'.esc_attr( $field['id'] ).'\' ).prop( \'selectedIndex\', -1 );">'.__( 'Reset selection', 'course-booking-system' ).'</a></span>';
		if ( !empty( $field['description'] ) ) {
			if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
				echo '<span class="woocommerce-help-tip help_tip" data-tip="'.esc_attr( $field['description'] ).'"></span>';
			} else {
				echo '<span class="description">'.wp_kses_post( $field['description'] ).'</span>';
			}
		}
	}
}