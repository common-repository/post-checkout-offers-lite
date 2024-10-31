<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
 $upsell_order_id = $order->get_order_number();  

 $parent_order_id = get_post_meta($upsell_order_id, 'mwb_wcpcolite_upsell_parent_order' , true); 
 if($parent_order_id) {

 	$parent_order = wc_get_order( $parent_order_id ) ;
?> 
<!-- For upsell parent order --> 

<div class="woocommerce-order">

	<?php if ( $parent_order ) : ?>

		<?php if ( $parent_order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'post_checkout_offers_lite' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $parent_order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'post_checkout_offers_lite' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'post_checkout_offers_lite' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'post_checkout_offers_lite' ), $parent_order ); ?></p>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Order number:', 'post_checkout_offers_lite' ); ?>
					<strong><?php echo $parent_order->get_order_number(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php _e( 'Date:', 'post_checkout_offers_lite' ); ?>
					<strong><?php echo wc_format_datetime( $parent_order->get_date_created() ); ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $parent_order->get_user_id() === get_current_user_id() && $parent_order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php _e( 'Email:', 'post_checkout_offers_lite' ); ?>
						<strong><?php echo $parent_order->get_billing_email(); ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php _e( 'Total:', 'post_checkout_offers_lite' ); ?>
					<strong><?php echo $parent_order->get_formatted_order_total(); ?></strong>
				</li>

				<?php if ( $parent_order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php _e( 'Payment method:', 'post_checkout_offers_lite' ); ?>
						<strong><?php echo wp_kses_post( $parent_order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $parent_order->get_payment_method(), $parent_order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $parent_order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'post_checkout_offers_lite' ), null ); ?></p>

	<?php endif; ?>

</div> 

<?php } ?> 

<!-- End of  upsell parent order -->

<div class="woocommerce-order">

	<?php if ( $order ) : ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'post_checkout_offers_lite' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'post_checkout_offers_lite' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'post_checkout_offers_lite' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<?php if ($parent_order_id) : ?>
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mwb-wcpco-thankyou-order-received" style="font-weight: bold;color: #000;"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you for purchasing the special offer product. Your order has been received.', 'post_checkout_offers_lite' ), $order ); ?></p> 
			<?php else : ?> 
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'post_checkout_offers_lite' ), $order ); ?></p> 
			<?php endif ;?>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Order number:', 'post_checkout_offers_lite' ); ?>
					<strong><?php echo $order->get_order_number(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php _e( 'Date:', 'post_checkout_offers_lite' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php _e( 'Email:', 'post_checkout_offers_lite' ); ?>
						<strong><?php echo $order->get_billing_email(); ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php _e( 'Total:', 'post_checkout_offers_lite' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php _e( 'Payment method:', 'post_checkout_offers_lite' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'post_checkout_offers_lite' ), null ); ?></p>

	<?php endif; ?>
</div>
