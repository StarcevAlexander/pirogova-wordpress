<?php
/**
 * Custom WooCommerce order statuses for delivery workflow.
 *
 * Statuses:
 *   wc-pirogova-cooking   — Готовится
 *   wc-pirogova-delivering — Доставляется
 *   wc-pirogova-delivered  — Доставлен
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register custom order statuses.
 */
add_action( 'init', 'pirogova_register_order_statuses' );
function pirogova_register_order_statuses(): void {
	$statuses = [
		'wc-pirogova-cooking' => [
			'label'                     => _x( 'Готовится', 'Order status', 'pirogova' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Готовится <span class="count">(%s)</span>', 'Готовится <span class="count">(%s)</span>', 'pirogova' ),
		],
		'wc-pirogova-delivering' => [
			'label'                     => _x( 'Доставляется', 'Order status', 'pirogova' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Доставляется <span class="count">(%s)</span>', 'Доставляется <span class="count">(%s)</span>', 'pirogova' ),
		],
		'wc-pirogova-delivered' => [
			'label'                     => _x( 'Доставлен', 'Order status', 'pirogova' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Доставлен <span class="count">(%s)</span>', 'Доставлен <span class="count">(%s)</span>', 'pirogova' ),
		],
	];

	foreach ( $statuses as $key => $args ) {
		register_post_status( $key, $args );
	}
}

/**
 * Add custom statuses to WooCommerce order status list.
 */
add_filter( 'wc_order_statuses', 'pirogova_add_order_statuses' );
function pirogova_add_order_statuses( array $order_statuses ): array {
	$new_statuses = [];

	foreach ( $order_statuses as $key => $status ) {
		$new_statuses[ $key ] = $status;
		// Insert delivery statuses after "processing".
		if ( 'wc-processing' === $key ) {
			$new_statuses['wc-pirogova-cooking']    = _x( 'Готовится', 'Order status', 'pirogova' );
			$new_statuses['wc-pirogova-delivering'] = _x( 'Доставляется', 'Order status', 'pirogova' );
			$new_statuses['wc-pirogova-delivered']  = _x( 'Доставлен', 'Order status', 'pirogova' );
		}
	}

	return $new_statuses;
}

/**
 * Keep custom statuses as "paid" (not needing payment).
 */
add_filter( 'woocommerce_order_is_paid_statuses', 'pirogova_paid_statuses' );
function pirogova_paid_statuses( array $statuses ): array {
	$statuses[] = 'pirogova-cooking';
	$statuses[] = 'pirogova-delivering';
	$statuses[] = 'pirogova-delivered';
	return $statuses;
}

/**
 * Bulk actions for orders list in admin.
 */
add_filter( 'bulk_actions-edit-shop_order', 'pirogova_bulk_actions' );
add_filter( 'bulk_actions-woocommerce_page_wc-orders', 'pirogova_bulk_actions' );
function pirogova_bulk_actions( array $actions ): array {
	$actions['mark_pirogova-cooking']    = esc_html__( 'Отметить: Готовится', 'pirogova' );
	$actions['mark_pirogova-delivering'] = esc_html__( 'Отметить: Доставляется', 'pirogova' );
	$actions['mark_pirogova-delivered']  = esc_html__( 'Отметить: Доставлен', 'pirogova' );
	return $actions;
}

/**
 * Add delivery status column in admin order list.
 */
add_filter( 'manage_edit-shop_order_columns', 'pirogova_order_columns' );
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'pirogova_order_columns' );
function pirogova_order_columns( array $columns ): array {
	$new = [];
	foreach ( $columns as $key => $val ) {
		$new[ $key ] = $val;
		if ( 'order_status' === $key ) {
			$new['pirogova_delivery_info'] = esc_html__( 'Адрес доставки', 'pirogova' );
		}
	}
	return $new;
}

add_action( 'manage_shop_order_posts_custom_column', 'pirogova_order_column_content', 10, 2 );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'pirogova_order_column_content', 10, 2 );
function pirogova_order_column_content( string $column, $post_id ): void {
	if ( 'pirogova_delivery_info' !== $column ) {
		return;
	}
	$order = wc_get_order( $post_id );
	if ( ! $order ) {
		return;
	}
	echo esc_html( $order->get_formatted_shipping_address() ?: __( 'Самовывоз', 'pirogova' ) );
}

/**
 * Email notifications for custom statuses.
 */
add_filter( 'woocommerce_email_order_statuses', 'pirogova_email_statuses' );
function pirogova_email_statuses( array $statuses ): array {
	$statuses[] = 'pirogova-delivering';
	return $statuses;
}
