<?php
global $wpdb;

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_attendances` (
	 `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
	 `course_id` int(11) NOT NULL,
	 `date` date NOT NULL,
	 `attendance` int(11) NOT NULL,
	 PRIMARY KEY (`attendance_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_bookings` (
	 `booking_id` int(11) NOT NULL AUTO_INCREMENT,
	 `course_id` int(11) NOT NULL,
	 `date` date NOT NULL,
	 `user_id` int(11) NOT NULL,
	 PRIMARY KEY (`booking_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_cancellations` (
	 `cancellation_id` int(11) NOT NULL AUTO_INCREMENT,
	 `course_id` int(11) NOT NULL,
	 `date` date NOT NULL,
	 `user_id` int(11) NOT NULL,
	 `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 PRIMARY KEY (`cancellation_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_logs` (
	 `log_id` int(11) NOT NULL AUTO_INCREMENT,
	 `user_id` int(11) NOT NULL,
	 `card_name` VARCHAR(255) NOT NULL,
	 `card` int(11) NULL,
	 `course_id` int(11) NOT NULL,
	 `action` VARCHAR(255) NOT NULL,
	 `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 PRIMARY KEY (`log_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_notes` (
	 `note_id` int(11) NOT NULL AUTO_INCREMENT,
	 `course_id` int(11) NOT NULL,
	 `date` date NOT NULL,
	 `note` text NOT NULL,
	 PRIMARY KEY (`note_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_substitutes` (
	 `substitute_id` int(11) NOT NULL AUTO_INCREMENT,
	 `course_id` int(11) NOT NULL,
	 `date` date NOT NULL,
	 `user_id` int(11) NOT NULL,
	 PRIMARY KEY (`substitute_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );

$create_table_query = "
	CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."cbs_waitlists` (
	 `waitlist_id` int(11) NOT NULL AUTO_INCREMENT,
	 `course_id` int(11) NOT NULL,
	 `date` date NOT NULL,
	 `user_id` int(11) NOT NULL,
	 PRIMARY KEY (`waitlist_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$wpdb->query( $create_table_query );