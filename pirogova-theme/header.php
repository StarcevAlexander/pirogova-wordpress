<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
	<div class="container header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo '<span class="header__logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
			}
			?>
		</a>

		<nav class="header__nav" aria-label="<?php esc_attr_e( 'Основная навигация', 'pirogova' ); ?>">
			<ul class="header__nav-list">
				<li><a href="#catalog"><?php esc_html_e( 'Каталог', 'pirogova' ); ?></a></li>
				<li><a href="#how-it-works"><?php esc_html_e( 'Как это работает', 'pirogova' ); ?></a></li>
				<li><a href="#media"><?php esc_html_e( 'О нас в СМИ', 'pirogova' ); ?></a></li>
				<li><a href="#contacts"><?php esc_html_e( 'Контакты', 'pirogova' ); ?></a></li>
			</ul>
		</nav>

		<div class="header__right">
			<?php
			$phone = get_theme_mod( 'pirogova_phone', '' );
			if ( $phone ) :
				?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="header__phone">
					<?php echo esc_html( $phone ); ?>
				</a>
			<?php endif; ?>

			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header__cart" aria-label="<?php esc_attr_e( 'Корзина', 'pirogova' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M16 10a4 4 0 01-8 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<span class="header__cart-count" id="cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : '0' ); ?></span>
				</a>
			<?php endif; ?>

			<button class="header__burger" aria-label="<?php esc_attr_e( 'Меню', 'pirogova' ); ?>" aria-expanded="false" id="burger-btn">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>

	</div>
</header>

<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
	<nav aria-label="<?php esc_attr_e( 'Мобильное меню', 'pirogova' ); ?>">
		<ul>
			<li><a href="#catalog"><?php esc_html_e( 'Каталог', 'pirogova' ); ?></a></li>
			<li><a href="#how-it-works"><?php esc_html_e( 'Как это работает', 'pirogova' ); ?></a></li>
			<li><a href="#media"><?php esc_html_e( 'О нас в СМИ', 'pirogova' ); ?></a></li>
			<li><a href="#contacts"><?php esc_html_e( 'Контакты', 'pirogova' ); ?></a></li>
		</ul>
		<?php if ( $phone ) : ?>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="mobile-menu__phone">
				<?php echo esc_html( $phone ); ?>
			</a>
		<?php endif; ?>
	</nav>
</div>
<div class="mobile-menu-overlay" id="mobile-overlay"></div>

<!-- Mini-cart drawer -->
<div class="mini-cart" id="mini-cart" role="dialog" aria-modal="true" aria-labelledby="mini-cart-title" aria-hidden="true" tabindex="-1">
	<div class="mini-cart__header">
		<h3 class="mini-cart__title" id="mini-cart-title"><?php esc_html_e( 'Корзина', 'pirogova' ); ?></h3>
		<button class="mini-cart__close" id="mini-cart-close" aria-label="<?php esc_attr_e( 'Закрыть корзину', 'pirogova' ); ?>">
			<svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="2" y1="2" x2="16" y2="16"/><line x1="16" y1="2" x2="2" y2="16"/></svg>
		</button>
	</div>
	<div class="mini-cart__body">
		<div class="mini-cart__items" id="mini-cart-items"></div>
		<p class="mini-cart__empty" id="mini-cart-empty"><?php esc_html_e( 'Корзина пуста', 'pirogova' ); ?></p>
	</div>
	<div class="mini-cart__footer" id="mini-cart-footer" hidden>
		<div class="mini-cart__total">
			<?php esc_html_e( 'Итого:', 'pirogova' ); ?> <span id="mini-cart-total">0 ₽</span>
		</div>
		<a href="<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#' ); ?>" class="btn btn--primary">
			<?php esc_html_e( 'Оформить заказ', 'pirogova' ); ?>
		</a>
		<a href="#catalog" class="btn btn--secondary mini-cart__continue">
			<?php esc_html_e( 'Продолжить покупки', 'pirogova' ); ?>
		</a>
	</div>
</div>
<div class="mini-cart-backdrop" id="mini-cart-backdrop"></div>
