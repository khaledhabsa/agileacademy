<?php
function cbs_shortcode_timetable( $atts = array(), $date = '' ) {
	global $wpdb;

	// Variables
	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$current_time = current_time( 'timestamp' );

	$booking_in_advance = get_option( 'course_booking_system_booking_in_advance' );
	$show_availability = get_option( 'course_booking_system_show_availability' );
	$show_cancelled = get_option( 'course_booking_system_show_cancelled' );

	$ajax = true;
	$content = '';

	// Attributes
	$category = ( !empty( $atts ) && array_key_exists( 'category', $atts ) ) ? $atts['category'] : '';
	$category = ( is_array( $category ) ) ? implode( ',', $category ) : $category; // Blocks
	$design = ( !empty( $atts ) && array_key_exists( 'design', $atts ) ) ? $atts['design'] : get_option( 'course_booking_system_design' ); // Shortcode or default option
	$design = ( !empty( $_REQUEST['design'] ) ) ? htmlspecialchars( $_REQUEST['design'] ) : $design; // AJAX

	if ( empty( $date ) && !empty( $atts ) && array_key_exists( 'date', $atts ) ) :
		$ajax = false;
		$date = $atts['date'];
	elseif ( empty( $date ) && $booking_in_advance > 1 ) :
		$ajax = false;
		$date = date( 'Y-m-d' );
		if ( date( 'N' ) == 7 && $current_time > strtotime( $date.' 17:00:00' ) )
			$date = date( 'Y-m-d', strtotime( '+1 day' ) );

		$content .= '<div class="cbs-pagination">
			<a href="#" class="action-week cbs-week cbs-week-prev" data-category="'.$category.'" data-design="'.$design.'" data-direction="prev" style="display: none;">'.__( 'Previous week', 'course-booking-system' ).'</a>
			<a href="#" class="action-week cbs-week cbs-week-next" data-category="'.$category.'" data-design="'.$design.'" data-direction="next">'.__( 'Next week', 'course-booking-system' ).'</a>
		</div>
		<div id="ajax">';
	endif;

	if ( $design == 'divided' ) :
		$daytimes = array (
			array( 'id' => 'morning', 'title' => __( 'Morning', 'course-booking-system' ), 'start' => '00:00:00', 'end' => '11:29:59' ),
			array( 'id' => 'noon', 'title' => __( 'Noon', 'course-booking-system' ), 'start' => '11:30:00', 'end' => '14:29:59' ),
			array( 'id' => 'evening', 'title' => __( 'Evening', 'course-booking-system' ), 'start' => '14:30:00', 'end' => '23:59:59' )
		);
	else :
		$daytimes = array (
			array( 'id' => 'day', 'title' => 'Day', 'start' => '00:00:00', 'end' => '23:59:59' )
		);
	endif;
	for ( $k = 0; $k < count( $daytimes ); $k++ ) {
		$content .= '<div class="cbs-timetable '.$design.' '.$daytimes[$k]['id'].'" data-design="'.$design.'">';
			for ( $i = 1; $i <= 7; $i++ ) {
				$weekday = strtotime( 'Sunday +'.$i.' days' );

				if ( $booking_in_advance == 1 && date( 'N' ) > date( 'N', $weekday ) ) :
					$day = $date.' next Sunday +'.$i.' days';
				else :
					$day = date( 'l' ) == $weekday ? 'today' : $date.' last Sunday +'.$i.' days';
				endif;

				$args = array(
					'category' => $category,
					'day'      => $i,
					'date'     => date( 'Y-m-d', strtotime( $day ) ),
					'start'    => $daytimes[$k]['start'],
					'end'      => $daytimes[$k]['end']
				);
				$courses = cbs_get_courses( $args );
				array_splice( $args, 3 ); // Remove last two elements (start, end)
				if ( !empty( $courses ) || ( $design == 'divided' && cbs_has_courses( $args ) ) ) :
					if ( strtotime( $day ) < strtotime( 'today' ) ) :
						$content .= '<div class="cbs-timetable-column disabled">';
					else :
						$content .= '<div class="cbs-timetable-column">';
					endif;
						$content .= '<h4><a href="'.cbs_get_weekday_permalink( $i, date( 'Y-m-d', strtotime( $day ) ) ).'">'.date_i18n( 'l', $weekday ).'</a> <time datetime="'.date( 'Y-m-d', strtotime( $day ) ).'">'.date_i18n( $date_format, strtotime( $day ) ).'</time></h4>';

						$content .= '<ul class="cbs-timetable-list">';

							if ( $design == 'divided' && empty( $courses ) || ( !cbs_has_courses( $args ) && !$show_cancelled ) )
								$content .= '<small class="cbs-no-courses">'.__( 'No courses at this time.', 'course-booking-system' ).'</small>';

							foreach ( $courses as $course ) :
								$disabled = '';
								$substitute_id = cbs_get_substitute_id( $course->id, date( 'Y-m-d', strtotime( $day ) ) );
								if ( $substitute_id == 99999 || cbs_is_holiday( date( 'd', strtotime( $day ) ), date( 'm', strtotime( $day ) ), date( 'Y', strtotime( $day ) ) ) || ( $course->day != 99 && !empty( $course->date ) && $course->date > date( 'Y-m-d',strtotime( $day ) ) ) ) :
									if ( $show_cancelled ) :
										$disabled = 'disabled';
									else :
										continue;
									endif;
								endif;

								$user = get_userdata( $course->user_id );

    							$slide = floor( ( strtotime( $day ) - strtotime( 'last '.date( 'l' ) ) ) / 604800 ) - 1;
								$permalink = get_permalink( $course->post_id ).'?course_id='.$course->id.'&slide='.$slide;
								$timetable_custom_url = get_post_meta( $course->post_id, 'timetable_custom_url', true );
								if ( !empty( $timetable_custom_url ) )
									$permalink = $timetable_custom_url;

								if ( has_post_thumbnail( $course->post_id ) ) :
									$content .= '<li class="cbs-timetable-list-item '.$disabled.'"><a title="'.$course->post_title.'" href="'.$permalink.'" style="background-color: '.get_post_meta( $course->post_id, 'color', true ).'; color: '.get_post_meta( $course->post_id, 'text_color', true ).'; background-image: url('.get_the_post_thumbnail_url( $course->post_id ).');">
										<div class="overlay" style="background-color: '.get_post_meta( $course->post_id, 'color', true ).'"></div>';
								else :
									$content .= '<li class="cbs-timetable-list-item '.$disabled.'"><a title="'.$course->post_title.'" href="'.$permalink.'" style="background-color: '.get_post_meta( $course->post_id, 'color', true ).'; color: '.get_post_meta( $course->post_id, 'text_color', true ).';">';
								endif;

									$post_date = get_the_date( 'Y-m-d', $course->post_id );
									$post_modified = get_the_modified_date( 'Y-m-d', $course->post_id );

									$max_ids = $wpdb->get_results( "SELECT MAX(id) AS id FROM ".$wpdb->prefix."cbs_data" );
									foreach ( $max_ids AS $max_id ) {
										$last_id = $max_id->id;
									}

									if ( $post_date > date( 'Y-m-d', strtotime( '-14 days' ) ) || ( $course->id == $last_id && $post_modified > date( 'Y-m-d', strtotime( '-1 month' ) ) ) )
										$content .= '<span title="'.__( 'New', 'course-booking-system' ).'" class="new">'.__( 'New', 'course-booking-system' ).'</span>';

									$content .= '<p class="timeslot">
										<time datetime="'.date( 'H:i', strtotime( $course->start ) ).'" class="timeslot-start">'.date( $time_format, strtotime( $course->start ) ).'</time>
										<span class="timeslot-delimiter"> - </span>
										<time datetime="'.date( 'H:i', strtotime( $course->end ) ).'" class="timeslot-end">'.date( $time_format, strtotime( $course->end ) ).'</time>
									</p>';

									$invitation_link = get_post_meta( $course->post_id, 'invitation_link', true );
									if ( !empty( $invitation_link ) ) :
										$content .= '<h5><span class="dashicons dashicons-controls-play"></span> '.$course->post_title.'</h5>';
									else :
										$content .= '<h5>'.$course->post_title.'</h5>';
									endif;

									$attendance = get_post_meta( $course->post_id, 'attendance', true );
									if ( $attendance > 0 && strtotime( $day ) >= strtotime( 'today' ) && ( empty( $course->date ) || strtotime( $course->date ) <= strtotime( $day ) ) && $substitute_id != 99999 && ( $show_availability || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) )
										$content .= '<p class="attendance" data-id="'.$course->id.'">'. cbs_attendance( $course->id, date( 'Y-m-d', strtotime( $day ) ), false ).'</p>';

									if ( $substitute_id == 99999 || ( !empty( $course->date ) && strtotime( $course->date ) > strtotime( $day ) ) ) :
										$content .= '<p class="trainer">'.__( 'Course is cancelled', 'course-booking-system' ).'</p>';
									elseif ( !empty( $substitute_id ) ) :
										$user_info = get_userdata( $substitute_id );
										$content .= '<p class="trainer trainer-has-substitute">'.$user_info->display_name.'</p>';
									else :
										$content .= '<p class="trainer">'.esc_html( $user->display_name ).'</p>';
									endif;
								$content .= '</a></li>';
							endforeach;

						$content .= '</ul>';
					$content .= '</div>';
				endif;
			}
		$content .= '</div>';
	}

	if ( !$ajax )
		$content .= '</div>
		<div id="ajax-loader" class="loader"><div></div><div></div><div></div></div>
		<div id="booking_in_advance" data-id="'.$booking_in_advance.'"></div>';

	return $content;
}

