<?php
// Additional course options
function cbs_admin_init() {
	add_meta_box( 'course-meta', __( 'Course Options', 'course-booking-system' ), 'cbs_meta_options', 'course', 'normal', 'high' );
	add_meta_box( 'course-data', __( 'Timeslots', 'course-booking-system' ), 'cbs_data_options', 'course', 'normal', 'high' );
}
add_action( 'admin_init', 'cbs_admin_init' );

function cbs_meta_options() {
	global $post;
	$custom = get_post_custom( $post->ID );

	$attendance = 0;
	if ( !empty( $custom['attendance'] ) )
		$attendance = $custom['attendance'][0];

	$free = '';
	if ( !empty( $custom['free'] ) )
		$free = $custom['free'][0];

	$price_level = 1;
	if ( !empty( $custom['price_level'] ) )
		$price_level = $custom['price_level'][0];

	$invitation_link = '';
	if ( !empty( $custom['invitation_link'] ) )
		$invitation_link = $custom['invitation_link'][0];

	$invitation_link_password = '';
	if ( !empty( $custom['invitation_link_password'] ) )
		$invitation_link_password = $custom['invitation_link_password'][0];

	$pattern = array( 'rgba', 'rgb', 'RGBA', 'RGB', '(', ')' );
	if ( !empty( $custom['color'] ) ) :
		$color = $custom['color'][0];

		// RGB to HEX
		if ( !str_contains( $color, '#' ) && !count( array_intersect( explode( ' ', $color ), $pattern ) ) ) :
			$color = str_replace( $pattern, '', $color );
			$rgb = explode( ',', $color, 3 );
			$color = sprintf( "#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2] );
		endif;
	else :
		$color = '#eeeeee';
	endif;

	if ( !empty( $custom['text_color'] ) ) :
		$text_color = $custom['text_color'][0];

		// RGB to HEX
		if ( !str_contains( $text_color, '#' ) && !count( array_intersect( explode( ' ', $text_color ), $pattern ) ) ) :
			$text_color = str_replace( $pattern, '', $text_color );
			$rgb = explode( ',', $text_color, 3 );
			$text_color = sprintf( "#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2] );
		endif;
	else :
		$text_color = '#000000';
	endif;

	if ( !empty( $custom['timetable_custom_url'] ) ) :
		$timetable_custom_url = $custom['timetable_custom_url'][0];
	else :
		$timetable_custom_url = '';
	endif;
	?>
	<table id="course-meta-table" class="widefat">
		<tbody>
			<tr>
				<td><label for="attendance"><?php _e( 'Number of participants', 'course-booking-system' ); ?>:</label></td>
				<td><input type="number" name="attendance" id="attendance" value="<?php esc_attr_e( $attendance ); ?>"></td>
			</tr>
			<tr>
				<td><label for="free"><?php _e( 'Free', 'woocommerce' ); ?>:</label></td>
				<td>
					<input type="checkbox" name="free" id="free" value="1" <?= ( $free ) ? 'checked="checked"' : '' ?>><?php _e( 'This course is free of charge', 'course-booking-system' ); ?>
					<p class="description"><?php _e( 'If a course is offered free of charge, the customer does not need to have a valid customer card or buy it in order to register for this course. A customer account is still required.', 'course-booking-system' ); ?></p>
				</td>
			</tr>
			<tr id="price_level_container" <?= ( $free ) ? 'style="display: none;"' : '' ?>>
				<td><label for="price_level"><?php _e( 'Price Level', 'course-booking-system' ); ?>:</label></td>
				<td>
					<input type="range" name="price_level" id="price_level" value="<?php esc_attr_e( $price_level ); ?>" min="1" max="5">
					<output id="output"><?= $price_level ?></output>
					<p class="description"><?php _e( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ); ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="invitation_link"><?php _e( 'Online course link', 'course-booking-system' ); ?>:</label></td>
				<td>
					<input type="url" name="invitation_link" id="invitation_link" value="<?php esc_attr_e( $invitation_link ); ?>" placeholder="https://">
					<p class="description"><?php _e( 'If the course takes place online, you can enter an invitation link for the online course here. This link will then be automatically sent to the registered users by email 15 minutes before the course starts. Leave this field empty if the course takes place on site.', 'course-booking-system' ); ?></p>

					<input type="text" name="invitation_link_password" id="invitation_link_password" value="<?php esc_attr_e( $invitation_link_password ); ?>">
					<p class="description"><?php _e( 'If a password is required to access the online course, enter it here.', 'course-booking-system' ); ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="color"><?php _e( 'Background Color', 'course-booking-system' ); ?>:</label></td>
				<td><input type="color" name="color" id="color" value="<?php esc_attr_e( $color ); ?>"></td>
			</tr>
			<tr>
				<td><label for="text_color"><?php _e( 'Text Color', 'course-booking-system' ); ?>:</label></td>
				<td><input type="color" name="text_color" id="text_color" value="<?php esc_attr_e( $text_color ); ?>"></td>
			</tr>
			<tr>
				<td><label for="timetable_custom_url"><?php _e( 'Timetable custom URL', 'course-booking-system' ); ?>:</label></td>
				<td><input type="url" name="timetable_custom_url" id="timetable_custom_url" value="<?php esc_attr_e( $timetable_custom_url ); ?>"></td>
			</tr>
		</tbody>
	</table>
	<?php
}

function cbs_data_options() {
	global $post;
	?>

	<div id="ajax-admin"><?= cbs_data_options_table( $post->ID ) ?></div>
	<div id="ajax-loader" class="loader"><div></div><div></div><div></div></div>

	<a href="#" class="add-timeslot">+ <?php _e( 'Add Timeslot', 'course-booking-system' ); ?></a>

	<?php
}

// Save
function cbs_save_postmeta( $post_id, $post, $update ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	global $wpdb;

	if ( isset( $_POST['attendance'] ) )
		update_post_meta( $post_id, 'attendance', sanitize_text_field( $_POST['attendance'] ) );

	if ( isset( $_POST['free'] ) ) :
		update_post_meta( $post_id, 'free', sanitize_text_field( $_POST['free'] ) );
	else :
		update_post_meta( $post_id, 'free', 0 );
	endif;

	if ( isset( $_POST['price_level'] ) )
		update_post_meta( $post_id, 'price_level', sanitize_text_field( $_POST['price_level'] ) );

	if ( isset( $_POST['invitation_link'] ) )
		update_post_meta( $post_id, 'invitation_link', sanitize_text_field( $_POST['invitation_link'] ) );

	if ( isset( $_POST['invitation_link_password'] ) )
		update_post_meta( $post_id, 'invitation_link_password', sanitize_text_field( $_POST['invitation_link_password'] ) );

	if ( isset( $_POST['color'] ) )
		update_post_meta( $post_id, 'color', sanitize_text_field( $_POST['color'] ) );

	if ( isset( $_POST['text_color'] ) )
		update_post_meta( $post_id, 'text_color', sanitize_text_field( $_POST['text_color'] ) );

	if ( isset( $_POST['timetable_custom_url'] ) )
		update_post_meta( $post_id, 'timetable_custom_url', sanitize_text_field( $_POST['timetable_custom_url'] ) );

	cbs_save_timeslots();
}
add_action( 'save_post_course', 'cbs_save_postmeta', 20, 3 );

function cbs_save_timeslots() {
	global $wpdb;

	if ( isset( $_POST['id'] ) ) :
		foreach ( $_POST['id'] AS $index => $id ) :
			$date = $type = NULL;
			if ( !empty( $_POST['date'][$index] ) ) :
				$date = sanitize_text_field( $_POST['date'][$index] );
				$type = '%s';
			endif;

			$wpdb->update(
				$wpdb->prefix.'cbs_data',
				array(
					'day' => intval( $_POST['day'][$index] ),
					'date' => $date,
					'start' => sanitize_text_field( $_POST['start'][$index] ),
					'end' => sanitize_text_field( $_POST['end'][$index] ),
					'user_id' => intval( $_POST['user_id'][$index] )
				),
				array( 'id' => $id ),
				array( '%d', $type, '%s', '%s', '%d' ),
				array( '%d' )
			);
		endforeach;
	endif;
}

// AJAX
function cbs_data_options_table( $post_id ) {
	ob_start();
	?>

	<table id="course-data-table" class="widefat">
		<thead>
			<th></th>
			<th><?php _e( 'Day', 'course-booking-system' ); ?></th>
			<th><?php _e( 'Date', 'course-booking-system' ); ?></th>
			<th><?php _e( 'Start', 'course-booking-system' ); ?></th>
			<th><?php _e( 'End', 'course-booking-system' ); ?></th>
			<th><?php _e( 'Trainer', 'course-booking-system' ); ?></th>
			<th><?php _e( 'Actions', 'course-booking-system' ); ?></th>
		</thead>
		<tbody>
			<?php
			$courses = cbs_get_courses( array(
				'post_id' => $post_id,
				'post_status' => 'any'
			) );
			if ( !empty( $courses ) ) :
				foreach ( $courses as $course ) :
					?>
					<tr>
						<td>
							#<?= $course->id ?>
							<input type="hidden" name="id[]" value="<?= $course->id ?>" class="id">
						</td>
						<td><select name="day[]">
							<option value="1" <?= $course->day == 1 ? 'selected="selected"' : '' ?>><?php _e( 'Monday', 'course-booking-system' ); ?></option>
							<option value="2" <?= $course->day == 2 ? 'selected="selected"' : '' ?>><?php _e( 'Tuesday', 'course-booking-system' ); ?></option>
							<option value="3" <?= $course->day == 3 ? 'selected="selected"' : '' ?>><?php _e( 'Wednesday', 'course-booking-system' ); ?></option>
							<option value="4" <?= $course->day == 4 ? 'selected="selected"' : '' ?>><?php _e( 'Thursday', 'course-booking-system' ); ?></option>
							<option value="5" <?= $course->day == 5 ? 'selected="selected"' : '' ?>><?php _e( 'Friday', 'course-booking-system' ); ?></option>
							<option value="6" <?= $course->day == 6 ? 'selected="selected"' : '' ?>><?php _e( 'Saturday', 'course-booking-system' ); ?></option>
							<option value="7" <?= $course->day == 7 ? 'selected="selected"' : '' ?>><?php _e( 'Sunday', 'course-booking-system' ); ?></option>
							<option value="99" <?= $course->day == 99 ? 'selected="selected"' : '' ?>><?php _e( 'Custom date', 'course-booking-system' ); ?></option>
						</select></td>
						<td><input type="date" name="date[]" value="<?php esc_attr_e( $course->date ); ?>"></td>
						<td><input type="time" name="start[]" value="<?php esc_attr_e( $course->start ); ?>" required></td>
						<td><input type="time" name="end[]" value="<?php esc_attr_e( $course->end ); ?>" required></td>
						<td>
							<?php
							wp_dropdown_users( array(
								'orderby'      => 'display_name',
								'order'        => 'ASC',
								'name'         => 'user_id[]',
								'selected'     => $course->user_id,
								'role__not_in' => array( 'customer', 'subscriber' )
							) );
							?>
						</td>
						<td>
							<a href="#" class="delete-timeslot dashicons-before dashicons-trash"><?php _e( 'Delete', 'course-booking-system' ); ?></a>
						</td>
					</tr>
					<?php
				endforeach;
			endif;
			?>
		</tbody>
	</table>

	<?php
	return ob_get_clean();
}

function cbs_add_timetable( $post_id = 0 ) {
	global $wpdb;
	cbs_save_timeslots();

	$post_id = intval( $_REQUEST['post_id'] );
	$wpdb->insert(
		$wpdb->prefix.'cbs_data',
		array( 'post_id' => $post_id ),
		array( '%d' )
	);

	echo cbs_data_options_table( $post_id );

	wp_die();
}
add_action( 'wp_ajax_cbs_add_timetable', 'cbs_add_timetable' );
add_action( 'wp_ajax_nopriv_cbs_add_timetable', 'cbs_add_timetable' );

function cbs_delete_timetable() {
	global $wpdb;
	cbs_save_timeslots();

	$delete_id = intval( $_REQUEST['delete_id'] );
	$post_id = intval( $_REQUEST['post_id'] );

	if ( !empty( $delete_id ) && !empty( $post_id ) ) :
		$wpdb->delete(
			$wpdb->prefix.'cbs_data',
			array( 'id' => $delete_id, 'post_id' => $post_id ),
			array( '%d', '%d' )
		);
	endif;

	echo cbs_data_options_table( $post_id );

	wp_die();
}
add_action( 'wp_ajax_cbs_delete_timetable', 'cbs_delete_timetable' );
add_action( 'wp_ajax_nopriv_cbs_delete_timetable', 'cbs_delete_timetable' );
