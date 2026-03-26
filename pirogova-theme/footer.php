<?php
/**
 * Footer template.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;
?>

<footer class="site-footer">
	<div class="container footer__inner">

		<div class="footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					echo '<span>' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
				}
				?>
			</a>
			<p class="footer__tagline"><?php echo esc_html( get_theme_mod( 'pirogova_tagline', __( 'Домашние пироги с доставкой', 'pirogova' ) ) ); ?></p>
		</div>

		<div class="footer__nav">
			<ul>
				<li><a href="#catalog"><?php esc_html_e( 'Каталог', 'pirogova' ); ?></a></li>
				<li><a href="#how-it-works"><?php esc_html_e( 'Как это работает', 'pirogova' ); ?></a></li>
				<li><a href="#media"><?php esc_html_e( 'О нас в СМИ', 'pirogova' ); ?></a></li>
				<li><a href="#contacts"><?php esc_html_e( 'Контакты', 'pirogova' ); ?></a></li>
			</ul>
		</div>

		<div class="footer__contacts">
			<?php
			$phone   = get_theme_mod( 'pirogova_phone', '' );
			$email   = get_theme_mod( 'pirogova_email', '' );
			$address = get_theme_mod( 'pirogova_address', '' );
			?>
			<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="footer__phone">
					<?php echo esc_html( $phone ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $email ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>" class="footer__email">
					<?php echo esc_html( $email ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $address ) : ?>
				<address class="footer__address"><?php echo esc_html( $address ); ?></address>
			<?php endif; ?>
		</div>

		<div class="footer__social">
			<?php
			$vk = get_theme_mod( 'pirogova_vk', '' );
			$tg = get_theme_mod( 'pirogova_telegram', '' );
			?>
			<?php if ( $vk ) : ?>
				<a href="<?php echo esc_url( $vk ); ?>" target="_blank" rel="noopener noreferrer" aria-label="ВКонтакте">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M15.07 2H8.93C3.33 2 2 3.33 2 8.93v6.14C2 20.67 3.33 22 8.93 22h6.14C20.67 22 22 20.67 22 15.07V8.93C22 3.33 20.67 2 15.07 2zm3.08 13.5h-1.61c-.61 0-.8-.49-1.89-1.59-.95-.92-1.37-.92-1.6-.92s-.27.09-.27.59v1.45c0 .42-.13.67-1.24.67-1.82 0-3.84-1.1-5.26-3.16C4.55 10.2 4 8.23 4 7.84c0-.23.09-.45.59-.45h1.61c.44 0 .61.2.78.67.86 2.48 2.3 4.65 2.89 4.65.22 0 .32-.1.32-.67V9.67c-.07-1.2-.69-1.3-.69-1.73 0-.21.17-.43.45-.43h2.53c.38 0 .51.2.51.64v3.43c0 .38.17.51.28.51.22 0 .41-.13.82-.54 1.27-1.42 2.17-3.6 2.17-3.6.12-.26.32-.51.76-.51h1.61c.48 0 .59.25.48.59-.2.95-2.16 3.7-2.16 3.7-.17.28-.23.4 0 .71.17.23.73.71 1.1 1.14.68.78 1.2 1.43 1.34 1.88.14.44-.09.67-.56.67z"/></svg>
				</a>
			<?php endif; ?>
			<?php if ( $tg ) : ?>
				<a href="<?php echo esc_url( $tg ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8l-1.68 7.94c-.12.56-.46.7-.93.43l-2.58-1.9-1.25 1.2c-.14.14-.25.25-.51.25l.18-2.6 4.72-4.26c.2-.19-.04-.29-.32-.1L7.46 14.7l-2.53-.79c-.55-.17-.56-.55.12-.81l9.91-3.82c.46-.17.86.11.68.72z"/></svg>
				</a>
			<?php endif; ?>
		</div>

	</div>

	<div class="footer__bottom">
		<div class="container">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Все права защищены.', 'pirogova' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Политика конфиденциальности', 'pirogova' ); ?></a>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
