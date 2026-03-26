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
