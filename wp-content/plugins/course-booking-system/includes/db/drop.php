<?php
global $wpdb;

$drop_table_query = "
	DROP TABLE `".$wpdb->prefix."cbs_attendances`;
";
$wpdb->query( $drop_table_query );

$drop_table_query = "
	DROP TABLE `".$wpdb->prefix."cbs_bookings`;
";
$wpdb->query( $drop_table_query );

$drop_table_query = "
	DROP TABLE `".$wpdb->prefix."cbs_cancellations`;
";
$wpdb->query( $drop_table_query );

$drop_table_query = "
	DROP TABLE `".$wpdb->prefix."cbs_notes`;
";
$wpdb->query( $drop_table_query );

$drop_table_query = "
	DROP TABLE `".$wpdb->prefix."cbs_substitutes`;
";
$wpdb->query( $drop_table_query );

$drop_table_query = "
	DROP TABLE `".$wpdb->prefix."cbs_waitlists`;
";
$wpdb->query( $drop_table_query );