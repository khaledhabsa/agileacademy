<?php
function cbs_update() {
	global $wpdb;
	global $cbs_db_version;

	$table_name = $wpdb->prefix."mp_timetable_attendances";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_attendances";
		$wpdb->query( $rename_table_query );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_bookings";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_bookings";
		$wpdb->query( $rename_table_query );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_cancellations";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_cancellations";
		$wpdb->query( $rename_table_query );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_data";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_data";
		$wpdb->query( $rename_table_query );

		$wpdb->query( "ALTER TABLE ".$wpdb->prefix."cbs_data CHANGE `column_id` `day` INT(11) NOT NULL" );
		$wpdb->query( "ALTER TABLE ".$wpdb->prefix."cbs_data CHANGE `event_id` `post_id` INT(11) NOT NULL" );
		$wpdb->query( "ALTER TABLE ".$wpdb->prefix."cbs_data CHANGE `event_start` `start` TIME NOT NULL" );
		$wpdb->query( "ALTER TABLE ".$wpdb->prefix."cbs_data CHANGE `event_end` `end` TIME NOT NULL" );
		$wpdb->query( "ALTER TABLE ".$wpdb->prefix."cbs_data DROP `description`" );
		$wpdb->query( "ALTER TABLE ".$wpdb->prefix."cbs_data ADD `date` DATE NULL AFTER `day`" );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_logs";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_logs";
		$wpdb->query( $rename_table_query );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_notes";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_notes";
		$wpdb->query( $rename_table_query );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_substitutes";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_substitutes";
		$wpdb->query( $rename_table_query );
	endif;

	$table_name = $wpdb->prefix."mp_timetable_waitlists";
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) :
		$rename_table_query = "RENAME TABLE ".$table_name." TO ".$wpdb->prefix."cbs_waitlists";
		$wpdb->query( $rename_table_query );
	endif;

	// Weekdays
	$weekdays = array( 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7 );

	$columns = get_posts( array(
		'post_type'      => 'mp-column',
		'posts_per_page' => -1,
		'fields'         => 'ids'
	) );
	if ( $columns ) :
		foreach ( $columns AS $column ) :
			$weekday = get_post_meta( $column, 'weekday', true );
			$option_day = get_post_meta( $column, 'option_day', true );

			if ( $weekday )
				$wpdb->query( "UPDATE ".$wpdb->prefix."cbs_data SET `day` = $weekdays[$weekday] WHERE `day` = $column" );

			if ( $option_day ) {
				$option_day = DateTime::createFromFormat( 'd/m/Y', $option_day );
				$date = $option_day->format( 'Y-m-d' );

				$wpdb->update(
					$wpdb->prefix.'cbs_data',
					array( 'date' => sanitize_text_field( $date ) ),
					array( 'day' => $column ),
					array( '%s' ),
					array( '%d' )
				);

				$wpdb->update(
					$wpdb->prefix.'cbs_data',
					array( 'day' => 99 ),
					array( 'day' => $column ),
					array( '%d' ),
					array( '%d' )
				);
			}

			wp_delete_post( $column );
		endforeach;
	endif;

	// General Updates
	$wpdb->query( "UPDATE ".$wpdb->prefix."posts SET `post_type` = 'course' WHERE `post_type` = 'mp-event'" );
	$wpdb->query( "UPDATE ".$wpdb->prefix."term_taxonomy SET `taxonomy` = 'course_category' WHERE `taxonomy` = 'mp-event_category'" );
	$wpdb->query( "DELETE FROM ".$wpdb->prefix."postmeta WHERE `meta_key` = 'sub_title'" );
	$wpdb->query( "DELETE FROM ".$wpdb->prefix."postmeta WHERE `meta_key` = 'hover_color'" );
	$wpdb->query( "DELETE FROM ".$wpdb->prefix."postmeta WHERE `meta_key` = 'hover_text_color'" );
	$wpdb->query( "DELETE FROM ".$wpdb->prefix."postmeta WHERE `meta_key` = 'timetable_disable_url'" );

	// Shortcodes
	$wpdb->query( "UPDATE ".$wpdb->prefix."options SET `option_value` = 'default' WHERE `option_name` = 'course_booking_system_design' AND `option_value` = 'modern'" );
	$wpdb->query( "UPDATE ".$wpdb->prefix."posts SET post_content = REPLACE(post_content, '[mp-timetable', '[timetable') WHERE post_content LIKE ('%[mp-timetable%')" );
	$wpdb->query( "UPDATE ".$wpdb->prefix."posts SET post_content = REPLACE(post_content, 'event_categ=', 'category=') WHERE post_content LIKE ('%event_categ=%')" );

	$wpdb->query( "UPDATE ".$wpdb->prefix."postmeta SET meta_value = REPLACE(meta_value, '[mp-timetable', '[timetable') WHERE meta_value LIKE ('%[mp-timetable%') AND meta_key = '_elementor_data'" ); // Elementor
	$wpdb->query( "UPDATE ".$wpdb->prefix."postmeta SET meta_value = REPLACE(meta_value, 'event_categ=', 'category=') WHERE meta_value LIKE ('%event_categ=%') AND meta_key = '_elementor_data'" ); // Elementor

	if ( is_plugin_active( '/mp-timetable/mp-timetable.php' ) )
		deactivate_plugins( '/mp-timetable/mp-timetable.php' );

	// Create missing tables
	require plugin_dir_path( __FILE__ ).'create.php';

	// Update DB version
	update_option( 'course_booking_system_db_version', $cbs_db_version );
}

function cbs_update_db_check() {
	global $cbs_db_version;

	if ( get_option( 'course_booking_system_db_version' ) != $cbs_db_version )
		cbs_update();
}
add_action( 'plugins_loaded', 'cbs_update_db_check' );