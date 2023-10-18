<?php
// Register the custom product type after init
function cbs_register_product_type() {
	class WC_Product_Redemption extends WC_Product {
		public function __construct( $product ) {
			$this->product_type = 'redeem';

			$this->virtual = 'yes';
			$this->downloadable = 'yes';

			parent::__construct( $product );
		}

		public function get_type() {
			return 'redeem';
		}
	}

	class WC_Product_Subscription extends WC_Product {
		public function __construct( $product ) {
			$this->product_type = 'subscription';

			$this->virtual = 'yes';
			$this->downloadable = 'yes';

			parent::__construct( $product );
		}

		public function get_type() {
			return 'subscription';
		}
	}

	class WC_Product_Video extends WC_Product {
		public function __construct( $product ) {
			$this->product_type = 'video';

			$this->virtual = 'yes';
			$this->downloadable = 'yes';

			parent::__construct( $product );
		}

		public function get_type() {
			return 'video';
		}
	}
}
add_action( 'plugins_loaded', 'cbs_register_product_type' );

// Add to product type drop down
function cbs_add_product_type_selector( $types ){
	$types['redeem'] = __( 'Redemption', 'course-booking-system' );
	$types['subscription'] = __( 'Subscription', 'course-booking-system' );
	$types['video'] = __( 'Video', 'course-booking-system' );
	return $types;
}
add_filter( 'product_type_selector', 'cbs_add_product_type_selector' );

// Hide Attributes data panel
function cbs_hide_attributes_data_panel( $tabs ) {
	$tabs['inventory']['class'][] = 'show_if_redeem show_if_subscription show_if_video';
	$tabs['shipping']['class'][]  = 'hide_if_redeem hide_if_subscription hide_if_video';
	$tabs['attribute']['class'][] = 'hide_if_redeem hide_if_subscription hide_if_video';
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'cbs_hide_attributes_data_panel' );

