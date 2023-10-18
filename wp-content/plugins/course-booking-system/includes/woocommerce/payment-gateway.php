<?php
function cbs_init_redemption_gateway_class() {

	class WC_Gateway_Custom extends WC_Payment_Gateway {

		public $domain;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {

			$this->domain = 'course-booking-system';

			$this->id                 = 'redemption';
			$this->icon               = apply_filters('woocommerce_redemption_gateway_icon', '');
			$this->has_fields         = false;
			$this->method_title       = __( 'Card redemption', $this->domain );
			$this->method_description = __( 'Allows payment via an existing and valid card of the Course Booking System. This payment method is only possible for videos. If a customer does not have a valid card, this payment method will not be offered.', $this->domain );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables
			$this->title		= $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );
			$this->order_status = $this->get_option( 'order_status', 'completed' );

			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

			// Customer emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}

		/**
		 * Initialise Gateway Settings Form Fields.
		 */
		public function init_form_fields() {

			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable card redemption', $this->domain ),
					'default' => 'yes'
				),
				'title' => array(
					'title'       => __( 'Title', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
					'default'     => __( 'Card redemption', $this->domain ),
					'desc_tip'    => true,
				),
				'order_status' => array(
					'title'       => __( 'Order Status', 'woocommerce' ),
					'type'        => 'select',
					'class'       => 'wc-enhanced-select',
					'description' => __( 'Choose which order status should be applied after a customer has chosen to pay by this payment method.', $this->domain ),
					'default'     => 'wc-completed',
					'desc_tip'    => true,
					'options'     => wc_get_order_statuses()
				),
				'description' => array(
					'title'       => __( 'Description', 'woocommerce' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
					'default'     => __( 'Use your existing card to redeem. Credit will be deducted from your card according to the number of videos.', $this->domain ),
					'desc_tip'    => true,
				),
				'instructions' => array(
					'title'       => __( 'Instructions', 'woocommerce' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woocommerce' ),
					'default'     => __( 'Paid by card redemption.', $this->domain ),
					'desc_tip'    => true,
				),
			);
		}

		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions )
				echo wpautop( wptexturize( $this->instructions ) );
		}

		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			if ( $this->instructions && !$sent_to_admin && 'redemption' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
		}

		public function payment_fields(){
			if ( $description = $this->get_description() ) {
				echo wpautop( wptexturize( $description ) );
			}
			/* ?>
			<div id="redemption_input">
				<p class="form-row form-row-wide">
					<label for="transaction" class=""><?php _e( 'Transaction ID', $this->domain); ?></label>
					<input type="text" class="" name="transaction" id="transaction" placeholder="" value="">
				</p>
			</div>
			<?php */
		}

		/**
		 * Process the payment and return the result.
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );

			$status = 'wc-' === substr( $this->order_status, 0, 3 ) ? substr( $this->order_status, 3 ) : $this->order_status;

			// Set order status
			$order->update_status( $status, __( 'Payed by card redemption payment method.', $this->domain ) );

			// Redeem cards
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$product = $cart_item['data'];
				$price_level = get_post_meta( $product->get_id(), '_video_price_level', true );

				$date = date( 'Y-m-d' );
				$user_id = get_current_user_id();
				$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );
				if ( is_user_logged_in() && $price_level == 5 ) {
					$card_name = 'card_5';
					$expire_name = 'expire_5';

					$card = get_the_author_meta( $card_name, $user_id );
					$expire = get_the_author_meta( $expire_name, $user_id );

					$flat = get_the_author_meta( 'flat_5', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_5', $user_id );
				} else if ( is_user_logged_in() && $price_level == 4 ) {
					$card_name = 'card_4';
					$expire_name = 'expire_4';

					$card = get_the_author_meta( $card_name, $user_id );
					$expire = get_the_author_meta( $expire_name, $user_id );

					if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_5';
						$expire_name = 'expire_5';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					}

					$flat = get_the_author_meta( 'flat_4', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_4', $user_id );
				} else if ( is_user_logged_in() && $price_level == 3 ) {
					$card_name = 'card_3';
					$expire_name = 'expire_3';

					$card = get_the_author_meta( $card_name, $user_id );
					$expire = get_the_author_meta( $expire_name, $user_id );

					if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_4';
						$expire_name = 'expire_4';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_5';
						$expire_name = 'expire_5';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					}

					$flat = get_the_author_meta( 'flat_3', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_3', $user_id );
				} else if ( is_user_logged_in() && $price_level == 2 ) {
					$card_name = 'card_2';
					$expire_name = 'expire_2';

					$card = get_the_author_meta( $card_name, $user_id );
					$expire = get_the_author_meta( $expire_name, $user_id );

					if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_3';
						$expire_name = 'expire_3';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_4';
						$expire_name = 'expire_4';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_5';
						$expire_name = 'expire_5';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					}

					$flat = get_the_author_meta( 'flat_2', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire_2', $user_id );
				} else if ( is_user_logged_in() ) {
					$card_name = 'card';
					$expire_name = 'expire';

					$card = get_the_author_meta( $card_name, $user_id );
					$expire = get_the_author_meta( $expire_name, $user_id );

					if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_2';
						$expire_name = 'expire_2';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_3';
						$expire_name = 'expire_3';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_4';
						$expire_name = 'expire_4';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					} if ( $price_level_for_lower_course && ( $card <= 0 || date( 'Y-m-d', strtotime($expire) ) < $date ) ) {
						$card_name = 'card_5';
						$expire_name = 'expire_5';

						$card = get_the_author_meta( $card_name, $user_id );
						$expire = get_the_author_meta( $expire_name, $user_id );
					}

					$flat = get_the_author_meta( 'flat', $user_id );
					$flat_expire = get_the_author_meta( 'flat_expire', $user_id );
				}

				if ( $flat && date( 'Y-m-d', strtotime($flat_expire) ) >= $date ) {
					// Pay by flat
				} else {
					$card--;
					update_user_meta( $user_id, $card_name, $card );
					cbs_log( $user_id, $card_name, $card, $cart_item['product_id'], __FUNCTION__ );
				}
			}

			// Reduce stock levels
			wc_reduce_stock_levels( $order->get_id() );

			// Remove cart
			WC()->cart->empty_cart();

			// Return thankyou redirect
			return array(
				'result'	=> 'success',
				'redirect'  => $this->get_return_url( $order )
			);
		}
	}
}
add_action( 'plugins_loaded', 'cbs_init_redemption_gateway_class' );

function cbs_add_redemption_gateway_class( $methods ) {
	$methods[] = 'WC_Gateway_Custom'; 
	return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'cbs_add_redemption_gateway_class' );

function cbs_conditional_payment_gateways( $available_gateways ) {
	if ( is_admin() ) 
		return $available_gateways;

	global $woocommerce;
	$video_in_cart = false;

	if ( !is_null( WC()->cart ) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'];
			$price_level = get_post_meta( $product->get_id(), '_video_price_level', true );

			$date = date( 'Y-m-d' );
			$user_id = get_current_user_id();
			$price_level_for_lower_course = get_option( 'course_booking_system_price_level_for_lower_course' );
			if ( is_user_logged_in() && $price_level == 5 ) {
				$card = get_the_author_meta( 'card_5', $user_id );
				$expire = get_the_author_meta( 'expire_5', $user_id );

				$flat = get_the_author_meta( 'flat_5', $user_id );
				$flat_expire = get_the_author_meta( 'flat_expire_5', $user_id );
			} else if ( is_user_logged_in() && $price_level == 4 ) {
				$card = get_the_author_meta( 'card_4', $user_id );
				$expire = get_the_author_meta( 'expire_4', $user_id );

				if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_5', $user_id );
					$expire = get_the_author_meta( 'expire_5', $user_id );
				}

				$flat = get_the_author_meta( 'flat_4', $user_id );
				$flat_expire = get_the_author_meta( 'flat_expire_4', $user_id );
			} else if ( is_user_logged_in() && $price_level == 3 ) {
				$card = get_the_author_meta( 'card_3', $user_id );
				$expire = get_the_author_meta( 'expire_3', $user_id );

				if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_4', $user_id );
					$expire = get_the_author_meta( 'expire_4', $user_id );
				} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_5', $user_id );
					$expire = get_the_author_meta( 'expire_5', $user_id );
				}

				$flat = get_the_author_meta( 'flat_3', $user_id );
				$flat_expire = get_the_author_meta( 'flat_expire_3', $user_id );
			} else if ( is_user_logged_in() && $price_level == 2 ) {
				$card = get_the_author_meta( 'card_2', $user_id );
				$expire = get_the_author_meta( 'expire_2', $user_id );

				if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_3', $user_id );
					$expire = get_the_author_meta( 'expire_3', $user_id );
				} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_4', $user_id );
					$expire = get_the_author_meta( 'expire_4', $user_id );
				} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_5', $user_id );
					$expire = get_the_author_meta( 'expire_5', $user_id );
				}

				$flat = get_the_author_meta( 'flat_2', $user_id );
				$flat_expire = get_the_author_meta( 'flat_expire_2', $user_id );
			} else if ( is_user_logged_in() ) {
				$card = get_the_author_meta( 'card', $user_id );
				$expire = get_the_author_meta( 'expire', $user_id );

				if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_2', $user_id );
					$expire = get_the_author_meta( 'expire_2', $user_id );
				} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_3', $user_id );
					$expire = get_the_author_meta( 'expire_3', $user_id );
				} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_4', $user_id );
					$expire = get_the_author_meta( 'expire_4', $user_id );
				} if ( $price_level_for_lower_course && ( $card <= 0 || $expire < $date ) ) {
					$card = get_the_author_meta( 'card_5', $user_id );
					$expire = get_the_author_meta( 'expire_5', $user_id );
				}

				$flat = get_the_author_meta( 'flat', $user_id );
				$flat_expire = get_the_author_meta( 'flat_expire', $user_id );
			}

			if ( !empty( $price_level ) && ( ( $card >= $woocommerce->cart->cart_contents_count && $expire >= $date ) || ( $flat && $flat_expire >= $date ) ) ) {
				$video_in_cart = true;
			} else {
				$video_in_cart = false;
				break;
			}
		}
	}

	if ( !$video_in_cart )
		unset( $available_gateways['redemption'] );

	return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'cbs_conditional_payment_gateways', 10, 1 );

/* // Process the payment
function cbs_process_redemption_payment(){
	if ( $_POST['payment_method'] != 'redemption' )
		return;

	// if ( !isset($_POST['transaction']) || empty($_POST['transaction']) )
		// wc_add_notice( __( 'Please add your transaction ID', $this->domain ), 'error' );
}
add_action( 'woocommerce_checkout_process', 'cbs_process_redemption_payment' );

// Update the order meta with field value
function cbs_redemption_payment_update_order_meta( $order_id ) {

	if ( $_POST['payment_method'] != 'redemption' )
		return;

	// echo '<pre>'; print_r($_POST); echo '</pre>'; exit();

	// update_post_meta( $order_id, 'transaction', $_POST['transaction'] );
}
add_action( 'woocommerce_checkout_update_order_meta', 'cbs_redemption_payment_update_order_meta' );

// Display field value on the order edit page
function cbs_redemption_checkout_field_display_admin_order_meta( $order ) {
	$method = get_post_meta( $order->id, '_payment_method', true );
	if ($method != 'redemption')
		return;

	// echo '<p><strong>'.__( 'Transaction ID').':</strong> ' . get_post_meta( $order->id, 'transaction', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'cbs_redemption_checkout_field_display_admin_order_meta', 10, 1 ); */
