<?php
include 'ics.php';

header( 'Content-type: text/calendar; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=invite.ics' );

$location    = urldecode( $_GET['location'] );
$description = urldecode( $_GET['description'] );
$date        = urldecode( $_GET['date'] );
$start       = urldecode( $_GET['start'] );
$end         = urldecode( $_GET['end'] );
$timezone    = urldecode( $_GET['timezone'] );
$account_url = urldecode( $_GET['account_url'] );

date_default_timezone_set( $timezone );

if ( date( 'I' ) == date( 'I', strtotime( $date ) ) ) : // Day light saving time

	$ics = new cbs_ICS( array(
		'location' => $location,
		'description' => $description,
		'dtstart' => date( 'm/d/Y H:i:s', strtotime( $date.' '.date( 'H:i:s', strtotime( $start ) - date( 'Z' ) ) ) ),
		'dtend' => date( 'm/d/Y H:i:s', strtotime( $date.' '.date( 'H:i:s', strtotime( $end ) - date( 'Z' ) ) ) ),
		'summary' => $description,
		'url' => $account_url
	) );

elseif ( date( 'I' ) ) : // From Summer to Winter

	$ics = new cbs_ICS( array(
		'location' => $location,
		'description' => $description,
		'dtstart' => date( 'm/d/Y H:i:s', strtotime( $date.' '.date( 'H:i:s', strtotime( $start ) - date( 'Z' ) + date( 'I' ) * 3600 ) ) ),
		'dtend' => date( 'm/d/Y H:i:s', strtotime( $date.' '.date( 'H:i:s', strtotime( $end ) - date( 'Z' ) + date( 'I' ) * 3600 ) ) ),
		'summary' => $description.' '.date( 'I' ),
		'url' => $account_url
	) );

else : // From Winter to Summer

	$ics = new cbs_ICS( array(
		'location' => $location,
		'description' => $description,
		'dtstart' => date( 'm/d/Y H:i:s', strtotime( $date.' '.date( 'H:i:s', strtotime( $start ) ) ) ),
		'dtend' => date( 'm/d/Y H:i:s', strtotime( $date.' '.date( 'H:i:s', strtotime( $end ) ) ) ),
		'summary' => $description,
		'url' => $account_url
	) );

endif;

echo $ics->to_string();