// Show pricing fields for redeem product
function cbs_woocommerce_js() {
	if ( 'product' != get_post_type() ) :
		return;
	endif;
	?>

	<script>
	jQuery( document ).ready( function() {
		// General classes for the Redemption product type
		jQuery( '.options_group.pricing' ).addClass( 'show_if_redeem show_if_subscription show_if_video' ).show();
		jQuery( '#general_product_data .options_group.show_if_downloadable' ).addClass( 'hide_if_redeem hide_if_subscription hide_if_video' ).hide();
		jQuery( '#inventory_product_data .options_group.show_if_simple' ).addClass( 'show_if_redeem hide_if_subscription hide_if_video' ).show();
		jQuery( '#inventory_product_data ._sold_individually_field' ).addClass( 'show_if_redeem hide_if_subscription hide_if_video' ).show();

		// If user changes product type to redeem
		jQuery( '#product-type' ).change( function() {
			if ( jQuery( '#product-type option:selected' ).val() == 'redeem' ) {

				if ( jQuery( '.downloadable_files tbody' ).children().length == 0 ) { // add file if no file has been added to product yet
					jQuery( '.downloadable_files .insert' ).click();
				}

				jQuery( '#_virtual' ).prop( 'checked', true );
				jQuery( '#_downloadable' ).prop( 'checked', true );
				jQuery( '#_download_limit' ).val( 1 );

				if ( jQuery( '#_redeem_price_level' ).val().length === 0 ) {
					jQuery( '#_redeem_price_level' ).val( 1 );
				} if ( jQuery( '#_redeem_quantity' ).val().length === 0 ) {
					jQuery( '#_redeem_quantity' ).val( 10 );
				} if ( jQuery( '#_redeem_expiry' ).val().length === 0 ) {
					jQuery( '#_redeem_expiry' ).val( 6 );

					var now = new Date();
					var expiry_date = new Date( now.setMonth( now.getMonth() + 6 ) );
					jQuery( '#_download_expiry' ).val( Math.round( ( expiry_date - new Date() ) / ( 1000 * 60 * 60 * 24 ) ) );
				}
			} else if ( jQuery( '#product-type option:selected' ).val() == 'subscription' ) {

				if ( jQuery( '.downloadable_files tbody' ).children().length == 0 ) { // add file if no file has been added to product yet
					jQuery( '.downloadable_files .insert' ).click();
				}

				jQuery( '#_virtual' ).prop( 'checked', true );
				jQuery( '#_downloadable' ).prop( 'checked', true );
			} else if ( jQuery( '#product-type option:selected' ).val() == 'video' ) {

				if ( jQuery( '.downloadable_files tbody' ).children().length == 0 ) { // add file if no file has been added to product yet
					jQuery( '.downloadable_files .insert' ).click();
				}

				jQuery( '#_virtual' ).prop( 'checked', true );
				jQuery( '#_downloadable' ).prop( 'checked', true );
			} else {
				// jQuery( '#_virtual' ).prop( 'checked', false );
				// jQuery( '#_downloadable' ).prop( 'checked', false );
				// jQuery( '#_download_limit' ).val( '' );
				// jQuery( '#_download_expiry' ).val( '' );

				// jQuery( '#_redeem_price_level' ).val( '' );
				// jQuery( '#_redeem_quantity' ).val( '' );
				// jQuery( '#_redeem_expiry' ).val( '' );
			}
		});

		// Onload: check if product has redeem attributes to set the right product type
		if ( jQuery( '#_redeem_price_level' ).val() && jQuery( '#_redeem_quantity' ).val() && ( jQuery( '#_redeem_expiry' ).val() || jQuery( '#_redeem_expiry_end' ).val() ) ) {
			jQuery( '#product-type' ).val( 'redeem' );
		}
		jQuery( '#product-type' ).trigger( 'change' );

		// Flatrate
		if ( jQuery( '#_redeem_flat' ).is( ':checked' ) ) {
			jQuery( '._redeem_quantity_field' ).hide();

			jQuery( '._redeem_expiry_fixed_field' ).show();
		} else {
			jQuery( '._redeem_quantity_field' ).show();

			jQuery( '._redeem_expiry_fixed_field' ).hide();
		}

		jQuery( '#_redeem_flat' ).change( function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '._redeem_quantity_field' ).hide();
				jQuery( '#_redeem_quantity' ).val( 99999 );

				jQuery( '._redeem_expiry_fixed_field' ).show();
			} else {
				jQuery( '._redeem_quantity_field' ).show();
				jQuery( '#_redeem_quantity' ).val( 10 );

				jQuery( '._redeem_expiry_fixed_field' ).hide();
			}	
		});

		// Fixed end date
		if ( jQuery( '#_redeem_expiry_fixed' ).is( ':checked' ) ) {
			jQuery( '._redeem_expiry_field' ).hide();

			jQuery( '._redeem_expiry_end_field' ).show();

			jQuery( '._sold_individually_field' ).hide();
		} else {
			jQuery( '._redeem_expiry_field' ).show();

			jQuery( '._redeem_expiry_end_field' ).hide();

			jQuery( '._sold_individually_field' ).show();
		}

		jQuery( '#_redeem_expiry_fixed' ).change( function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '._redeem_expiry_field' ).hide();
				jQuery( '#_redeem_expiry' ).val( '' );

				jQuery( '._redeem_expiry_end_field' ).show();

				var d = new Date();
				jQuery( '#_redeem_expiry_end' ).val( d.getFullYear() + '-' + ( d.getMonth() + 2 ) + '-01' );

				jQuery( '._sold_individually_field' ).hide();
				jQuery( '#_sold_individually' ).prop( 'checked', true );
			} else {
				jQuery( '._redeem_expiry_field' ).show();
				jQuery( '#_redeem_expiry' ).val( 6 );

				jQuery( '._redeem_expiry_end_field' ).hide();
				jQuery( '#_redeem_expiry_end' ).val( '' );

				jQuery( '._sold_individually_field' ).show();
			}	
		});

		// Set the redeem link
		var title       = jQuery( '#title' ).val() + ' ' + '<?php _e( 'redeem', 'course-booking-system' ); ?>';
		// var url         = '<?= get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>?message=purchase';
		var url         = '<?= wc_placeholder_img_src() ?>?message=purchase';
		var price_level = 1;
		var quantity    = 10;
		var expiry      = 6;
		jQuery( '#redeem_options input:not(#_redeem_price_level_2)' ).change( function() {
			title       = jQuery( '#title' ).val() + ' ' + '<?php _e( 'redeem', 'course-booking-system' ); ?>';
			price_level = jQuery( '#_redeem_price_level' ).val();
			quantity    = jQuery( '#_redeem_quantity' ).val();
			expiry      = jQuery( '#_redeem_expiry' ).val();
			expiry_end  = jQuery( '#_redeem_expiry_end' ).val();

			jQuery( '.downloadable_files tbody tr:first-child .file_name input[type="text"]' ).val( title );
			jQuery( '.downloadable_files tbody tr:first-child .file_url input[type="text"]' ).val( url+'&price_level='+price_level+'&quantity='+quantity+'&expiry='+expiry );

			if ( expiry_end !== 'undefined' && expiry_end !== '' ) {
				jQuery( '#_download_expiry' ).val( expiry_end );
			} else {
				var now = new Date();
				var expiry_date = new Date( now.setMonth( now.getMonth() + parseInt( expiry ) ) );
				jQuery( '#_download_expiry' ).val( Math.round( ( expiry_date - new Date() ) / ( 1000 * 60 * 60 * 24 ) ) );
			}

			if ( jQuery( '#_redeem_price_level_mixed' ).is( ':checked' ) ) {
				price_level_2 = jQuery( '#_redeem_price_level_2' ).val();
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_name input[type="text"]' ).val( title );
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_url input[type="text"]' ).val( url+'&price_level='+price_level_2+'&quantity='+quantity+'&expiry='+expiry );
			} else if ( jQuery( '#_redeem_price_level_upgrade' ).is( ':checked' ) ) {
				price_level_2 = jQuery( '#_redeem_price_level_2' ).val();
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_name input[type="text"]' ).val( title );
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_url input[type="text"]' ).val( url+'&price_level='+price_level_2+'&quantity=-'+quantity+'&expiry='+expiry );
			}
		});

		// Mixed cards und upgrade card
		if ( jQuery( '#_redeem_price_level_mixed' ).is( ':checked' ) ) {
			jQuery( '._redeem_price_level_2_field' ).show();
			jQuery( '._redeem_price_level_upgrade_field' ).hide();
		} else if ( jQuery( '#_redeem_price_level_upgrade' ).is( ':checked' ) ) {
			jQuery( '._redeem_price_level_2_field' ).show();
			jQuery( '._redeem_price_level_mixed_field' ).hide();
			jQuery( '._redeem_flat_field' ).hide();
		} else {
			jQuery( '._redeem_price_level_2_field' ).hide();
			jQuery( '._redeem_flat_field' ).show();
		}

		jQuery( '#_redeem_price_level_mixed' ).change( function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '._redeem_price_level_2_field' ).show();

				jQuery( '._redeem_price_level_upgrade_field' ).hide();
				jQuery( '#_redeem_price_level_upgrade' ).prop( 'checked', false );

				if ( jQuery( '.downloadable_files tbody' ).children().length == 1 ) { // add file if second file has not been added to product yet
					jQuery( '.downloadable_files .insert' ).click();
				}
			} else {
				jQuery( '._redeem_price_level_2_field' ).hide();
				jQuery( '._redeem_price_level_2_field input' ).val( '' );

				jQuery( '._redeem_price_level_upgrade_field' ).show();

				jQuery( '._redeem_flat_field' ).show();

				if ( jQuery( '.downloadable_files tbody' ).children().length == 2 ) { // remove second file
					jQuery( '.downloadable_files tbody tr:nth-child(2) .delete' ).click();
				}
			}	
		});

		jQuery( '#_redeem_price_level_upgrade' ).change( function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '._redeem_price_level_2_field' ).show();

				jQuery( '._redeem_price_level_mixed_field' ).hide();
				jQuery( '#_redeem_price_level_mixed' ).prop( 'checked', false );

				jQuery( '._redeem_flat_field' ).hide();
				jQuery( '#_redeem_flat' ).prop( 'checked', false );

				if ( jQuery( '.downloadable_files tbody' ).children().length == 1 ) { // add file if second file has not been added to product yet
					jQuery( '.downloadable_files .insert' ).click();
				}
			} else {
				jQuery( '._redeem_price_level_2_field' ).hide();
				jQuery( '._redeem_price_level_2_field input' ).val( '' );

				jQuery( '._redeem_price_level_mixed_field' ).show();

				jQuery( '._redeem_flat_field' ).show();

				if ( jQuery( '.downloadable_files tbody' ).children().length == 2 ) { // remove second file
					jQuery( '.downloadable_files tbody tr:nth-child(2) .delete' ).click();
				}
			}	
		});

		jQuery( '#redeem_options input#_redeem_price_level_2' ).change( function() {
			title         = jQuery( '#title' ).val() + ' ' + '<?php _e( 'redeem', 'course-booking-system' ); ?>';
			price_level_2 = jQuery( '#_redeem_price_level_2' ).val();
			quantity      = jQuery( '#_redeem_quantity' ).val();
			expiry        = jQuery( '#_redeem_expiry' ).val();

			if ( jQuery( '#_redeem_price_level_mixed' ).is( ':checked' ) ) {
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_name input[type="text"]' ).val( title );
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_url input[type="text"]' ).val( url+'&price_level='+price_level_2+'&quantity='+quantity+'&expiry='+expiry );
			} else if ( jQuery( '#_redeem_price_level_upgrade' ).is( ':checked' ) ) {
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_name input[type="text"]' ).val( title );
				jQuery( '.downloadable_files tbody tr:nth-child(2) .file_url input[type="text"]' ).val( url+'&price_level='+price_level_2+'&quantity=-'+quantity+'&expiry='+expiry );
			}
		});

		// Subscriptions
		if ( jQuery( '#_subscription_start_fixed' ).is( ':checked' ) ) {
			jQuery( '._subscription_start_field' ).show();
		} else {
			jQuery( '._subscription_start_field' ).hide();
		}

		jQuery( '#_subscription_start_fixed' ).change( function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '._subscription_start_field' ).show();
			} else {
				jQuery( '._subscription_start_field' ).hide();
				jQuery( '#_subscription_start' ).val( '' );
			}	
		});

		jQuery( '#subscription_options input' ).change( function() {
			title     = jQuery( '#title' ).val() + ' ' + '<?php _e( 'redeem', 'course-booking-system' ); ?>';
			url       = '<?= wc_placeholder_img_src() ?>?message=video';
			expiry    = jQuery( '#_video_expiry' ).val();

			jQuery( '.downloadable_files tbody tr:first-child .file_name input[type="text"]' ).val( title );
			jQuery( '.downloadable_files tbody tr:first-child .file_url input[type="text"]' ).val( url );

			jQuery( '#_sold_individually' ).prop( 'checked', true );
			jQuery( '#_download_expiry' ).val( expiry );
		});

		// Video
		jQuery( '#video_options input' ).change( function() {
			title     = jQuery( '#title' ).val() + ' ' + '<?php _e( 'watch', 'course-booking-system' ); ?>';
			url       = '<?= wc_placeholder_img_src() ?>?message=video';
			video_url = jQuery( '#_video_url' ).val();
			expiry    = jQuery( '#_video_expiry' ).val();

			jQuery( '.downloadable_files tbody tr:first-child .file_name input[type="text"]' ).val( title );
			jQuery( '.downloadable_files tbody tr:first-child .file_url input[type="text"]' ).val( url );

			jQuery( '#_sold_individually' ).prop( 'checked', true );
			jQuery( '#_download_expiry' ).val( expiry );
		});
	});
	</script>

	<?php
}
add_action( 'admin_footer', 'cbs_woocommerce_js' );

