<?php
/**
 * Default page template.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="site-main page-content">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
