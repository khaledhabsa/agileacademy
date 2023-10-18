<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>
<style>
	form.woocommerce-checkout {
		width: 55%;
		margin: auto;
	}

	#order_review_heading {
		width: 118px;
		height: 36px;
	}
	
</style>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
	<h1 style="width: 619px;
height: 48px;
top: 116px;
left: 412px;
">Checkout</h1>

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>
		<div class="">
			<h2>Order details</h2>

			<?php
			do_action('woocommerce_review_order_before_cart_contents');

			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
			?>
					<div class="">
						<img src="<?php echo wp_get_attachment_url($_product->get_image_id()); ?>" style="width: 96px;height: 96px" />
							<h2 style="display: inline;"><?= $_product->get_name() ?></h2>
					</div>

					</tr>
			<?php
				}
			}

			do_action('woocommerce_review_order_after_cart_contents');
			?>

		</div>
		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action('woocommerce_checkout_billing'); ?>
				<?php do_action('woocommerce_checkout_shipping'); ?>

			</div>

		</div>

		<?php do_action('woocommerce_checkout_after_customer_details'); ?>

	<?php endif; ?>

	<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

	<h3 id="order_review_heading"><?php esc_html_e('Summary', 'woocommerce'); ?></h3>

	<?php do_action('woocommerce_checkout_before_order_review'); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action('woocommerce_checkout_order_review'); ?>
	</div>

	<?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>