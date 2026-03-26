<?php
/**
 * "О нас в СМИ" (Media) section.
 * Uses ACF (or fallback to post meta) for media mentions.
 * Media mentions are stored as a custom post type "pirogova_media".
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

// Register CPT if not already done via theme-setup (called early via init).
if ( ! post_type_exists( 'pirogova_media' ) ) {
	pirogova_register_media_cpt();
}

$media_items = get_posts( [
	'post_type'      => 'pirogova_media',
	'posts_per_page' => 8,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
] );

if ( empty( $media_items ) ) {
	return;
}
?>

<section class="media-section" id="media" aria-labelledby="media-title">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title" id="media-title"><?php esc_html_e( 'О нас в СМИ', 'pirogova' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Нас уже заметили', 'pirogova' ); ?></p>
		</div>

		<div class="media-grid">
			<?php foreach ( $media_items as $item ) : ?>
				<?php
				$logo_id   = get_post_meta( $item->ID, '_media_logo', true );
				$url       = get_post_meta( $item->ID, '_media_url', true );
				$quote     = get_post_meta( $item->ID, '_media_quote', true );
				$logo_src  = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
				?>
				<div class="media-card">
					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="media-card__link" aria-label="<?php echo esc_attr( $item->post_title ); ?>">
					<?php endif; ?>

					<?php if ( $logo_src ) : ?>
						<img src="<?php echo esc_url( $logo_src ); ?>"
						     alt="<?php echo esc_attr( $item->post_title ); ?>"
						     class="media-card__logo"
						     loading="lazy">
					<?php else : ?>
						<span class="media-card__name"><?php echo esc_html( $item->post_title ); ?></span>
					<?php endif; ?>

					<?php if ( $quote ) : ?>
						<blockquote class="media-card__quote">
							<p><?php echo esc_html( $quote ); ?></p>
						</blockquote>
					<?php endif; ?>

					<?php if ( $url ) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/**
 * Register "Media mentions" custom post type.
 */
add_action( 'init', 'pirogova_register_media_cpt' );
function pirogova_register_media_cpt(): void {
	if ( post_type_exists( 'pirogova_media' ) ) {
		return;
	}
	register_post_type( 'pirogova_media', [
		'labels'        => [
			'name'               => esc_html__( 'СМИ и пресса', 'pirogova' ),
			'singular_name'      => esc_html__( 'Упоминание в СМИ', 'pirogova' ),
			'add_new_item'       => esc_html__( 'Добавить упоминание', 'pirogova' ),
			'edit_item'          => esc_html__( 'Редактировать упоминание', 'pirogova' ),
		],
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-megaphone',
		'supports'      => [ 'title', 'page-attributes' ],
		'rewrite'       => false,
	] );
}

/**
 * Add meta boxes for media CPT.
 */
add_action( 'add_meta_boxes', 'pirogova_media_meta_boxes' );
function pirogova_media_meta_boxes(): void {
	add_meta_box(
		'pirogova_media_fields',
		esc_html__( 'Данные публикации', 'pirogova' ),
		'pirogova_media_meta_box_cb',
		'pirogova_media',
		'normal',
		'high'
	);
}

function pirogova_media_meta_box_cb( WP_Post $post ): void {
	wp_nonce_field( 'pirogova_media_save', 'pirogova_media_nonce' );
	$url   = get_post_meta( $post->ID, '_media_url', true );
	$quote = get_post_meta( $post->ID, '_media_quote', true );
	?>
	<p>
		<label for="media_url"><strong><?php esc_html_e( 'Ссылка на статью', 'pirogova' ); ?></strong></label><br>
		<input type="url" id="media_url" name="media_url" value="<?php echo esc_attr( $url ); ?>" class="widefat">
	</p>
	<p>
		<label for="media_quote"><strong><?php esc_html_e( 'Цитата из статьи', 'pirogova' ); ?></strong></label><br>
		<textarea id="media_quote" name="media_quote" rows="3" class="widefat"><?php echo esc_textarea( $quote ); ?></textarea>
	</p>
	<p>
		<label><strong><?php esc_html_e( 'Логотип издания', 'pirogova' ); ?></strong></label><br>
		<?php
		$logo_id = get_post_meta( $post->ID, '_media_logo', true );
		echo wp_get_attachment_image( absint( $logo_id ), 'thumbnail', false, [ 'id' => 'media_logo_preview', 'style' => $logo_id ? '' : 'display:none' ] );
		?>
		<br>
		<input type="hidden" id="media_logo" name="media_logo" value="<?php echo esc_attr( $logo_id ); ?>">
		<button type="button" class="button" id="media_logo_btn"><?php esc_html_e( 'Выбрать изображение', 'pirogova' ); ?></button>
		<script>
			jQuery(function($){
				$('#media_logo_btn').on('click', function(){
					var frame = wp.media({ title: '<?php esc_html_e( 'Выберите логотип', 'pirogova' ); ?>', multiple: false });
					frame.on('select', function(){
						var att = frame.state().get('selection').first().toJSON();
						$('#media_logo').val(att.id);
						$('#media_logo_preview').attr('src', att.url).show();
					});
					frame.open();
				});
			});
		</script>
	</p>
	<?php
}

add_action( 'save_post_pirogova_media', 'pirogova_save_media_meta' );
function pirogova_save_media_meta( int $post_id ): void {
	if ( ! isset( $_POST['pirogova_media_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pirogova_media_nonce'] ) ), 'pirogova_media_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['media_url'] ) ) {
		update_post_meta( $post_id, '_media_url', esc_url_raw( wp_unslash( $_POST['media_url'] ) ) );
	}
	if ( isset( $_POST['media_quote'] ) ) {
		update_post_meta( $post_id, '_media_quote', sanitize_textarea_field( wp_unslash( $_POST['media_quote'] ) ) );
	}
	if ( isset( $_POST['media_logo'] ) ) {
		update_post_meta( $post_id, '_media_logo', absint( wp_unslash( $_POST['media_logo'] ) ) );
	}
}