function cbs_custom_field_video_ids() {
	$video_ids = array();
	$args = array ( 
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array( 
			array( 
				'key' => '_video_url', 
				'value' => '',
				'compare' => '!='
			)
		)
	);
	$videos = get_posts( $args );

	if ( !empty( $videos ) ) :
		foreach ( $videos AS $video ) :
			$video_ids[$video->ID] = $video->post_title;
		endforeach;

		woocommerce_wp_multi_select( array(
			'id'			=> '_video_ids',
			'name'			=> '_video_ids[]',
			'class'			=> '',
			'label'			=> __( 'Additional videos', 'course-booking-system' ),
			'desc_tip'		=> 'true',
			'description'	=> __( 'Additional videos are products that are additionally made available after the purchase of this product in the video library.', 'course-booking-system' ),
			'options'		=> $video_ids
		) );
	endif;
}
add_action( 'woocommerce_product_options_related', 'cbs_custom_field_video_ids' );

// Save custom multi-select fields to database
function cbs_save_custom_field_video_ids( $post_id ) {
	// if ( isset( $_POST['_video_ids'] ) ) {
		$post_data = $_POST['_video_ids'];

		$video_ids = array();
		if ( is_array( $post_data ) && sizeof( $post_data ) > 0 ) {
			foreach ( $post_data as $value ) {
				$video_ids[] = esc_attr( $value );
			}
		}
		update_post_meta( $post_id, '_video_ids', $video_ids );
	// }
}
add_action( 'woocommerce_process_product_meta', 'cbs_save_custom_field_video_ids', 30, 1 );

