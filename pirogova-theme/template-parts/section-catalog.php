<?php
/**
 * Catalog section template.
 * Queries WooCommerce products and renders catalog cards.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$products = wc_get_products( [
	'status'  => 'publish',
	'limit'   => 12,
	'orderby' => 'menu_order',
	'order'   => 'ASC',
] );

if ( empty( $products ) ) {
	return;
}
?>

<section class="catalog" id="catalog" aria-labelledby="catalog-title">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title" id="catalog-title"><?php esc_html_e( 'Наши пироги', 'pirogova' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Выберите пирог и граммовку', 'pirogova' ); ?></p>
		</div>

		<div class="catalog__grid">
			<?php foreach ( $products as $product ) : ?>
				<?php
				$product_id   = $product->get_id();
				$name         = $product->get_name();
				$short_desc   = $product->get_short_description();
				$image_id     = $product->get_image_id();
				$image_src    = $image_id ? wp_get_attachment_image_url( $image_id, 'pirogova-catalog' ) : wc_placeholder_img_src( 'pirogova-catalog' );
				$image_alt    = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : $name;
				$in_stock     = $product->is_in_stock();

				// Price display: show "от X ₽" for variable products.
				if ( $product instanceof WC_Product_Variable ) {
					$min_price  = $product->get_variation_price( 'min' );
					$price_html = sprintf(
						/* translators: %s: formatted price */
						esc_html__( 'от %s', 'pirogova' ),
						wc_price( $min_price )
					);
				} else {
					$price_html = $product->get_price_html();
				}

				// Weight variants for quick display on card.
				$variations = [];
				if ( $product instanceof WC_Product_Variable ) {
					foreach ( $product->get_available_variations() as $var ) {
						foreach ( $var['attributes'] as $attr_val ) {
							$variations[] = [
								'id'    => $var['variation_id'],
								'label' => $attr_val,
							];
							break;
						}
					}
				}
				?>
				<article class="catalog-card <?php echo $in_stock ? '' : 'catalog-card--out-of-stock'; ?>"
				         data-product-id="<?php echo esc_attr( $product_id ); ?>"
				         role="article"
				         aria-label="<?php echo esc_attr( $name ); ?>">

					<button class="catalog-card__image-wrap pirogova-popup-trigger"
					        data-product-id="<?php echo esc_attr( $product_id ); ?>"
					        aria-haspopup="dialog"
					        aria-label="<?php echo esc_attr( sprintf( __( 'Открыть %s', 'pirogova' ), $name ) ); ?>">
						<img src="<?php echo esc_url( $image_src ); ?>"
						     alt="<?php echo esc_attr( $image_alt ); ?>"
						     class="catalog-card__img"
						     loading="lazy"
						     width="600"
						     height="600">
						<?php if ( ! $in_stock ) : ?>
							<span class="catalog-card__badge catalog-card__badge--oos"><?php esc_html_e( 'Нет в наличии', 'pirogova' ); ?></span>
						<?php endif; ?>
					</button>

					<div class="catalog-card__body">
						<h3 class="catalog-card__title">
							<button class="pirogova-popup-trigger catalog-card__title-btn"
							        data-product-id="<?php echo esc_attr( $product_id ); ?>">
								<?php echo esc_html( $name ); ?>
							</button>
						</h3>

						<?php if ( $short_desc ) : ?>
							<p class="catalog-card__desc"><?php echo wp_kses_post( $short_desc ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $variations ) ) : ?>
							<div class="catalog-card__weights">
								<?php foreach ( $variations as $i => $var ) : ?>
									<button class="catalog-card__weight-btn pirogova-popup-trigger"
									        data-product-id="<?php echo esc_attr( $product_id ); ?>"
									        data-variation-id="<?php echo esc_attr( $var['id'] ); ?>">
										<?php echo esc_html( $var['label'] ); ?>
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="catalog-card__footer">
							<div class="catalog-card__price"><?php echo wp_kses_post( $price_html ); ?></div>
							<button class="btn btn--primary catalog-card__btn pirogova-popup-trigger"
							        data-product-id="<?php echo esc_attr( $product_id ); ?>"
							        <?php echo ! $in_stock ? 'disabled aria-disabled="true"' : ''; ?>>
								<?php esc_html_e( 'Выбрать', 'pirogova' ); ?>
							</button>
						</div>
					</div>

				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
