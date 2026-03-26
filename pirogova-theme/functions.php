<?php
/**
 * Pirogova Theme Functions
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

define( 'PIROGOVA_VERSION', '1.0.0' );
define( 'PIROGOVA_DIR', get_template_directory() );
define( 'PIROGOVA_URI', get_template_directory_uri() );

// Load includes.
require_once PIROGOVA_DIR . '/inc/theme-setup.php';
require_once PIROGOVA_DIR . '/inc/order-statuses.php';
require_once PIROGOVA_DIR . '/inc/ajax-handlers.php';

if ( class_exists( 'WooCommerce' ) ) {
	require_once PIROGOVA_DIR . '/inc/woocommerce.php';
}

/**
 * Enqueue frontend assets.
 */
add_action( 'wp_enqueue_scripts', 'pirogova_enqueue_assets' );
function pirogova_enqueue_assets(): void {
	wp_enqueue_style(
		'pirogova-main',
		PIROGOVA_URI . '/assets/css/main.css',
		[],
		PIROGOVA_VERSION
	);

	// Swiper for catalog carousel inside popup.
	wp_enqueue_style(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		[],
		'11.0.0'
	);

	wp_enqueue_script(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		[],
		'11.0.0',
		true
	);

	wp_enqueue_script(
		'pirogova-main',
		PIROGOVA_URI . '/assets/js/main.js',
		[ 'swiper' ],
		PIROGOVA_VERSION,
		true
	);

	wp_localize_script(
		'pirogova-main',
		'PirogovaData',
		[
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'pirogova_nonce' ),
			'cartUrl'      => wc_get_cart_url(),
			'checkoutUrl'  => wc_get_checkout_url(),
			'currency'     => get_woocommerce_currency_symbol(),
			'i18n'         => [
				'addToCart'     => esc_html__( 'В корзину', 'pirogova' ),
				'adding'        => esc_html__( 'Добавляем...', 'pirogova' ),
				'added'         => esc_html__( 'Добавлено!', 'pirogova' ),
				'goToCart'      => esc_html__( 'Перейти в корзину', 'pirogova' ),
				'selectWeight'  => esc_html__( 'Выберите вес', 'pirogova' ),
				'close'         => esc_html__( 'Закрыть', 'pirogova' ),
			],
		]
	);
}

/**
 * Enqueue admin assets.
 */
add_action( 'admin_enqueue_scripts', 'pirogova_admin_assets' );
function pirogova_admin_assets( string $hook ): void {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php', 'edit.php' ], true ) ) {
		return;
	}
	wp_enqueue_style(
		'pirogova-admin',
		PIROGOVA_URI . '/assets/css/admin.css',
		[],
		PIROGOVA_VERSION
	);
}
