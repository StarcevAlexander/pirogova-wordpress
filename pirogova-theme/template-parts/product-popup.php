<?php
/**
 * Product popup modal.
 * Loaded once on the page; JS populates it dynamically.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="popup-overlay" id="popup-overlay" aria-hidden="true" role="presentation"></div>

<div class="popup"
     id="product-popup"
     role="dialog"
     aria-modal="true"
     aria-labelledby="popup-product-name"
     aria-hidden="true"
     tabindex="-1">

	<button class="popup__close" id="popup-close" aria-label="<?php esc_attr_e( 'Закрыть', 'pirogova' ); ?>">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
			<line x1="2" y1="2" x2="18" y2="18"/>
			<line x1="18" y1="2" x2="2" y2="18"/>
		</svg>
	</button>

	<div class="popup__inner">

		<!-- Left: image gallery -->
		<div class="popup__gallery">
			<div class="swiper popup-swiper" id="popup-swiper">
				<div class="swiper-wrapper" id="popup-swiper-wrapper">
					<!-- Images injected by JS -->
					<div class="swiper-slide popup__placeholder-slide">
						<div class="popup__img-skeleton"></div>
					</div>
				</div>
				<div class="swiper-pagination popup-swiper-pagination"></div>
				<button class="swiper-button-prev" aria-label="<?php esc_attr_e( 'Предыдущее фото', 'pirogova' ); ?>"></button>
				<button class="swiper-button-next" aria-label="<?php esc_attr_e( 'Следующее фото', 'pirogova' ); ?>"></button>
			</div>
		</div>

		<!-- Right: product details -->
		<div class="popup__details">

			<div class="popup__loading" id="popup-loading" aria-live="polite" aria-label="<?php esc_attr_e( 'Загрузка...', 'pirogova' ); ?>">
				<div class="spinner"></div>
			</div>

			<div class="popup__content" id="popup-content" hidden>

				<h2 class="popup__title" id="popup-product-name"></h2>
				<div class="popup__description" id="popup-description"></div>

				<!-- Weight/variation selector -->
				<div class="popup__weights" id="popup-weights" role="group" aria-labelledby="popup-weights-label">
					<span class="popup__weights-label" id="popup-weights-label"><?php esc_html_e( 'Выберите граммовку:', 'pirogova' ); ?></span>
					<div class="popup__weights-options" id="popup-weights-options">
						<!-- Variation buttons injected by JS -->
					</div>
				</div>

				<!-- Price -->
				<div class="popup__price-wrap">
					<div class="popup__price" id="popup-price" aria-live="polite"></div>
				</div>

				<!-- Quantity + Add to cart -->
				<div class="popup__actions">
					<div class="popup__qty">
						<button class="popup__qty-btn" id="popup-qty-minus" aria-label="<?php esc_attr_e( 'Уменьшить количество', 'pirogova' ); ?>">−</button>
						<input type="number"
						       class="popup__qty-input"
						       id="popup-qty-input"
						       value="1"
						       min="1"
						       max="99"
						       aria-label="<?php esc_attr_e( 'Количество', 'pirogova' ); ?>">
						<button class="popup__qty-btn" id="popup-qty-plus" aria-label="<?php esc_attr_e( 'Увеличить количество', 'pirogova' ); ?>">+</button>
					</div>

					<button class="btn btn--primary popup__add-to-cart"
					        id="popup-add-to-cart"
					        disabled
					        aria-disabled="true">
						<?php esc_html_e( 'В корзину', 'pirogova' ); ?>
					</button>
				</div>

				<!-- Feedback message after adding to cart -->
				<div class="popup__cart-feedback" id="popup-cart-feedback" aria-live="polite" hidden>
					<span class="popup__cart-msg" id="popup-cart-msg"></span>
					<a href="<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#' ); ?>"
					   class="btn btn--secondary popup__checkout-link">
						<?php esc_html_e( 'Оформить заказ', 'pirogova' ); ?>
					</a>
				</div>

			</div><!-- /.popup__content -->

		</div><!-- /.popup__details -->
	</div><!-- /.popup__inner -->

</div><!-- /#product-popup -->
