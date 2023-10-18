<?php
global $wpdb, $post;

// Variables
$course_id = !empty( $_REQUEST['course_id'] ) ? intval( $_REQUEST['course_id'] ) : 0;
$courses = cbs_get_courses( array(
	'id' => $course_id
) );
$post_id = !empty( $post->ID ) ? $post->ID : reset( $courses )->post_id;

$user_id = get_current_user_id();
$current_time = current_time( 'timestamp' );

$free              = get_post_meta( $post_id, 'free', true );
$price_level       = get_post_meta( $post_id, 'price_level', true );
$price_level_names = array( 1 => get_option( 'course_booking_system_price_level_title' ), 2 => get_option( 'course_booking_system_price_level_title_2' ), 3 => get_option( 'course_booking_system_price_level_title_3' ), 4 => get_option( 'course_booking_system_price_level_title_4' ), 5 => get_option( 'course_booking_system_price_level_title_5' ) );
$price_level_name  = $price_level_names[$price_level];
$invitation_link   = get_post_meta( $post_id, 'invitation_link', true );

// Options
$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

$intro               = get_option( 'course_booking_system_intro' );
$booking_in_advance  = get_option( 'course_booking_system_booking_in_advance' );
$show_availability   = get_option( 'course_booking_system_show_availability' );
$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );

$deleting_in_advance = get_option( 'course_booking_system_deleting_in_advance' );

$holiday_start       = get_option( 'course_booking_system_holiday_start' );
$holiday_end         = get_option( 'course_booking_system_holiday_end' );
$holiday_description = get_option( 'course_booking_system_holiday_description' );
$opening             = get_option( 'course_booking_system_opening' );

if ( isset( $_GET['message'] ) && $_GET['message'] == 'purchase' ) :
	?>
	<div class="woocommerce-message">
		<?php _e( 'Thank you for your purchase. Your card was successfully redeemed. You can now book appointments.', 'course-booking-system' ); ?>
	</div>
	<?php
endif;

if ( empty( $course_id ) ) :
	?>
	<h2><?php _e( 'No course selected', 'course-booking-system' ); ?></h2>
	<p><?php _e( 'Thank you for your interest in this course. This course has several course times. Please select your preferred time to book the course:', 'course-booking-system' ); ?></p>
	<ul>
		<?php
		$courses = cbs_get_courses( array(
			'post_id' => $post_id
		) );
		foreach ( $courses as $course ) :
			?>
			<li><a href="<?= get_permalink( $post_id ) ?>?course_id=<?= $course->id ?>">
				<?php
				if ( $course->day == 99 ) :
					echo date_i18n( $date_format, strtotime( $course->date ) );
				else :
					echo cbs_get_weekday( $course->day );
				endif;
				echo ', ';

				echo date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) );
				?>
			</a></li>
		<?php endforeach; ?>
	</ul>

	<?php if ( count( $courses ) == 0 ) : ?>
		<p><?php _e( 'Unfortunately no course times are available. This can be because the event is not assigned to a column or the column does not have a weekday or a specific date.', 'course-booking-system' ); ?></p>
	<?php
	endif;

	return;
endif;

do_action( 'cbs_before_single_course', $course_id );

