<?php
/**
 * Hero section template.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

$title    = get_theme_mod( 'pirogova_hero_title',    __( 'Домашние пироги с доставкой', 'pirogova' ) );
$subtitle = get_theme_mod( 'pirogova_hero_subtitle', __( 'Готовим с любовью, доставляем быстро', 'pirogova' ) );
$btn_text = get_theme_mod( 'pirogova_hero_btn',      __( 'Выбрать пирог', 'pirogova' ) );
$bg_id    = get_theme_mod( 'pirogova_hero_bg', 0 );
$bg_url   = $bg_id ? wp_get_attachment_image_url( $bg_id, 'pirogova-hero' ) : PIROGOVA_URI . '/assets/images/hero-default.jpg';
?>

<section class="hero" id="hero" aria-labelledby="hero-title">
	<div class="hero__bg" style="background-image: url('<?php echo esc_url( $bg_url ); ?>');" role="img" aria-label="<?php esc_attr_e( 'Домашние пироги', 'pirogova' ); ?>"></div>
	<div class="hero__overlay"></div>
	<div class="container hero__content">
		<h1 class="hero__title" id="hero-title"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $subtitle ) : ?>
			<p class="hero__subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
		<a href="#catalog" class="btn btn--primary hero__cta">
			<?php echo esc_html( $btn_text ); ?>
		</a>
	</div>
	<a href="#catalog" class="hero__scroll-hint" aria-label="<?php esc_attr_e( 'Прокрутить вниз', 'pirogova' ); ?>">
		<svg width="24" height="40" viewBox="0 0 24 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
			<rect x="1" y="1" width="22" height="38" rx="11" stroke="currentColor" stroke-width="2"/>
			<circle class="hero__scroll-dot" cx="12" cy="10" r="3" fill="currentColor"/>
		</svg>
	</a>
</section>