// Add a custom product tab
function cbs_custom_product_tabs( $tabs ) {
	$tabs['redeem'] = array(
		'label'		=> __( 'Redemption', 'course-booking-system' ),
		'target'	=> 'redeem_options',
		'class'		=> array( 'show_if_redeem' )
	);

	$tabs['subscription'] = array(
		'label'		=> __( 'Subscription', 'course-booking-system' ),
		'target'	=> 'subscription_options',
		'class'		=> array( 'show_if_subscription' )
	);

	$tabs['video'] = array(
		'label'		=> __( 'Video', 'course-booking-system' ),
		'target'	=> 'video_options',
		'class'		=> array( 'show_if_video' )
	);

	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'cbs_custom_product_tabs' );

// Content of the product tab
function cbs_options_product_tab_content() {
	global $post;
	?>

	<div id="redeem_options" class="panel woocommerce_options_panel">
		<div class="options_group">
			<?php
			if ( cbs_is_licensed() ) :
				woocommerce_wp_text_input( array(
					'id'			=> '_redeem_price_level',
					'label'			=> __( 'Price Level', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ),
					'type'			=> 'number',
					'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5' )
				) );

				woocommerce_wp_checkbox( array(
					'id'			=> '_redeem_price_level_mixed',
					'label'			=> __( 'Mixed cards', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'Mixed cards are cards which contain the same quantity of two different price levels in one card.', 'course-booking-system' )
				) );

				woocommerce_wp_checkbox( array(
					'id'			=> '_redeem_price_level_upgrade',
					'label'			=> __( 'Upgrade card', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'With an upgrade card a customer is able to upgrade (or downgrade) a already purchased card to another price level. The card is converted from the 2nd entry to the price level to the first entry.', 'course-booking-system' )
				) );

				woocommerce_wp_text_input( array(
					'id'			=> '_redeem_price_level_2',
					'label'			=> '2. '.__( 'Price Level', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ),
					'type'			=> 'number',
					'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5' )
				) );
			else :
				woocommerce_wp_text_input( array(
					'id'			=> '_redeem_price_level',
					'label'			=> __( 'Price Level', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ),
					'type'			=> 'number',
					'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5', 'readonly' => 'readonly' )
				) );

				woocommerce_wp_checkbox( array(
					'id'			=> '_redeem_price_level_mixed',
					'label'			=> __( 'Mixed cards', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'Mixed cards are cards which contain the same quantity of two different price levels in one card.', 'course-booking-system' ),
					'custom_attributes' => array( 'disabled' => 'disabled' )
				) );

				woocommerce_wp_checkbox( array(
					'id'			=> '_redeem_price_level_upgrade',
					'label'			=> __( 'Upgrade card', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'With an upgrade card a customer is able to upgrade (or downgrade) a already purchased card to another price level. The card is converted from the 2nd entry to the price level to the first entry.', 'course-booking-system' ),
					'custom_attributes' => array( 'disabled' => 'disabled' )
				) );

				woocommerce_wp_text_input( array(
					'id'			=> '_redeem_price_level_2',
					'label'			=> '2. '.__( 'Price Level', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ),
					'type'			=> 'number',
					'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5', 'readonly' => 'readonly' )
				) );
			endif;

			woocommerce_wp_checkbox( array(
				'id'			=> '_redeem_flat',
				'label'			=> __( 'Flatrate', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'With a flat rate card, customers can book as many courses as they want within the card duration.', 'course-booking-system' )
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_redeem_quantity',
				'label'			=> __( 'Number of appointments per card', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'The number of appointments per card determines how many appointments can be redeemed with a card. With a 10-card, a customer can, for example, redeem 10 appointments.', 'course-booking-system' ),
				'type'			=> 'number',
				'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '99999' )
			) );

			woocommerce_wp_checkbox( array(
				'id'			=> '_redeem_expiry_fixed',
				'label'			=> __( 'Fixed end date', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'With a fixed end date, cards have a date until when the card is valid.', 'course-booking-system' )
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_redeem_expiry',
				'label'			=> __( 'Card duration (in months)', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Number of months how long the card is valid.', 'course-booking-system' ),
				'type'			=> 'number',
				'custom_attributes' => array( 'step' => '0.01', 'min' => '0', 'max' => '24' )
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_redeem_expiry_end',
				'label'			=> __( 'Expiry end date', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Date until when the card is valid.', 'course-booking-system' ),
				'type'			=> 'date'
			) );

			woocommerce_wp_checkbox( array(
				'id'			=> '_purchasable_once',
				'label'			=> __( 'Sell this product only once per customer', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Once a customer purchases this product, it cannot be purchased again.', 'course-booking-system' )
			) );
			?>
		</div>
	</div>

	<div id="subscription_options" class="panel woocommerce_options_panel">
		<div class="options_group">
			<?php
			if ( cbs_is_licensed() ) :
				woocommerce_wp_text_input( array(
					'id'			=> '_subscription_price_level',
					'label'			=> __( 'Price Level', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ),
					'type'			=> 'number',
					'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5' )
				) );
			else :
				woocommerce_wp_text_input( array(
					'id'			=> '_subscription_price_level',
					'label'			=> __( 'Price Level', 'course-booking-system' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The price level determines which card customers can use to register for which course. With the card from price level 2, for example, a customer can only register for courses with price level 2.', 'course-booking-system' ),
					'type'			=> 'number',
					'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5', 'readonly' => 'readonly' )
				) );
			endif;

			woocommerce_wp_text_input( array(
				'id'			=> '_subscription_expiry',
				'label'			=> __( 'Subscription duration (in months)', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Number of months how long the subscription is valid.', 'course-booking-system' ),
				'type'			=> 'number',
				'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '24' )
			) );

			woocommerce_wp_checkbox( array(
				'id'			=> '_subscription_start_fixed',
				'label'			=> __( 'Fixed start date', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'With a fixed start date, subscriptions have a date from when the subscription is valid. Otherwise, the subscription begins immediately after purchase.', 'course-booking-system' )
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_subscription_start',
				'label'			=> __( 'Subscription start date', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Date from when the subscription is valid.', 'course-booking-system' ),
				'type'			=> 'date'
			) );
			?>
		</div>
	</div>

	<div id="video_options" class="panel woocommerce_options_panel">
		<div class="options_group">
			<?php
			woocommerce_wp_text_input( array(
				'id'			=> '_video_url',
				'label'			=> __( 'Video URL', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Use modern video formats like mp4. If the url does not point to an video file the user gets redirected to this url.', 'course-booking-system' ),
				'type'			=> 'text',
				'placeholder'	=> 'https://'
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_video_url_password',
				'label'			=> __( 'Password', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'If a password is required to access the online course, enter it here.', 'course-booking-system' ),
				'type'			=> 'text'
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_video_expiry',
				'label'			=> __( 'Video duration (in days)', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Number of days the video will be available. Leave empty if you do not want the video to expire.', 'course-booking-system' ),
				'type'			=> 'number',
				'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '99999' )
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_video_price_level',
				'label'			=> __( 'Video price level', 'course-booking-system' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'This video can be redeemed with an existing card. Leave empty to not allow card redemption for this video.', 'course-booking-system' ),
				'type'			=> 'number',
				'custom_attributes' => array( 'step' => '1', 'min' => '1', 'max' => '5' )
			) );
			?>
		</div>
	</div>

	<?php
}
add_action( 'woocommerce_product_data_panels', 'cbs_options_product_tab_content' );

// Save the custom fields
function cbs_save_custom_tab_field_redeem( $post_id ) {
	if ( isset( $_POST['_redeem_price_level'] ) ) :
		update_post_meta( $post_id, '_redeem_price_level', sanitize_text_field( $_POST['_redeem_price_level'] ) );
	endif;

	if ( isset( $_POST['_redeem_price_level_mixed'] ) ) :
		update_post_meta( $post_id, '_redeem_price_level_mixed', sanitize_text_field( $_POST['_redeem_price_level_mixed'] ) );
	else :
		update_post_meta( $post_id, '_redeem_price_level_mixed', '' );
	endif;

	if ( isset( $_POST['_redeem_price_level_upgrade'] ) ) :
		update_post_meta( $post_id, '_redeem_price_level_upgrade', sanitize_text_field( $_POST['_redeem_price_level_upgrade'] ) );
	else :
		update_post_meta( $post_id, '_redeem_price_level_upgrade', '' );
	endif;

	if ( isset( $_POST['_redeem_price_level_2'] ) ) :
		update_post_meta( $post_id, '_redeem_price_level_2', sanitize_text_field( $_POST['_redeem_price_level_2'] ) );
	endif;

	if ( isset( $_POST['_redeem_flat'] ) ) :
		update_post_meta( $post_id, '_redeem_flat', sanitize_text_field( $_POST['_redeem_flat'] ) );
	else :
		update_post_meta( $post_id, '_redeem_flat', '' );
	endif;

	if ( isset( $_POST['_redeem_quantity'] ) ) :
		update_post_meta( $post_id, '_redeem_quantity', sanitize_text_field( $_POST['_redeem_quantity'] ) );
	endif;

	if ( isset( $_POST['_redeem_expiry_fixed'] ) ) :
		update_post_meta( $post_id, '_redeem_expiry_fixed', sanitize_text_field( $_POST['_redeem_expiry_fixed'] ) );
	else :
		update_post_meta( $post_id, '_redeem_expiry_fixed', '' );
	endif;

	if ( isset( $_POST['_redeem_expiry'] ) ) :
		update_post_meta( $post_id, '_redeem_expiry', sanitize_text_field( $_POST['_redeem_expiry'] ) );
	endif;

	if ( isset( $_POST['_redeem_expiry_end'] ) ) :
		update_post_meta( $post_id, '_redeem_expiry_end', sanitize_text_field( $_POST['_redeem_expiry_end'] ) );
	endif;

	update_post_meta( $post_id, '_purchasable_once', sanitize_text_field( $_POST['_purchasable_once'] ) );
}
add_action( 'woocommerce_process_product_meta_redeem', 'cbs_save_custom_tab_field_redeem' );

function cbs_save_custom_tab_field_subscription( $post_id ) {
	if ( isset( $_POST['_subscription_price_level'] ) ) :
		update_post_meta( $post_id, '_subscription_price_level', sanitize_text_field( $_POST['_subscription_price_level'] ) );
	endif;

	if ( isset( $_POST['_subscription_expiry'] ) ) :
		update_post_meta( $post_id, '_subscription_expiry', sanitize_text_field( $_POST['_subscription_expiry'] ) );
	endif;

	if ( isset( $_POST['_subscription_start_fixed'] ) ) :
		update_post_meta( $post_id, '_subscription_start_fixed', sanitize_text_field( $_POST['_subscription_start_fixed'] ) );
	else :
		update_post_meta( $post_id, '_subscription_start_fixed', '' );
	endif;

	if ( isset( $_POST['_subscription_start'] ) ) :
		update_post_meta( $post_id, '_subscription_start', sanitize_text_field( $_POST['_subscription_start'] ) );
	endif;
}
add_action( 'woocommerce_process_product_meta_subscription', 'cbs_save_custom_tab_field_subscription' );

function cbs_save_custom_tab_field_video( $post_id ) {
	if ( isset( $_POST['_video_url'] ) ) :
		update_post_meta( $post_id, '_video_url', sanitize_text_field( $_POST['_video_url'] ) );
	endif;

	if ( isset( $_POST['_video_url_password'] ) ) :
		update_post_meta( $post_id, '_video_url_password', sanitize_text_field( $_POST['_video_url_password'] ) );
	endif;

	if ( isset( $_POST['_video_expiry'] ) ) :
		update_post_meta( $post_id, '_video_expiry', sanitize_text_field( $_POST['_video_expiry'] ) );
	endif;

	if ( isset( $_POST['_video_price_level'] ) ) :
		update_post_meta( $post_id, '_video_price_level', sanitize_text_field( $_POST['_video_price_level'] ) );
	endif;
}
add_action( 'woocommerce_process_product_meta_video', 'cbs_save_custom_tab_field_video' );

// Add missing add to cart link to product single
add_action( 'woocommerce_subscription_add_to_cart', function() {
	do_action( 'woocommerce_simple_add_to_cart' );
});

add_action( 'woocommerce_video_add_to_cart', function() {
	do_action( 'woocommerce_simple_add_to_cart' );
});

// Add product meta
function cbs_product_meta() {
	global $product;
	$date_format = get_option( 'date_format' );

	$subscription_expiry = get_post_meta( $product->get_id(), '_subscription_expiry', true );
	if ( !empty( $subscription_expiry ) ) {
		echo '<span class="subscription">'.__( 'If you already have a valid subscription or are registered in a subscription course, its validity will be automatically extended after successful purchase. Otherwise, the desired course can be set in the account immediately after purchase.', 'course-booking-system' ).'</span>'.PHP_EOL;

		$subscription_start = get_post_meta( $product->get_id(), '_subscription_start', true );
		if ( !empty( $subscription_start ) ) {
			echo '<span class="subscription-expiry">'.__( 'Subscription duration:', 'course-booking-system' ).' '.sprintf( _n( '%s month', '%s months', $subscription_expiry, 'course-booking-system' ), number_format_i18n( $subscription_expiry ) ).' '.__( 'from', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $subscription_start ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( $subscription_start.' +'.$subscription_expiry.' months' ) ).'</span>'.PHP_EOL;
		} else {
			echo '<span class="subscription-expiry">'.__( 'Subscription duration:', 'course-booking-system' ).' '.sprintf( _n( '%s month', '%s months', $subscription_expiry, 'course-booking-system' ), number_format_i18n( $subscription_expiry ) ).' '.__( 'until', 'course-booking-system' ).' '.date_i18n( $date_format, strtotime( '+'.$subscription_expiry.' months' ) ).'</span>'.PHP_EOL;
		}
	}

	$video_expiry = get_post_meta( $product->get_id(), '_video_expiry', true );
	if ( !empty( $video_expiry ) ) {
		echo '<span class="video-expiry">'.__( 'Video duration (in days):', 'course-booking-system' ).' '.$video_expiry.'</span>'.PHP_EOL;
	}

	$video_price_level = get_post_meta( $product->get_id(), '_video_price_level', true );
	if ( !empty( $video_price_level ) ) {
		$price_level_names = array( 1 => get_option( 'course_booking_system_price_level_title' ), 2 => get_option( 'course_booking_system_price_level_title_2' ), 3 => get_option( 'course_booking_system_price_level_title_3' ), 4 => get_option( 'course_booking_system_price_level_title_4' ), 5 => get_option( 'course_booking_system_price_level_title_5' ) );
		$price_level_name  = $price_level_names[$video_price_level];
		echo '<span class="video-price-level">'.__( 'Card redemption:', 'course-booking-system' ).' '.$price_level_name.'</span>'.PHP_EOL;
	}
}
add_action( 'woocommerce_product_meta_end', 'cbs_product_meta' );

// Sell products only once per customer
function cbs_products_purchasable_once( $purchasable, $product ) {
	$targeted_products = wc_get_products( array(
		'limit'      => -1,
		'status'     => 'publish',
		'return'     => 'ids',
		'meta_key'   => '_purchasable_once',
		'meta_value' => 'yes'
	) );

	if ( !is_user_logged_in() || $product->is_type( 'variable' ) || empty( $targeted_products ) )
		return $purchasable;

	$user = wp_get_current_user();
	$product_id = $product->get_id();
	if ( in_array( $product_id, $targeted_products ) && wc_customer_bought_product( $user->user_email, $user->ID, $product_id ) )
		$purchasable = false;

	return $purchasable;
}
add_filter( 'woocommerce_variation_is_purchasable', 'cbs_products_purchasable_once', 10, 2 );
add_filter( 'woocommerce_is_purchasable', 'cbs_products_purchasable_once', 10, 2 );