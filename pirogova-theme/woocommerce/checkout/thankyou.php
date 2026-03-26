<?php
/**
 * Thank you page override.
 *
 * @package Pirogova
 * @see WooCommerce default: woocommerce/templates/checkout/thankyou.php
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php if ( $order ) : ?>

		<section class="thankyou">
			<div class="container thankyou__container">

				<div class="thankyou__icon" aria-hidden="true">
					<svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" width="80" height="80">
						<circle cx="40" cy="40" r="38" stroke="#2E7D32" stroke-width="4"/>
						<path d="M24 40l12 12 20-24" stroke="#2E7D32" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>

				<?php if ( $order->has_status( 'failed' ) ) : ?>
					<h1 class="thankyou__title thankyou__title--failed">
						<?php esc_html_e( 'Оплата не прошла', 'pirogova' ); ?>
					</h1>
					<p class="thankyou__text">
						<?php
						/* translators: %s: order pay URL */
						printf( esc_html__( 'Произошла ошибка оплаты. %s попробовать снова.', 'pirogova' ),
							'<a href="' . esc_url( $order->get_checkout_payment_url() ) . '" class="btn btn--primary">' . esc_html__( 'Нажмите, чтобы', 'pirogova' ) . '</a>'
						);
						?>
					</p>
				<?php else : ?>
					<h1 class="thankyou__title">
						<?php esc_html_e( 'Спасибо за заказ!', 'pirogova' ); ?>
					</h1>
					<p class="thankyou__text">
						<?php esc_html_e( 'Ваш заказ принят. Мы уже начинаем готовить ваш пирог.', 'pirogova' ); ?>
					</p>
				<?php endif; ?>

				<div class="thankyou__order-info">
					<div class="thankyou__info-row">
						<span><?php esc_html_e( 'Номер заказа:', 'pirogova' ); ?></span>
						<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
					</div>
					<div class="thankyou__info-row">
						<span><?php esc_html_e( 'Дата:', 'pirogova' ); ?></span>
						<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
					</div>
					<div class="thankyou__info-row">
						<span><?php esc_html_e( 'Статус:', 'pirogova' ); ?></span>
						<strong><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></strong>
					</div>
					<div class="thankyou__info-row">
						<span><?php esc_html_e( 'Сумма:', 'pirogova' ); ?></span>
						<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
					</div>
					<?php
					$delivery_time = $order->get_meta( '_pirogova_delivery_time' );
					if ( $delivery_time ) :
						?>
						<div class="thankyou__info-row">
							<span><?php esc_html_e( 'Время доставки:', 'pirogova' ); ?></span>
							<strong><?php echo esc_html( $delivery_time ); ?></strong>
						</div>
					<?php endif; ?>
				</div>

				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

				<div class="thankyou__actions">
					<a href="<?php echo esc_url( home_url( '/#catalog' ) ); ?>" class="btn btn--secondary">
						<?php esc_html_e( 'Вернуться в каталог', 'pirogova' ); ?>
					</a>
					<a href="<?php echo esc_url( wc_get_account_orders_endpoint_url() ); ?>" class="btn btn--primary">
						<?php esc_html_e( 'Мои заказы', 'pirogova' ); ?>
					</a>
				</div>

			</div>
		</section>

	<?php else : ?>

		<section class="thankyou">
			<div class="container">
				<p><?php esc_html_e( 'Заказ не найден.', 'pirogova' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'На главную', 'pirogova' ); ?>
				</a>
			</div>
		</section>

	<?php endif; ?>

</div>
