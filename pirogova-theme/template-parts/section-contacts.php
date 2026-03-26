<?php
/**
 * Contacts section.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

$phone   = get_theme_mod( 'pirogova_phone', '' );
$email   = get_theme_mod( 'pirogova_email', '' );
$address = get_theme_mod( 'pirogova_address', '' );
$map_url = get_theme_mod( 'pirogova_map_url', '' );
$vk      = get_theme_mod( 'pirogova_vk', '' );
$tg      = get_theme_mod( 'pirogova_telegram', '' );
?>

<section class="contacts" id="contacts" aria-labelledby="contacts-title">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title" id="contacts-title"><?php esc_html_e( 'Контакты', 'pirogova' ); ?></h2>
		</div>

		<div class="contacts__inner">
			<div class="contacts__info">

				<?php if ( $phone ) : ?>
					<div class="contacts__item">
						<div class="contacts__icon" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.72A2 2 0 012 .99h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7a2 2 0 011.72 2.02z"/></svg>
						</div>
						<div>
							<span class="contacts__label"><?php esc_html_e( 'Телефон', 'pirogova' ); ?></span>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="contacts__value">
								<?php echo esc_html( $phone ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $email ) : ?>
					<div class="contacts__item">
						<div class="contacts__icon" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						</div>
						<div>
							<span class="contacts__label"><?php esc_html_e( 'Email', 'pirogova' ); ?></span>
							<a href="mailto:<?php echo esc_attr( $email ); ?>" class="contacts__value">
								<?php echo esc_html( $email ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $address ) : ?>
					<div class="contacts__item">
						<div class="contacts__icon" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
						</div>
						<div>
							<span class="contacts__label"><?php esc_html_e( 'Адрес', 'pirogova' ); ?></span>
							<address class="contacts__value"><?php echo esc_html( $address ); ?></address>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $vk || $tg ) : ?>
					<div class="contacts__social">
						<?php if ( $vk ) : ?>
							<a href="<?php echo esc_url( $vk ); ?>" target="_blank" rel="noopener noreferrer" class="contacts__social-link" aria-label="ВКонтакте">
								<svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 2C8.27 2 2 8.27 2 16s6.27 14 14 14 14-6.27 14-14S23.73 2 16 2zm5.85 17.93h-2.1c-.79 0-1.04-.63-2.46-2.06-1.24-1.2-1.79-1.2-2.09-1.2-.43 0-.55.12-.55.77v1.88c0 .55-.17.88-1.61.88-2.38 0-5.01-1.44-6.86-4.12C6.13 13.22 5.5 10.97 5.5 10.45c0-.3.12-.58.77-.58h2.1c.57 0 .79.27 1.01.9.87 3.22 2.3 6.04 2.9 6.04.22 0 .32-.1.32-.67v-2.6c-.07-1.56-.9-1.7-.9-2.26 0-.27.21-.56.56-.56h3.3c.47 0 .63.25.63.79v3.51c0 .47.21.63.34.63.22 0 .41-.16.82-.57 1.29-1.44 2.2-3.64 2.2-3.64.12-.27.33-.53.9-.53h2.1c.63 0 .77.32.63.76-.26 1.23-2.82 4.82-2.82 4.82-.22.35-.3.5 0 .88.22.3.95.92 1.44 1.47.89 1.01 1.57 1.86 1.75 2.44.17.57-.11.87-.72.87z"/></svg>
							</a>
						<?php endif; ?>
						<?php if ( $tg ) : ?>
							<a href="<?php echo esc_url( $tg ); ?>" target="_blank" rel="noopener noreferrer" class="contacts__social-link" aria-label="Telegram">
								<svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 2C8.27 2 2 8.27 2 16s6.27 14 14 14 14-6.27 14-14S23.73 2 16 2zm6.49 9.58l-2.37 11.18c-.17.77-.63.96-1.28.6l-3.53-2.6-1.7 1.63c-.19.19-.35.35-.71.35l.25-3.55 6.47-5.85c.28-.25-.06-.39-.43-.14l-8 5.04-3.45-1.08c-.75-.23-.76-.75.16-1.11l13.47-5.2c.62-.22 1.17.15.96 1.13z"/></svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>

			<?php if ( $map_url ) : ?>
				<div class="contacts__map">
					<iframe
						src="<?php echo esc_url( $map_url ); ?>"
						width="100%"
						height="400"
						frameborder="0"
						allowfullscreen
						loading="lazy"
						title="<?php esc_attr_e( 'Карта расположения', 'pirogova' ); ?>"
						referrerpolicy="no-referrer-when-downgrade">
					</iframe>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
