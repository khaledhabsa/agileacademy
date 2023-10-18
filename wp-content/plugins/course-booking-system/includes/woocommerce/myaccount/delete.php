<?php
function cbs_account_add_link( $menu_items ) {
	if ( current_user_can( 'manage_options' ) )
		return $menu_items;

	$menu_items['delete'] = __( 'Delete account', 'course-booking-system' );

	return $menu_items;
}
add_filter( 'woocommerce_account_menu_items', 'cbs_account_add_link' );

function cbs_account_get_endpoint_url( $url, $endpoint, $value, $permalink ) {
	if ( $endpoint === 'delete' ) {
		$url = add_query_arg( 'wc-api', 'wc-delete-account', home_url() ); 
		$url = wp_nonce_url( $url, 'wc_delete_user' ); 
	}

	return $url;
}
add_filter( 'woocommerce_get_endpoint_url', 'cbs_account_get_endpoint_url', 10, 4 );

function cbs_account_delete() {
	if ( !current_user_can( 'manage_options' ) ) {
		$security_check_result = check_admin_referer( 'wc_delete_user' );
		if ( $security_check_result ) {
			require_once( ABSPATH.'wp-admin/includes/user.php' );
			wp_delete_user( get_current_user_id() );
			wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'?message=delete' );
			die();
		}
	}

	wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
	die();
}
add_action( 'woocommerce_api_'.strtolower( 'wc-delete-account' ), 'cbs_account_delete' ); 