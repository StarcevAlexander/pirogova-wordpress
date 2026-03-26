<?php
/**
 * WooCommerce customizations.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

// Remove default WC wrappers — we use our own layout.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );

add_action( 'woocommerce_before_main_content', 'pirogova_wc_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content',  'pirogova_wc_wrapper_end',   10 );

function pirogova_wc_wrapper_start(): void {
	echo '<main class="site-main woocommerce-page"><div class="container">';
}
function pirogova_wc_wrapper_end(): void {
	echo '</div></main>';
}

/**
 * Remove default breadcrumbs.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Declare HPOS compatibility.
 */
add_action( 'before_woocommerce_init', 'pirogova_declare_hpos' );
function pirogova_declare_hpos(): void {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

/**
 * Add "Вес" (weight/gramm) attribute to product admin.
 * We rely on WooCommerce built-in variable product + attributes.
 * This filter ensures the weight attribute appears first in the popup.
 */
add_filter( 'woocommerce_attribute_taxonomies', 'pirogova_sort_attributes' );
function pirogova_sort_attributes( array $taxonomies ): array {
	// Move weight-like attribute to top.
	usort( $taxonomies, static function ( $a, $b ) {
		$weight_names = [ 'вес', 'weight', 'gramm', 'gram', 'масса' ];
		$a_is_weight  = in_array( mb_strtolower( $a->attribute_name ), $weight_names, true );
		$b_is_weight  = in_array( mb_strtolower( $b->attribute_name ), $weight_names, true );
		if ( $a_is_weight && ! $b_is_weight ) {
			return -1;
		}
		if ( ! $a_is_weight && $b_is_weight ) {
			return 1;
		}
		return 0;
	} );
	return $taxonomies;
}

/**
 * Set catalog columns.
 */
add_filter( 'loop_shop_columns', fn() => 3 );
add_filter( 'loop_shop_per_page', fn() => 12 );

/**
 * Disable quantity field on product loops (we handle it in popup).
 */
add_filter( 'woocommerce_loop_add_to_cart_args', function ( array $args ) {
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' pirogova-popup-trigger' : 'pirogova-popup-trigger';
	return $args;
} );

/**
 * Customize checkout fields for Russian locale.
 */
add_filter( 'woocommerce_checkout_fields', 'pirogova_checkout_fields' );
function pirogova_checkout_fields( array $fields ): array {
	// Make company optional.
	if ( isset( $fields['billing']['billing_company'] ) ) {
		$fields['billing']['billing_company']['required'] = false;
		$fields['billing']['billing_company']['class']    = [ 'form-row-wide', 'optional' ];
	}

	// Add delivery time field.
	$fields['order']['pirogova_delivery_time'] = [
		'type'        => 'text',
		'label'       => esc_html__( 'Желаемое время доставки', 'pirogova' ),
		'placeholder' => esc_html__( 'Например: сегодня в 18:00', 'pirogova' ),
		'required'    => false,
		'class'       => [ 'form-row-wide' ],
		'priority'    => 5,
	];

	// Add doorbell/apartment field.
	$fields['billing']['billing_apartment'] = [
		'type'        => 'text',
		'label'       => esc_html__( 'Квартира / офис', 'pirogova' ),
		'placeholder' => esc_html__( 'Номер квартиры или офиса', 'pirogova' ),
		'required'    => false,
		'class'       => [ 'form-row-last' ],
		'priority'    => 55,
	];

	return $fields;
}

/**
 * Save custom checkout fields to order meta.
 */
add_action( 'woocommerce_checkout_update_order_meta', 'pirogova_save_checkout_fields' );
function pirogova_save_checkout_fields( int $order_id ): void {
	if ( isset( $_POST['pirogova_delivery_time'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$order = wc_get_order( $order_id );
		if ( $order ) {
			$order->update_meta_data( '_pirogova_delivery_time', sanitize_text_field( wp_unslash( $_POST['pirogova_delivery_time'] ) ) );
			$order->save();
		}
	}
	if ( isset( $_POST['billing_apartment'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$order = wc_get_order( $order_id );
		if ( $order ) {
			$order->update_meta_data( '_billing_apartment', sanitize_text_field( wp_unslash( $_POST['billing_apartment'] ) ) );
			$order->save();
		}
	}
}

/**
 * Display delivery time in admin order view.
 */
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'pirogova_display_delivery_time_admin' );
function pirogova_display_delivery_time_admin( WC_Order $order ): void {
	$time = $order->get_meta( '_pirogova_delivery_time' );
	if ( $time ) {
		echo '<p><strong>' . esc_html__( 'Время доставки:', 'pirogova' ) . '</strong> ' . esc_html( $time ) . '</p>';
	}
	$apt = $order->get_meta( '_billing_apartment' );
	if ( $apt ) {
		echo '<p><strong>' . esc_html__( 'Кв/офис:', 'pirogova' ) . '</strong> ' . esc_html( $apt ) . '</p>';
	}
}

/**
 * Add delivery time to order emails.
 */
add_action( 'woocommerce_email_after_order_table', 'pirogova_email_delivery_time', 10, 2 );
function pirogova_email_delivery_time( WC_Order $order, bool $sent_to_admin ): void {
	$time = $order->get_meta( '_pirogova_delivery_time' );
	if ( $time ) {
		echo '<p><strong>' . esc_html__( 'Желаемое время доставки:', 'pirogova' ) . '</strong> ' . esc_html( $time ) . '</p>';
	}
}

/**
 * YooKassa compatibility: ensure fiscal data (ФФД 1.2) is available.
 * Requires plugin: WooCommerce YooKassa (official, slug: yookassa).
 *
 * The plugin handles fiscal receipts automatically when enabled in gateway settings.
 * Product tax (НДС) is set per-product in the WooCommerce product edit screen.
 */
add_filter( 'yookassa_default_tax_rate', 'pirogova_yookassa_tax_rate' );
function pirogova_yookassa_tax_rate(): string {
	// НДС не облагается (для домашней выпечки / УСН).
	// Change to 'vat20' if your business is VAT-registered.
	return 'none';
}