function cbs_shortcode_statistics( $atts ) {
	global $wpdb;

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	if ( !empty( $_GET['start'] ) ) {
		$start = sanitize_text_field( $_GET['start'] );
	} else {
		$start = date( 'Y-m-d', strtotime( '-1 week' ) );
	} if ( !empty( $_GET['end'] ) ) {
		$end = sanitize_text_field( $_GET['end'] );
	} else {
		$end = date( 'Y-m-d', strtotime( $start.' +6 days' ) );
	}
	
	$content = '<form method="GET" action="'.$_SERVER['REQUEST_URI'].'" accept-charset="utf-8">
		<p>'.__( 'Please choose a start date for a week long period. The system then automatically determines the end date of the period.', 'course-booking-system' ).'</p>

		<label>'.__( 'Start of period', 'course-booking-system' ).'</label>
		<input type="date" name="start" value="'.$start.'">

		<!-- <label>'.__( 'End of period', 'course-booking-system' ).'</label>
		<input type="date" name="end" value="'.$end.'"> -->

		<input type="submit" value="'.__( 'Search period', 'course-booking-system' ).'">
		<small><a href="'.$_SERVER['REQUEST_URI'].'">× '.__( 'Delete period', 'course-booking-system' ).'</a></small>
	</form>';

	if ( is_user_logged_in() && current_user_can( 'administrator' ) ) :
		$content .= '<h2>'.__( 'Number of courses booked (excluding subscriptions)', 'course-booking-system' ).'</h2>';
		$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE date >= '$start' AND date <= '$end'" );
		$content .= '<p><strong>'.count( $bookings ).'</strong> '.__( 'Bookings', 'woocommerce' ).' '.__( 'in the period', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end ) ).'.</p>';
		$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE date >= '".date( 'Y-m-01', strtotime( $start ) )."' AND date <= '".date( 'Y-m-t', strtotime( $start ) )."'" );
		$content .= '<p><strong>'.count( $bookings ).'</strong> '.__( 'Bookings', 'woocommerce' ).' '.__( 'in the month', 'course-booking-system' ).' '.date_i18n( 'F', strtotime( $start ) ).'.</p>';

		$abos = $abo_alternates = 0;

		$users = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE (meta_key = 'abo' AND meta_value = 1) OR (meta_key = 'abo_2' AND meta_value = 1) OR (meta_key = 'abo_3' AND meta_value = 1)" );
		foreach ( $users as $user ) {
			$user_id       = $user->user_id;
			$abo_start     = get_the_author_meta( 'abo_start', $user_id );
			$abo_expire    = get_the_author_meta( 'abo_expire', $user_id );
			$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
			$abo_alternate = explode( ',', $abo_alternate );

			if ( $abo_start <= $start && $abo_expire >= $end ) {
				$abos++;
			}

			foreach ( $abo_alternate as $abo_alternate_date ) {
				if ( $abo_alternate_date >= $start && $abo_alternate_date <= $end ) {
					$abo_alternates++;
				}
			}
		}

		if ( $abos > 0 ) :
			$content .= '<h2>'.__( 'Number of subscriptions', 'course-booking-system' ).'</h2>';
			$content .= '<p><strong>'.( $abos - $abo_alternates ).'</strong> '.__( 'Subscriptions', 'course-booking-system' ).' '.__( 'in the period', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end ) ).' ('.$abos.' '.__( 'Subscriptions', 'course-booking-system' ).', '.$abo_alternates.' '.__( 'Unsubscriptions', 'course-booking-system' ).').</p>';
		endif;

		$content .= '<h2>'.__( 'Number of courses per trainer', 'course-booking-system' ).'</h2>';
		$content .= '<p class="statistics-note">'.__( 'Events on specific dates are not included.', 'course-booking-system' ).'</p>';

		$user_info = '';
		$users = get_users( [ 'role__in' => [ 'administrator', 'editor', 'author', 'contributor' ] ] );
		foreach ( $users AS $user ) {
			$hours = 0;
			$list  = '<ul>';
			$user_id = $user->ID;

			$courses = cbs_get_courses( array(
				'user_id' => $user_id
			) );
			foreach ( $courses as $course ) {
				$course_id = $course->id;
				$post_id   = $course->post_id;
				$day       = $course->day;
				$date      = $course->date;
				$start     = $course->start;
				$end       = $course->end;
				
				$hours++;

				// Substitutes to remove
				$substitutes = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."cbs_substitutes WHERE course_id = $course_id AND user_id <> $user_id AND date >= '$start' AND date <= '$end'" );
				$hours = $hours - count( $substitutes );

				if ( empty( $substitutes ) ) {
					$list .= '<li>'.cbs_get_weekday( $day ).', '.get_the_title( $post_id ).', '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).'</li>';
				} else {
					$list .= '<li><del>';
					$list .= cbs_get_weekday( $day ).', '.get_the_title( $post_id ).', '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) );
					foreach ( $substitutes as $substitute ) {
						if ( $substitute->$user_id = 99999 ) {
							$list .= ' ('.__( 'Course is cancelled', 'course-booking-system' ).')';
						} else {
							$list .= ' ('.__( 'Substitute', 'course-booking-system' ).': ';
						}
					}
					$list .= '</del></li>';
				}
			}

			// Substitutes from other classes to add
			$substitutes = $wpdb->get_results( "SELECT course_id FROM ".$wpdb->prefix."cbs_substitutes WHERE user_id = $user_id AND date >= '$start' AND date <= '$end'" );
			$hours = $hours + count( $substitutes );

			foreach ( $substitutes as $substitute ) {
				$courses = cbs_get_courses( array(
					'id' => $substitute->course_id
				) );
				foreach ( $courses as $course ) {
					$course_id   = $course->id;
					$post_id     = $course->post_id;
					$day         = $course->day;
					$date        = $course->date;
					$start       = $course->start;
					$end         = $course->end;

					$list .= '<li>'.__( 'Substitute', 'course-booking-system' ).': '.cbs_get_weekday( $day ).', '.get_the_title( $post_id ).', '.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).'</li>';
				}
			}

			$list .= '</ul>';

			if ( $hours > 0 ) {
				$user_info = get_userdata( $user_id );
				if ( $user_id == 99999 ) {
					$display_name = __( 'Course is cancelled', 'course-booking-system' );
				} else {
					$display_name = esc_html( $user_info->display_name );
				}
				$content .= '<h4>'.$display_name.': '.$hours.' '.__( 'hours', 'course-booking-system' ).' ('.( $hours - count( $substitutes ) ).' '.__( 'regular hours', 'course-booking-system' ).', '.count( $substitutes ).' '.__( 'Substitutes', 'course-booking-system' ).')</h4>';
				$content .= $list;
			}
		}

		$content .= '<h2 class="statistics-orders-headline">'.__( 'Orders placed by employees', 'course-booking-system' ).'</h2>';
		$count = 0;
		$users = get_users( [ 'role__in' => [ 'administrator', 'editor', 'author', 'contributor' ] ] );
		foreach ( $users AS $user ) {
			$orders = get_posts( array(
				'numberposts' => -1,
				'post_type'   => 'shop_order',
				'post_status' => 'any',
				'author'      => $user->ID,
				'date_query'  => array(
					array(
						'after'     => $start,
						'before'    => $end,
						'inclusive' => true,
					),
				)
			) );

			$user_info = get_userdata( $user->ID );
			if ( !empty( $user_info->display_name ) ) {
				$display_name = esc_html( $user_info->display_name );
			} else {
				$display_name = __( 'Without name', 'course-booking-system' );
			}

			if ( count( $orders ) > 0 ) {
				$content .= '<p class="statistics-orders-content">'.$display_name.': <strong>'.sprintf( _n( '%s Order', '%s Orders', count( $orders ), 'course-booking-system' ), number_format_i18n( count( $orders ) ) ).'</strong></p>';
				$content .= '<ul class="statistics-orders-content">';
					foreach ( $orders AS $order ) {
						$order_details = new WC_Order( $order->ID );
						$content .= '<li><a href="'.admin_url( 'post.php?post='.absint( $order->ID ) ).'&action=edit">#'.$order->ID.'</a>: '.$order_details->get_billing_first_name().' '.$order_details->get_billing_last_name().'</li>';
					}
				$content .= '</ul>';
			}
		}

		if ( $count == 0 )
			$content .= '<p>'.__( 'During this period no orders have been placed by employees.', 'course-booking-system' ).'</p>';
	endif;

	return $content;
}

function cbs_add_shortcodes() {
	add_shortcode( 'timetable', 'cbs_shortcode_timetable' );
	add_shortcode( 'statistics', 'cbs_shortcode_statistics' );
}
add_action( 'init', 'cbs_add_shortcodes' );
