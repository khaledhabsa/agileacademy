<?php
function cbs_register_settings() {
	add_option( 'course_booking_system_intro', __( 'We attach great importance to good quality within the courses. Therefore, our courses only take place with a limited number of participants. Here you can see the current occupancy of this course and register for these courses. Book this course now. Choose your desired date below and book the course. In order to book a course you need to have an account with a valid card.', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_intro', 'course_booking_system_callback' );

	add_option( 'course_booking_system_booking_in_advance', 7 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_booking_in_advance', 'course_booking_system_callback' );

	add_option( 'course_booking_system_deleting_in_advance', 24 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_deleting_in_advance', 'course_booking_system_callback' );

	add_option( 'course_booking_system_auto_cancel', '' );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_auto_cancel', 'course_booking_system_callback' );

	add_option( 'course_booking_system_auto_cancel_number', 3 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_auto_cancel_number', 'course_booking_system_callback' );

	add_option( 'course_booking_system_auto_cancel_advance', 5 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_auto_cancel_advance', 'course_booking_system_callback' );

	add_option( 'course_booking_system_expire_extend', '' );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_expire_extend', 'course_booking_system_callback' );

	add_option( 'course_booking_system_waitlist_auto_booking', '' );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_waitlist_auto_booking', 'course_booking_system_callback' );

	add_option( 'course_booking_system_design', '' );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_design', 'course_booking_system_callback' );

	add_option( 'course_booking_system_show_availability', 1 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_show_availability', 'course_booking_system_callback' );

	add_option( 'course_booking_system_show_cancelled', 0 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_show_cancelled', 'course_booking_system_callback' );

	add_option( 'course_booking_system_message_offset', 0 );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_message_offset', 'course_booking_system_callback' );

	add_option( 'course_booking_system_license', '' );
	register_setting( 'course_booking_system_options_group_general', 'course_booking_system_license', 'course_booking_system_callback' );


	add_option( 'course_booking_system_price_level_title', __( 'Price Level', 'course-booking-system' ).' 1' );
	register_setting( 'course_booking_system_options_group_price_level', 'course_booking_system_price_level_title', 'course_booking_system_callback' );

	add_option( 'course_booking_system_price_level_title_2', __( 'Price Level', 'course-booking-system' ).' 2' );
	register_setting( 'course_booking_system_options_group_price_level', 'course_booking_system_price_level_title_2', 'course_booking_system_callback' );

	add_option( 'course_booking_system_price_level_title_3', __( 'Price Level', 'course-booking-system' ).' 3' );
	register_setting( 'course_booking_system_options_group_price_level', 'course_booking_system_price_level_title_3', 'course_booking_system_callback' );

	add_option( 'course_booking_system_price_level_title_4', __( 'Price Level', 'course-booking-system' ).' 4' );
	register_setting( 'course_booking_system_options_group_price_level', 'course_booking_system_price_level_title_4', 'course_booking_system_callback' );

	add_option( 'course_booking_system_price_level_title_5', __( 'Price Level', 'course-booking-system' ).' 5' );
	register_setting( 'course_booking_system_options_group_price_level', 'course_booking_system_price_level_title_5', 'course_booking_system_callback' );

	add_option( 'course_booking_system_price_level_for_lower_course', '' );
	register_setting( 'course_booking_system_options_group_price_level', 'course_booking_system_price_level_for_lower_course', 'course_booking_system_callback' );


	add_option( 'course_booking_system_abo_expire', 6 );
	register_setting( 'course_booking_system_options_group_abo', 'course_booking_system_abo_expire', 'course_booking_system_callback' );

	add_option( 'course_booking_system_abo_period', 4 );
	register_setting( 'course_booking_system_options_group_abo', 'course_booking_system_abo_period', 'course_booking_system_callback' );

	add_option( 'course_booking_system_abo_alternate', 0 );
	register_setting( 'course_booking_system_options_group_abo', 'course_booking_system_abo_alternate', 'course_booking_system_callback' );


	add_option( 'course_booking_system_holiday_new_year', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_new_year', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_epiphany', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_epiphany', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_labor_day', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_labor_day', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_national_holiday', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_national_holiday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_national_holiday_austria', 0 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_national_holiday_austria', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_reformation_day', '' );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_reformation_day', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_all_saints_day', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_all_saints_day', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_christmas_eve', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_christmas_eve', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_christmas_day_1', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_christmas_day_1', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_christmas_day_2', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_christmas_day_2', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_new_years_eve', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_new_years_eve', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_good_friday', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_good_friday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_easter_sunday', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_easter_sunday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_easter_monday', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_easter_monday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_ascension', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_ascension', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_whit_sunday', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_whit_sunday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_whit_monday', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_whit_monday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_corpus_christi', 1 );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_corpus_christi', 'course_booking_system_callback' );


	add_option( 'course_booking_system_holiday_start', '' );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_start', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_end', '' );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_end', 'course_booking_system_callback' );

	add_option( 'course_booking_system_holiday_description', 'Wir machen Urlaub.' );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_holiday_description', 'course_booking_system_callback' );

	add_option( 'course_booking_system_opening', '' );
	register_setting( 'course_booking_system_options_group_holiday', 'course_booking_system_opening', 'course_booking_system_callback' );


	add_option( 'course_booking_system_woocommerce_auto_complete_order', 1 );
	register_setting( 'course_booking_system_options_group_woocommerce', 'course_booking_system_woocommerce_auto_complete_order', 'course_booking_system_callback' );

	add_option( 'course_booking_system_woocommerce_birthday', '' );
	register_setting( 'course_booking_system_options_group_woocommerce', 'course_booking_system_woocommerce_birthday', 'course_booking_system_callback' );

	add_option( 'course_booking_system_woocommerce_birthday_email', '' );
	register_setting( 'course_booking_system_options_group_woocommerce', 'course_booking_system_woocommerce_birthday_email', 'course_booking_system_callback' );

	add_option( 'course_booking_system_woocommerce_referral', '' );
	register_setting( 'course_booking_system_options_group_woocommerce', 'course_booking_system_woocommerce_referral', 'course_booking_system_callback' );

	add_option( 'course_booking_system_woocommerce_referral_price_level', 1 );
	register_setting( 'course_booking_system_options_group_woocommerce', 'course_booking_system_woocommerce_referral_price_level', 'course_booking_system_callback' );

	add_option( 'course_booking_system_bookings_past', 15 );
	register_setting( 'course_booking_system_options_group_woocommerce', 'course_booking_system_bookings_past', 'course_booking_system_callback' );


	add_option( 'course_booking_system_email_deleting', '' );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_deleting', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_booking', '' );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_booking', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_cancel', '' );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_cancel', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_cancel_address', get_option( 'admin_email' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_cancel_address', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_expire', 1 );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_expire', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_waitlist', '' );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_waitlist', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_waitlist_address', get_option( 'admin_email' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_waitlist_address', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_waitlist_subject', __( 'A place has become available for your course on', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_waitlist_subject', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_waitlist_content', __( 'You are now welcome to book the course if you are interested. Here you can access your account and book the course:', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_waitlist_content', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_invitation_subject', __( 'Your course starts right away', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_invitation_subject', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_invitation_content', __( 'Click on the following link to go to your account and take part now:', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_invitation_content', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_expire_subject', __( 'Your card will expire soon', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_expire_subject', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_expire_content', __( 'We hereby remind you that your card will expire soon. Log into your account to see the exact expiry date of your cards:', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_expire_content', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_expire_content_2', __( 'Please redeem your appointments before the expiry date so that the remaining appointments on your card do not expire.', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_expire_content_2', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_flat_subject', __( 'Your flatrate will expire soon', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_flat_subject', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_flat_content', __( 'We hereby remind you that your flatrate will expire soon. Log into your account to see the exact expiry date of your cards:', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_flat_content', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_flat_content_2', __( 'Please buy a new card after the flatrate has expired in order to continue participating in our courses.', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_flat_content_2', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_birthday_subject', __( 'Happy Birthday', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_birthday_subject', 'course_booking_system_callback' );

	add_option( 'course_booking_system_email_birthday_content', __( 'We wish you all the best for your birthday and a great new year. May your wishes come true.', 'course-booking-system' ) );
	register_setting( 'course_booking_system_options_group_email', 'course_booking_system_email_birthday_content', 'course_booking_system_callback' );
}
add_action( 'admin_init', 'cbs_register_settings' );

function cbs_register_options_page() {
	add_options_page( __( 'Course Booking System', 'course-booking-system' ), __( 'Course Booking System', 'course-booking-system' ), 'manage_options', 'course_booking_system', 'cbs_options_page' );
}
add_action( 'admin_menu', 'cbs_register_options_page' );

function cbs_add_settings_link_plugin_page( $links ) {
	array_unshift( $links , '<a href="'.admin_url( 'options-general.php?page=course_booking_system' ).'">'.__( 'Settings' ).'</a>' ); // Add link as first element in array
	return $links;
}
add_filter( 'plugin_action_links_course-booking-system/course-booking-system.php', 'cbs_add_settings_link_plugin_page' );

function cbs_options_page() {
	?>
	<div class="wrap">
		<h1><?php _e( 'Settings', 'course-booking-system' ); ?> › <?php _e( 'Course Booking System', 'course-booking-system' ); ?></h1>

		<?php
		global $cbs_active_tab;
		$cbs_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		?>
		<h2 class="nav-tab-wrapper"><?php do_action( 'cbs_settings_tab' ); ?></h2>
		<?php do_action( 'cbs_settings_content' ); ?>
	</div>
	<?php
}

function cbs_general_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'general' || '' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=general' ) ?>"><?php _e( 'General', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_general_tab', 1 );

function cbs_general_render_options_page() {
	global $cbs_active_tab;
	if ( '' || 'general' != $cbs_active_tab )
		return;
	?>
 
	<form method="post" action="options.php">
		<?php settings_fields( 'course_booking_system_options_group_general' ); ?>

		<h2><?php _e( 'Settings for the course booking system', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Here you can make settings for the course booking system.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_intro"><?php _e( 'Intro text', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<textarea rows="5" name="course_booking_system_intro" id="course_booking_system_intro" class="large-text"><?= get_option( 'course_booking_system_intro' ) ?></textarea>
						<?php else : ?>
							<textarea rows="5" name="course_booking_system_intro" id="course_booking_system_intro" class="large-text" readonly><?= get_option( 'course_booking_system_intro' ) ?></textarea>
						<?php endif; ?>
						<p class="description"><?php _e( 'This text is shown before each course. A separate text can also be stored and displayed for each course.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_booking_in_advance"><?php _e( 'Bookings in advance (in weeks)', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_booking_in_advance" id="course_booking_system_booking_in_advance" type="number" value="<?= get_option( 'course_booking_system_booking_in_advance' ) ?>" min="1" max="52" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_booking_in_advance" id="course_booking_system_booking_in_advance" type="number" value="<?= get_option( 'course_booking_system_booking_in_advance' ) ?>" min="1" max="52" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_deleting_in_advance"><?php _e( 'Cancellations in advance (in hours)', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_deleting_in_advance" id="course_booking_system_deleting_in_advance" type="number" value="<?= get_option( 'course_booking_system_deleting_in_advance' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_deleting_in_advance" id="course_booking_system_deleting_in_advance" type="number" value="<?= get_option( 'course_booking_system_deleting_in_advance' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
						<p class="description"><?php _e( 'The cancellations in advance (in hours) indicate how many hours before the start of the course a booking can be cancelled free of charge. After this time, only a cancellation is possible. In the event of a cancellation, no credit will be credited.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_auto_cancel"><?php _e( 'Automatic course cancellation', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_auto_cancel" id="course_booking_system_auto_cancel" value="1" <?= get_option( 'course_booking_system_auto_cancel' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Automatically set „Course is cancelled', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'Set courses automatically to „Course is cancelled if attendance is below a certain amount. The value from the field „Cancellations in advance (in hours)“ applies.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr id="tr-auto_cancel_number" <?= get_option( 'course_booking_system_auto_cancel' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_auto_cancel_number"><?php _e( 'Number of participants for automatic course cancellation', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_auto_cancel_number" id="course_booking_system_auto_cancel_number" type="number" value="<?= get_option( 'course_booking_system_auto_cancel_number' ) ?>" class="regular-text">
						<p class="description"><?php _e( 'Courses with fewer maximum possible participants will not be cancelled. If there are no bookings in a course, regardless of the maximum number of participants, the course will be automatically cancelled 12 hours in advance.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr id="tr-auto_cancel_advance" <?= get_option( 'course_booking_system_auto_cancel' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_auto_cancel_advance"><?php _e( 'Automatic cancellation in advance (in hours)', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_auto_cancel_advance" id="course_booking_system_auto_cancel_advance" type="number" value="<?= get_option( 'course_booking_system_auto_cancel_advance' ) ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_expire_extend"><?php _e( 'Extend card expiry date', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_expire_extend" id="course_booking_system_expire_extend" value="1" <?= get_option( 'course_booking_system_expire_extend' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Automatically extend the expiry date of the card by one week', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'Automatically extend the expiry date of the card by one week if the cancellation of a booking has not been made by the customer.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_waitlist_auto_booking"><?php _e( 'Waiting list auto-booking', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_waitlist_auto_booking" id="course_booking_system_waitlist_auto_booking" value="1" <?= get_option( 'course_booking_system_waitlist_auto_booking' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Automatically book courses for people on the waiting list', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'If checked: People on the waiting list are automatically booked into a course as soon as a place becomes available. The order of the waiting list will be followed. The person who received a booking will be notified.', 'course-booking-system' ); ?></p>
						<p class="description"><?php _e( 'In not checked: As soon as a place becomes free, all persons will be notified by email at the same time. Every person has the chance to register for the vacant space. The principle of "first come, first served" applies here.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e( 'Design', 'course-booking-system' ); ?></h2>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_design"><?php _e( 'Timetable Design', 'course-booking-system' ); ?></label></th>
					<td>
						<select name="course_booking_system_design" id="course_booking_system_design">
							<option value="default" <?= get_option( 'course_booking_system_design' ) == 'default' ? 'selected="selected"' : '' ?>><?php _e( 'Default', 'course-booking-system' ); ?></option>
							<option value="divided" <?= get_option( 'course_booking_system_design' ) == 'divided' ? 'selected="selected"' : '' ?> <?= !cbs_is_licensed() ? 'disabled' : '' ?>><?php _e( 'Divided', 'course-booking-system' ); ?></option>
							<option value="list" <?= ( get_option( 'course_booking_system_design' ) == 'list' ) ? 'selected="selected"' : '' ?> <?= !cbs_is_licensed() ? 'disabled' : '' ?>><?php _e( 'List', 'course-booking-system' ); ?></option>
						</select>
						<?php
						if ( !cbs_is_licensed() )
							_e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' );
						?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_show_availability"><?php _e( 'Availability', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_show_availability" id="course_booking_system_show_availability" value="1" <?= get_option('course_booking_system_show_availability' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Show availability of a single course to all users', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'If the checkbox is not checked, only administrators, editors, authors and contributors can see the availability of the courses.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_show_cancelled"><?php _e( 'Cancelled courses', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_show_cancelled" id="course_booking_system_show_cancelled" value="1" <?= get_option('course_booking_system_show_cancelled' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Show cancelled courses in timetable', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'If the checkbox is not checked, cancelled courses are hidden in the timetable. Otherwise cancelled courses are visible but grayed out.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_message_offset"><?php _e( 'Offset', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_message_offset" id="course_booking_system_message_offset" type="number" value="<?= get_option( 'course_booking_system_message_offset' ) ?>" class="regular-text">
						<p class="description"><?php _e( 'The offset describes how much space you need above your info boxes so that the message is visible and does not, for example, disappear under the menu.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e( 'License', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'If you want to activate the Pro version to be able to use all functions of the plugin, <a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">buy a Pro license in the ComMotion online shop</a>.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_license"><?php _e( 'License', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_license" id="course_booking_system_license" type="password" value="<?= get_option( 'course_booking_system_license' ) ?>" class="regular-text">

						<?php if ( cbs_is_licensed() ) : ?>
							<p class="description" style="color: green;"><?php _e( 'The license you entered is valid. You can use all pro features.', 'course-booking-system' ); ?></p>
						<?php elseif ( !empty( get_option( 'course_booking_system_license' ) ) ) : ?>
							<p class="description" style="color: red;"><?php _e( 'The license you entered is invalid. Please try again.', 'course-booking-system' ); ?></p>
						<?php endif; ?>
					</tr>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_general_render_options_page' );

function cbs_price_level_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'price-level' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=price-level' ) ?>"><?php _e( 'Price Levels', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_price_level_tab' );

function cbs_price_level_render_options_page() {
	global $cbs_active_tab;
	if ( 'price-level' != $cbs_active_tab )
		return;
	?>

	<form method="post" action="options.php">
		<?php settings_fields( 'course_booking_system_options_group_price_level' ); ?>

		<h2><?php _e( 'Price Levels', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Here you can make the settings for the different price levels.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_price_level_title"><?php _e( 'Name of the price level', 'course-booking-system' ); ?> 1</label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_price_level_title" id="course_booking_system_price_level_title" type="text" value="<?= get_option( 'course_booking_system_price_level_title' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_price_level_title" id="course_booking_system_price_level_title" type="text" value="<?= get_option( 'course_booking_system_price_level_title' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_price_level_title_2"><?php _e( 'Name of the price level', 'course-booking-system' ); ?> 2</label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_price_level_title_2" id="course_booking_system_price_level_title_2" type="text" value="<?= get_option( 'course_booking_system_price_level_title_2' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_price_level_title_2" id="course_booking_system_price_level_title_2" type="text" value="<?= get_option( 'course_booking_system_price_level_title_2' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_price_level_title_3"><?php _e( 'Name of the price level', 'course-booking-system' ); ?> 3</label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_price_level_title_3" id="course_booking_system_price_level_title_3" type="text" value="<?= get_option( 'course_booking_system_price_level_title_3' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_price_level_title_3" id="course_booking_system_price_level_title_3" type="text" value="<?= get_option( 'course_booking_system_price_level_title_3' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_price_level_title_4"><?php _e( 'Name of the price level', 'course-booking-system' ); ?> 4</label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_price_level_title_4" id="course_booking_system_price_level_title_4" type="text" value="<?= get_option( 'course_booking_system_price_level_title_4' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_price_level_title_4" id="course_booking_system_price_level_title_4" type="text" value="<?= get_option( 'course_booking_system_price_level_title_4' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_price_level_title_5"><?php _e( 'Name of the price level', 'course-booking-system' ); ?> 5</label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_price_level_title_5" id="course_booking_system_price_level_title_5" type="text" value="<?= get_option( 'course_booking_system_price_level_title_5' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_price_level_title_5" id="course_booking_system_price_level_title_5" type="text" value="<?= get_option( 'course_booking_system_price_level_title_5' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_price_level_for_lower_course"><?php _e( 'Higher price levels', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_price_level_for_lower_course" id="course_booking_system_price_level_for_lower_course" value="1" <?= get_option( 'course_booking_system_price_level_for_lower_course' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Allow cards with higher price levels for courses with lower price levels', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'Allow your customers to book a course with a lower price level using a card with a higher price level. This only applies to normal cards and not to flatrates.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_price_level_render_options_page' );

function cbs_abo_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'abo' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=abo' ) ?>"><?php _e( 'Subscription', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_abo_tab' );

function cbs_abo_render_options_page() {
	global $cbs_active_tab;
	if ( 'abo' != $cbs_active_tab )
		return;
	?>

	<form method="post" action="options.php">
		<?php settings_fields( 'course_booking_system_options_group_abo' ); ?>

		<h2><?php _e( 'Subscription', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Here you can manage the settings for the subscriptions.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_abo_expire"><?php _e( 'Subscription duration (in months)', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_abo_expire" id="course_booking_system_abo_expire" type="number" value="<?= get_option( 'course_booking_system_abo_expire' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_abo_expire" id="course_booking_system_abo_expire" type="number" value="<?= get_option( 'course_booking_system_abo_expire' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
						<p class="description"><?php _e( 'Number of months by which the duration of a subscription is automatically extended.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_abo_period"><?php _e( 'Notice period (in weeks)', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_abo_period" id="course_booking_system_abo_period" type="number" value="<?= get_option( 'course_booking_system_abo_period' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_abo_period" id="course_booking_system_abo_period" type="number" value="<?= get_option( 'course_booking_system_abo_period' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
						<p class="description"><?php _e( 'Number of weeks allowed before the subscription ends. If the notice period is missed, a subscription is automatically extended by the number of months specified in the field above.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_abo_alternate"><?php _e( 'Allowed subscription unsubscriptions', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_abo_alternate" id="course_booking_system_abo_alternate" type="number" value="<?= get_option( 'course_booking_system_abo_alternate' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_abo_alternate" id="course_booking_system_abo_alternate" type="number" value="<?= get_option( 'course_booking_system_abo_alternate' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
						<p class="description"><?php _e( 'Number of subscription cancellations that are allowed per subscription period. If the number of cancellations exceeds the permitted number, the customer can only cancel appointments for which the appointment is made, but no credit is credited to the customer\'s card. Leave blank if a subscription customer can unsubscribe as often as they like.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_abo_render_options_page' );

function cbs_holiday_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'holiday' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=holiday' ) ?>"><?php _e( 'Holidays', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_holiday_tab' );

function cbs_holiday_render_options_page() {
	global $cbs_active_tab;
	if ( 'holiday' != $cbs_active_tab )
		return;
	?>

	<form method="post" action="options.php">
		<?php settings_fields( 'course_booking_system_options_group_holiday' ); ?>

		<h2><?php _e( 'Holidays', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Here you can enter fixed and flexible holidays. On these days, the course is blocked and not released for booking.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label><?php _e( 'Fixed Holidays', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_holiday_new_year" id="course_booking_system_holiday_new_year" value="1" <?= get_option( 'course_booking_system_holiday_new_year' ) ? 'checked="checked"' : '' ?> /><?php _e( 'New Year', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_epiphany" id="course_booking_system_holiday_epiphany" value="1" <?= get_option( 'course_booking_system_holiday_epiphany' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Epiphany', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_labor_day" id="course_booking_system_holiday_labor_day" value="1" <?= get_option( 'course_booking_system_holiday_labor_day' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Labor Day', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_national_holiday" id="course_booking_system_holiday_national_holiday" value="1" <?= get_option( 'course_booking_system_holiday_national_holiday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'National Holiday', 'course-booking-system' ); ?> <?php _e( '(Germany)', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_national_holiday_austria" id="course_booking_system_holiday_national_holiday_austria" value="1" <?= get_option( 'course_booking_system_holiday_national_holiday_austria' ) ? 'checked="checked"' : '' ?> /><?php _e( 'National Holiday', 'course-booking-system' ); ?> <?php _e( '(Austria)', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_reformation_day" id="course_booking_system_holiday_reformation_day" value="1" <?= get_option( 'course_booking_system_holiday_reformation_day' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Reformation Day', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_all_saints_day" id="course_booking_system_holiday_all_saints_day" value="1" <?= get_option( 'course_booking_system_holiday_all_saints_day' ) ? 'checked="checked"' : '' ?> /><?php _e( 'All Saints\' Day', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_christmas_eve" id="course_booking_system_holiday_christmas_eve" value="1" <?= get_option( 'course_booking_system_holiday_christmas_eve' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Christmas Eve', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_christmas_day_1" id="course_booking_system_holiday_christmas_day_1" value="1" <?= get_option( 'course_booking_system_holiday_christmas_day_1' ) ? 'checked="checked"' : '' ?> /><?php _e( '1. Christmas Day', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_christmas_day_2" id="course_booking_system_holiday_christmas_day_2" value="1" <?= get_option( 'course_booking_system_holiday_christmas_day_2' ) ? 'checked="checked"' : '' ?> /><?php _e( '2. Christmas Day', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_new_years_eve" id="course_booking_system_holiday_new_years_eve" value="1" <?= get_option( 'course_booking_system_holiday_new_years_eve' ) ? 'checked="checked"' : '' ?> /><?php _e( 'New Year\'s Eve', 'course-booking-system' ); ?><br />
					</td>
				</tr>
				<tr>
					<th><label><?php _e( 'Moving Holidays', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_holiday_good_friday" id="course_booking_system_holiday_good_friday" value="1" <?= get_option( 'course_booking_system_holiday_good_friday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Good Friday', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_easter_sunday" id="course_booking_system_holiday_easter_sunday" value="1" <?= get_option( 'course_booking_system_holiday_easter_sunday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Easter Sunday', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_easter_monday" id="course_booking_system_holiday_easter_monday" value="1" <?= get_option( 'course_booking_system_holiday_easter_monday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Easter Monday', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_ascension" id="course_booking_system_holiday_ascension" value="1" <?= get_option( 'course_booking_system_holiday_ascension' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Ascension', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_whit_sunday" id="course_booking_system_holiday_whit_sunday" value="1" <?= get_option( 'course_booking_system_holiday_whit_sunday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Whit Sunday', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_whit_monday" id="course_booking_system_holiday_whit_monday" value="1" <?= get_option( 'course_booking_system_holiday_whit_monday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Whit Monday', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_holiday_corpus_christi" id="course_booking_system_holiday_corpus_christi" value="1" <?= get_option( 'course_booking_system_holiday_corpus_christi' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Corpus Christi', 'course-booking-system' ); ?><br />
					</td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e( 'Vacation', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'You can enter your vacation here. The rates between the start date and the end date of the vacation are blocked all day. Courses that have already been booked will not be held.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_holiday_start"><?php _e( 'Beginning of vacation', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_holiday_start" id="course_booking_system_holiday_start" type="date" value="<?= get_option( 'course_booking_system_holiday_start' ) ?>" class="regular-text" placeholder="YYYY-MM-DD">
						<?php else : ?>
							<input name="course_booking_system_holiday_start" id="course_booking_system_holiday_start" type="date" value="<?= get_option( 'course_booking_system_holiday_start' ) ?>" class="regular-text" placeholder="YYYY-MM-DD" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_holiday_end"><?php _e( 'End of vacation', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_holiday_end" id="course_booking_system_holiday_end" type="date" value="<?= get_option( 'course_booking_system_holiday_end' ) ?>" class="regular-text" placeholder="YYYY-MM-DD">
						<?php else : ?>
							<input name="course_booking_system_holiday_end" id="course_booking_system_holiday_end" type="date" value="<?= get_option( 'course_booking_system_holiday_end' ) ?>" class="regular-text" placeholder="YYYY-MM-DD" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_holiday_description"><?php _e( 'Vacation description', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<textarea rows="5" name="course_booking_system_holiday_description" id="course_booking_system_holiday_description" class="large-text"><?= get_option( 'course_booking_system_holiday_description' ) ?></textarea>
						<?php else : ?>
							<textarea rows="5" name="course_booking_system_holiday_description" id="course_booking_system_holiday_description" class="large-text" readonly><?= get_option( 'course_booking_system_holiday_description' ) ?></textarea>
						<?php endif; ?>
						<p class="description"><?php _e( 'This label is displayed for the blocked courses.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_opening"><?php _e( 'Opening', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_opening" id="course_booking_system_opening" type="date" value="<?= get_option( 'course_booking_system_opening' ) ?>" class="regular-text" placeholder="YYYY-MM-DD">
						<?php else : ?>
							<input name="course_booking_system_opening" id="course_booking_system_opening" type="date" value="<?= get_option( 'course_booking_system_opening' ) ?>" class="regular-text" placeholder="YYYY-MM-DD" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
						<p class="description"><?php _e( 'If your course offer starts only after a certain date, you can set this start date here.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_holiday_render_options_page' );

function cbs_woocommerce_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'woocommerce' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=woocommerce' ) ?>"><?php _e( 'WooCommerce', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_woocommerce_tab' );

function cbs_woocommerce_render_options_page() {
	global $cbs_active_tab;
	if ( 'woocommerce' != $cbs_active_tab )
		return;
	?>

	<form method="post" action="options.php">
		<?php settings_fields( 'course_booking_system_options_group_woocommerce' ); ?>

		<h2>WooCommerce</h2>
		<p><?php _e( 'Here you can make individual settings for WooCommerce.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label><?php _e( 'Auto-complete order', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_woocommerce_auto_complete_order" id="course_booking_system_woocommerce_auto_complete_order" value="1" <?= get_option( 'course_booking_system_woocommerce_auto_complete_order' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Complete WooCommerce orders automatically', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'If you choose this option, all WooCommerce orders are automatically set to "completed" so that the purchased card can be redeemed automatically and the customer can book a course with the card immediately after purchase.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label><?php _e( 'Birthday', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_woocommerce_birthday" id="course_booking_system_woocommerce_birthday" value="1" <?= get_option( 'course_booking_system_woocommerce_birthday' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Ask for the birthday when a user registers (this field is optional for the customer)', 'course-booking-system' ); ?><br />
						<input type="checkbox" name="course_booking_system_woocommerce_birthday_email" id="course_booking_system_woocommerce_birthday_email" value="1" <?= get_option( 'course_booking_system_woocommerce_birthday_email' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Send an email to the customer on birthday, if the birthday was set', 'course-booking-system' ); ?><br />
					</td>
				</tr>
				<tr>
					<th><label><?php _e( 'Referral', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_woocommerce_referral" id="course_booking_system_woocommerce_referral" value="1" <?= get_option( 'course_booking_system_woocommerce_referral' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Give customers the opportunity to recommend friends and acquaintances. The new customer and the referrer receive a free credit.', 'course-booking-system' ); ?><br />
					</td>
				</tr>
				<tr id="tr-referral_price_level" <?= get_option( 'course_booking_system_woocommerce_referral' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_woocommerce_referral_price_level"><?php _e( 'Price level for free credit after a successful referral', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_woocommerce_referral_price_level" id="course_booking_system_woocommerce_referral_price_level" type="number" min="1" max="5" value="<?= get_option( 'course_booking_system_woocommerce_referral_price_level' ) ?>" class="regular-text">
					</td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e( 'Account', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Here you can manage the settings for the account.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_bookings_past"><?php _e( 'Past bookings', 'course-booking-system' ); ?></label></th>
					<td>
						<?php if ( cbs_is_licensed() ) : ?>
							<input name="course_booking_system_bookings_past" id="course_booking_system_bookings_past" type="number" value="<?= get_option( 'course_booking_system_bookings_past' ) ?>" class="regular-text">
						<?php else : ?>
							<input name="course_booking_system_bookings_past" id="course_booking_system_bookings_past" type="number" value="<?= get_option( 'course_booking_system_bookings_past' ) ?>" class="regular-text" readonly> <?php _e( '<a href="https://commotion.online/en/shop/course-booking-system-pro-license/" target="_blank">Pro Feature</a>', 'course-booking-system' ); ?>
						<?php endif; ?>
						<p class="description"><?php _e( 'Number of past bookings that are visible in the account.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_woocommerce_render_options_page' );

function cbs_email_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'email' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=email' ) ?>"><?php _e( 'Emails', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_email_tab' );

function cbs_email_render_options_page() {
	global $cbs_active_tab;
	if ( 'email' != $cbs_active_tab )
		return;
	?>

	<form method="post" action="options.php">
		<?php settings_fields( 'course_booking_system_options_group_email' ); ?>

		<h2><?php _e( 'Emails', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Determine which emails should be sent.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_email_deleting"><?php _e( 'Email for course reversion', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_email_deleting" id="course_booking_system_email_deleting" value="1" <?= get_option('course_booking_system_email_deleting' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Send an email to the customer if an admin reverses or cancels a course', 'course-booking-system' ); ?><br />
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_email_booking"><?php _e( 'Email for course booking', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_email_booking" id="course_booking_system_email_booking" value="1" <?= get_option('course_booking_system_email_booking' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Send an email if a customer books a course', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'The email will be sent to both the course trainer and the course participant when booking. An email is also sent to both of them if the client cancels a course. Emails are always sent for courses with 2 or fewer maximum participants.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_email_cancel"><?php _e( 'Email in case of course cancellation', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_email_cancel" id="course_booking_system_email_cancel" value="1" <?= get_option('course_booking_system_email_cancel' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Send an email if a customer cancels a course', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'In the event of cancellation, the e-mail will be sent to the e-mail address entered below. The trainer of the course will also receive a copy of this email (CC). Emails are always sent for courses with 2 or fewer maximum participants.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr id="tr-email_cancel_address" <?= get_option('course_booking_system_email_cancel' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_email_cancel_address"><?php _e( 'Email address for the course cancellation email', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_email_cancel_address" id="course_booking_system_email_cancel_address" type="email" value="<?= get_option( 'course_booking_system_email_cancel_address' ) ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_email_expire"><?php _e( 'Email on expiry', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_email_expire" id="course_booking_system_email_expire" value="1" <?= get_option('course_booking_system_email_expire' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Send an email when a card or flat expires', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'Automatic sending of an email one week before a card or flat rate expires.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_email_waitlist"><?php _e( 'Email for waitlist in case of course reversion', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_email_waitlist" id="course_booking_system_email_waitlist" value="1" <?= get_option( 'course_booking_system_email_waitlist' ) ? 'checked="checked"' : '' ?> /><?php _e( 'Send an email in BCC when a course is cancelled and there is a waitlist', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'The email will be sent to the email address entered below in BCC upon cancellation of a course by a customer. The email will only be sent if there is a waitlist.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr id="tr-email_waitlist_address" <?= get_option( 'course_booking_system_email_waitlist' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_email_waitlist_address"><?php _e( 'Email address for the waitlist email (BCC)', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="course_booking_system_email_waitlist_address" id="course_booking_system_email_waitlist_address" type="email" value="<?= get_option( 'course_booking_system_email_waitlist_address' ) ?>" class="regular-text">
					</td>
				</tr>
			</tbody>
		</table>

		<h2><?php _e( 'Content', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Here you are able to manage the content of all emails. For your security sometimes only parts of the emails content can be changed. If WooCommerce is installed, the settings of the those email templates will be used by default.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr <?= get_option( 'course_booking_system_waitlist_auto_booking' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_email_waitlist_subject"><?php _e( 'Waiting list', 'course-booking-system' ); ?> <?php _e( 'Subject', 'course-booking-system' ); ?></label></th>
					<td>
						<textarea rows="3" name="course_booking_system_email_waitlist_subject" id="course_booking_system_email_waitlist_subject" class="large-text"><?= get_option( 'course_booking_system_email_waitlist_subject' ) ?></textarea>
					</td>
				</tr>
				<tr <?= get_option( 'course_booking_system_waitlist_auto_booking' ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
					<th><label for="course_booking_system_email_waitlist_content"><?php _e( 'Waiting list', 'course-booking-system' ); ?> <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
					<td>
						<textarea rows="3" name="course_booking_system_email_waitlist_content" id="course_booking_system_email_waitlist_content" class="large-text"><?= get_option( 'course_booking_system_email_waitlist_content' ) ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_email_invitation_subject"><?php _e( 'Online course link', 'course-booking-system' ); ?> <?php _e( 'Subject', 'course-booking-system' ); ?></label></th>
					<td>
						<textarea rows="3" name="course_booking_system_email_invitation_subject" id="course_booking_system_email_invitation_subject" class="large-text"><?= get_option( 'course_booking_system_email_invitation_subject' ) ?></textarea>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_email_invitation_content"><?php _e( 'Online course link', 'course-booking-system' ); ?> <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
					<td>
						<textarea rows="3" name="course_booking_system_email_invitation_content" id="course_booking_system_email_invitation_content" class="large-text"><?= get_option( 'course_booking_system_email_invitation_content' ) ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<div id="div-email_expire" <?= get_option( 'course_booking_system_email_expire' ) ? 'style="display: block;"' : 'style="display: none;"' ?>>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th><label for="course_booking_system_email_expire_subject"><?php _e( 'Card expires', 'course-booking-system' ); ?> <?php _e( 'Subject', 'course-booking-system' ); ?></label></th>
						<td>
							<textarea rows="3" name="course_booking_system_email_expire_subject" id="course_booking_system_email_expire_subject" class="large-text"><?= get_option( 'course_booking_system_email_expire_subject' ) ?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for="course_booking_system_email_expire_content"><?php _e( 'Card expires', 'course-booking-system' ); ?> <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
						<td>
							<textarea rows="3" name="course_booking_system_email_expire_content" id="course_booking_system_email_expire_content" class="large-text"><?= get_option( 'course_booking_system_email_expire_content' ) ?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for="course_booking_system_email_expire_content_2"><?php _e( 'Card expires', 'course-booking-system' ); ?> 2 <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
						<td>
							<textarea rows="3" name="course_booking_system_email_expire_content_2" id="course_booking_system_email_expire_content_2" class="large-text"><?= get_option( 'course_booking_system_email_expire_content_2' ) ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th><label for="course_booking_system_email_flat_subject"><?php _e( 'Flatrate expires', 'course-booking-system' ); ?> <?php _e( 'Subject', 'course-booking-system' ); ?></label></th>
						<td>
							<textarea rows="3" name="course_booking_system_email_flat_subject" id="course_booking_system_email_flat_subject" class="large-text"><?= get_option( 'course_booking_system_email_flat_subject' ) ?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for="course_booking_system_email_flat_content"><?php _e( 'Flatrate expires', 'course-booking-system' ); ?> <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
						<td>
							<textarea rows="3" name="course_booking_system_email_flat_content" id="course_booking_system_email_flat_content" class="large-text"><?= get_option( 'course_booking_system_email_flat_content' ) ?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for="course_booking_system_email_flat_content_2"><?php _e( 'Flatrate expires', 'course-booking-system' ); ?> 2 <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
						<td>
							<textarea rows="3" name="course_booking_system_email_flat_content_2" id="course_booking_system_email_flat_content_2" class="large-text"><?= get_option( 'course_booking_system_email_flat_content_2' ) ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_email_birthday_subject"><?php _e( 'Birthday', 'course-booking-system' ); ?> <?php _e( 'Subject', 'course-booking-system' ); ?></label></th>
					<td>
						<textarea rows="3" name="course_booking_system_email_birthday_subject" id="course_booking_system_email_birthday_subject" class="large-text"><?= get_option( 'course_booking_system_email_birthday_subject' ) ?></textarea>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_email_birthday_content"><?php _e( 'Birthday', 'course-booking-system' ); ?> <?php _e( 'Content', 'course-booking-system' ); ?></label></th>
					<td>
						<textarea rows="3" name="course_booking_system_email_birthday_content" id="course_booking_system_email_birthday_content" class="large-text"><?= get_option( 'course_booking_system_email_birthday_content' ) ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_email_render_options_page' );

function cbs_statistics_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'statistics' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=statistics' ) ?>"><?php _e( 'Statistics', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_statistics_tab' );

function cbs_statistics_render_options_page() {
	global $cbs_active_tab;
	if ( 'statistics' != $cbs_active_tab )
		return;

	global $wpdb;

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	if ( !empty( $_REQUEST['start'] ) ) {
		$start = sanitize_text_field( $_REQUEST['start'] );
	} else {
		$start = date( 'Y-m-d', strtotime( '-1 week' ) );
	} if ( !empty( $_REQUEST['start_comparison'] ) ) {
		$start_comparison = sanitize_text_field( $_REQUEST['start_comparison'] );
	} else {
		$start_comparison = date( 'Y-m-d', strtotime( '-2 weeks' ) );
	} if ( !empty( $_REQUEST['end'] ) ) {
		$end = sanitize_text_field( $_REQUEST['end'] );
	} else {
		$end = date( 'Y-m-d', strtotime( $start.' +6 days' ) );
	} if ( !empty( $_REQUEST['end_comparison'] ) ) {
		$end_comparison = sanitize_text_field( $_REQUEST['end_comparison'] );
	} else {
		$end_comparison = date( 'Y-m-d', strtotime( $start_comparison.' +6 days' ) );
	}
	?>

	<form method="post" action="<?= admin_url( 'options-general.php?page=course_booking_system&tab=statistics' ) ?>">
		<?php settings_fields( 'course_booking_system_options_group_email' ); ?>

		<h2><?php _e( 'Statistics', 'course-booking-system' ); ?></h2>
		<p><?php _e( 'Determine which emails should be sent.', 'course-booking-system' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="start"><?php _e( 'Start of period', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="start" id="start" type="date" value="<?= htmlspecialchars( $start ) ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th><label for="start_comparison"><?php _e( 'Start of comparison period', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="start_comparison" id="start_comparison" type="date" value="<?= htmlspecialchars( $start_comparison ) ?>" class="regular-text">
					</td>
				</tr>
				<!-- <tr>
					<th><label for="end"><?php _e( 'End of period', 'course-booking-system' ); ?></label></th>
					<td>
						<input name="end" id="end" type="date" value="<?= htmlspecialchars( $end ) ?>" class="regular-text">
					</td>
				</tr> -->
			</tbody>
		</table>

		<?php submit_button(  __( 'Search period', 'course-booking-system' ) ); ?>
		<p class="submit" style="margin-top: 0; padding-top: 0;"><small><a href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=statistics' ) ?>">× <?php _e( 'Delete period', 'course-booking-system' ); ?></a></small></p>
	</form>

	<h2><?php _e( 'Number of courses booked (excluding subscriptions)', 'course-booking-system' ); ?></h2>
	<?php
	$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE date >= '$start' AND date <= '$end'" );
	$bookings_comparison = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE date >= '$start_comparison' AND date <= '$end_comparison'" );
	echo '<p><strong>'.count( $bookings ).'</strong> '.__( 'Bookings', 'woocommerce' ).' '.__( 'in the period', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end ) ).'.</p>';
	echo '<p><strong>'.count( $bookings_comparison ).'</strong> '.__( 'Bookings', 'woocommerce' ).' '.__( 'in the period', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $start_comparison ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end_comparison ) ).'.</p>';
	$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE date >= '".date( 'Y-m-01', strtotime( $start ) )."' AND date <= '".date( 'Y-m-t', strtotime( $start ) )."'" );
	echo '<p><strong>'.count( $bookings ).'</strong> '.__( 'Bookings', 'woocommerce' ).' '.__( 'in the month', 'course-booking-system' ).' '.date_i18n( 'F', strtotime( $start ) ).'.</p>';

	$abos = $abos_comparison = $abo_alternates = $abo_alternates_comparison = 0;

	$users = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE (meta_key = 'abo' AND meta_value = 1) OR (meta_key = 'abo_2' AND meta_value = 1) OR (meta_key = 'abo_3' AND meta_value = 1)" );
	foreach ( $users as $user ) {
		$user_id       = $user->user_id;
		$abo_start     = get_the_author_meta( 'abo_start', $user_id );
		$abo_expire    = get_the_author_meta( 'abo_expire', $user_id );
		$abo_alternate = get_the_author_meta( 'abo_alternate', $user_id );
		$abo_alternate = explode( ',', $abo_alternate );

		if ( $abo_start <= $start && $abo_expire >= $end ) {
			$abos++;
			$abos_comparison++;
		}

		foreach ( $abo_alternate as $abo_alternate_date ) {
			if ( $abo_alternate_date >= $start && $abo_alternate_date <= $end ) {
				$abo_alternates++;
			}

			if ( $abo_alternate_date >= $start_comparison && $abo_alternate_date <= $end_comparison ) {
				$abo_alternates_comparison++;
			}
		}
	}

	if ( $abos > 0 ) :
		?>
		<h2><?php _e( 'Number of subscriptions', 'course-booking-system' ); ?></h2>
		<?php
		echo '<p><strong>'.( $abos - $abo_alternates ).'</strong> '.__( 'Subscriptions', 'course-booking-system' ).' '.__( 'in the period', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end ) ).' ('.$abos.' '.__( 'Subscriptions', 'course-booking-system' ).', '.$abo_alternates.' '.__( 'Unsubscriptions', 'course-booking-system' ).').</p>';
		echo '<p><strong>'.( $abos_comparison - $abo_alternates_comparison ).'</strong> '.__( 'Subscriptions', 'course-booking-system' ).' '.__( 'in the period', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $start_comparison ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end_comparison ) ).' ('.$abos_comparison.' '.__( 'Subscriptions', 'course-booking-system' ).', '.$abo_alternates_comparison.' '.__( 'Unsubscriptions', 'course-booking-system' ).').</p>';
	endif;
	?>

	<h2><?php _e( 'Number of courses per trainer', 'course-booking-system' ); ?></h2>
	<p class="statistics-note">'<?php _e( 'Events on specific dates are not included.', 'course-booking-system' ); ?></p>
	<table class="form-table" role="presentation">
		<tbody>
			<?php
			$user_info = '';
			$users = get_users( [ 'role__in' => [ 'administrator', 'editor', 'author', 'contributor' ] ] );
			foreach ( $users AS $user ) {
				$hours = $hours_comparison = 0;
				$list = $list_comparison = '<ul>';
				$user_id = $user->ID;

				$courses = cbs_get_courses( array(
					'user_id' => $user_id
				) );
				foreach ( $courses as $course ) {
					$hours++;
					$hours_comparison++;

					// Substitutes to remove
					$substitutes = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."cbs_substitutes WHERE course_id = $course->id AND user_id <> $user_id AND date >= '$start' AND date <= '$end'" );
					$hours = $hours - count( $substitutes );

					if ( empty( $substitutes ) ) {
						$list .= '<li>'.cbs_get_weekday( $course->day ).', '.get_the_title( $course->post_id ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</li>';
					} else {
						$list .= '<li><del>';
						$list .= cbs_get_weekday( $course->day ).', '.get_the_title( $course->post_id ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) );
						foreach ( $substitutes as $substitute ) {
							if ( $substitute->$user_id = 99999 ) {
								$list .= ' ('.__( 'Course is cancelled', 'course-booking-system' ).')';
							} else {
								$list .= ' ('.__( 'Substitute', 'course-booking-system' ).': ';
							}
						}
						$list .= '</del></li>';
					}

					$substitutes_comparison = $wpdb->get_results( "SELECT user_id FROM ".$wpdb->prefix."cbs_substitutes WHERE course_id = $course->id AND user_id <> $user_id AND date >= '$start_comparison' AND date <= '$end_comparison'" );
					$hours_comparison = $hours_comparison - count( $substitutes_comparison );

					if ( empty( $substitutes_comparison ) ) {
						$list_comparison .= '<li>'.cbs_get_weekday( $course->day ).', '.get_the_title( $course->post_id ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</li>';
					} else {
						$list_comparison .= '<li><del>';
						$list_comparison .= cbs_get_weekday( $course->day ).', '.get_the_title( $course->post_id ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) );
						foreach ( $substitutes_comparison as $substitute ) {
							if ( $substitute->$user_id = 99999 ) {
								$list_comparison .= ' ('.__( 'Course is cancelled', 'course-booking-system' ).')';
							} else {
								$list_comparison .= ' ('.__( 'Substitute', 'course-booking-system' ).': ';
							}
						}
						$list_comparison .= '</del></li>';
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
						$list .= '<li>'.__( 'Substitute', 'course-booking-system' ).': '.cbs_get_weekday( $course->day ).', '.get_the_title( $course->post_id ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</li>';
					}
				}

				$substitutes_comparison = $wpdb->get_results( "SELECT course_id FROM ".$wpdb->prefix."cbs_substitutes WHERE user_id = $user_id AND date >= '$start_comparison' AND date <= '$end_comparison'" );
				$hours_comparison = $hours_comparison + count( $substitutes_comparison );

				foreach ( $substitutes_comparison as $substitute ) {
					$courses = cbs_get_courses( array(
						'id' => $substitute->course_id
					) );
					foreach ( $courses as $course ) {
						$list_comparison .= '<li>'.__( 'Substitute', 'course-booking-system' ).': '.cbs_get_weekday( $course->day ).', '.get_the_title( $course->post_id ).', '.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).'</li>';
					}
				}

				$list .= '</ul>';
				$list_comparison .= '</ul>';

				if ( $hours > 0 || $hours_comparison > 0 ) {
					$user_info = get_userdata( $user_id );
					if ( $user_id == 99999 ) {
						$display_name = __( 'Course is cancelled', 'course-booking-system' );
					} else {
						$display_name = esc_html( $user_info->display_name );
					}
					?>

					<tr>
						<th>
							<h3><?= $display_name ?></h3>
						</th>
						<td>
							<h3><?= date_i18n( $date_format, strtotime( $start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end ) ) ?></h3>
							<h4><?= $hours.' '.__( 'hours', 'course-booking-system' ).' ('.( $hours - count( $substitutes ) ).' '.__( 'regular hours', 'course-booking-system' ).', '.count( $substitutes ).' '.__( 'Substitutes', 'course-booking-system' ).')' ?></h4>
							<?= $list ?>
						</td>
						<td>
							<h3><?= date_i18n( $date_format, strtotime( $start_comparison ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end_comparison ) ) ?></h3>
							<h4><?= $hours.' '.__( 'hours', 'course-booking-system' ).' ('.( $hours - count( $substitutes ) ).' '.__( 'regular hours', 'course-booking-system' ).', '.count( $substitutes ).' '.__( 'Substitutes', 'course-booking-system' ).')' ?></h4>
							<?= $list_comparison ?>
						</td>
					</tr>

					<?php
				}
			}
			?>
		</tbody>
	</table>

	<h2 class="statistics-orders-headline"><?php _e( 'Orders placed by employees', 'course-booking-system' ); ?></h2>
	<table class="form-table" role="presentation">
		<tbody>
			<?php
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

				$orders_comparison = get_posts( array(
					'numberposts' => -1,
					'post_type'   => 'shop_order',
					'post_status' => 'any',
					'author'      => $user->ID,
					'date_query'  => array(
						array(
							'after'     => $start_comparison,
							'before'    => $end_comparison,
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
					$count++;
					?>
					<tr>
						<th>
							<h3><?= $display_name ?></h3>
						</th>
						<td>
							<h3><?= date_i18n( $date_format, strtotime( $start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end ) ) ?></h3>
							<h4><?= sprintf( _n( '%s Order', '%s Orders', count( $orders ), 'course-booking-system' ), number_format_i18n( count( $orders ) ) ) ?></h4>
							<ul class="statistics-orders-content">
								<?php
								foreach ( $orders AS $order ) {
									$order_details = new WC_Order( $order->ID );
									echo '<li><a href="'.admin_url( 'post.php?post='.absint( $order->ID ) ).'&action=edit">#'.$order->ID.'</a>: '.$order_details->get_billing_first_name().' '.$order_details->get_billing_last_name().'</li>';
								}
								?>
							</ul>
						</td>
						<td>
							<h3><?= date_i18n( $date_format, strtotime( $start_comparison ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $end_comparison ) ) ?></h3>
							<h4><?= sprintf( _n( '%s Order', '%s Orders', count( $orders_comparison ), 'course-booking-system' ), number_format_i18n( count( $orders_comparison ) ) ) ?></h4>
							<ul class="statistics-orders-content">
								<?php
								foreach ( $orders_comparison AS $order ) {
									$order_details = new WC_Order( $order->ID );
									echo '<li><a href="'.admin_url( 'post.php?post='.absint( $order->ID ) ).'&action=edit">#'.$order->ID.'</a>: '.$order_details->get_billing_first_name().' '.$order_details->get_billing_last_name().'</li>';
								}
								?>
							</ul>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>

	<?php
	if ( $count == 0 ) {
		echo '<p>'.__( 'During this period no orders have been placed by employees.', 'course-booking-system' ).'</p>';
	}
}
add_action( 'cbs_settings_content', 'cbs_statistics_render_options_page' );


function cbs_import_tab() {
	global $cbs_active_tab; ?>
	<a class="nav-tab <?= $cbs_active_tab == 'import' ? 'nav-tab-active' : '' ?>" href="<?= admin_url( 'options-general.php?page=course_booking_system&tab=import' ) ?>"><?php _e( 'Import', 'course-booking-system' ); ?></a>
	<?php
}
add_action( 'cbs_settings_tab', 'cbs_import_tab' );

function cbs_import_render_options_page() {
	global $cbs_active_tab;
	if ( 'import' != $cbs_active_tab )
		return;

	global $wpdb;
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_FILES ) ) {
		$count = 0;
		$skipped = 0;
		$handle = fopen( $_FILES['course_booking_system_import']['tmp_name'], 'r' );
		$headers = fgetcsv( $handle, 1000, ',' );
		while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== FALSE ) {
			if ( is_numeric( $data[0] ) ) {
				$course_id = $data[0];
			} else {
				$post = get_page_by_title( $data[0], OBJECT, 'mp-event' );
				$course_id = $post->ID;
			}
			$date = $data[1];
			$user_id = $data[2];

			if ( $_POST['course_booking_system_import_usernames'] ) {
				$user = get_user_by( 'login', $user_id );
				if ( $user ) {
					$user_id = $user->ID;
				}
			}

			if ( empty( $date ) ) {
				$date = date( 'Y-m-d' );
				$entries = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course_id AND user_id = $user_id" );
			} else {
				$entries = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE course_id = $course_id AND date = '$date' AND user_id = $user_id" );
			}

			if ( !$_POST['course_booking_system_duplicate_entries'] || !count( $entries ) ) {
				$wpdb->insert(
					$wpdb->prefix.'cbs_bookings',
					array(
						'course_id' => $course_id,
						'date' => $date,
						'user_id' => $user_id
					), array(
						'%d',
						'%s',
						'%d'
					)
				);
			} else {
				$count--;
				$skipped++;
			}

			if ( $wpdb->last_error !== '' ) {
				$wpdb->print_error();
			} else {
				$count++;
			}
		}
		fclose( $handle );
		?>

		<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
			<p><strong><?php printf( __( 'Import completed successfully. %1s records were imported. %2s records were skipped.', 'course-booking-system' ), $count, $skipped ); ?></strong></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.' ); ?></span></button>
		</div>

	<?php } ?>

	<form method="post" action="<?= admin_url( 'options-general.php?page=course_booking_system&tab=import' ) ?>" accept-charset="utf-8" enctype='multipart/form-data'>
		<?php settings_fields( 'course_booking_system_options_group_email' ); ?>

		<h2><?php _e( 'Import', 'course-booking-system' ); ?></h2>
		<p><?php printf( __( 'Import bookings by uploading a CSV file. Please note our <a href="%s" target="_blank">instructions for importing bookings</a>.', 'course-booking-system' ), 'https://commotion.online/wordpress-blog/csv-liste-buchungen-importieren/' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th><label for="course_booking_system_import"><?php _e( 'Import file', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="file" name="course_booking_system_import" id="course_booking_system_import" class="regular-text">
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_import_usernames"><?php _e( 'Import usernames', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_import_usernames" id="course_booking_system_import_usernames" value="1" /><?php _e( 'Import usernames instead of user IDs', 'course-booking-system' ); ?><br />
						<p class="description"><?php _e( 'If this checkmark is set, the system automatically replaces the user name with the associated one.', 'course-booking-system' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="course_booking_system_duplicate_entries"><?php _e( 'Duplicate entries', 'course-booking-system' ); ?></label></th>
					<td>
						<input type="checkbox" name="course_booking_system_duplicate_entries" id="course_booking_system_duplicate_entries" value="1" checked="checked" /><?php _e( 'Skip duplicate entries', 'course-booking-system' ); ?><br />
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
	<?php
}
add_action( 'cbs_settings_content', 'cbs_import_render_options_page' );
