<?php
/**
 * Fallback index template.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="site-main">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Записи не найдены.', 'pirogova' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
