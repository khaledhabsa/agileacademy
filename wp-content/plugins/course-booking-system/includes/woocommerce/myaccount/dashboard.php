<?php
function cbs_woocommerce_account_dashboard() {
	global $wpdb;
	$user_id = get_current_user_id();
	$current_time = current_time( 'timestamp' );

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );
	$timezone    = get_option( 'timezone_string' );

	$blog_title     = get_bloginfo( 'name' );
	$store_address  = get_option( 'woocommerce_store_address' );
	$store_postcode = get_option( 'woocommerce_store_postcode' );
	$store_city     = get_option( 'woocommerce_store_city' );
	$account_url    = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

	$deleting_in_advance  = get_option( 'course_booking_system_deleting_in_advance' );
	$abo_alternate_option = get_option( 'course_booking_system_abo_alternate' );

	$holiday_start       = get_option( 'course_booking_system_holiday_start' );
	$holiday_end         = get_option( 'course_booking_system_holiday_end' );
	$holiday_description = get_option( 'course_booking_system_holiday_description' );

	$abo_start     = get_the_author_meta( 'abo_start', $user_id );
	$abo_expire    = get_the_author_meta( 'abo_expire', $user_id );
	$abo_course    = get_the_author_meta( 'abo_course', $user_id );
	$abo_course_2  = get_the_author_meta( 'abo_course_2', $user_id );
	$abo_course_3  = get_the_author_meta( 'abo_course_3', $user_id );
	$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
	$abo_alternate = explode( ',', $abo_alternate );

	$card     = get_the_author_meta( 'card', $user_id );
	$expire   = get_the_author_meta( 'expire', $user_id );
	$card_2   = get_the_author_meta( 'card_2', $user_id );
	$expire_2 = get_the_author_meta( 'expire_2', $user_id );
	$card_3   = get_the_author_meta( 'card_3', $user_id );
	$expire_3 = get_the_author_meta( 'expire_3', $user_id );
	$card_4   = get_the_author_meta( 'card_4', $user_id );
	$expire_4 = get_the_author_meta( 'expire_5', $user_id );
	$card_5   = get_the_author_meta( 'card_4', $user_id );
	$expire_5 = get_the_author_meta( 'expire_5', $user_id );

	if ( isset( $_GET['message'] ) && $_GET['message'] == 'purchase' ) :
		?>
		<div class="woocommerce-message">
			<?php _e( 'Thank you for your purchase. Your card was successfully redeemed. You can now book appointments.', 'course-booking-system' ); ?>
			<?php if ( isset( $_COOKIE['last-course-visited'] ) && filter_var( $_COOKIE['last-course-visited'], FILTER_VALIDATE_URL ) ) : ?>
				<br>
				<?php _e( 'Go now to your last visited course and continue booking.', 'course-booking-system' ); ?>
				<a href="<?= htmlspecialchars( $_COOKIE['last-course-visited'] ) ?>">» <?php _e( 'Go to course', 'course-booking-system' ); ?></a>
			<?php endif; ?>
		</div>
	<?php elseif ( ( isset( $_GET['message'] ) && $_GET['message'] == 'subscription' ) || ( $abo_expire > date( 'Y-m-d' ) && empty( $abo_course ) ) ) :
		?>
		<div class="woocommerce-message subscription">
			<?php
			if ( $abo_expire > date( 'Y-m-d' ) && empty( $abo_course ) ) {
				?>

				<p><?php _e( 'Thank you. You have a valid subscription. Please select the course of your choice so that this place is reserved for you.', 'course-booking-system' ); ?></p>

				<?php
				$options = array();
				$price_levels = array();
				$courses = cbs_get_courses();
				foreach ( $courses as $course ) {
					$options[$course->id] = $course->post_title.', '.cbs_get_weekday( $course->day ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' );

					$price_levels[$course->id] = get_post_meta( $course->post_id, 'price_level', true );
				}

				$customer = new WC_Customer( $user_id );
				$order = $customer->get_last_order();
				foreach ( $order->get_items() as $item_id => $item ) {
					$product_id = $item->get_product_id();
					$subscription_price_level = get_post_meta( $product_id, '_subscription_price_level', true );
				}
				?>

				<form>
					<p class="form-field _subscription_course_field">
						<label for="_subscription_course"><?php _e( 'Subscription course', 'course-booking-system' ); ?></label>
						<select id="_subscription_course" name="_subscription_course" class="select short">
							<option value=""><?php _e( 'Choose a course', 'course-booking-system' ); ?></option>
							<?php
							foreach ( $options AS $key => $value ) {
								if ( $subscription_price_level == $price_levels[$key] || empty( $subscription_price_level ) ) {
									?>
									<option value="<?= $key ?>"><?= $value ?></option>
									<?php
								}
							}
							?>
						</select>
					</p>
				</form>
			<?php } else {
				_e( 'Many Thanks. You already have a valid subscription that has just been extended.', 'course-booking-system' );
			}
			?>
		</div>
	<?php elseif ( isset( $_GET['message'] ) && $_GET['message'] == 'limit' ) : ?>
		<div class="woocommerce-error">
			<?php _e( 'Sorry, you have reached your download limit for this file', 'woocommerce' ); ?>
		</div>
	<?php endif; ?>

	<p><?= sprintf( __( 'You can also view your bookings and cancel bookings in your account. If you cancel your course at least %s hours before the course starts, you will receive a credit.', 'course-booking-system' ), $deleting_in_advance ) ?></p>

	<div id="ajax"></div>
	<div id="ajax-loader" class="loader"><div></div><div></div><div></div></div>

	<div id="account" class="course"><?php include 'dashboard-status.php'; ?></div>
	<div id="account-loader" class="loader"><div></div><div></div><div></div></div>

	<?php
	$bookings = $wpdb->get_results( "SELECT booking_id, course_id, date FROM ".$wpdb->prefix."cbs_bookings WHERE user_id = $user_id AND date >= CURDATE() ORDER BY date" );
	if ( count( $bookings ) > 0 ) {
		?>
		<h2 id="bookings" class="bookings-headline"><?php _e( 'Your course bookings', 'course-booking-system' ); ?></h2>
		<table class="bookings-table">
			<tbody>
				<tr>
					<th><?php _e( 'Course', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Date', 'woocommerce' ); ?></th>
					<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
					<th></th>
				</tr>
				<?php
				foreach ( $bookings as $booking ) {
					$booking_id = $booking->booking_id;
					$course_id  = $booking->course_id;
					$date       = $booking->date;

					$courses = cbs_get_courses( array(
						'id' => $course_id
					) );
					foreach ( $courses as $course ) {
						$invitation_link = get_post_meta( $course->post_id, 'invitation_link', true );
						$invitation_link_password = get_post_meta( $course->post_id, 'invitation_link_password', true );

						$actions = '';
						$location = !empty( $invitation_link ) ? $account_url : $blog_title.', '.$store_address.', '.$store_postcode.' '.$store_city;
						$ical = '<br><a href="'.plugins_url( '../../ics-download.php?location='.urlencode( $location ).'&description='.urlencode( $course->post_title ).'&date='.urlencode( $date ).'&start='.urlencode( $course->start ).'&end='.urlencode( $course->end ).'&timezone='.$timezone.'&account_url='.urlencode( $account_url ), __FILE__ ).'">+ iCal</a>';
						if ( !empty( $invitation_link ) && ( $current_time + 25 * MINUTE_IN_SECONDS ) >= strtotime( $date.' '.$course->start ) && $current_time < strtotime( $date.' '.$course->end ) ) {
							$actions = '<a href="'.$invitation_link.'">'.__( 'Take part now', 'course-booking-system' ).'</a>';
							$actions .= !empty( $invitation_link_password ) ? '<br>'.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password : '';
							?>
							<div class="woocommerce-message"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<div class="woocommerce-message fixed"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<?php
						} else if ( ( strtotime( $date.' '.$course->start ) - $deleting_in_advance * HOUR_IN_SECONDS ) > $current_time ) {
							$actions = '<a href="#" class="action-booking-delete" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_id.'" data-booking="'.$booking_id.'"><span class="action-icon">×</span> '.__( 'Reverse', 'course-booking-system' ).'</a>'.$ical;
						} else {
							$actions = '<a href="#" class="action-booking-delete decline" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_id.'" data-booking="'.$booking_id.'" data-confirm="'.__( 'Do you really want to cancel your booked course? No credit will be given.', 'course-booking-system' ).'"><span class="action-icon">×</span> '.__( 'Cancel', 'course-booking-system' ).'</a>'.$ical;
						}

						echo '<tr id="booking-id-'.$booking_id.'"><td><a href="'.get_permalink( $course->post_id ).'?course_id='.$course_id.'">'.$course->post_title.'</a></td><td><a href="'.cbs_get_weekday_permalink( $course->day, $date ).'">';

						if ( $course->day != 99 )
							echo cbs_get_weekday( $course->day ).', ';

						echo date_i18n( $date_format, strtotime( $date ) ).'</a></td><td>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td>'.$actions.'</td></tr>';
					}
				}
				?>
			</tbody>
		</table>
	<?php } else if ( empty( $abo ) && $abo_expire < date( 'Y-m-d' ) ) { ?>
		<div class="woocommerce-info"><?php _e( 'You haven\'t booked any upcoming courses.', 'course-booking-system' ); ?></div>
		<?php
	}

	if ( !empty( $abo ) || $abo_expire > date( 'Y-m-d' ) ) {
		if ( !empty( $abo_course ) ) {
			?>
			<h2 class="subscription-headline"><?php _e( 'Your subscription', 'course-booking-system' ); ?></h2>
			<table class="subscription-table">
				<tbody>
					<tr>
						<th><?php _e( 'Course', 'course-booking-system' ); ?></th>
						<th><?php _e( 'Date', 'woocommerce' ); ?></th>
						<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
						<th></th>
					</tr>
					<?php
					$courses = cbs_get_courses( array(
						'id' => $abo_course
					) );
					foreach ( $courses as $course ) {
						$post_id = $course->post_id;
						$post_title = $course->post_title;
						$day = $course->day;
						$weekday = date( 'l', strtotime( 'Sunday +'.$course->day.' days' ) );
					}

					$difference_weeks = floor( ( strtotime( $abo_expire ) - strtotime( $abo_start ) ) / 604800);
					for ( $i = 1; $i <= $difference_weeks; $i++ ) {
						$date = date( 'Y-m-d', strtotime( 'last '.$weekday, strtotime( $abo_start.' +'.$i.' weeks' ) ) );

						$substitute_id = cbs_get_substitute_id( $abo_course, $date );
						$invitation_link = get_post_meta( $post_id, 'invitation_link', true );

						$actions = '';
						$location = !empty( $invitation_link ) ? $account_url : $blog_title.', '.$store_address.', '.$store_postcode.' '.$store_city;
						$ical = '<br><a href="'.plugins_url( '../../ics-download.php?location='.urlencode( $location ).'&description='.urlencode( $post_title ).'&date='.urlencode( $date ).'&start='.urlencode( $start ).'&end='.urlencode( $end ).'&timezone='.$timezone.'&account_url='.urlencode( $account_url ), __FILE__ ).'">+ iCal</a>';
						if ( cbs_is_holiday( date( 'd', strtotime($date) ), date( 'm', strtotime($date) ), date( 'Y', strtotime($date) ) ) ) {
							$actions = __( 'Holiday', 'course-booking-system' );
						} else if ( ( $date > $holiday_start && $date < $holiday_end ) || $date == $holiday_start || $date == $holiday_end || $substitute_id == 99999 ) {
							$actions = __( 'Cancelled', 'course-booking-system' );
						} else if ( !empty( $invitation_link ) && $course->day == date( 'N' ) && ( $current_time + 25 * MINUTE_IN_SECONDS ) > strtotime( $date.' '.$start ) && $current_time < strtotime( $date.' '.$end ) ) {
							$actions = '<a href="'.$invitation_link.'">'.__( 'Take part now', 'course-booking-system' ).'</a>';
							?>
							<div class="woocommerce-message"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<div class="woocommerce-message fixed"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<?php
						} else if ( ( strtotime( $date.' '.$start ) - $deleting_in_advance * HOUR_IN_SECONDS ) > $current_time && ( empty( $abo_alternate_option ) || count( $abo_alternate ) < $abo_alternate_option ) ) {
							$actions = '<a href="#" class="action-abo-delete" data-id="'.$abo_course.'" data-date="'.$date.'" data-user="'.$user_id.'"><span class="action-icon">×</span> '.__( 'Reverse', 'course-booking-system' ).'</a>'.$ical;
						} else if ( strtotime( $date.' '.$start ) > $current_time ) {
							$actions = '<a href="#" class="action-abo-delete decline" data-id="'.$abo_course.'" data-date="'.$date.'" data-user="'.$user_id.'" data-confirm="'.__( 'Do you really want to cancel your booked course? No credit will be given.', 'course-booking-system' ).'"><span class="action-icon">×</span> '.__( 'Cancel', 'course-booking-system' ).'</a>'.$ical;
						} if ( !in_array( $date, $abo_alternate ) && strtotime( $date.' '.$end ) >= $current_time ) {
							echo '<tr id="abo-date-'.$date.'"><td><a href="'.get_permalink( $post_id ).'?course_id='.$abo_course.'">'.$post_title.'</a></td><td><a href="'.cbs_get_weekday_permalink( $day, $date ).'">'.cbs_get_weekday( $day ).', '.date_i18n( $date_format, strtotime( $date ) ).'</a></td><td>'.date( $time_format, strtotime( $start ) ).' - '.date( $time_format, strtotime( $end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td>'.$actions.'</td></tr>';
						}
					}
					?>
				</tbody>
			</table>
		<?php } ?>

		<?php if ( !empty( $abo_course_2 ) ) { ?>
			<table class="subscription-table-2">
				<tbody>
					<tr>
						<th><?php _e( 'Course', 'course-booking-system' ); ?></th>
						<th><?php _e( 'Date', 'woocommerce' ); ?></th>
						<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
						<th></th>
					</tr>
					<?php
					$courses = cbs_get_courses( array(
						'id' => $abo_course_2
					) );
					foreach ( $courses as $course ) {
						$post_id_2 = $course->post_id;
						$post_title_2 = $course->post_title;
						$day_2 = $course->day;
						$weekday_2 = date( 'l', strtotime( 'Sunday +'.$course->day.' days' ) );
					}

					for ( $i = 1; $i <= $difference_weeks; $i++ ) {
						$date = date( 'Y-m-d', strtotime( 'last '.$weekday_2, strtotime( $abo_start.' +'.$i.' weeks' ) ) );

						$substitute_id = cbs_get_substitute_id( $abo_course_2, $date );
						$invitation_link = get_post_meta( $post_id_2, 'invitation_link', true );

						$actions = '';
						$location = !empty( $invitation_link ) ? $account_url : $blog_title.', '.$store_address.', '.$store_postcode.' '.$store_city;
						$ical = '<br><a href="'.plugins_url( '../../ics-download.php?location='.urlencode( $location ).'&description='.urlencode( $post_title_2 ).'&date='.urlencode( $date ).'&start='.urlencode( $start_2 ).'&end='.urlencode( $end_2 ).'&timezone='.$timezone.'&account_url='.urlencode( $account_url ), __FILE__ ).'">+ iCal</a>';
						if ( cbs_is_holiday( date( 'd', strtotime($date) ), date( 'm', strtotime($date) ), date( 'Y', strtotime($date) ) ) ) {
							$actions = __( 'Holiday', 'course-booking-system' );
						} else if ( ( $date > $holiday_start && $date < $holiday_end ) || $date == $holiday_start || $date == $holiday_end || $substitute_id == 99999 ) {
							$actions = __( 'Cancelled', 'course-booking-system' );
						} else if ( !empty( $invitation_link ) && $weekdays[$day_2]['weekday'] == strtolower( date('l') ) && ( $current_time + 25 * MINUTE_IN_SECONDS ) > strtotime( $date.' '.$start_2 ) && $current_time < strtotime( $date.' '.$end_2 ) ) {
							$actions = '<a href="'.$invitation_link.'">'.__( 'Take part now', 'course-booking-system' ).'</a>';
							?>
							<div class="woocommerce-message"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<div class="woocommerce-message fixed"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<?php
						} else if ( ( strtotime( $date.' '.$start_2 ) - $deleting_in_advance * HOUR_IN_SECONDS ) > $current_time && ( empty( $abo_alternate_option ) || count( $abo_alternate ) < $abo_alternate_option ) ) {
							$actions = '<a href="#" class="action-abo-delete" data-id="'.$abo_course_2.'" data-date="'.$date.'" data-user="'.$user_id.'"><span class="action-icon">×</span> '.__( 'Reverse', 'course-booking-system' ).'</a>'.$ical;
						} else if ( strtotime( $date.' '.$start_2 ) > $current_time ) {
							$actions = '<a href="#" class="action-abo-delete decline" data-id="'.$abo_course_2.'" data-date="'.$date.'" data-user="'.$user_id.'" data-confirm="'.__( 'Do you really want to cancel your booked course? No credit will be given.', 'course-booking-system' ).'"><span class="action-icon">×</span> '.__( 'Cancel', 'course-booking-system' ).'</a>'.$ical;
						} if ( !in_array( $date, $abo_alternate ) && strtotime( $date.' '.$end_2 ) > $current_time ) {
							echo '<tr id="abo-date-'.$date.'"><td><a href="'.get_permalink( $post_id_2 ).'?course_id='.$abo_course_2.'">'.$post_title_2.'</a></td><td><a href="'.cbs_get_weekday_permalink( $day_2, $date ).'">'.cbs_get_weekday( $day_2 ).', '.date_i18n( $date_format, strtotime( $date ) ).'</a></td><td>'.date( $time_format, strtotime( $start_2 ) ).' - '.date( $time_format, strtotime( $end_2 ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td>'.$actions.'</td></tr>';
						}
					}
					?>
				</tbody>
			</table>
			<?php
		}

		if ( !empty( $abo_course_3 ) ) {
			?>
			<table class="subscription-table-3">
				<tbody>
					<tr>
						<th><?php _e( 'Course', 'course-booking-system' ); ?></th>
						<th><?php _e( 'Date', 'woocommerce' ); ?></th>
						<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
						<th></th>
					</tr>
					<?php
					$courses = cbs_get_courses( array(
						'id' => $abo_course_3
					) );
					foreach ( $courses as $course ) {
						$post_id_3 = $course->post_id;
						$post_title_3 = $course->post_title;
						$day_3 = $course->day;
						$weekday_3 = date( 'l', strtotime( 'Sunday +'.$course->day.' days' ) );
					}

					for ( $i = 1; $i <= $difference_weeks; $i++ ) {
						$date = date( 'Y-m-d', strtotime( 'last '.$weekday_3, strtotime( $abo_start.' +'.$i.' weeks' ) ) );

						$substitute_id = cbs_get_substitute_id( $abo_course_3, $date );
						$invitation_link = get_post_meta( $post_id_3, 'invitation_link', true );

						$actions = '';
						$location = !empty( $invitation_link ) ? $account_url : $blog_title.', '.$store_address.', '.$store_postcode.' '.$store_city;
						$ical = '<br><a href="'.plugins_url( '../../ics-download.php?location='.urlencode( $location ).'&description='.urlencode( $post_title_3 ).'&date='.urlencode( $date ).'&start='.urlencode( $start_3 ).'&end='.urlencode( $end_3 ).'&timezone='.$timezone.'&account_url='.urlencode( $account_url ), __FILE__ ).'">+ iCal</a>';
						if ( cbs_is_holiday( date( 'd', strtotime($date) ), date( 'm', strtotime($date) ), date( 'Y', strtotime($date) ) ) ) {
							$actions = __( 'Holiday', 'course-booking-system' );
						} else if ( ( $date > $holiday_start && $date < $holiday_end ) || $date == $holiday_start || $date == $holiday_end || $substitute_id == 99999 ) {
							$actions = __( 'Cancelled', 'course-booking-system' );
						} else if ( !empty( $invitation_link ) && $weekdays[$day_3]['weekday'] == strtolower( date('l') ) && ( $current_time + 25 * MINUTE_IN_SECONDS ) > strtotime( $date.' '.$start_3 ) && $current_time < strtotime( $date.' '.$end_3 ) ) {
							$actions = '<a href="'.$invitation_link.'">'.__( 'Take part now', 'course-booking-system' ).'</a>';
							?>
							<div class="woocommerce-message"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<div class="woocommerce-message fixed"><?php _e( 'Your course starts right away. Click on the following link and take part now:', 'course-booking-system' ); ?> <a href="<?= $invitation_link ?>" target="_blank"><?php _e( 'Take part now', 'course-booking-system' ); ?></a> <?= !empty( $invitation_link_password ) ? '('.__( 'Password:', 'course-booking-system' ).' '.$invitation_link_password.')' : '' ?></div>
							<?php
						} else if ( ( strtotime( $date.' '.$start_3 ) - $deleting_in_advance * HOUR_IN_SECONDS ) > $current_time && ( empty( $abo_alternate_option ) || count( $abo_alternate ) < $abo_alternate_option ) ) {
							$actions = '<a href="#" class="action-abo-delete" data-id="'.$abo_course_3.'" data-date="'.$date.'" data-user="'.$user_id.'"><span class="action-icon">×</span> '.__( 'Reverse', 'course-booking-system' ).'</a>'.$ical;
						} else if ( strtotime( $date.' '.$start_3 ) > $current_time ) {
							$actions = '<a href="#" class="action-abo-delete decline" data-id="'.$abo_course_3.'" data-date="'.$date.'" data-user="'.$user_id.'" data-confirm="'.__( 'Do you really want to cancel your booked course? No credit will be given.', 'course-booking-system' ).'"><span class="action-icon">×</span> '.__( 'Cancel', 'course-booking-system' ).'</a>'.$ical;
						} if ( !in_array( $date, $abo_alternate ) && strtotime( $date.' '.$end_3 ) > $current_time ) {
							echo '<tr id="abo-date-'.$date.'"><td><a href="'.get_permalink( $post_id_3 ).'?course_id='.$abo_course_3.'">'.$post_title_3.'</a></td><td><a href="'.cbs_get_weekday_permalink( $day_3, $date ).'">'.cbs_get_weekday( $day_3 ).', '.date_i18n( $date_format, strtotime( $date ) ).'</a></td><td>'.date( $time_format, strtotime( $start_3 ) ).' - '.date( $time_format, strtotime( $end_3 ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td>'.$actions.'</td></tr>';
						}
					}
					?>
				</tbody>
			</table>
			<?php
		}
	}

	$bookings_past = get_option( 'course_booking_system_bookings_past' );
	$bookings = $wpdb->get_results( "SELECT booking_id, course_id, date FROM ".$wpdb->prefix."cbs_bookings WHERE user_id = $user_id AND date < CURDATE() ORDER BY date DESC LIMIT $bookings_past" );
	if ( count( $bookings ) > 0 ) {
		?>
		<h2 class="bookings-past-headline"><?= sprintf( __( 'Your most recently attended courses (max. %s courses)', 'course-booking-system' ), $bookings_past ) ?></h2>
		<table class="bookings-past-table">
			<tbody>
				<tr>
					<th><?php _e( 'Course', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Date', 'woocommerce' ); ?></th>
					<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
				</tr>
				<?php
				foreach ( $bookings as $booking ) {
					$course_id = $booking->course_id;
					$date = $booking->date;

					$courses = cbs_get_courses( array(
						'id' => $course_id
					) );
					foreach ( $courses as $course ) {
						echo '<tr><td><a href="'.get_permalink( $course->post_id ).'?course_id='.$course_id.'">'.$course->post_title.'</a></td><td><a href="'.cbs_get_weekday_permalink( $course->day, $date ).'">'.cbs_get_weekday( $course->day ).', '.date_i18n( $date_format, strtotime( $date ) ).'</a></td><td>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td></tr>';
					}
				}
				?>
			</tbody>
		</table>
		<?php
	}

	$waitlists = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_waitlists WHERE user_id = $user_id AND date >= CURDATE() ORDER BY date" );
	if ( count( $waitlists ) > 0 ) {
		?>
		<h2 class="waitlists-headline"><?php _e( 'Your waiting list', 'course-booking-system' ); ?></h2>
		<table class="waitlists-table">
			<tbody>
				<tr>
					<th><?php _e( 'Course', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Date', 'woocommerce' ); ?></th>
					<th><?php _e( 'Time', 'course-booking-system' ); ?></th>
					<th></th>
				</tr>
				<?php
				foreach ( $waitlists as $waitlist ) {
					$waitlist_id = $waitlist->waitlist_id;
					$course_id = $waitlist->course_id;
					$date = $waitlist->date;

					$courses = cbs_get_courses( array(
						'id' => $course_id
					) );
					foreach ( $courses as $course ) {
						echo '<tr id="waitlist-id-'.$waitlist_id.'"><td><a href="'.get_permalink( $course->post_id ).'?course_id='.$course_id.'">'.$course->post_title.'</a></td><td><a href="'.cbs_get_weekday_permalink( $course->day, $date ).'">'.cbs_get_weekday( $course->day ).', '.date_i18n( $date_format, strtotime( $date ) ).'</a></td><td>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td><a href="#" class="action-waitlist-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$waitlist->user_id.'" data-waitlist="'.$waitlist_id.'"><span class="action-icon">×</span> '.__( 'Reverse', 'course-booking-system' ).'</a></td></tr>';
					}
				}
				?>
			</tbody>
		</table>
		<?php
	}
}
add_action( 'woocommerce_account_dashboard', 'cbs_woocommerce_account_dashboard', 10, 0 );

// Video library
function cbs_video_library( $menu_links ) {
	$args = array ( 
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'meta_query' => array( 
			array( 
				'key' => '_video_url', 
				'value' => '',
				'compare' => '!='
			), 
		), 
	);
	$videos = new WP_Query( $args );

	if ( $videos->have_posts() ) :
		$new = array( 'video' => __( 'Video library', 'course-booking-system' ) );

		$menu_links = array_slice( $menu_links, 0, 1, true ) 
		+ $new 
		+ array_slice( $menu_links, 1, NULL, true );
	endif;

	return $menu_links;
}
add_filter( 'woocommerce_account_menu_items', 'cbs_video_library' );

function cbs_add_endpoint() {
	add_rewrite_endpoint( 'video', EP_PAGES );
}
add_action( 'init', 'cbs_add_endpoint' );

function cbs_my_account_endpoint_content() {
 	if ( isset( $_GET['message'] ) && $_GET['message'] == 'purchase' ) :
		?>
		<div class="woocommerce-message">
			<?php _e( 'Thank you for your purchase. Your video was successfully added to your library. You can now watch the purchased video.', 'course-booking-system' ); ?>
		</div>
		<?php
	endif;

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$user_id = get_current_user_id();
	$downloads = wc_get_customer_download_permissions( $user_id );

	// Ordered videos
	if ( count( $downloads ) > 0 ) :
		foreach ( $downloads as $download ) :
			$product_id = $download->product_id;
			$product = wc_get_product( $product_id );

			$video_url = get_post_meta( $product_id, '_video_url', true );
			$video_url_password = get_post_meta( $product_id, '_video_url_password', true );
			$video_expiry = $download->access_expires;

			$path_parts = pathinfo( $video_url );
			$extensions = array( 'mp4', 'webm', 'ogg' );

			if ( !empty( $video_url ) && ( strtotime( $video_expiry ) > current_time( 'timestamp' ) || empty($video_expiry) ) ) {
				?>
				<h3><?= $product->get_name() ?></h3>

				<?php if ( !empty( $path_parts['extension'] ) && in_array( strtolower( $path_parts['extension'] ), $extensions ) ) : ?>
					<video controls controlslist="nodownload" onContextMenu="return false;">
						<source src="<?= $video_url ?>" type="video/<?= $path_parts['extension'] ?>">
						<?php _e( 'Your browser does not support the video tag.', 'course-booking-system' ); ?>
					</video>
				<?php elseif ( str_contains( $video_url, 'youtube' ) || str_contains( $video_url, 'youtu.be' ) ) : ?>
					<?php
					$youtube_url = parse_url( $video_url );
					if ( str_contains( $video_url, 'youtu.be' ) ) {
						$youtube_id = str_replace( '/', '', $youtube_url['path'] ).'?rel=0';
					} else {
						if ( str_contains( $video_url, 'playlist' ) ) {
							parse_str( $youtube_url['query'], $params );
							$youtube_id = 'videoseries?list='.$params['list'];
						} else {
							parse_str( $youtube_url['query'], $params );
							$youtube_id = $params['v'].'?rel=0';
						}
					}
					?>
					<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/<?= $youtube_id ?>&controls=0&rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; modestbranding; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				<?php elseif ( str_contains( $video_url, 'vimeo' ) ) : ?>
						<?php
						$vimeo_url = parse_url( $video_url );
						$vimeo_url = explode( '/', $vimeo_url['path'] );
						$vimeo_id = end( $vimeo_url );

						if ( str_contains( $video_url, 'showcase' ) ) {
							?>
							<div style='padding:56.25% 0 0 0;position:relative;'><iframe src='https://vimeo.com/showcase/<?= $vimeo_id ?>/embed' allowfullscreen frameborder='0' style='position:absolute;top:0;left:0;width:100%;height:100%;'></iframe></div>
						<?php } else if ( count( $vimeo_url ) > 2 ) { ?>
							<?php
							$vimeo_id = $vimeo_url[1];
							$h = end( $vimeo_url );
							?>
							<iframe src="https://player.vimeo.com/video/<?= $vimeo_id ?>?h=<?= $h ?>&badge=0&autopause=0" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Vimeo video player"></iframe>
							<script src="https://player.vimeo.com/api/player.js"></script>
						<?php } else { ?>
							<iframe src="https://player.vimeo.com/video/<?= $vimeo_id ?>?badge=0&autopause=0" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
						<?php } ?>
					<?php else : ?>
					<div class="woocommerce-message">
						<?php _e( 'The video is only available externally and cannot be played here. To play the video, please follow this link:', 'course-booking-system' ); ?>
						<a href="<?= $video_url ?>" target="_blank" class="button wc-forward"><?php _e( 'Go to video', 'course-booking-system' ); ?></a>
					</div>
				<?php endif; ?>

				<p><?= !empty( $video_expiry ) ? sprintf( __( 'The video is available until %s o\'clock.', 'course-booking-system' ), date_i18n( $date_format.' '.$time_format, strtotime( $video_expiry ) ) ) : __( 'The video is available indefinitely.', 'course-booking-system' ) ?><?= !empty( $video_url_password ) ? '<br>'.__( 'Password:', 'course-booking-system' ).' '.$video_url_password : '' ?></p>
				<?php
			}
		endforeach;
	endif;

	// Additional videos
	$video_ids = array();
	$args = array ( 
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array( 
			array( 
				'key' => '_video_ids', 
				'value' => '',
				'compare' => '!='
			)
		)
	);
	$videos = get_posts( $args );

	if ( !empty( $videos ) ) :
		foreach ( $videos AS $video ) :
			if ( wc_customer_bought_product( '', get_current_user_id(), $video->ID ) ) :
				$video_ids[$video->ID] = $video->post_title;
			endif;
		endforeach;
	endif;

	if ( count( $video_ids ) > 0 ) :
		foreach ( $video_ids as $video_id => $title ) :
			foreach ( get_post_meta( $video_id, '_video_ids', true ) AS $product_id ) :
				$product = wc_get_product( $product_id );

				$video_url = get_post_meta( $product_id, '_video_url', true );
				$video_url_password = get_post_meta( $product_id, '_video_url_password', true );

				$path_parts = pathinfo( $video_url );
				$extensions = array( 'mp4', 'webm', 'ogg' );

				if ( !empty( $video_url ) ) {
					?>
					<h3><?= $product->get_name() ?></h3>

					<?php if ( !empty( $path_parts['extension'] ) && in_array( strtolower( $path_parts['extension'] ), $extensions ) ) : ?>
						<video controls controlslist="nodownload" onContextMenu="return false;">
							<source src="<?= $video_url ?>" type="video/<?= $path_parts['extension'] ?>">
							<?php _e( 'Your browser does not support the video tag.', 'course-booking-system' ); ?>
						</video>
					<?php elseif ( str_contains( $video_url, 'youtube' ) || str_contains( $video_url, 'youtu.be' ) ) : ?>
						<?php
						$youtube_url = parse_url( $video_url );
						if ( str_contains( $video_url, 'youtu.be' ) ) {
							$youtube_id = str_replace( '/', '', $youtube_url['path'] ).'?rel=0';
						} else {
							if ( str_contains( $video_url, 'playlist' ) ) {
								parse_str( $youtube_url['query'], $params );
								$youtube_id = 'videoseries?list='.$params['list'];
							} else {
								parse_str( $youtube_url['query'], $params );
								$youtube_id = $params['v'].'?rel=0';
							}
						}
						?>
						<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/<?= $youtube_id ?>&controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; modestbranding; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					<?php elseif ( str_contains( $video_url, 'vimeo' ) ) : ?>
						<?php
						$vimeo_url = parse_url( $video_url );
						$vimeo_url = explode( '/', $vimeo_url['path'] );
						$vimeo_id = end( $vimeo_url );

						if ( str_contains( $video_url, 'showcase' ) ) {
							?>
							<div style='padding:56.25% 0 0 0;position:relative;'><iframe src='https://vimeo.com/showcase/<?= $vimeo_id ?>/embed' allowfullscreen frameborder='0' style='position:absolute;top:0;left:0;width:100%;height:100%;'></iframe></div>
						<?php } else if ( count( $vimeo_url ) > 2 ) { ?>
							<?php
							$vimeo_id = $vimeo_url[1];
							$h = end( $vimeo_url );
							?>
							<iframe src="https://player.vimeo.com/video/<?= $vimeo_id ?>?h=<?= $h ?>&badge=0&autopause=0" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Vimeo video player"></iframe>
							<script src="https://player.vimeo.com/api/player.js"></script>
						<?php } else { ?>
							<iframe src="https://player.vimeo.com/video/<?= $vimeo_id ?>?badge=0&autopause=0" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
						<?php } ?>
					<?php else : ?>
						<div class="woocommerce-message">
							<?php _e( 'The video is only available externally and cannot be played here. To play the video, please follow this link:', 'course-booking-system' ); ?>
							<a href="<?= $video_url ?>" target="_blank" class="button wc-forward"><?php _e( 'Go to video', 'course-booking-system' ); ?></a>
						</div>
					<?php endif; ?>

					<p><?= sprintf( __( 'The video is part of your purchased product "%s".', 'course-booking-system' ), $title ) ?><?= !empty( $video_url_password ) ? '<br>'.__( 'Password:', 'course-booking-system' ).' '.$video_url_password : '' ?></p>
					<?php
				}
			endforeach;
		endforeach;
	endif;

	if ( count( $downloads ) == 0 && count( $video_ids ) == 0 ) :
		?>
		<div class="woocommerce-message">
			<?php _e( 'You don\'t have any videos available. Please buy a video in our shop.', 'course-booking-system' ); ?>
			<a href="<?= get_permalink( wc_get_page_id('shop') ) ?>" tabindex="1" class="button wc-forward"><?php _e( 'Go to shop', 'course-booking-system' ); ?></a>
		</div>
		<?php
	endif;
}
add_action( 'woocommerce_account_video_endpoint', 'cbs_my_account_endpoint_content' );