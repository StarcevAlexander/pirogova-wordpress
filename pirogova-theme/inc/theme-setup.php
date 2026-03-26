<?php
/**
 * Theme setup: support declarations, menus, customizer.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'pirogova_setup' );
function pirogova_setup(): void {
	load_theme_textdomain( 'pirogova', PIROGOVA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );
	add_theme_support( 'custom-logo', [
		'height'      => 80,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	] );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( [
		'primary' => esc_html__( 'Основное меню', 'pirogova' ),
		'footer'  => esc_html__( 'Меню в футере', 'pirogova' ),
	] );

	// Thumbnail sizes.
	add_image_size( 'pirogova-catalog', 600, 600, true );
	add_image_size( 'pirogova-popup',   900, 900, true );
	add_image_size( 'pirogova-hero',   1920, 1080, true );
}

/**
 * Customizer settings.
 */
add_action( 'customize_register', 'pirogova_customizer' );
function pirogova_customizer( WP_Customize_Manager $wp_customize ): void {
	// --- Contacts panel ---
	$wp_customize->add_panel( 'pirogova_contacts_panel', [
		'title'    => esc_html__( 'Контакты и настройки', 'pirogova' ),
		'priority' => 30,
	] );

	// Section: Contact info.
	$wp_customize->add_section( 'pirogova_contact_section', [
		'title' => esc_html__( 'Контактная информация', 'pirogova' ),
		'panel' => 'pirogova_contacts_panel',
	] );

	$fields = [
		'pirogova_phone'    => [ 'label' => esc_html__( 'Телефон', 'pirogova' ),  'type' => 'text' ],
		'pirogova_email'    => [ 'label' => esc_html__( 'Email', 'pirogova' ),    'type' => 'email' ],
		'pirogova_address'  => [ 'label' => esc_html__( 'Адрес', 'pirogova' ),   'type' => 'textarea' ],
		'pirogova_map_url'  => [ 'label' => esc_html__( 'Ссылка на карту (iframe src)', 'pirogova' ), 'type' => 'url' ],
		'pirogova_vk'       => [ 'label' => esc_html__( 'ВКонтакте URL', 'pirogova' ), 'type' => 'url' ],
		'pirogova_telegram' => [ 'label' => esc_html__( 'Telegram URL', 'pirogova' ),  'type' => 'url' ],
		'pirogova_tagline'  => [ 'label' => esc_html__( 'Слоган в футере', 'pirogova' ), 'type' => 'text' ],
	];

	foreach ( $fields as $id => $args ) {
		$wp_customize->add_setting( $id, [ 'sanitize_callback' => 'sanitize_text_field', 'default' => '' ] );
		$wp_customize->add_control( $id, [
			'label'   => $args['label'],
			'section' => 'pirogova_contact_section',
			'type'    => $args['type'],
		] );
	}

	// Section: Hero.
	$wp_customize->add_section( 'pirogova_hero_section', [
		'title' => esc_html__( 'Главный экран (Hero)', 'pirogova' ),
		'panel' => 'pirogova_contacts_panel',
	] );

	$hero_fields = [
		'pirogova_hero_title'    => [ 'label' => esc_html__( 'Заголовок', 'pirogova' ),     'type' => 'text',     'default' => 'Домашние пироги с доставкой' ],
		'pirogova_hero_subtitle' => [ 'label' => esc_html__( 'Подзаголовок', 'pirogova' ),  'type' => 'textarea', 'default' => 'Готовим с любовью, доставляем быстро' ],
		'pirogova_hero_btn'      => [ 'label' => esc_html__( 'Текст кнопки', 'pirogova' ),  'type' => 'text',     'default' => 'Выбрать пирог' ],
	];

	foreach ( $hero_fields as $id => $args ) {
		$default = $args['default'] ?? '';
		$wp_customize->add_setting( $id, [ 'sanitize_callback' => 'sanitize_text_field', 'default' => $default ] );
		$wp_customize->add_control( $id, [
			'label'   => $args['label'],
			'section' => 'pirogova_hero_section',
			'type'    => $args['type'],
		] );
	}

	// Hero background image.
	$wp_customize->add_setting( 'pirogova_hero_bg', [ 'sanitize_callback' => 'absint', 'default' => 0 ] );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'pirogova_hero_bg', [
		'label'     => esc_html__( 'Фоновое изображение Hero', 'pirogova' ),
		'section'   => 'pirogova_hero_section',
		'mime_type' => 'image',
	] ) );
}
