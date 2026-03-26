<?php
/**
 * AJAX handlers: product popup data, add to cart, cart count.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get product data for popup (variations, images, price).
 * Called via: wp_ajax_pirogova_get_product / wp_ajax_nopriv_pirogova_get_product
 */
add_action( 'wp_ajax_pirogova_get_product',        'pirogova_ajax_get_product' );
add_action( 'wp_ajax_nopriv_pirogova_get_product', 'pirogova_ajax_get_product' );
function pirogova_ajax_get_product(): void {
	check_ajax_referer( 'pirogova_nonce', 'nonce' );

	$product_id = absint( wp_unslash( $_POST['product_id'] ?? 0 ) );
	if ( ! $product_id ) {
		wp_send_json_error( [ 'message' => __( 'Неверный ID продукта.', 'pirogova' ) ] );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product || ! $product->is_visible() ) {
		wp_send_json_error( [ 'message' => __( 'Продукт не найден.', 'pirogova' ) ] );
	}

	$data = [
		'id'          => $product->get_id(),
		'name'        => $product->get_name(),
		'description' => wp_kses_post( $product->get_description() ? $product->get_description() : $product->get_short_description() ),
		'type'        => $product->get_type(),
		'variations'  => [],
		'images'      => [],
	];

	// Main image.
	$attachment_ids = $product->get_gallery_image_ids();
	array_unshift( $attachment_ids, $product->get_image_id() );
	foreach ( array_unique( array_filter( $attachment_ids ) ) as $att_id ) {
		$data['images'][] = [
			'id'  => $att_id,
			'src' => wp_get_attachment_image_url( $att_id, 'pirogova-popup' ),
			'alt' => get_post_meta( $att_id, '_wp_attachment_image_alt', true ),
		];
	}

	// Variations for variable products.
	if ( $product instanceof WC_Product_Variable ) {
		$available = $product->get_available_variations();
		foreach ( $available as $var ) {
			$variation     = wc_get_product( $var['variation_id'] );
			$weight_label  = '';

			// Get weight attribute label (support any attribute named like "вес", "weight", "gramm", etc.)
			foreach ( $var['attributes'] as $attr_name => $attr_value ) {
				$weight_label = $attr_value;
				break; // Use first attribute as weight label.
			}

			$image_id  = $variation->get_image_id() ?: $product->get_image_id();
			$image_src = wp_get_attachment_image_url( $image_id, 'pirogova-popup' );

			$data['variations'][] = [
				'id'           => $var['variation_id'],
				'weight_label' => $weight_label ?: $variation->get_sku(),
				'price_html'   => $variation->get_price_html(),
				'price'        => (float) $variation->get_price(),
				'in_stock'     => $variation->is_in_stock(),
				'image_id'     => $image_id,
				'image_src'    => $image_src,
				'image_alt'    => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			];
		}

		// Sort by price ascending.
		usort( $data['variations'], static fn( $a, $b ) => $a['price'] <=> $b['price'] );
	}

	wp_send_json_success( $data );
}

/**
 * AJAX add to cart.
 * Called via: wp_ajax_pirogova_add_to_cart / wp_ajax_nopriv_pirogova_add_to_cart
 */
add_action( 'wp_ajax_pirogova_add_to_cart',        'pirogova_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_pirogova_add_to_cart', 'pirogova_ajax_add_to_cart' );
function pirogova_ajax_add_to_cart(): void {
	check_ajax_referer( 'pirogova_nonce', 'nonce' );

	$product_id   = absint( wp_unslash( $_POST['product_id']   ?? 0 ) );
	$variation_id = absint( wp_unslash( $_POST['variation_id'] ?? 0 ) );
	$quantity     = absint( wp_unslash( $_POST['quantity']      ?? 1 ) );

	if ( ! $product_id ) {
		wp_send_json_error( [ 'message' => __( 'Неверный ID продукта.', 'pirogova' ) ] );
	}

	$quantity = max( 1, $quantity );

	$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );

	if ( false === $added ) {
		$notices = wc_get_notices( 'error' );
		$message = ! empty( $notices ) ? wp_strip_all_tags( $notices[0]['notice'] ) : __( 'Ошибка добавления в корзину.', 'pirogova' );
		wc_clear_notices();
		wp_send_json_error( [ 'message' => $message ] );
	}

	wc_clear_notices();

	wp_send_json_success( [
		'cart_count'   => WC()->cart->get_cart_contents_count(),
		'cart_total'   => WC()->cart->get_cart_total(),
		'cart_url'     => wc_get_cart_url(),
		'checkout_url' => wc_get_checkout_url(),
		'message'      => __( 'Товар добавлен в корзину!', 'pirogova' ),
	] );
}

/**
 * Return updated cart count (used after mini-cart operations).
 */
add_action( 'wp_ajax_pirogova_cart_count',        'pirogova_ajax_cart_count' );
add_action( 'wp_ajax_nopriv_pirogova_cart_count', 'pirogova_ajax_cart_count' );
function pirogova_ajax_cart_count(): void {
	check_ajax_referer( 'pirogova_nonce', 'nonce' );
	wp_send_json_success( [
		'count' => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
	] );
}
