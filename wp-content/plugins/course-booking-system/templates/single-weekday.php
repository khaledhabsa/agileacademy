<?php
global $wpdb;

// Options
$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

$show_availability            = get_option( 'course_booking_system_show_availability' );
$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );

$holiday_start       = get_option( 'course_booking_system_holiday_start' );
$holiday_end         = get_option( 'course_booking_system_holiday_end' );
$holiday_description = get_option( 'course_booking_system_holiday_description' );

$booking_in_advance = get_option( 'course_booking_system_booking_in_advance' );

// Variables
$attendance_all = 0;

if ( isset( $_GET['weekday'] ) )
	$day = intval( $_GET['weekday'] );

if ( isset( $_GET['date'] ) ) :
	$date = htmlspecialchars( $_REQUEST['date'] );
else :
	$date = date( 'Y-m-d', strtotime( 'Sunday +'.$day.' days' ) );
endif;

do_action( 'cbs_before_archive_course', $day, $date );
?>

<div class="container section-inner">
	<?php
	$date_visual = cbs_get_weekday( $day ).', '.date_i18n( $date_format, strtotime( $date ) );

	if ( $day == 99 ) :
		$select_dates = array( $date );
		$date_visual = date_i18n( $date_format, strtotime( $date ) );
	elseif ( is_user_logged_in() && ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) ) :

		$select_dates = array( date( $date, strtotime( 'Sunday +'.$day.' days' ) ) );

		for ( $i = 1; $i <= $booking_in_advance; $i++ ) {
			$select_dates[] = date( 'Y-m-d', strtotime( $date.'-'.$i.' weeks' ) );
		}
		?>
		<form method="GET" action="/course/">
			<input type="hidden" name="weekday" id="weekday" value="<?= $day ?>">
			<p>
				<label for="date"><?php _e( 'Date', 'course-booking-system' ); ?></label>
				<select name="date" id="date">
					<?php foreach ( $select_dates as $select_date ) : ?>
						<option value="<?= $select_date ?>" <?= $select_date == $date ? 'selected="selected"' : '' ?>><?= date_i18n( $date_format, strtotime( $select_date ) ) ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<input type="submit" value="<?php _e( 'Choose date', 'course-booking-system' ); ?>">
		</form>
	<?php endif; ?>

	<p><?= sprintf( __( 'Here you see an overview with the current load for all courses on %s. For more information and options please click the button below the course.', 'course-booking-system' ), $date_visual ) ?></p>

	<div id="ajax"></div>
	<div id="ajax-loader" class="loader"><div></div><div></div><div></div></div>

	<div class="cbs-weekday-grid">
		<?php
		$courses = cbs_get_courses( array(
			'day' => $day,
			'date' => $date
		) );

		foreach ( $courses as $course ) :
			$attendance        = get_post_meta( $course->post_id, 'attendance', true );
			$free              = get_post_meta( $course->post_id, 'free', true );
			$price_level       = get_post_meta( $course->post_id, 'price_level', true );
			$price_level_names = array( 1 => get_option( 'course_booking_system_price_level_title' ), 2 => get_option( 'course_booking_system_price_level_title_2' ), 3 => get_option( 'course_booking_system_price_level_title_3' ), 4 => get_option( 'course_booking_system_price_level_title_4' ), 5 => get_option( 'course_booking_system_price_level_title_5' ) );
			$price_level_name  = $price_level_names[$price_level];
			$invitation_link   = get_post_meta( $course->post_id, 'invitation_link', true );
			?>

			<div class="cbs-weekday-course course post-<?= $course->post_id ?>">
				<h2><a href="<?= get_permalink( $course->post_id ) ?>?course_id=<?= $course->id ?>">
					<?= get_the_title( $course->post_id ) ?>,
					<time datetime="<?= $course->start ?>" class="timeslot-start"><?= date( $time_format, strtotime( $course->start ) ); ?></time> - <time datetime="<?= $course->end ?>" class="timeslot-end"><?= date( $time_format, strtotime( $course->end ) ); ?></time> <?php _e( 'o\'clock', 'course-booking-system' ); ?>
				</a></h2>

				<h4><?= $free ? __( 'Free', 'woocommerce' ) : $price_level_name ?></h4>

				<p class="event-user vcard">
					<?= get_avatar( $course->user_id, 'thumbnail', '', __( 'Trainer', 'course-booking-system' ) ); ?>
					<?php
					$substitute_id = cbs_get_substitute_id( $course->id, $date );
					if ( ( $date > $holiday_start && $date < $holiday_end ) || $date == $holiday_start || $date == $holiday_end ) {
						echo $holiday_description;
					} else if ( $substitute_id == 99999 ) {
						_e( 'Course is cancelled', 'course-booking-system' );
					} else {
						$user_info = get_userdata( $course->user_id );
						?>

						<span class="trainer <?= !empty( $substitute_id ) ? 'trainer-has-substitute' : '' ?>">
							<?php _e( 'Trainer', 'course-booking-system' ); ?>: <strong><?= $user_info->display_name ?></strong>
						</span>

						<?php
						if ( !empty( $substitute_id ) ) {
							$user_info = get_userdata( $substitute_id );
							?>
							<br><span class="substitute"><?php _e( 'Substitute', 'course-booking-system' ); ?>: <strong><?= $user_info->display_name ?></strong></span>
							<?php
						}
					}
					?>
				</p>

				<?php
				$table = '';
				$attendance_count = 0;
				$admin_email = get_option( 'admin_email' );
				$mailto = 'mailto:'.$admin_email.'?bcc=';

				$abos = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE (meta_key = 'abo_course' AND meta_value = $course->id) OR (meta_key = 'abo_course_2' AND meta_value = $course->id) OR (meta_key = 'abo_course_3' AND meta_value = $course->id)" );
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

						$customer_orders = array();
						if ( array_key_exists( 'wc-paid', wc_get_order_statuses() ) ) {
							$customer_orders = wc_get_orders( array(
								'meta_key' => '_customer_user',
								'meta_value' => $abo->user_id,
								'status' => array( 'wc-on-hold', 'wc-processing', 'wc-completed' ),
								'numberposts' => 1
							) );
						}
						if ( count( $customer_orders ) > 0 )
							$description .= ' <span class="unpaid subscription-unpaid">(<a href="'.site_url().'/wp-admin/edit.php?post_status=all&post_type=shop_order&_customer_user='.$abo->user_id.'">'.__( 'Not paid', 'course-booking-system' ).'</a>)</span>';

						$table .= '<tr><td>'.$attendance_count.'</td><td class="subscription"><a href="'.get_edit_user_link( $abo->user_id ).'">'.$user_info->first_name.' '.$user_info->last_name.'</a> '.$description.'</td><td><a href="#" class="button btn btn-primary et_pb_button action-abo-delete" title="Storno" data-id="'.$course->id.'" data-date="'.$date.'" data-user="'.$abo->user_id.'">×</a></td></tr>';
					}
				}
				$attendance_abo = $attendance_count;

				$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course->id AND date = '$date'" );
				foreach ( $bookings as $booking ) {
					$attendance_count++;

					$user_info = get_userdata( $booking->user_id );
					$mailto   .= $user_info->user_email.',';

					$table .= '<tr>';
						$table .= '<td>'.$attendance_count.'</td>';

						if ( $price_level_for_lower_course ) {
							if ( $price_level == 4 ) {
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

						if ( $price_level_for_lower_course && $price_level != $price_level_card )
							$description .= ' ('.$price_level_names[$price_level_card].')';

						$abo_start = get_the_author_meta( 'abo_start', $booking->user_id );
						$abo_expire = get_the_author_meta( 'abo_expire', $booking->user_id );
						if ( $abo_start <= $date && $abo_expire >= $date )
							$description .= ' <span class="booking-subscription-indication subscription-indication">('.__( 'Subscription', 'course-booking-system' ).')</span>';

						$logs = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_logs WHERE user_id = $booking->user_id ORDER BY log_id DESC LIMIT 2" );
						if ( count( $logs ) > 0 ) {
							foreach ( $logs as $log ) {
								if ( $log->action == 'referral' ) {
									$description .= ' <span class="referral">('.__( 'Referral', 'course-booking-system' ).')</span>';
								}
							}
						}

						$customer_orders = array();
						if ( array_key_exists( 'wc-paid', wc_get_order_statuses() ) ) {
							$customer_orders = wc_get_orders( array(
								'meta_key' => '_customer_user',
								'meta_value' => $booking->user_id,
								'status' => array( 'wc-on-hold', 'wc-processing', 'wc-completed' ),
								'numberposts' => 1
							) );
						}

						if ( count( $customer_orders ) > 0 )
							$description .= ' <span class="unpaid booking-unpaid">(<a href="'.site_url().'/wp-admin/edit.php?post_status=all&post_type=shop_order&_customer_user='.$booking->user_id.'">'.__( 'Not paid', 'course-booking-system' ).'</a>)</span>';

						$table .= '<td class="booking"><a href="'.get_edit_user_link( $booking->user_id ).'"><span class="first-name">'.$user_info->first_name.'</span> <span class="last-name">'.$user_info->last_name.'</span></a>'.$description.'</td>';

						$table .= '<td><a href="#" class="button btn btn-primary et_pb_button action-booking-delete" title="Storno" data-id="'.$course->id.'" data-date="'.$date.'" data-user="'.$booking->user_id.'" data-booking="'.$booking->booking_id.'">×</a></td>';
					$table .= '</tr>';
				}
				$attendance_this = $attendance_count;
				$attendance_all = $attendance_all + $attendance_count;

				for ( $k = 1; $k <= ( intval( $attendance ) - intval( $attendance_count ) ); $k++ ) {
					$table .= '<tr><td>'.( $k + $attendance_count ).'</td><td><input type="text" class="livesearch-input" data-id="'.$course->id.'" data-date="'.$date.'" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></td><td></td></tr>';
				}

				if ( ( $show_availability || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) && !( $date >= $holiday_start && $date <= $holiday_end ) && !cbs_is_holiday( date( 'd', strtotime($date) ), date( 'm', strtotime($date) ), date( 'Y', strtotime($date) ) ) ) {
					if ( $attendance_count == $attendance ) {
						echo '<p class="availability">'.__( 'Availability:', 'course-booking-system' ).' <strong class="red">'.( intval( $attendance ) - intval( $attendance_count ) ).'</strong></p>';
						echo '<div class="progress-wrapper red"><progress value="'.$attendance_count.'" max="'.$attendance.'"></progress></div>';
					} else if ( $attendance_count > $attendance / 2 ) {
						echo '<p class="availability">'.__( 'Availability:', 'course-booking-system' ).' <strong class="yellow">'.( intval( $attendance ) - intval( $attendance_count ) ).'</strong></p>';
						echo '<div class="progress-wrapper yellow"><progress value="'.$attendance_count.'" max="'.$attendance.'"></progress></div>';
					} else {
						if ( ( intval( $attendance ) - intval( $attendance_count ) ) > 5 ) {
							echo '<p class="availability">'.__( 'Availability:', 'course-booking-system' ).' <strong class="amount">'.sprintf( __( 'more than 5', 'course-booking-system' ), ( intval( $attendance ) - intval( $attendance_count ) ) ).'</strong></p>';
						} else {
							echo '<p class="availability">'.__( 'Availability:', 'course-booking-system' ).' <strong class="amount">'.( intval( $attendance ) - intval( $attendance_count ) ).'</strong></p>';
						}
						echo '<div class="progress-wrapper"><progress value="'.$attendance_count.'" max="'.$attendance.'"></progress></div>';
					}
				}

				$permalink = get_permalink( $course->post_id ).'?course_id='.$course->id;
				$timetable_custom_url = get_post_meta( $course->post_id, 'timetable_custom_url', true );
				if ( !empty( $timetable_custom_url ) )
					$permalink = $timetable_custom_url;
				?>

				<a href="<?= $permalink ?>" class="button btn btn-primary et_pb_button"><?php _e( 'Go to course', 'course-booking-system' ); ?></a>

				<?php if ( is_user_logged_in() && ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || get_current_user_id() == $course->user_id || ( !empty( $substitute_id ) && get_current_user_id() == $substitute_id ) || $_SERVER['HTTP_HOST'] == 'pfotenakademie.de' || $_SERVER['HTTP_HOST'] == 'hundeschule-team-wuff.com' ) && !( $date >= $holiday_start && $date <= $holiday_end ) && !cbs_is_holiday( date( 'd', strtotime($date) ), date( 'm', strtotime($date) ), date( 'Y', strtotime($date) ) ) ) { ?>
					<h3><?php _e( 'Bookings', 'woocommerce' ); ?></h3>
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

					<p class="email"><a href="<?= substr( $mailto, 0, -1 ).'&subject='.__( 'Information about the course', 'course-booking-system' ).' '.get_the_title( $course->post_id ).' '.__( 'on', 'course-booking-system' ).' '.cbs_get_weekday( $course->day ).', '.date_i18n( $date_format, strtotime( $date ) ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ) ?>"><?php _e( 'Email to all participants', 'course-booking-system' ); ?></a></p>

					<p><span class="participants"><strong><?php _e( 'Number of participants', 'course-booking-system' ); ?></strong>: <?= $attendance_this ?></span>
					<?php $note = cbs_get_note( $course->id, $date ); ?>
					<?= !empty( $note ) ? '<br><strong>'.__( 'Note', 'woocommerce' ).'</strong>: '.$note : '' ?>
					</p>

					<?php
					$waitlists = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_waitlists WHERE course_id = $course->id AND date = '$date'" );
					if ( count( $waitlists ) > 0 || $attendance_count >= $attendance ) {
						?>
						<h3><?php _e( 'Waiting list', 'course-booking-system' ); ?></h3>
						<table>
							<tbody>
								<tr>
									<th></th>
									<th><?php _e( 'Name', 'woocommerce' ); ?></th>
									<th></th>
								</tr>
								<?php
								$waitlist_count = 1;
								$mailto = 'mailto:'.$admin_email.'?bcc=';

								foreach ( $waitlists as $waitlist ) {
									$user_info = get_userdata( $waitlist->user_id );
									$mailto    .= $user_info->user_email.',';

									echo '<tr><td>'.$waitlist_count.'</td><td><a href="'.get_edit_user_link( $waitlist->user_id ).'">'.$user_info->first_name.' '.$user_info->last_name.'</a></td><td><a href="#" class="button btn btn-primary et_pb_button action-waitlist-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course->id.'" data-date="'.$date.'" data-user="'.$waitlist->user_id.'">×</a></td></tr>';
									$waitlist_count++;
								}
								?>
								<tr><td><?= $waitlist_count ?></td><td><input type="text" class="livesearch-input-waitlist" data-id="<?= $course->id ?>" data-date="<?= $date ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></td><td></td></tr>
							</tbody>
						</table>
						<p class="email"><a href="<?= substr( $mailto, 0, -1 ).'&subject='.__( 'Information about the course', 'course-booking-system' ).' '.get_the_title( $course->post_id ).' '.__( 'on', 'course-booking-system' ).' '.cbs_get_weekday( $course->day ).', '.date_i18n( $date_format, strtotime( $date ) ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ) ?>"><?php _e( 'Email to all on the waiting list', 'course-booking-system' ); ?></a></p>
						<?php
					}

					$cancellations = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_cancellations WHERE course_id = $course->id AND date = '$date'" );
					if ( count( $cancellations ) > 0 ) {
						?>
						<h3><?php _e( 'Cancellations', 'course-booking-system' ); ?></h3>
						<table>
							<tbody>
								<tr>
									<th></th>
									<th><?php _e( 'Name', 'woocommerce' ); ?></th>
									<th><?php _e( 'Timestamp', 'woocommerce' ); ?></th>
								</tr>
								<?php
								$cancellation_count = 1;
								foreach ( $cancellations as $cancellation ) {
									$user_info = get_userdata( $cancellation->user_id );
									echo '<tr><td>'.$cancellation_count.'</td><td><a href="'.get_edit_user_link( $cancellation->user_id ).'">'.$user_info->first_name.' '.$user_info->last_name.'</a></td><td>'.date_i18n( $date_format.' '.$time_format, strtotime( $cancellation->timestamp ) ).'</td></tr>';
									$cancellation_count++;
								}
								?>
							</tbody>
						</table>
					<?php
				}
			}
			?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php
	if ( ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) && $attendance_all > 0 ) :
		?>
		<h2><?php _e( 'Daily statistics', 'course-booking-system' ); ?></h2>
		<p class="participants-total"><strong><?php _e( 'Total participants', 'course-booking-system' ); ?></strong>: <?= $attendance_all ?></p>
	<?php endif; ?>

	<div id="event-loader" class="loader"><div></div><div></div><div></div></div>

</div>

<?php do_action( 'cbs_after_archive_course', $day, $date ); ?>