foreach ( $courses as $course ) {
	if ( $course->day == 99 ) :
		$weekday = strtotime( $course->date );
		echo '<h2>'.get_the_title( $post_id ).', <span class="weekday">'.date_i18n( $date_format, strtotime( $course->date ) ).', </span>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</h2>';
	else :
		$weekday = strtotime( 'Sunday +'.$course->day.' days' );
		echo '<h2>'.get_the_title( $post_id ).', <span class="weekday">'.date_i18n( 'l', $weekday ).', </span>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</h2>';
	endif;

	echo '<h4>'; echo ( $free ) ? __( 'Free', 'woocommerce' ) : $price_level_name; echo '</h4>';
	echo '<p class="intro">'.$intro.'</p>';

	if ( $course->day == 99 ) :
		$start_date = $course->date;
		$booking_in_advance_date = $course->date;
	elseif ( !empty( $opening ) && $opening > date( 'Y-m-d' ) ) :
		$start_date = $opening;
		$booking_in_advance_date = date( 'Y-m-d', strtotime( $start_date.' +'.$booking_in_advance.' weeks' ) );
	elseif ( !empty( $course->date ) && $course->date > date( 'Y-m-d' ) ) :
		$start_date = $course->date;
		$booking_in_advance_date = date( 'Y-m-d', strtotime( $course->date.' +'.$booking_in_advance.' weeks' ) );
	else :
		$initial_slide = empty( $_GET['slide'] ) ? 0 : htmlspecialchars( $_GET['slide'] );

		if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) {
			if ( empty( $start_date ) ) {
				$start_date = date( 'Y-m-d', strtotime( '-'.$booking_in_advance.' weeks' ) );
			} else {
				$start_date = date( 'Y-m-d', strtotime( $start_date.' -'.$booking_in_advance.' weeks' ) );
			}

			$initial_slide = $initial_slide + $booking_in_advance;
		} else {
			$start_date = date( 'Y-m-d' );
		}

		echo '<div id="initial-slide" data-id="'.$initial_slide.'"></div>';
		$booking_in_advance_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ).' +'.$booking_in_advance.' weeks' ) );
	endif;

	echo '<div class="slider">';
	for ( $i = strtotime( strtolower( date( 'l', $weekday ) ), strtotime( $start_date ) ); $i <= strtotime( $booking_in_advance_date ); $i = strtotime( '+1 week', $i ) ) {
		$date = date( 'Y-m-d', $i );
		$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course_id AND date = '$date'" );

		if ( ( $date != date( 'Y-m-d' ) || $current_time < strtotime( $date.' '.$course->end ) ) || ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) {
			echo '<div class="slide">';
				echo '<h3><span class="weekday">'.date_i18n( 'l', $weekday ).', </span>'.date_i18n( $date_format, strtotime( $date ) ).'</h3>';

				$substitute_id = cbs_get_substitute_id( $course_id, $date );

				if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
					?>
					<form action="#" method="POST">
						<input type="hidden" name="course_id" value="<?= $course_id ?>">
						<input type="hidden" name="date" value="<?= $date ?>">

						<?php $user_info = get_userdata( $course->user_id ); ?>
						<p><?php _e( 'Trainer', 'course-booking-system' ); ?>: <strong><?= $user_info->display_name ?></strong><br>
							<label for="user_id"><?php _e( 'Substitute', 'course-booking-system' ); ?></label>
							<select name="user_id" onchange="cbs_action_substitute( <?= $course_id ?>, '<?= $date ?>', this.options[this.selectedIndex].value );">
								<option value="99999"<?= ( $substitute_id == 99999 ) ? ' selected="selected"' : '' ?>><?php _e( 'Course is cancelled', 'course-booking-system' ); ?></option>

								<?php
								$admins = get_users( [ 'role__in' => [ 'administrator', 'editor', 'author', 'contributor' ] ] );
								foreach ( $admins AS $admin ) {
									$user_info = get_userdata( $admin->ID );
									if ( !empty( $user_info->display_name ) ) {
										$display_name = esc_html( $user_info->display_name );
									} else {
										$display_name = __( 'Without name', 'course-booking-system' );
									}
									?>

									<option value="<?= esc_html( $admin->ID ) ?>"<?= ( $admin->ID == $substitute_id || ( empty( $substitute_id ) && $admin->ID == $course->user_id ) ) ? ' selected="selected"' : '' ?>><?= $display_name ?></option>
								<?php } ?>
							</select>
						</p>
					</form>
					<?php
				} else {
					if ( $substitute_id == 99999 ) {
						?>
						<p><?php _e( 'Course is cancelled', 'course-booking-system' ); ?></p>
						<?php
					} else {
						$user_info = get_userdata( $course->user_id );
						?>
						<p><span class="trainer <?= !empty( $substitute_id ) ? 'trainer-has-substitute' : '' ?>"><?php _e( 'Trainer', 'course-booking-system' ); ?>: <strong><?= $user_info->display_name ?></strong></span>
							<?php
							if ( !empty( $substitute_id ) ) {
								$user_info = get_userdata( $substitute_id );
								?>
								<br><span class="substitute"><?php _e( 'Substitute', 'course-booking-system' ); ?>: <strong><?= $user_info->display_name ?></strong></span>
								<?php
							}
							?>
						</p>
						<?php
					}
				}

				do_action( 'cbs_single_course_before_availability', $course_id, $date );

				$table = '';
				$booked = array();
				$attendance_count = 0;
				$admin_email = get_option( 'admin_email' );
				$mailto = 'mailto:'.$admin_email.'?bcc=';

				$abos = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE (meta_key = 'abo_course' AND meta_value = $course_id) OR (meta_key = 'abo_course_2' AND meta_value = $course_id) OR (meta_key = 'abo_course_3' AND meta_value = $course_id)" );
				foreach ( $abos as $abo ) {
					$abo_start = get_the_author_meta( 'abo_start', $abo->user_id );
					$abo_expire = get_the_author_meta( 'abo_expire', $abo->user_id );
					$abo_alternate = get_the_author_meta( 'abo_alternate', $abo->user_id );
					$abo_alternate = explode( ',', $abo_alternate );
					if ( $abo_start <= $date && $abo_expire >= $date && !in_array( $date, $abo_alternate ) ) {
						$attendance_count++;

						$user_info = get_userdata( $abo->user_id );
						$mailto   .= $user_info->user_email.',';

						$description = '<span class="subscription-indication">('.__( 'Subscription', 'course-booking-system' ).')</span>';
						if ( !empty( get_the_author_meta( 'description', $abo->user_id ) ) )
							$description .= ' <span class="description booking-description">('.get_the_author_meta( 'description', $abo->user_id ).')</span>';

						if ( strtotime( $user_info->user_registered ) > strtotime( $date.' -7 days' ) )
							$description .= ' ('.__( 'New customer', 'course-booking-system' ).')';

						$table .= '<tr><td>'.$attendance_count.'</td><td class="subscription"><a href="'.get_edit_user_link( $abo->user_id ).'">'.$user_info->first_name.' '.$user_info->last_name.'</a> '.$description.'</td>';
							if ( ( strtotime( $date.' '.$course->start ) - $deleting_in_advance * HOUR_IN_SECONDS ) > $current_time ) {
								$table .= '<td><a href="#" class="button btn btn-primary et_pb_button action-abo-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$abo->user_id.'">×</a></td>';
							} else {
								$table .= '<td><a href="#" class="button btn btn-primary et_pb_button action-abo-delete" title="'.__( 'Cancel', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$abo->user_id.'">×</a><br><a href="#" class="button btn btn-primary et_pb_button action-abo-delete goodwill" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$abo->user_id.'" data-goodwill="true">×</a></td>';
							}
						$table .= '</tr>';
					}
				}
				$attendance_abo = $attendance_count;

				foreach ( $bookings as $booking ) {
					$booked[] = $booking->user_id;
					$attendance_count++;

					$user_info = get_userdata( $booking->user_id );
					$mailto   .= $user_info->user_email.',';

					$table .= '<tr>';
						$table .= '<td>'.$attendance_count.'</td>';

						if ( $price_level_for_lower_course ) {
							if ( $price_level == 5 ) {
								$price_level_card = 5;
							} else if ( $price_level == 4 ) {
								if ( get_the_author_meta( 'card_4', $booking->user_id ) > 0 && get_the_author_meta( 'expire_4', $booking->user_id ) >= $date ) {
									$price_level_card = 4;
								} else if ( get_the_author_meta( 'card_5', $booking->user_id ) > 0 && get_the_author_meta( 'expire_5', $booking->user_id ) >= $date ) {
									$price_level_card = 5;
								} else {
									$price_level_card = 4;
								}
							} else if ( $price_level == 3 ) {
								if ( get_the_author_meta( 'card_3', $booking->user_id ) > 0 && get_the_author_meta( 'expire_3', $booking->user_id ) >= $date ) {
									$price_level_card = 3;
								} else if ( get_the_author_meta( 'card_4', $booking->user_id ) > 0 && get_the_author_meta( 'expire_4', $booking->user_id ) >= $date ) {
									$price_level_card = 4;
								} else if ( get_the_author_meta( 'card_5', $booking->user_id ) > 0 && get_the_author_meta( 'expire_5', $booking->user_id ) >= $date ) {
									$price_level_card = 5;
								} else {
									$price_level_card = 3;
								}
							} else if ( $price_level == 2 ) {
								if ( get_the_author_meta( 'card_2', $booking->user_id ) > 0 && get_the_author_meta( 'expire_2', $booking->user_id ) >= $date ) {
									$price_level_card = 2;
								} else if ( get_the_author_meta( 'card_3', $booking->user_id ) > 0 && get_the_author_meta( 'expire_3', $booking->user_id ) >= $date ) {
									$price_level_card = 3;
								} else if ( get_the_author_meta( 'card_4', $booking->user_id ) > 0 && get_the_author_meta( 'expire_4', $booking->user_id ) >= $date ) {
									$price_level_card = 4;
								} else if ( get_the_author_meta( 'card_5', $booking->user_id ) > 0 && get_the_author_meta( 'expire_5', $booking->user_id ) >= $date ) {
									$price_level_card = 5;
								} else {
									$price_level_card = 2;
								}
							} else if ( $price_level == 1 ) {
								if ( get_the_author_meta( 'card', $booking->user_id ) > 0 && get_the_author_meta( 'expire', $booking->user_id ) >= $date ) {
									$price_level_card = 1;
								} else if ( get_the_author_meta( 'card_2', $booking->user_id ) > 0 && get_the_author_meta( 'expire_2', $booking->user_id ) >= $date ) {
									$price_level_card = 2;
								} else if ( get_the_author_meta( 'card_3', $booking->user_id ) > 0 && get_the_author_meta( 'expire_3', $booking->user_id ) >= $date ) {
									$price_level_card = 3;
								} else if ( get_the_author_meta( 'card_4', $booking->user_id ) > 0 && get_the_author_meta( 'expire_4', $booking->user_id ) >= $date ) {
									$price_level_card = 4;
								} else if ( get_the_author_meta( 'card_5', $booking->user_id ) > 0 && get_the_author_meta( 'expire_5', $booking->user_id ) >= $date ) {
									$price_level_card = 5;
								} else {
									$price_level_card = 1;
								}
							}
						}

						$description = '';
						if ( !empty( get_the_author_meta( 'description', $booking->user_id ) ) )
							$description = ' <span class="description booking-description">('.get_the_author_meta( 'description', $booking->user_id ).')</span>';

						if ( strtotime( $user_info->user_registered ) > strtotime( $date.' -7 days' ) )
							$description .= ' ('.__( 'New customer', 'course-booking-system' ).')';

						if ( $price_level_for_lower_course && $price_level != $price_level_card && !$free )
							$description .= ' ('.$price_level_names[$price_level_card].')';

						$abo_start = get_the_author_meta( 'abo_start', $booking->user_id );
						$abo_expire = get_the_author_meta( 'abo_expire', $booking->user_id );
						if ( $abo_start <= $date && $abo_expire >= $date && !$free )
							$description .= ' <span class="booking-subscription-indication subscription-indication">('.__( 'Subscription', 'course-booking-system' ).')</span>';

						$logs = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_logs WHERE user_id = $booking->user_id ORDER BY log_id DESC LIMIT 2" );
						if ( count( $logs ) > 0 ) {
							foreach ( $logs as $log ) {
								if ( substr( $log->action, 0, 8 ) == 'referral' )
									$description .= ' <span class="referral">('.__( 'Referral', 'course-booking-system' ).')</span>';
							}
						}

						$table .= '<td><a href="'.get_edit_user_link( $booking->user_id ).'"><span class="first-name">'.$user_info->first_name.'</span> <span class="last-name">'.$user_info->last_name.'</span></a>'.$description.'</td>';

						if ( ( strtotime( $date.' '.$course->start ) - $deleting_in_advance * HOUR_IN_SECONDS ) > $current_time ) {
							$table .= '<td><a href="#" class="button btn btn-primary et_pb_button action-booking-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$booking->user_id.'" data-booking="'.$booking->booking_id.'">×</a></td>';
						} else {
							$table .= '<td><a href="#" class="button btn btn-primary et_pb_button action-booking-delete" title="'.__( 'Cancel', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$booking->user_id.'" data-booking="'.$booking->booking_id.'">×</a><br><a href="#" class="button btn btn-primary et_pb_button action-booking-delete goodwill" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$booking->user_id.'" data-booking="'.$booking->booking_id.'" data-goodwill="true">×</a></td>';
						}
					$table .= '</tr>';
				}

				$attendance = !empty( cbs_get_attendance( $course_id, $date ) ) ? cbs_get_attendance( $course_id, $date ) : get_post_meta( $post_id, 'attendance', true );
				for ( $k = 1; $k <= ( intval( $attendance ) - intval( $attendance_count ) ); $k++ ) {
					$table .= '<tr><td>'.( $k + $attendance_count ).'</td><td><input type="text" class="livesearch-input" data-id="'.$course_id.'" data-date="'.$date.'" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></td><td></td></tr>';
				}

				$queue = array();
				$waitlists = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_waitlists WHERE course_id = $course_id AND date = '$date'" );
				foreach ( $waitlists as $waitlist ) {
					$queue[] = $waitlist->user_id;
				}

				if ( ( $show_availability || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) && !( $date >= $holiday_start && $date <= $holiday_end ) && !cbs_is_holiday( date( 'd', $i ), date( 'm', $i ), date( 'Y', $i ) ) ) {
					if ( $attendance_count >= $attendance ) {
						?>
						<p class="availability"><?php _e( 'Availability:', 'course-booking-system' ); ?> <strong class="red">0</strong><?php if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) : ?> | <small><a href="#" title="<?php _e( 'Increase the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance + 1 ?>">+ 1</a> <?php _e( 'or', 'course-booking-system' ); ?> <a href="#" title="<?php _e( 'Decrease the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance - 1 ?>">- 1</a></small><?php endif; ?></p>
						<div class="progress-wrapper red"><progress value="<?= $attendance_count ?>" max="<?= $attendance ?>"></progress></div>
						<?php
					} else if ( $attendance_count > $attendance / 2 ) {
						?>
						<p class="availability"><?php _e( 'Availability:', 'course-booking-system' ); ?> <strong class="yellow"><?= ( intval( $attendance ) - intval( $attendance_count ) ) ?></strong><?php if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) : ?> | <small><a href="#" title="<?php _e( 'Increase the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance + 1 ?>">+ 1</a> <?php _e( 'or', 'course-booking-system' ); ?> <a href="#" title="<?php _e( 'Decrease the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance - 1 ?>">- 1</a></small><?php endif; ?></p>
						<div class="progress-wrapper yellow"><progress value="<?= $attendance_count ?>" max="<?= $attendance ?>"></progress></div>
						<?php
					} else {
						if ( ( intval( $attendance ) - intval( $attendance_count ) ) > 5 ) {
							?>
							<p class="availability"><?php _e( 'Availability:', 'course-booking-system' ); ?> <strong><?= sprintf( __( 'more than 5', 'course-booking-system' ), ( intval( $attendance ) - intval( $attendance_count ) ) ) ?></strong><?php if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) : ?> | <small><a href="#" title="<?php _e( 'Increase the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance + 1 ?>">+ 1</a> <?php _e( 'or', 'course-booking-system' ); ?> <a href="#" title="<?php _e( 'Decrease the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance - 1 ?>">- 1</a></small><?php endif; ?></p>
							<?php
						} else {
							?>
							<p class="availability"><?php _e( 'Availability:', 'course-booking-system' ); ?> <strong><?= ( intval( $attendance ) - intval( $attendance_count ) ) ?></strong><?php if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) : ?> | <small><a href="#" title="<?php _e( 'Increase the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance + 1 ?>">+ 1</a> <?php _e( 'or', 'course-booking-system' ); ?> <a href="#" title="<?php _e( 'Decrease the number of participants by 1 for this day only.', 'course-booking-system' ); ?>" class="action-attendance" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" data-attendance="<?= $attendance - 1 ?>">- 1</a></small><?php endif; ?></p>
							<?php
						}
						?>
						<div class="progress-wrapper"><progress value="<?= $attendance_count ?>" max="<?= $attendance ?>"></progress></div>
						<?php
					}
				}

				do_action( 'cbs_single_course_after_availability', $course_id, $date );

				if ( is_user_logged_in() && $price_level == 5 ) {
					$card = get_the_author_meta( 'card_5', $user_id );
					$expire = get_the_author_meta( 'expire_5', $user_id );
					$card_level_name = $price_level_names[5];

					$flat = get_the_author_meta( 'flat_5', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_5', $user_id );
				} else if ( is_user_logged_in() && $price_level == 4 ) {
					$card = get_the_author_meta( 'card_4', $user_id );
					$expire = get_the_author_meta( 'expire_4', $user_id );
					$card_level_name = $price_level_names[4];

					if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_5', $user_id );
						$expire = get_the_author_meta( 'expire_5', $user_id );
						$card_level_name = $price_level_names[5];
					}

					$flat = get_the_author_meta( 'flat_4', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_4', $user_id );
				} else if ( is_user_logged_in() && $price_level == 3 ) {
					$card = get_the_author_meta( 'card_3', $user_id );
					$expire = get_the_author_meta( 'expire_3', $user_id );
					$card_level_name = $price_level_names[3];

					if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_4', $user_id );
						$expire = get_the_author_meta( 'expire_4', $user_id );
						$card_level_name = $price_level_names[4];
					} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_5', $user_id );
						$expire = get_the_author_meta( 'expire_5', $user_id );
						$card_level_name = $price_level_names[5];
					}

					$flat = get_the_author_meta( 'flat_3', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_3', $user_id );
				} else if ( is_user_logged_in() && $price_level == 2 ) {
					$card = get_the_author_meta( 'card_2', $user_id );
					$expire = get_the_author_meta( 'expire_2', $user_id );
					$card_level_name = $price_level_names[2];

					if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_3', $user_id );
						$expire = get_the_author_meta( 'expire_3', $user_id );
						$card_level_name = $price_level_names[3];
					} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_4', $user_id );
						$expire = get_the_author_meta( 'expire_4', $user_id );
						$card_level_name = $price_level_names[4];
					} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_5', $user_id );
						$expire = get_the_author_meta( 'expire_5', $user_id );
						$card_level_name = $price_level_names[5];
					}

					$flat = get_the_author_meta( 'flat_2', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_2', $user_id );
				} else if ( is_user_logged_in() ) {
					$card = get_the_author_meta( 'card', $user_id );
					$expire = get_the_author_meta( 'expire', $user_id );
					$card_level_name = $price_level_names[1];

					if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_2', $user_id );
						$expire = get_the_author_meta( 'expire_2', $user_id );
						$card_level_name = $price_level_names[2];
					} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_3', $user_id );
						$expire = get_the_author_meta( 'expire_3', $user_id );
						$card_level_name = $price_level_names[3];
					} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_4', $user_id );
						$expire = get_the_author_meta( 'expire_4', $user_id );
						$card_level_name = $price_level_names[4];
					} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
						$card = get_the_author_meta( 'card_5', $user_id );
						$expire = get_the_author_meta( 'expire_5', $user_id );
						$card_level_name = $price_level_names[5];
					}

					$flat = get_the_author_meta( 'flat', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire', $user_id );
				}

				do_action( 'cbs_single_course_before_booking_options', $course_id, $date, $booked );

				if ( ( $date > $holiday_start && $date < $holiday_end ) || $date == $holiday_start || $date == $holiday_end ) { ?>
					<p><em><?= $holiday_description ?></em></p>
				<?php } else if ( $substitute_id == 99999 || ( $course->user_id == 99999 && empty( $substitute_id ) ) || ( $course->user_id == -1 && empty( $substitute_id ) ) || ( empty( $course->user_id ) && empty( $substitute_id ) ) ) { ?>
					<p class="course-cancelled"><em><?php _e( 'Course is cancelled', 'course-booking-system' ); ?>.</em></p>
				<?php } else if ( cbs_is_holiday( date('d', $i), date('m', $i), date('Y', $i) ) ) { ?>
					<p><em><?php _e( 'Holiday. Course does not take place.', 'course-booking-system' ); ?></em></p>
				<?php } else if ( $attendance == 0 ) { ?>
					<p><em><?php _e( 'This course has no maximum number of participants.', 'course-booking-system' ); ?></em></p>
				<?php } else if ( is_user_logged_in() && $attendance_count < $attendance && ( ( $card > 0 && $expire >= $date ) || ( $flat && $flat_expire >= $date ) || $free ) ) { ?>
					<a href="#" class="button btn btn-primary et_pb_button action-booking" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>" <?= in_array( $user_id, $booked ) ? 'data-confirm="'.__( 'You have already booked this course.', 'course-booking-system' ).' '.__( 'Book again?', 'course-booking-system' ).'"' : '' ?>><?php _e( 'Book course', 'course-booking-system' ); ?></a>
					<p><small>
						<?php
						if ( $free ) {
							_e( 'This course is free of charge', 'course-booking-system' );
						} else if ( $flat && $flat_expire >= $date ) {
							_e( 'You have a flatrate for this course', 'course-booking-system' );
						} else {
							echo sprintf( __( '%s left on card', 'course-booking-system' ), $card );
							?>
							(<?= $card_level_name ?>)
							<?php
						}
						?>
					</small></p>
				<?php } else if ( is_user_logged_in() && in_array( $user_id, $queue ) ) { ?>
					<p><?php _e( 'You are entered in the waiting list for this course.', 'course-booking-system' ); ?><br><a href="<?= get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>"><?php _e( 'Go to your waiting lists', 'course-booking-system' ); ?></a></p>
				<?php } else if ( is_user_logged_in() && $attendance_count >= $attendance && ( ( $card > 0 && $expire >= $date ) || ( $flat && $flat_expire >= $date ) || $free ) && !in_array( $user_id, $booked ) ) { ?>
					<p><?php _e( 'Unfortunately, this course has already reached the maximum number of participants. If you are still interested in this course, you can sign up for the waiting list. You will then be informed by email as soon as a place is available.', 'course-booking-system' ); ?></p>
					<a href="#" class="button btn btn-primary et_pb_button action-waitlist" data-id="<?= $course_id ?>" data-date="<?= $date ?>" data-user="<?= $user_id ?>"><?php _e( 'Waiting list', 'course-booking-system' ); ?></a>
				<?php } else if ( is_user_logged_in() && !in_array( $user_id, $booked ) && !$free ) { ?>
					<p><?php _e( 'Unfortunately you do not have a valid card. Please buy a new card in our online shop.', 'course-booking-system' ); ?></p>
					<?php $last_visited_course = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
					<a href="<?= get_permalink( wc_get_page_id( 'shop' ) ) ?>?price-level=<?= $price_level ?>&last-course-visited=<?= urlencode( $last_visited_course ) ?>" class="button btn btn-primary et_pb_button"><?php _e( 'Shop', 'woocommerce' ); ?></a>
				<?php } else if ( !in_array( $user_id, $booked ) || !is_user_logged_in() ) { ?>
					<p><?php _e( 'You have to log into your account to book the course or to be able to register on the waiting list.', 'course-booking-system' ); ?></p>
					<a href="<?= get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'?message=login&redirect_to='.urlencode( get_permalink( $post_id ).'?course_id='.$course_id ) ?>" class="button btn btn-primary et_pb_button"><?php _e( 'Login', 'woocommerce' ); ?></a>
				<?php }

				do_action( 'cbs_single_course_after_booking_options', $course_id, $date, $booked );

				if ( in_array( $user_id, $booked ) && is_user_logged_in() ) {
					if ( !empty( $invitation_link ) && $course->day == date( 'N' ) && ( $current_time + 25 * MINUTE_IN_SECONDS ) >= strtotime( $date.' '.$course->start ) && $current_time < strtotime( $date.' '.$course->end ) ) {
						?>
						<p><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ) ?></a>
						<?php
					} else {
						?>
						<p><?php _e( 'You have already booked this course.', 'course-booking-system' ); ?><br><a href="<?= get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>"><?php _e( 'Go to your bookings', 'course-booking-system' ); ?></a></p>
						<?php
					}
				}

				if ( is_user_logged_in() && ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || get_current_user_id() == $course->user_id || ( !empty( $substitute_id ) && get_current_user_id() == $substitute_id ) || $_SERVER['HTTP_HOST'] == 'pfotenakademie.de' || $_SERVER['HTTP_HOST'] == 'hundeschule-team-wuff.com' ) && !( $date >= $holiday_start && $date <= $holiday_end ) && !cbs_is_holiday( date( 'd', $i ), date( 'm', $i ), date( 'Y', $i ) ) ) {
					?>
					<h4><?php _e( 'Bookings', 'woocommerce' ); ?></h4>
					<table>
						<tbody>
							<tr>
								<th></th>
								<th><?php _e( 'Name', 'woocommerce' ); ?></th>
								<th></th>
							</tr>
							<?= $table ?>
						</tbody>
					</table>
					<p class="email"><small><a href="<?= substr( $mailto, 0, -1 ).'&subject='.__( 'Information about the course', 'course-booking-system' ).' '.get_the_title( $post_id ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( 'l', $weekday ).', '.date_i18n( $date_format, $i ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ) ?>"><?php _e( 'Email to all participants', 'course-booking-system' ); ?></a></small></p>

					<?php $note = cbs_get_note( $course_id, $date ); ?>
					<label for="note_<?= $i ?>"><?php _e( 'Note', 'woocommerce' ); ?></label>
					<textarea id="note_<?= $i ?>" name="note_<?= $i ?>" onchange="cbs_note( <?= $course_id ?>, '<?= $date ?>', this.value );"><?= !empty( $note ) ? $note : '' ?></textarea>

					<?php if ( count( $waitlists ) > 0 || $attendance_count >= $attendance ) { ?>
						<h4><?php _e( 'Waiting list', 'course-booking-system' ); ?></h4>
						<table>
							<tbody>
								<tr>
									<th></th>
									<th><?php _e( 'Name', 'course-booking-system' ); ?></th>
									<th></th>
								</tr>
								<?php
								$waitlist_count = 1;
								$mailto = 'mailto:'.$admin_email.'?bcc=';

								foreach ( $waitlists as $waitlist ) {
									$user_info = get_userdata( $waitlist->user_id );
									$mailto   .= $user_info->user_email.',';

									echo '<tr><td>'.$waitlist_count.'</td><td><a href="'.get_edit_user_link( $waitlist->user_id ).'">'.$user_info->first_name.' '.$user_info->last_name.'</a></td><td><a href="#" class="button btn btn-primary et_pb_button action-waitlist-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$waitlist->user_id.'">×</a></td></tr>';
									$waitlist_count++;
								}
								?>
								<tr><td><?= $waitlist_count ?></td><td><input type="text" class="livesearch-input-waitlist" data-id="<?= $course_id ?>" data-date="<?= $date ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></td><td></td></tr>
							</tbody>
						</table>
						<p class="email"><small><a href="<?= substr( $mailto, 0, -1 ).'&subject='.__( 'Information about the course', 'course-booking-system' ).' '.get_the_title( $post_id ).' '.__( 'on', 'course-booking-system' ).' '.date_i18n( 'l', $weekday ).', '.date_i18n( $date_format, $i ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ) ?>"><?php _e( 'Email to all on the waiting list', 'course-booking-system' ); ?></a></small></p>
						<?php
					}

					$cancellations = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_cancellations WHERE course_id = $course_id AND date = '$date'" );
					if ( count( $cancellations ) > 0 ) { ?>
						<h4><?php _e( 'Cancellations', 'course-booking-system' ); ?></h4>
						<table>
							<tbody>
								<tr>
									<th></th>
									<th><?php _e( 'Name', 'course-booking-system' ); ?></th>
									<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
								</tr>
								<?php
								$cancellation_count = 1;
								foreach ( $cancellations as $cancellation ) {
									$user_info = get_userdata( $cancellation->user_id );
									echo '<tr><td>'.$cancellation_count.'</td><td><a href="'.get_edit_user_link( $cancellation->user_id ).'">'.$user_info->first_name.' '.$user_info->last_name.'</a></td><td><small>'.date_i18n( $date_format.' '.$time_format, strtotime( $cancellation->timestamp ) ).'</small></td></tr>';
									$cancellation_count++;
								}
								?>
							</tbody>
						</table>
						<?php
					}
				}
			echo '</div>';
		}
	} echo '</div>';
}

do_action( 'cbs_after_single_course', $course_id );
