<?php
// Allow contributors to add and edit users
function add_contributor_cap() {
	$role = get_role( 'contributor' );
	$role->add_cap( 'view_admin_dashboard' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'list_users' );
	$role->add_cap( 'promote_users' );
	$role->add_cap( 'create_users' );
	$role->add_cap( 'add_users' );

	$role->add_cap( 'create_shop_orders' );
	$role->add_cap( 'edit_shop_order' );
	$role->add_cap( 'edit_shop_order_terms' );
	$role->add_cap( 'edit_shop_orders' );
	$role->add_cap( 'edit_others_shop_orders' );
	$role->add_cap( 'manage_shop_order_terms' );
	$role->add_cap( 'publish_shop_orders' );
	$role->add_cap( 'read_shop_order' );
}
add_action( 'admin_init', 'add_contributor_cap' );

// Add custom fields to user admin panel
function cbs_add_profile_fields( $user ) {
	global $wpdb;

	$price_level_title   = get_option( 'course_booking_system_price_level_title' );
	$price_level_title_2 = get_option( 'course_booking_system_price_level_title_2' );
	$price_level_title_3 = get_option( 'course_booking_system_price_level_title_3' );
	$price_level_title_4 = get_option( 'course_booking_system_price_level_title_4' );
	$price_level_title_5 = get_option( 'course_booking_system_price_level_title_5' );
	?>

	<h2 class="subscriptions-headline"><?php _e( 'Subscriptions', 'course-booking-system' ); ?></h2>
	<table class="form-table subscriptions-table">
		<tr>
			<th><label for="abo"><?php _e( 'Subscription (automatic renewal)', 'course-booking-system' ); ?></label></th>
			<td>
				<input type="checkbox" name="abo" id="abo" value="1" <?= get_user_meta( $user->ID, 'abo', true ) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
			</td>
		</tr>
		<tr>
			<th><label for="abo_2"><?php _e( '2. Subscription (automatic renewal)', 'course-booking-system' ); ?></label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="checkbox" name="abo_2" id="abo_2" value="1" <?= get_user_meta( $user->ID, 'abo_2', true ) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
				<?php else : ?>
					<input type="checkbox" name="abo_2" id="abo_2" value="1" <?= get_user_meta( $user->ID, 'abo_2', true ) ? 'checked="checked"' : '' ?> disabled /><?php _e( 'Yes', 'woocommerce' ); ?> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?><br />
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="abo_3"><?php _e( '3. Subscription (automatic renewal)', 'course-booking-system' ); ?></label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="checkbox" name="abo_3" id="abo_3" value="1" <?= get_user_meta( $user->ID, 'abo_3', true ) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
				<?php else : ?>
					<input type="checkbox" name="abo_3" id="abo_3" value="1" <?= get_user_meta( $user->ID, 'abo_3', true ) ? 'checked="checked"' : '' ?> disabled /><?php _e( 'Yes', 'woocommerce' ); ?> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?><br />
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="abo_start"><?php _e( 'Subscription(s) valid from', 'course-booking-system' ); ?></label></th>
			<td>
				<input type="date" name="abo_start" id="abo_start" value="<?php esc_attr_e( get_the_author_meta( 'abo_start', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<tr>
			<th><label for="abo_expire"><?php _e( 'Subscription(s) valid until', 'course-booking-system' ); ?></label></th>
			<td>
				<input type="date" name="abo_expire" id="abo_expire" value="<?php esc_attr_e( get_the_author_meta( 'abo_expire', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<?php
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		$courses = cbs_get_courses();
		?>
		<tr id="tr-abo_course" <?= ( get_user_meta( $user->ID, 'abo', true ) || get_the_author_meta( 'abo_expire', $user->ID ) > date( 'Y-m-d' ) ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="abo_course"><?php _e( 'Subscription course', 'course-booking-system' ); ?></label></th>
			<td>
				<select name="abo_course" id="abo_course">
					<option value=""><?php _e( 'Choose a course', 'course-booking-system' ); ?></option>
					<?php foreach ( $courses as $course ) { ?>
						<?php $user_info = get_userdata( $course->user_id ); ?>
						<option value="<?= $course->id ?>" <?= get_user_meta( $user->ID, 'abo_course', true ) == $course->id ? 'selected="selected"' : '' ?>><?= $course->post_title ?>, <?= cbs_get_weekday( $course->day ) ?>, <?= date( $time_format, strtotime( $course->start ) ) ?> - <?= date( $time_format, strtotime( $course->end ) ) ?> <?php _e( 'o\'clock', 'course-booking-system' ); ?>, <?= $user_info->display_name ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr id="tr-abo_course_2" <?= get_user_meta( $user->ID, 'abo_2', true ) || ( !empty( get_user_meta($user->ID, 'abo_course_2', true) ) && get_the_author_meta( 'abo_expire', $user->ID ) ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="abo_course_2">2. <?php _e( 'Subscription course', 'course-booking-system' ); ?></label></th>
			<td>
				<select name="abo_course_2" id="abo_course_2">
					<option value=""><?php _e( 'Choose a course', 'course-booking-system' ); ?></option>
					<?php foreach ( $courses as $course ) { ?>
						<option value="<?= $course->id ?>" <?= (get_user_meta($user->ID, 'abo_course_2', true) == $course->id) ? 'selected="selected"' : '' ?>><?= $course->post_title ?>, <?= cbs_get_weekday( $course->day ) ?>, <?= date( $time_format, strtotime( $course->start ) ) ?> - <?= date( $time_format, strtotime( $course->end ) ) ?> <?php _e( 'o\'clock', 'course-booking-system' ); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr id="tr-abo_course_3" <?= get_user_meta( $user->ID, 'abo_3', true ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="abo_course_3">3. <?php _e( 'Subscription course', 'course-booking-system' ); ?></label></th>
			<td>
				<select name="abo_course_3" id="abo_course_3">
					<option value=""><?php _e( 'Choose a course', 'course-booking-system' ); ?></option>
					<?php foreach ( $courses as $course ) { ?>
						<option value="<?= $course->id ?>" <?= (get_user_meta($user->ID, 'abo_course_3', true) == $course->id) ? 'selected="selected"' : '' ?>><?= $course->post_title ?>, <?= cbs_get_weekday( $course->day ) ?>, <?= date( $time_format, strtotime( $course->start ) ) ?> - <?= date( $time_format, strtotime( $course->end ) ) ?> <?php _e( 'o\'clock', 'course-booking-system' ); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="abo_alternate"><?php _e( 'Unsubscribe subscription', 'course-booking-system' ); ?></label></th>
			<td>
				<input type="text" name="abo_alternate" id="abo_alternate" value="<?php esc_attr_e( get_the_author_meta( 'abo_alternate', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>

	<h2 class="flatrates-headline"><?php _e( 'Flatrates', 'course-booking-system' ); ?></h2>
	<table class="form-table flatrates-table">
		<tr>
			<th><label for="flat"><?php _e( 'Flatrate', 'course-booking-system' ); ?> (<?= $price_level_title ?>)</label></th>
			<td>
				<input type="checkbox" name="flat" id="flat" value="1" <?= get_user_meta($user->ID, 'flat', true) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
			</td>
		</tr>
		<tr id="tr-flat_expire" <?= get_user_meta( $user->ID, 'flat', true ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="flat_expire"><?php _e( 'Flatrate valid until', 'course-booking-system' ); ?> (<?= $price_level_title ?>)</label></th>
			<td>
				<input type="date" name="flat_expire" id="flat_expire" value="<?php esc_attr_e( get_the_author_meta( 'flat_expire', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<tr>
			<th><label for="flat_2">2. <?php _e( 'Flatrate', 'course-booking-system' ); ?> (<?= $price_level_title_2 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="checkbox" name="flat_2" id="flat_2" value="1" <?= get_user_meta($user->ID, 'flat_2', true) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
				<?php else : ?>
					<input type="checkbox" name="flat_2" id="flat_2" value="1" <?= get_user_meta($user->ID, 'flat_2', true) ? 'checked="checked"' : '' ?> disabled /><?php _e( 'Yes', 'woocommerce' ); ?> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?><br />
				<?php endif; ?>
			</td>
		</tr>
		<tr id="tr-flat_expire_2" <?= get_user_meta( $user->ID, 'flat_2', true ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="flat_expire_2">2. <?php _e( 'Flatrate valid until', 'course-booking-system' ); ?> (<?= $price_level_title_2 ?>)</label></th>
			<td>
				<input type="date" name="flat_expire_2" id="flat_expire_2" value="<?php esc_attr_e( get_the_author_meta( 'flat_expire_2', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<tr>
			<th><label for="flat_3">3. <?php _e( 'Flatrate', 'course-booking-system' ); ?> (<?= $price_level_title_3 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="checkbox" name="flat_3" id="flat_3" value="1" <?= get_user_meta($user->ID, 'flat_3', true) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
				<?php else : ?>
					<input type="checkbox" name="flat_3" id="flat_3" value="1" <?= get_user_meta($user->ID, 'flat_3', true) ? 'checked="checked"' : '' ?> disabled /><?php _e( 'Yes', 'woocommerce' ); ?> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?><br />
				<?php endif; ?>
			</td>
		</tr>
		<tr id="tr-flat_expire_3" <?= get_user_meta( $user->ID, 'flat_3', true ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="flat_expire_3">3. <?php _e( 'Flatrate valid until', 'course-booking-system' ); ?> (<?= $price_level_title_3 ?>)</label></th>
			<td>
				<input type="date" name="flat_expire_3" id="flat_expire_3" value="<?php esc_attr_e( get_the_author_meta( 'flat_expire_3', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<tr>
			<th><label for="flat_4">4. <?php _e( 'Flatrate', 'course-booking-system' ); ?> (<?= $price_level_title_4 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="checkbox" name="flat_4" id="flat_4" value="1" <?= get_user_meta($user->ID, 'flat_4', true) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
				<?php else : ?>
					<input type="checkbox" name="flat_4" id="flat_4" value="1" <?= get_user_meta($user->ID, 'flat_4', true) ? 'checked="checked"' : '' ?> disabled /><?php _e( 'Yes', 'woocommerce' ); ?> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?><br />
				<?php endif; ?>
			</td>
		</tr>
		<tr id="tr-flat_expire_4" <?= get_user_meta( $user->ID, 'flat_4', true ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="flat_expire_4">4. <?php _e( 'Flatrate valid until', 'course-booking-system' ); ?> (<?= $price_level_title_4 ?>)</label></th>
			<td>
				<input type="date" name="flat_expire_4" id="flat_expire_4" value="<?php esc_attr_e( get_the_author_meta( 'flat_expire_4', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<tr>
			<th><label for="flat_5">5. <?php _e( 'Flatrate', 'course-booking-system' ); ?> (<?= $price_level_title_5 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="checkbox" name="flat_5" id="flat_5" value="1" <?= get_user_meta($user->ID, 'flat_5', true) ? 'checked="checked"' : '' ?> /><?php _e( 'Yes', 'woocommerce' ); ?><br />
				<?php else : ?>
					<input type="checkbox" name="flat_5" id="flat_5" value="1" <?= get_user_meta($user->ID, 'flat_5', true) ? 'checked="checked"' : '' ?> disabled /><?php _e( 'Yes', 'woocommerce' ); ?> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?><br />
				<?php endif; ?>
			</td>
		</tr>
		<tr id="tr-flat_expire_5" <?= get_user_meta( $user->ID, 'flat_5', true ) ? 'style="display: table-row;"' : 'style="display: none;"' ?>>
			<th><label for="flat_expire_5">3. <?php _e( 'Flatrate valid until', 'course-booking-system' ); ?> (<?= $price_level_title_5 ?>)</label></th>
			<td>
				<input type="date" name="flat_expire_5" id="flat_expire_5" value="<?php esc_attr_e( get_the_author_meta( 'flat_expire_5', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
	</table>

	<h2 class="cards-headline"><?php _e( 'Cards', 'course-booking-system' ); ?></h2>
	<table class="form-table cards-table">
		<tr>
			<th><label for="card"><?php _e( 'Card', 'course-booking-system' ); ?> (<?= $price_level_title ?>)</label></th>
			<td>
				<input type="number" name="card" id="card" value="<?php esc_attr_e( get_the_author_meta( 'card', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="expire"><?php _e( 'Card valid until', 'course-booking-system' ); ?> (<?= $price_level_title ?>)</label></th>
			<td>
				<input type="date" name="expire" id="expire" value="<?php esc_attr_e( get_the_author_meta( 'expire', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
		<tr>
			<th><label for="card_2">2. <?php _e( 'Card', 'course-booking-system' ); ?> (<?= $price_level_title_2 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="number" name="card_2" id="card_2" value="<?php esc_attr_e( get_the_author_meta( 'card_2', $user->ID ) ); ?>" class="regular-text" />
				<?php else : ?>
					<input type="number" name="card_2" id="card_2" value="<?php esc_attr_e( get_the_author_meta( 'card_2', $user->ID ) ); ?>" class="regular-text" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="expire_2">2. <?php _e( 'Card valid until', 'course-booking-system' ); ?> (<?= $price_level_title_2 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="date" name="expire_2" id="expire_2" value="<?php esc_attr_e( get_the_author_meta( 'expire_2', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
				<?php else : ?>
					<input type="date" name="expire_2" id="expire_2" value="<?php esc_attr_e( get_the_author_meta( 'expire_2', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="card_3">3. <?php _e( 'Card', 'course-booking-system' ); ?> (<?= $price_level_title_3 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="number" name="card_3" id="card_3" value="<?php esc_attr_e( get_the_author_meta( 'card_3', $user->ID ) ); ?>" class="regular-text" />
				<?php else : ?>
					<input type="number" name="card_3" id="card_3" value="<?php esc_attr_e( get_the_author_meta( 'card_3', $user->ID ) ); ?>" class="regular-text" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="expire_3">3. <?php _e( 'Card valid until', 'course-booking-system' ); ?> (<?= $price_level_title_3 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="date" name="expire_3" id="expire_3" value="<?php esc_attr_e( get_the_author_meta( 'expire_3', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
				<?php else : ?>
					<input type="date" name="expire_3" id="expire_3" value="<?php esc_attr_e( get_the_author_meta( 'expire_3', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="card_4">4. <?php _e( 'Card', 'course-booking-system' ); ?> (<?= $price_level_title_4 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="number" name="card_4" id="card_4" value="<?php esc_attr_e( get_the_author_meta( 'card_4', $user->ID ) ); ?>" class="regular-text" />
				<?php else : ?>
					<input type="number" name="card_4" id="card_4" value="<?php esc_attr_e( get_the_author_meta( 'card_4', $user->ID ) ); ?>" class="regular-text" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="expire_4">4. <?php _e( 'Card valid until', 'course-booking-system' ); ?> (<?= $price_level_title_4 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="date" name="expire_4" id="expire_4" value="<?php esc_attr_e( get_the_author_meta( 'expire_4', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
				<?php else : ?>
					<input type="date" name="expire_4" id="expire_4" value="<?php esc_attr_e( get_the_author_meta( 'expire_4', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="card_5">5. <?php _e( 'Card', 'course-booking-system' ); ?> (<?= $price_level_title_5 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="number" name="card_5" id="card_5" value="<?php esc_attr_e( get_the_author_meta( 'card_5', $user->ID ) ); ?>" class="regular-text" />
				<?php else : ?>
					<input type="number" name="card_5" id="card_5" value="<?php esc_attr_e( get_the_author_meta( 'card_5', $user->ID ) ); ?>" class="regular-text" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="expire_5">5. <?php _e( 'Card valid until', 'course-booking-system' ); ?> (<?= $price_level_title_5 ?>)</label></th>
			<td>
				<?php if ( cbs_is_licensed() ) : ?>
					<input type="date" name="expire_5" id="expire_5" value="<?php esc_attr_e( get_the_author_meta( 'expire_5', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
				<?php else : ?>
					<input type="date" name="expire_5" id="expire_5" value="<?php esc_attr_e( get_the_author_meta( 'expire_5', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" disabled /> <?php printf( __( '<a href="%s" target="_blank">Pro Feature</a>', 'course-booking-system' ), 'https://commotion.online/en/shop/course-booking-system-pro-license/' ); ?>
				<?php endif; ?>
			</td>
		</tr>
	</table>

	<h2 class="others-headline"><?php _e( 'Others', 'course-booking-system' ); ?></h2>
	<table class="form-table others-table">
		<tr>
			<th><label for="birthday"><?php _e( 'Birthday', 'course-booking-system' ); ?></label></th>
			<td>
				<input type="date" name="birthday" id="birthday" value="<?php esc_attr_e( get_the_author_meta( 'birthday', $user->ID ) ); ?>" class="regular-text" placeholder="YYYY-MM-DD" />
			</td>
		</tr>
	</table>

	<div id="ajax"></div>
	<div id="ajax-loader" class="loader"><div></div><div></div><div></div></div>

	<?php
	$bookings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_bookings WHERE user_id = $user->ID AND date >= CURDATE() ORDER BY date" );
	if ( count( $bookings ) > 0 ) {
		?>
		<h2 class="bookings-headline"><?php _e( 'Bookings', 'course-booking-system' ); ?></h2>
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
					$course_id = $booking->course_id;
					$date = $booking->date;
					$user_id = $booking->user_id;

					$courses = $wpdb->get_results( "SELECT post_title, day, post_id, start, end FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id WHERE ".$wpdb->prefix."cbs_data.id = $course_id LIMIT 1" );
					foreach ( $courses as $course ) {
						if ( empty( cbs_get_weekday( $course->day ) ) ) {
							$args = array(
								'p'               => $course->day,
								'post_type'       => 'mp-column',
								'posts_per_page'  => 1,
								'meta_query'      => array(
									array(
										'key'     => 'column_option',
										'value'   => 'date',
										'compare' => '='
									)
								)
							);

							$query = new WP_Query( $args );

							foreach ( $query->posts as $post ) {
								$weekday = $post->post_title;
							}
						} else {
							$weekday = cbs_get_weekday( $course->day );
						}
						echo '<tr id="booking-id-'.$booking_id.'"><td><a href="'.get_edit_post_link( $course->post_id ).'">'.$course->post_title.'</a></td><td>'.$weekday.', '.date_i18n( $date_format, strtotime( $date ) ).'</td><td>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td><a href="#" class="button btn btn-primary et_pb_button action-booking-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_id.'" data-booking="'.$booking_id.'">×</a></td></tr>';
					}
				}
				?>
			</tbody>
		</table>
	<?php
	}

	$bookings_past = get_option( 'course_booking_system_bookings_past' );
	$bookings = $wpdb->get_results( "SELECT booking_id, course_id, date FROM ".$wpdb->prefix."cbs_bookings WHERE user_id = $user->ID AND date < CURDATE() ORDER by date DESC LIMIT $bookings_past" );
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

					$courses = $wpdb->get_results( "SELECT post_title, day, post_id, start, end FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id WHERE ".$wpdb->prefix."cbs_data.id = $course_id LIMIT 1" );
					foreach ( $courses as $course ) {
						echo '<tr><td><a href="'.get_edit_post_link( $course->post_id ).'">'.$course->post_title.'</a></td><td>'.cbs_get_weekday( $course->day ).', '.date_i18n( $date_format, strtotime( $date ) ).'</td><td>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td></tr>';
					}
				}
				?>
			</tbody>
		</table>
		<?php
	}

	$waitlists = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_waitlists WHERE user_id = $user->ID AND date >= CURDATE()" );
	if ( count( $waitlists ) > 0 ) {
		?>
		<h2 class="waitlists-headline"><?php _e( 'Waiting list', 'course-booking-system' ); ?></h2>
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
					$user_id = $waitlist->user_id;

					$courses = $wpdb->get_results( "SELECT post_title, day, post_id, start, end FROM ".$wpdb->prefix."posts JOIN ".$wpdb->prefix."cbs_data ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."cbs_data.post_id WHERE ".$wpdb->prefix."cbs_data.id = $course_id LIMIT 1" );
					foreach ( $courses as $course ) {
						echo '<tr id="waitlist-id-'.$waitlist_id.'"><td><a href="'.get_edit_post_link( $course->post_id ).'">'.$course->post_title.'</a></td><td>'.cbs_get_weekday( $course->day ).', '.date_i18n( $date_format, strtotime( $date ) ).'</td><td>'.date( $time_format, strtotime( $course->start ) ).' - '.date( $time_format, strtotime( $course->end ) ).' '.__( 'o\'clock', 'course-booking-system' ).'</td><td><a href="#" class="button btn btn-primary et_pb_button action-waitlist-delete" title="'.__( 'Reverse', 'course-booking-system' ).'" data-id="'.$course_id.'" data-date="'.$date.'" data-user="'.$user_id.'" data-waitlist="'.$waitlist_id.'">×</a></td></tr>';
					}
				}
				?>
			</tbody>
		</table>
		<?php
	}
	?>

	<p><a href="#" id="cbs-logs"><?php _e( 'Show logs', 'course-booking-system' ); ?></a></p>

	<?php
	$logs = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."cbs_logs WHERE user_id = $user->ID ORDER BY log_id DESC LIMIT 999" );
	if ( count( $logs ) > 0 ) {
		?>
		<h2 class="logs-headline"><?php _e( 'Logs', 'course-booking-system' ); ?></h2>
		<table class="logs-table">
			<tbody>
				<tr>
					<th><?php _e( 'Log ID', 'course-booking-system' ); ?></th>
					<th><?php _e( 'User ID', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Card name', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Card', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Course ID', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Action', 'course-booking-system' ); ?></th>
					<th><?php _e( 'Timestamp', 'course-booking-system' ); ?></th>
					<th></th>
				</tr>
				<?php
				foreach ( $logs as $log ) {
					$log_id = $log->log_id;
					$user_id = $log->user_id;
					$card_name = $log->card_name;
					$card = $log->card;
					$course_id = $log->course_id;
					$action = $log->action;
					$timestamp = $log->timestamp;

					echo '<tr id="log-id-'.$log_id.'"><td>'.$log_id.'</td><td>'.$user_id.'</td><td>'.$card_name.'</td><td>'.$card.'</td><td>'.$course_id.'</td><td>'.$action.'</td><td>'.$timestamp.'</td></tr>';
				}
				?>
			</tbody>
		</table>
		<?php
	}
}
add_action( 'show_user_profile', 'cbs_add_profile_fields' );
add_action( 'edit_user_profile', 'cbs_add_profile_fields' );

// Save the custom fields
function cbs_save_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	if ( isset( $_POST['abo'] ) ) :
		update_user_meta( $user_id, 'abo', intval( $_POST['abo'] ) );
	else :
		update_user_meta( $user_id, 'abo', 0 );
	endif;

	if ( isset( $_POST['abo_2'] ) ) :
		update_user_meta( $user_id, 'abo_2', intval( $_POST['abo_2'] ) );
	else :
		update_user_meta( $user_id, 'abo_2', 0 );
	endif;

	if ( isset( $_POST['abo_3'] ) ) :
		update_user_meta( $user_id, 'abo_3', intval( $_POST['abo_3'] ) );
	else :
		update_user_meta( $user_id, 'abo_3', 0 );
	endif;

	if ( isset( $_POST['abo_start'] ) )
		update_user_meta( $user_id, 'abo_start', sanitize_text_field( $_POST['abo_start'] ) );

	if ( isset( $_POST['abo_expire'] ) )
		update_user_meta( $user_id, 'abo_expire', sanitize_text_field( $_POST['abo_expire'] ) );

	if ( isset( $_POST['abo_course'] ) )
		update_user_meta( $user_id, 'abo_course', sanitize_text_field( $_POST['abo_course'] ) );

	if ( isset( $_POST['abo_course_2'] ) )
		update_user_meta( $user_id, 'abo_course_2', sanitize_text_field( $_POST['abo_course_2'] ) );

	if ( isset( $_POST['abo_course_3'] ) )
		update_user_meta( $user_id, 'abo_course_3', sanitize_text_field( $_POST['abo_course_3'] ) );

	if ( isset( $_POST['abo_alternate'] ) )
		update_user_meta( $user_id, 'abo_alternate', sanitize_text_field( $_POST['abo_alternate'] ) );


	if ( isset( $_POST['flat'] ) ) :
		update_user_meta( $user_id, 'flat', intval( $_POST['flat'] ) );
	else :
		update_user_meta( $user_id, 'flat', 0 );
	endif;

	if ( isset( $_POST['flat_expire'] ) )
		update_user_meta( $user_id, 'flat_expire', sanitize_text_field( $_POST['flat_expire'] ) );

	if ( isset( $_POST['flat_2'] ) ) :
		update_user_meta( $user_id, 'flat_2', intval( $_POST['flat_2'] ) );
	else :
		update_user_meta( $user_id, 'flat_2', 0 );
	endif;

	if ( isset( $_POST['flat_expire_2'] ) )
		update_user_meta( $user_id, 'flat_expire_2', sanitize_text_field( $_POST['flat_expire_2'] ) );

	if ( isset( $_POST['flat_3'] ) ) :
		update_user_meta( $user_id, 'flat_3', intval( $_POST['flat_3'] ) );
	else :
		update_user_meta( $user_id, 'flat_3', 0 );
	endif;

	if ( isset( $_POST['flat_expire_3'] ) ) { update_user_meta( $user_id, 'flat_expire_3', sanitize_text_field( $_POST['flat_expire_3'] ) ); }

	if ( isset( $_POST['flat_4'] ) ) :
		update_user_meta( $user_id, 'flat_4', intval( $_POST['flat_4'] ) );
	else :
		update_user_meta( $user_id, 'flat_4', 0 );
	endif;

	if ( isset( $_POST['flat_expire_4'] ) )
		update_user_meta( $user_id, 'flat_expire_4', sanitize_text_field( $_POST['flat_expire_4'] ) );

	if ( isset( $_POST['flat_5'] ) ) :
		update_user_meta( $user_id, 'flat_5', intval( $_POST['flat_5'] ) );
	else :
		update_user_meta( $user_id, 'flat_5', 0 );
	endif;

	if ( isset( $_POST['flat_expire_5'] ) )
		update_user_meta( $user_id, 'flat_expire_5', sanitize_text_field( $_POST['flat_expire_5'] ) );


	if ( isset( $_POST['card'] ) ) {
		if ( get_the_author_meta( 'card', $user_id ) != $_POST['card'] )
			cbs_log( $user_id, 'card', intval( $_POST['card'] ), 0, __FUNCTION__ );

		update_user_meta( $user_id, 'card', intval( $_POST['card'] ) );
	}

	if ( isset( $_POST['expire'] ) )
		update_user_meta( $user_id, 'expire', sanitize_text_field( $_POST['expire'] ) );

	if ( isset( $_POST['card_2'] ) ) {
		if ( get_the_author_meta( 'card_2', $user_id ) != $_POST['card_2'] )
			cbs_log( $user_id, 'card_2', intval( $_POST['card_2'] ), 0, __FUNCTION__ );

		update_user_meta( $user_id, 'card_2', intval( $_POST['card_2'] ) );
	}

	if ( isset( $_POST['expire_2'] ) )
		update_user_meta( $user_id, 'expire_2', sanitize_text_field( $_POST['expire_2'] ) );

	if ( isset( $_POST['card_3'] ) ) {
		if ( get_the_author_meta( 'card_3', $user_id ) != $_POST['card_3'] )
			cbs_log( $user_id, 'card_3', intval( $_POST['card_3'] ), 0, __FUNCTION__ );

		update_user_meta( $user_id, 'card_3', intval( $_POST['card_3'] ) );
	}

	if ( isset( $_POST['expire_3'] ) )
		update_user_meta( $user_id, 'expire_3', sanitize_text_field( $_POST['expire_3'] ) );

	if ( isset( $_POST['card_4'] ) ) {
		if ( get_the_author_meta( 'card_4', $user_id ) != $_POST['card_4'] )
			cbs_log( $user_id, 'card_4', intval( $_POST['card_4'] ), 0, __FUNCTION__ );

		update_user_meta( $user_id, 'card_4', intval( $_POST['card_4'] ) );
	}

	if ( isset( $_POST['expire_4'] ) )
		update_user_meta( $user_id, 'expire_4', sanitize_text_field( $_POST['expire_4'] ) );

	if ( isset( $_POST['card_5'] ) ) {
		if ( get_the_author_meta( 'card_5', $user_id ) != $_POST['card_5'] )
			cbs_log( $user_id, 'card_5', intval( $_POST['card_5'] ), 0, __FUNCTION__ );

		update_user_meta( $user_id, 'card_5', intval( $_POST['card_5'] ) );
	}

	if ( isset( $_POST['expire_5'] ) )
		update_user_meta( $user_id, 'expire_5', sanitize_text_field( $_POST['expire_5'] ) );


	if ( isset( $_POST['birthday'] ) )
		update_user_meta( $user_id, 'birthday', sanitize_text_field( $_POST['birthday'] ) );
}
add_action( 'personal_options_update', 'cbs_save_profile_fields' );
add_action( 'edit_user_profile_update', 'cbs_save_profile_fields' );

// Modify user table
function cbs_modify_user_table( $column ) {
	$price_level_title   = get_option( 'course_booking_system_price_level_title' );
	$price_level_title_2 = get_option( 'course_booking_system_price_level_title_2' );
	$price_level_title_3 = get_option( 'course_booking_system_price_level_title_3' );
	$price_level_title_4 = get_option( 'course_booking_system_price_level_title_4' );
	$price_level_title_5 = get_option( 'course_booking_system_price_level_title_5' );

	$column['abo_expire'] = __( 'Subscription(s) valid until', 'course-booking-system' );
	$column['card']       = __( 'Card', 'course-booking-system' ).' ('.$price_level_title.')';
	$column['expire']     = __( 'Card valid until', 'course-booking-system' );
	$column['card_2']     = __( 'Card', 'course-booking-system' ).' 2 ('.$price_level_title_2.')';
	$column['expire_2']   = __( 'Card valid until', 'course-booking-system' );
	$column['card_3']     = __( 'Card', 'course-booking-system' ).' 3 ('.$price_level_title_3.')';
	$column['expire_3']   = __( 'Card valid until', 'course-booking-system' );
	$column['card_4']     = __( 'Card', 'course-booking-system' ).' 4 ('.$price_level_title_4.')';
	$column['expire_4']   = __( 'Card valid until', 'course-booking-system' );
	$column['card_5']     = __( 'Card', 'course-booking-system' ).' 5 ('.$price_level_title_5.')';
	$column['expire_5']   = __( 'Card valid until', 'course-booking-system' );
	$column['registered'] = __( 'Registered', 'course-booking-system' );
	return $column;
}
add_filter( 'manage_users_columns', 'cbs_modify_user_table' );

function cbs_modify_user_table_row( $val, $column_name, $user_id ) {
	$date_format = get_option( 'date_format' );
	switch ( $column_name ) {
		case 'abo_expire' :
			if ( !empty( get_the_author_meta( 'abo_expire', $user_id ) ) ) {
				return date_i18n( $date_format, strtotime( get_the_author_meta( 'abo_expire', $user_id ) ) );
			} else {
				return;
			}
		case 'card' :
			return get_the_author_meta( 'card', $user_id );
		case 'expire' :
			if ( !empty( get_the_author_meta( 'expire', $user_id ) ) ) {
				return date_i18n( $date_format, strtotime( get_the_author_meta( 'expire', $user_id ) ) );
			} else {
				return;
			}
		case 'card_2' :
			return get_the_author_meta( 'card_2', $user_id );
		case 'expire_2' :
			if ( !empty( get_the_author_meta( 'expire_2', $user_id ) ) ) {
				return date_i18n( $date_format, strtotime( get_the_author_meta( 'expire_2', $user_id ) ) );
			}
		case 'card_3' :
			return get_the_author_meta( 'card_3', $user_id );
		case 'expire_3' :
			if ( !empty( get_the_author_meta( 'expire_3', $user_id ) ) ) {
				return date_i18n( $date_format, strtotime( get_the_author_meta( 'expire_3', $user_id ) ) );
			} else {
				return;
			}
		case 'card_4' :
			return get_the_author_meta( 'card_4', $user_id );
		case 'expire_4' :
			if ( !empty( get_the_author_meta( 'expire_4', $user_id ) ) ) {
				return date_i18n( $date_format, strtotime( get_the_author_meta( 'expire_4', $user_id ) ) );
			} else {
				return;
			}
		case 'card_5' :
			return get_the_author_meta( 'card_5', $user_id );
		case 'expire_5' :
			if ( !empty( get_the_author_meta( 'expire_5', $user_id ) ) ) {
				return date_i18n( $date_format, strtotime( get_the_author_meta( 'expire_5', $user_id ) ) );
			} else {
				return;
			}
		case 'registered' :
			$user = get_userdata( $user_id );
			return '<abbr title="'.$user->user_registered.'">'.date_i18n( $date_format, strtotime( $user->user_registered ) ).'</abbr>';
		default:
	}
	return $val;
}
add_filter( 'manage_users_custom_column', 'cbs_modify_user_table_row', 10, 3 );

function cbs_get_sortable_columns( $columns ) {
	return wp_parse_args( array( 'registered' => 'registered' ), $columns );
}
add_filter( 'manage_users_sortable_columns', 'cbs_get_sortable_columns' );

function cbs_remove_users_columns( $column_headers ) {
	unset( $column_headers['role'] );
	unset( $column_headers['posts'] );

	return $column_headers;
}
add_filter( 'manage_users_columns', 'cbs_remove_users_columns' );