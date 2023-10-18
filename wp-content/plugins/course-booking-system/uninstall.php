<?php
/**
 * Course Booking System Uninstall
 *
 * Uninstalling Course Booking System deletes tables, options and user meta.
 *
 * @package Course Booking System\Uninstaller
 * @version 1.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

include_once plugin_dir_path( __FILE__ ) . 'includes/db/drop.php';

global $wpdb;
$wpdb->query( "DELETE FROM `".$wpdb->prefix."options` WHERE `option_name` LIKE ('course_booking_system_%')" );

$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_2'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_3'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_start'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_expire'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_course'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_course_2'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_course_3'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'abo_alternate'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_2'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_2'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_3'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_3'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_4'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_4'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'card_5'" );
$wpdb->query( "DELETE FROM `".$wpdb->prefix."usermeta` WHERE `meta_key` = 'expire_5'" );

flush_rewrite_rules();
wp_cache_flush();
