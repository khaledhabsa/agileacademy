<?php
if ( $quantity == 99999 || $quantity == -99999 ) { // Flatrate
	$flat_names   = array( 1 => 'flat', 2 => 'flat_2', 3 => 'flat_3', 4 => 'flat_4', 5 => 'flat_5' );
	$flat_name    = $flat_names[$price_level];

	$expire_names = array( 1 => 'flat_expire', 2 => 'flat_expire_2', 3 => 'flat_expire_3', 4 => 'flat_expire_4', 5 => 'flat_expire_5' );
	$expire_name  = $expire_names[$price_level];
	$expire       = get_the_author_meta( $expire_name, $user_id );

	update_user_meta( $user_id, $flat_name, 1 );

	if ( $quantity == -99999 ) { // Flatrate cancelled
		update_user_meta( $user_id, $expire_name, date( 'Y-m-d' ) );
	} else if ( !empty( $expiry_end ) ) { // Fixed expiry end
		update_user_meta( $user_id, $expire_name, $expiry_end );
	} else if ( $expiry < 0.1 ) { // Hack for a flat for one day
		if ( $expire > date( 'Y-m-d' ) ) {
			update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( $expire.' +1 day' ) ) );
		} else {
			update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+1 day' ) ) );
		}
	} else if ( $expiry < 1 ) { // Hack for a flat for one week
		if ( $expire > date( 'Y-m-d' ) ) {
			update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( $expire.' +1 week' ) ) );
		} else {
			update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+1 week' ) ) );
		}
	} else {
		if ( $expire > date( 'Y-m-d' ) ) {
			update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( $expire.' +'.$expiry.' months' ) ) );
		} else {
			update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+'.$expiry.' months' ) ) );
		}
	}
} else {
	$card_names   = array( 1 => 'card', 2 => 'card_2', 3 => 'card_3', 4 => 'card_4', 5 => 'card_5' );
	$card_name    = $card_names[$price_level];
	$card         = get_the_author_meta( $card_name, $user_id );

	$expire_names = array( 1 => 'expire', 2 => 'expire_2', 3 => 'expire_3', 4 => 'expire_4', 5 => 'expire_5' );
	$expire_name  = $expire_names[$price_level];
	$expire       = get_the_author_meta( $expire_name, $user_id );

	if ( !empty( $card ) && ( $expire > date( 'Y-m-d' ) || $card < 0 ) ) {
		$card_balance = $card + $quantity;
	} else {
		$card_balance = $quantity;
	}
	update_user_meta( $user_id, $card_name, $card_balance );
	cbs_log( $user_id, $card_name, $card_balance, '', 'redeem' );

	if ( $expiry < 0.1 && strtotime( '+1 day' ) > strtotime( $expire ) ) { // Hack for a card for one day
		update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+1 day' ) ) );
	} else if ( $expiry == 2.5 && strtotime( '+10 weeks' ) > strtotime( $expire ) ) { // Hack for a card for ten weeks
		update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+10 weeks' ) ) );
	} else if ( strtotime( '+'.$expiry.' months' ) > strtotime( $expire ) ) {
		update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+'.$expiry.' months' ) ) );
	}
}