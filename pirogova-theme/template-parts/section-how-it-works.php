<?php
/**
 * "How it works" section — 4 steps.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

$steps = [
	[
		'icon'  => 'choose',
		'num'   => '01',
		'title' => __( 'Выбираете пирог', 'pirogova' ),
		'desc'  => __( 'Смотрите каталог, выбираете любимый вкус и нужную граммовку', 'pirogova' ),
	],
	[
		'icon'  => 'order',
		'num'   => '02',
		'title' => __( 'Оформляете заказ', 'pirogova' ),
		'desc'  => __( 'Указываете адрес доставки и удобное время, оплачиваете онлайн', 'pirogova' ),
	],
	[
		'icon'  => 'bake',
		'num'   => '03',
		'title' => __( 'Мы готовим', 'pirogova' ),
		'desc'  => __( 'Свежий пирог выпекается специально для вас из домашних продуктов', 'pirogova' ),
	],
	[
		'icon'  => 'deliver',
		'num'   => '04',
		'title' => __( 'Доставляем к вам', 'pirogova' ),
		'desc'  => __( 'Курьер привозит тёплый пирог прямо к вашей двери', 'pirogova' ),
	],
];
?>

<section class="how-it-works" id="how-it-works" aria-labelledby="hiw-title">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title" id="hiw-title"><?php esc_html_e( 'Как это работает', 'pirogova' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Всего 4 простых шага до вашего любимого пирога', 'pirogova' ); ?></p>
		</div>

		<ol class="hiw__steps" role="list">
			<?php foreach ( $steps as $step ) : ?>
				<li class="hiw__step">
					<div class="hiw__step-num" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></div>
					<div class="hiw__step-icon hiw__step-icon--<?php echo esc_attr( $step['icon'] ); ?>" aria-hidden="true">
						<?php echo pirogova_hiw_icon( $step['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3 class="hiw__step-title"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="hiw__step-desc"><?php echo esc_html( $step['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php
/**
 * Returns inline SVG icon for a step.
 */
function pirogova_hiw_icon( string $name ): string {
	$icons = [
		'choose'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="32" cy="28" r="16"/><path d="M20 44c0-6.627 5.373-12 12-12s12 5.373 12 12"/><path d="M32 12v4M32 40v4M16 28h4M44 28h4"/></svg>',
		'order'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="12" y="8" width="40" height="48" rx="4"/><path d="M22 20h20M22 30h20M22 40h12"/><circle cx="46" cy="46" r="10" fill="white" stroke="currentColor"/><path d="M42 46l2.5 2.5L50 42"/></svg>',
		'bake'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 40h44v8a4 4 0 01-4 4H14a4 4 0 01-4-4v-8z"/><path d="M10 40c0-8 4-14 10-18l4-10h16l4 10c6 4 10 10 10 18"/><path d="M24 32c0-4 4-6 8-4s8 0 8-4"/></svg>',
		'deliver' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="20" width="36" height="24" rx="3"/><path d="M38 28h10l8 10v6H38V28z"/><circle cx="16" cy="50" r="5"/><circle cx="46" cy="50" r="5"/><path d="M11 50H2M21 50h17M51 50h5"/></svg>',
	];
	return $icons[ $name ] ?? '';
}
