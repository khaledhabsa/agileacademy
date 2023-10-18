<?php
// Add text to login and register form
function cbs_woocommerce_login_text() {
	if ( !is_user_logged_in() && isset( $_GET['message'] ) ) {
		?>
		<div class="woocommerce"><div class="woocommerce-info">
			<?php
			if ( $_GET['message'] == 'login' ) {
				_e( 'Please login first and then try again.', 'course-booking-system' );
			} else if ( $_GET['message'] == 'delete' ) {
				_e( 'The user account has been successfully deleted.', 'course-booking-system' );
			}
			?>
		</div></div>
	<?php
	}
}
add_action( 'woocommerce_before_customer_login_form', 'cbs_woocommerce_login_text' );

// Add fields to register form
function cbs_woocommerce_extra_register_fields() {
	?>
	<p class="form-row form-row-first">
		<label for="billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_first_name" id="billing_first_name" value="<?php if ( !empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>
	<p class="form-row form-row-last">
		<label for="billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_last_name" id="billing_last_name" value="<?php if ( !empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>
	<div class="clear"></div>
	<p class="form-row form-row-wide">
		<label for="billing_address_1"><?php _e( 'Address', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_address_1" id="billing_address_1" value="<?php if ( !empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
	</p>
	<p class="form-row form-row-first">
		<label for="billing_postcode"><?php _e( 'Postcode', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_postcode" id="billing_postcode" value="<?php if ( !empty( $_POST['billing_postcode'] ) ) esc_attr_e( $_POST['billing_postcode'] ); ?>" />
	</p>
	<p class="form-row form-row-last">
		<label for="billing_city"><?php _e( 'City', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_city" id="billing_city" value="<?php if ( !empty( $_POST['billing_city'] ) ) esc_attr_e( $_POST['billing_city'] ); ?>" />
	</p>
	<div class="clear"></div>
	<?php if ( 'hidden' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) { ?>
		<p class="form-row form-row-wide">
			<label for="billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?> <?= ( 'required' === get_option( 'woocommerce_checkout_phone_field', 'required' ) ) ? '<span class="required">*</span>' : '' ?></label>
			<input type="text" class="input-text" name="billing_phone" id="billing_phone" value="<?php if ( !empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
		</p>
	<?php
	}
	if ( get_option( 'course_booking_system_woocommerce_birthday' ) ) { ?>
		<p class="form-row form-row-wide">
			<label for="birthday"><?php _e( 'Birthday', 'course-booking-system' ); ?> <span class="optional">(<?php _e( 'optional', 'woocommerce' ); ?>)</span></label>
			<input type="date" class="input-text" name="birthday" id="birthday" value="<?php if ( !empty( $_POST['birthday'] ) ) esc_attr_e( $_POST['birthday'] ); ?>" placeholder="YYYY-MM-DD" />
		</p>
	<?php }
	if ( get_option( 'course_booking_system_woocommerce_referral' ) ) { ?>
		<p class="form-row form-row-wide">
			<label for="referral"><?php _e( 'Who recommended you? (Enter email and the referrer and you get a free credit)', 'course-booking-system' ); ?> <span class="optional">(<?php _e( 'optional', 'woocommerce' ); ?>)</span></label>
			<input type="email" class="input-text" name="referral" id="referral" value="<?php if ( !empty( $_POST['referral'] ) ) esc_attr_e( $_POST['referral'] ); ?>" />
		</p>
	<?php }
}
add_action( 'woocommerce_register_form_start', 'cbs_woocommerce_extra_register_fields' );

function cbs_woocommerce_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>'.__( 'First name', 'woocommerce' ).'</strong>' ) );
	} if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		$validation_errors->add( 'billing_last_name_error', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>'.__( 'Last name', 'woocommerce' ).'</strong>' ) );
	} if ( isset( $_POST['billing_address_1'] ) && empty( $_POST['billing_address_1'] ) ) {
		$validation_errors->add( 'billing_address_1_error', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>'.__( 'Address', 'woocommerce' ).'</strong>' ) );
	} if ( isset( $_POST['billing_postcode'] ) && empty( $_POST['billing_postcode'] ) ) {
		$validation_errors->add( 'billing_postcode_error', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>'.__( 'Postcode', 'woocommerce' ).'</strong>' ) );
	} if ( isset( $_POST['billing_city'] ) && empty( $_POST['billing_city'] ) ) {
		$validation_errors->add( 'billing_city_error', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>'.__( 'City', 'woocommerce' ).'</strong>' ) );
	} if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
		$validation_errors->add( 'billing_phone_error', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>'.__( 'Phone', 'woocommerce' ).'</strong>' ) );
	}
	return $validation_errors;
}
add_action( 'woocommerce_register_post', 'cbs_woocommerce_validate_extra_register_fields', 10, 3 );

function cbs_woocommerce_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		update_user_meta( $customer_id, 'first_name', esc_attr( $_POST['billing_first_name'] ) );
		update_user_meta( $customer_id, 'billing_first_name', esc_attr( $_POST['billing_first_name'] ) );
	} if ( isset( $_POST['billing_last_name'] ) ) {
		update_user_meta( $customer_id, 'last_name', esc_attr( $_POST['billing_last_name'] ) );
		update_user_meta( $customer_id, 'billing_last_name', esc_attr( $_POST['billing_last_name'] ) );
	} if ( isset( $_POST['billing_first_name'] ) && isset( $_POST['billing_last_name'] ) ) {
        wp_update_user( array(
			'ID' => $customer_id,
			'display_name' => esc_attr( trim( $_POST['billing_first_name'].' '.$_POST['billing_last_name'] ) )
        ) );
	} if ( isset( $_POST['billing_address_1'] ) ) {
		update_user_meta( $customer_id, 'billing_address_1', esc_attr( $_POST['billing_address_1'] ) );
	} if ( isset( $_POST['billing_postcode'] ) ) {
		update_user_meta( $customer_id, 'billing_postcode', esc_attr( $_POST['billing_postcode'] ) );
	} if ( isset( $_POST['billing_city'] ) ) {
		update_user_meta( $customer_id, 'billing_city', esc_attr( $_POST['billing_city'] ) );
	} if ( isset( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', esc_attr( $_POST['billing_phone'] ) );
	} if ( isset( $_POST['birthday'] ) ) {
		update_user_meta( $customer_id, 'birthday', esc_attr( $_POST['birthday'] ) );
	}
}
add_action( 'woocommerce_created_customer', 'cbs_woocommerce_save_extra_register_fields' );

// User names with prename.lastname
function cbs_woocommerce_usernames( $new_customer_data ) {
	if ( isset( $_POST['billing_first_name'] ) ) $first_name = esc_attr( $_POST['billing_first_name'] );
	if ( isset( $_POST['billing_last_name'] ) ) $last_name = esc_attr( $_POST['billing_last_name'] );

	if ( !empty( $first_name ) || !empty( $last_name ) ) {
		$complete_name = trim( $first_name ) . ' ' . trim( $last_name );
		$username = sanitize_user( str_replace( ' ', '.', strtolower( $complete_name ) ) );
		if ( !username_exists( $username ) ) {
			$new_customer_data['user_login'] = $username;
		}
	}

	return $new_customer_data;
}
add_filter( 'woocommerce_new_customer_data', 'cbs_woocommerce_usernames', 10, 1 );

// Referral
function cbs_woocommerce_created_customer( $customer_id, $new_customer_data, $password_generated ) {
	if ( isset( $_POST['referral'] ) ) $referral = esc_attr( $_POST['referral'] );
	if ( !empty( $referral ) ) {
		$price_level = get_option( 'course_booking_system_woocommerce_referral_price_level' );

		$referrer = get_user_by( 'email', $referral );
		if ( !empty( $referrer ) ) {
			$user_id = $referrer->ID;

			$card_names   = array( 1 => 'card', 2 => 'card_2', 3 => 'card_3', 4 => 'card_4', 5 => 'card_5' );
			$card_name    = $card_names[$price_level];
			$card         = get_the_author_meta( $card_name, $user_id );

			$expire_names = array( 1 => 'expire', 2 => 'expire_2', 3 => 'expire_3', 4 => 'expire_4', 5 => 'expire_5' );
			$expire_name  = $expire_names[$price_level];
			$expire       = get_the_author_meta( $expire_name, $user_id );

			// Referrer customer
			if ( !empty( $card ) && $expire > date( 'Y-m-d' ) ) {
				$card_balance = $card + 1;
			} else {
				$card_balance = 1;
				update_user_meta( $user_id, $expire_name, date( 'Y-m-d', strtotime( '+1 month' ) ) );
			}
			update_user_meta( $user_id, $card_name, $card_balance );
			cbs_log( $user_id, $card_name, $card_balance, '', 'referral ('.$referral.')' );

			// Created customer
			update_user_meta( $customer_id, $card_name, 1 );
			update_user_meta( $customer_id, $expire_name, date( 'Y-m-d', strtotime( '+1 month' ) ) );
			cbs_log( $customer_id, $card_name, 1, '', 'referral ('.$referral.')' );
		}
	}
}
add_action( 'woocommerce_created_customer', 'cbs_woocommerce_created_customer', 10, 3 ); 

// Redirect after login to previous page
function cbs_woocommerce_login_redirect( $redirect ) {
	$redirect_page_id = url_to_postid( $redirect );
	$checkout_page_id = wc_get_page_id( 'checkout' );

	if ( $redirect_page_id == $checkout_page_id ) {
		return $redirect;
	} else if ( isset( $_GET['redirect_to'] ) ) {
		return urldecode( $_GET['redirect_to'] );
	}

	return get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
}
add_filter( 'woocommerce_login_redirect', 'cbs_woocommerce_login_redirect' );

// Redirect after registration to previous page
function cbs_woocommerce_registration_redirect( $redirect ) {
 	if ( isset( $_GET['redirect_to'] ) )
		return urldecode( $_GET['redirect_to'] );

	return get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
}
add_filter( 'woocommerce_registration_redirect', 'cbs_woocommerce_registration_redirect' );

// Redirect to redeem page if already payed
function cbs_woocommerce_auto_complete_order( $order_id ) {
	if ( !$order_id )
		return;

	// Check if order contains not redeemable products
	/* $order = wc_get_order( $order_id );
	$items = $order->get_items(); 
	foreach ( $items as $item_id => $item ) {
		$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
		$product = wc_get_product( $product_id );
		if ( $product->get_type() != 'redeem' && $product->product_type != 'redeem' ) {
			return;
		}
	} */

	// Auto complete order
	if ( get_option( 'course_booking_system_woocommerce_auto_complete_order' ) ) {
		$order = wc_get_order( $order_id );
		if ( $order->get_status() == 'pending' || $order->get_status() == 'processing' || $order->get_status() == 'on-hold' )
			$order->update_status( 'completed' );
	}

	// Redirect to redemption
	$user_id = get_current_user_id();
	$downloads = wc_get_customer_available_downloads( $user_id );
	if ( count( $downloads ) > 0 ) {
		wp_redirect( $downloads[0]['download_url'] );
		exit;
	}
}
add_action( 'woocommerce_thankyou', 'cbs_woocommerce_auto_complete_order', 20, 1 );

// Redeem
function cbs_woocommerce_download_file_redirect( $file_path, $filename ) {
	$user_id = get_current_user_id();
	$downloads = wc_get_customer_available_downloads( $user_id );
	$count = count( $downloads );

	$product_id    = sanitize_text_field( $_GET['download_file'] );
	$price_level   = get_post_meta( $product_id, '_redeem_price_level', true );
	$quantity      = get_post_meta( $product_id, '_redeem_quantity', true );
	$price_level_2 = get_post_meta( $product_id, '_redeem_price_level_2', true );
	$expiry        = get_post_meta( $product_id, '_redeem_expiry', true );
	$expiry_end    = get_post_meta( $product_id, '_redeem_expiry_end', true );
	$subscription_price_level = get_post_meta( $product_id, '_subscription_price_level', true );
	$subscription_expiry      = get_post_meta( $product_id, '_subscription_expiry', true );
	$subscription_start       = get_post_meta( $product_id, '_subscription_start', true );
	$video_url     = get_post_meta( $product_id, '_video_url', true );

	if ( !empty( $price_level_2 ) && $count > 0 && $product_id == $downloads[0]['product_id'] ) { // Mixed cards
		$price_level = $price_level_2;
		$quantity = $quantity * -1; // Negative quantity
	}

	if ( !empty( $price_level ) && !empty( $quantity ) && ( !empty( $expiry ) || !empty( $expiry_end ) ) ) {
		require 'redeem.php';

		if ( $count > 0 && empty( get_post_meta( $downloads[0]['product_id'], '_video_url', true ) ) ) {
			wp_redirect( $downloads[0]['download_url'] );
			exit;
		} else if ( isset( $_COOKIE['last-course-visited'] ) && filter_var( $_COOKIE['last-course-visited'], FILTER_VALIDATE_URL ) ) {
			wp_redirect( htmlspecialchars( $_COOKIE['last-course-visited'].'&message=purchase' ) );
			exit;
		} else {
			$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			wp_redirect( $account_url.'?message=purchase' );
			exit;
		}
	} else if ( !empty( $subscription_price_level ) && !empty( $subscription_expiry ) ) {
		require 'subscription.php';

		if ( $count > 0 && empty( get_post_meta( $downloads[0]['product_id'], '_video_url', true ) ) ) {
			wp_redirect( $downloads[0]['download_url'] );
			exit;
		} else {
			$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			wp_redirect( $account_url.'?message=subscription' );
			exit;
		}
	} else if ( !empty( $video_url ) ) {
		$count--;
		if ( $count > 1 && $product_id != $downloads[$count]['product_id'] && !empty( get_post_meta( $downloads[$count]['product_id'], '_video_url', true ) ) ) {
			wp_redirect( $downloads[$count]['download_url'] );
			exit;
		} else {
			$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			wp_redirect( $account_url.'video/?message=purchase' );
			exit;
		}
	}
}
add_action( 'woocommerce_download_file_xsendfile', 'cbs_woocommerce_download_file_redirect', 10, 2 );
add_action( 'woocommerce_download_file_redirect', 'cbs_woocommerce_download_file_redirect', 10, 2 );

function cbs_woocommerce_download_file_redeem( $order ) {
	global $wpdb;
	$user_id = $order->get_user_id();
	$downloads = wc_get_customer_available_downloads( $user_id );

	foreach ( $downloads as $download ) {
		$download_path = parse_url( $download['download_url'] );
		parse_str( $download_path['query'], $get_download );
		$product_id = sanitize_text_field( $get_download['download_file'] );
		$order_key = sanitize_text_field( $get_download['order'] );

		$price_level   = get_post_meta( $product_id, '_redeem_price_level', true );
		$quantity      = get_post_meta( $product_id, '_redeem_quantity', true );
		$price_level_2 = get_post_meta( $product_id, '_redeem_price_level_2', true );
		$expiry        = get_post_meta( $product_id, '_redeem_expiry', true );
		$expiry_end    = get_post_meta( $product_id, '_redeem_expiry_end', true );
		$video_url     = get_post_meta( $product_id, '_video_url', true );

		if ( !empty( $price_level ) && !empty( $quantity ) && ( !empty( $expiry ) || !empty( $expiry_end ) ) ) {
			for ( $i = 0; $i < $download['downloads_remaining']; $i++ ) { 
				require 'redeem.php';
			}

			$wpdb->update(
				$wpdb->prefix.'woocommerce_downloadable_product_permissions', 
				array( 'downloads_remaining' => 0, 'download_count' => $download['downloads_remaining'] ), 
				array( 'order_key' => $order_key, 'product_id' => $product_id ),
				array( '%d', '%d' ),
				array( '%s', '%d' )
			);

			if ( !empty( $price_level_2 ) ) { // Mixed cards
				for ( $i = 0; $i < $download['downloads_remaining']; $i++ ) { 
					$price_level = $price_level_2;
					require 'redeem.php';
				}
				break;
			}
		}
	}
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'cbs_woocommerce_download_file_redeem' );

/* function cbs_woocommerce_download_file_redeem( $order, $data_store ) {
	global $wpdb;

	if ( !is_admin() ) {
		return;
	}

	if ( get_option( 'course_booking_system_woocommerce_auto_complete_order' ) ) {
		if ( $order->get_status() == 'pending' || $order->get_status() == 'processing' || $order->get_status() == 'on-hold' ) {
			$order->update_status( 'completed' );
		}
	}

	$user_id = get_post_meta( $order->id, '_customer_user', true );
	$downloads = wc_get_customer_available_downloads( $user_id );

	foreach ( $downloads as $download ) {
		$download_path = parse_url( $download['download_url'] );
		parse_str( $download_path['query'], $get_download );
		$product_id = sanitize_text_field( $get_download['download_file'] );
		$order_key = sanitize_text_field( $get_download['order'] );

		// if ( $temp != $product_id) { // pretends that mixed cards (same product_id) are not getting redeemed multiple times
			$temp = $product_id;

			$product = wc_get_product( $product_id );
			$files = $product->get_files();

			// foreach ( $files as $file ) {
				$product_id    = $downloads[0]['product_id'];
				$price_level   = get_post_meta( $product_id, '_redeem_price_level', true );
				$quantity      = get_post_meta( $product_id, '_redeem_quantity', true );
				$price_level_2 = get_post_meta( $product_id, '_redeem_price_level_2', true );
				$expiry        = get_post_meta( $product_id, '_redeem_expiry', true );

				// if ( count($downloads) > 0 ) { echo '<pre>'; print_r($downloads); echo '</pre>'; exit; }

				if ( !empty( $price_level ) && !empty( $quantity ) && !empty( $expiry ) ) {
					require 'redeem.php';

					$wpdb->update(
						$wpdb->prefix.'woocommerce_downloadable_product_permissions', 
						array( 'downloads_remaining' => 0, 'download_count' => 1 ), 
						array( 'order_key' => $order_key ),
						array( '%d', '%d' ),
						array( '%s' )
					);

					if ( !empty( $price_level_2 ) && count( $downloads ) > 0 ) { // Mixed cards
						$price_level = $price_level_2;
						require 'redeem.php';
						break; // pretends that mixed cards (same product_id) are not getting redeemed multiple times
					}
				}
			// }
		// }
	}
}
// add_action( 'woocommerce_process_shop_order_meta', 'cbs_woocommerce_download_file_redeem', 10, 2 );
// add_action( 'save_post_shop_order', 'cbs_woocommerce_download_file_redeem', 1000, 3 );
add_action( 'woocommerce_after_order_object_save', 'cbs_woocommerce_download_file_redeem', 100, 2 ); */

function cbs_withdraw_redeemed_card( $order_id, $order ) {
	global $wpdb;
	$user_id = $order->get_user_id();
	$downloads = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."woocommerce_downloadable_product_permissions WHERE order_id = $order_id" );
	foreach ( $downloads as $download ) {
		$product_id = $download->product_id;
		$order_key = $download->order_key;

		$price_level   = get_post_meta( $product_id, '_redeem_price_level', true );
		$quantity      = get_post_meta( $product_id, '_redeem_quantity', true ) * -1; // Multiplicate by -1 to get negative quantity
		$price_level_2 = get_post_meta( $product_id, '_redeem_price_level_2', true );
		$expiry        = get_post_meta( $product_id, '_redeem_expiry', true );
		$expiry_end    = get_post_meta( $product_id, '_redeem_expiry_end', true );
		$video_url     = get_post_meta( $product_id, '_video_url', true );

		if ( !empty( $price_level ) && !empty( $quantity ) && ( !empty( $expiry ) || !empty( $expiry_end ) ) ) {
			require 'redeem.php';

			$wpdb->update(
				$wpdb->prefix.'woocommerce_downloadable_product_permissions', 
				array( 'downloads_remaining' => 1 ), 
				array( 'order_key' => $order_key ),
				array( '%d' ),
				array( '%s' )
			);

			if ( !empty( $price_level_2 ) ) { // Mixed cards
				$price_level = $price_level_2;
				require 'redeem.php';
				break;
			}
		}
	}
}
add_action( 'woocommerce_order_status_cancelled', 'cbs_withdraw_redeemed_card', 20, 2 );
add_action( 'woocommerce_order_status_refunded', 'cbs_withdraw_redeemed_card', 20, 2 );
add_action( 'woocommerce_order_status_failed', 'cbs_withdraw_redeemed_card', 20, 2 );

// Redirect to account page if download already redeemed
function cbs_wp_die_handler( $message, $title = '', $args = array() ) {
	if ( $message = __( 'Sorry, you have reached your download limit for this file', 'woocommerce' ) ) {
		$account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		wp_redirect( $account_url.'?message=limit' );
		exit;
	}

	die();
}
add_filter( 'wp_die_handler', function( $handler ) {
	return ( !is_admin() && isset($_REQUEST['download_file']) && isset($_REQUEST['order']) ) ? 'cbs_wp_die_handler' : $handler;
}, 10 );

// Check if settings are correct
function cbs_woocommerce_file_download_method() {
	// Check if supported download method is used
	$woocommerce_file_download_method = get_option( 'woocommerce_file_download_method' );
	$woocommerce_downloads_require_login = get_option( 'woocommerce_downloads_require_login' );
	// if ( ( $woocommerce_file_download_method != 'xsendfile' && $woocommerce_file_download_method != 'redirect' ) || $woocommerce_downloads_require_login != 'yes' ) {
	if ( ( $woocommerce_file_download_method != 'xsendfile' && $woocommerce_file_download_method != 'redirect' ) ) {
		?>
		<div class="error">
			<p><?php _e( 'The plugin "Course Booking System" uses the <a href="'.admin_url().'/admin.php?page=wc-settings&tab=products&section=downloadable">WooCommerce file download method setting</a> to redeem products. Please make sure that the setting is set to "X-Accel-Redirect / X-Sendfile" or "Redirect only (Insecure)". Please also make sure that the setting "Downloads Require Login" is checked.', 'course-booking-system' ) ?></p>
		</div>
		<?php
	}

	// Check if user can login via account page
	$woocommerce_enable_guest_checkout = get_option( 'woocommerce_enable_guest_checkout' );
	$woocommerce_enable_checkout_login_reminder = get_option( 'woocommerce_enable_checkout_login_reminder' );
	if ( $woocommerce_enable_guest_checkout != 'no' || $woocommerce_enable_checkout_login_reminder != 'yes' ) {
		?>
		<div class="error">
			<p><?php _e( 'The plugin "Course Booking System" uses the <a href="'.admin_url().'/admin.php?page=wc-settings&tab=account">WooCommerce user account</a> to redeem products. Please make sure that the setting for "Allow customers to place orders without an account" is not checked and the setting for "Allow customers to log into an existing account during checkout" is checked.', 'course-booking-system' ) ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'cbs_woocommerce_file_download_method' );

// Filter products by price level
function cbs_show_only_products_with_specific_metakey( $meta_query, $query ) {
	if ( !is_shop() || get_option( 'woocommerce_shop_page_display' ) ) return $meta_query;

	if ( isset( $_GET['price-level'] ) ) :
		$price_level = sanitize_text_field( $_GET['price-level'] );
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key' => '_redeem_price_level',
				'value' => $price_level,
				'compare' => 'EXISTS'
			), array(
				'key' => '_redeem_price_level_2',
				'value' => $price_level,
				'compare' => 'EXISTS'
			), array(
				'key' => '_subscription_price_level',
				'value' => $price_level,
				'compare' => 'EXISTS'
			)
		);
	endif;

	return $meta_query;
}
add_filter( 'woocommerce_product_query_meta_query', 'cbs_show_only_products_with_specific_metakey', 10, 2 );

function cbs_woocommerce_product_archive_description() {

	if ( isset( $_GET['price-level'] ) && !get_option( 'woocommerce_shop_page_display' ) ) :
		?>

		<div class="woocommerce-message"><?php _e( 'Thank you for your interest in a card for your desired course. The appropriate cards are shown below.', 'course-booking-system' ); ?><a href="<?= get_permalink( wc_get_page_id( 'shop' ) ) ?>"><?php _e( 'Show all products', 'course-booking-system' ); ?></a></div>

		<?php
	endif;
}
add_action( 'woocommerce_archive_description', 'cbs_woocommerce_product_archive_description', 10 );

// Cookies
function cbs_cookie_last_course_visited() {
	if ( !empty( $_GET['last-course-visited'] ) )
		setcookie( 'last-course-visited', urldecode( $_GET['last-course-visited'] ), current_time( 'timestamp' ) + HOUR_IN_SECONDS, '/' );
}
add_action( 'init', 'cbs_cookie_last_course_visited' );