<?php
if ( !empty( $subscription_start ) ) {
	update_user_meta( $user_id, 'abo_start', $subscription_start );
	$abo_expire = date( 'Y-m-d', strtotime( $subscription_start.' +'.$subscription_expiry.' months -1 day' ) );
} else {
	$abo_expire = get_the_author_meta( 'abo_expire', $user_id );

	if ( $abo_expire > date( 'Y-m-d' ) ) {
		$abo_expire = date( 'Y-m-d', strtotime( $abo_expire.' +'.$subscription_expiry.' months' ) );
	} else {
		$abo_expire = date( 'Y-m-d', strtotime( '+'.$subscription_expiry.' months -1 day' ) );
	}
}
update_user_meta( $user_id, 'abo_expire', $abo_expire );

// Extend card expiry date
$card_names   = array( 1 => 'card', 2 => 'card_2', 3 => 'card_3', 4 => 'card_4', 5 => 'card_5' );
$card_name    = $card_names[$subscription_price_level];
$card         = get_the_author_meta( $card_name, $user_id );

$expire_names = array( 1 => 'expire', 2 => 'expire_2', 3 => 'expire_3', 4 => 'expire_4', 5 => 'expire_5' );
$expire_name  = $expire_names[$subscription_price_level];
$expire       = get_the_author_meta( $expire_name, $user_id );

if ( !empty( $subscription_start ) || ( $card > 0 && $expire < date( 'Y-m-d' ) ) ) { // Empty card quantity before extending expiry date if new subscription has a start date or card is expired
	update_user_meta( $user_id, $card_name, 0 );
	cbs_log( $user_id, $card_name, 0, '', 'subscription' );
}

if ( strtotime( $abo_expire ) > strtotime( $expire ) ) {
	update_user_meta( $user_id, $expire_name, $abo_expire );
}