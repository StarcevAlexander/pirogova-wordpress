<?php
/**
 * Front page template — one-page layout.
 *
 * @package Pirogova
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main-content">
	<?php get_template_part( 'template-parts/section', 'hero' ); ?>
	<?php get_template_part( 'template-parts/section', 'catalog' ); ?>
	<?php get_template_part( 'template-parts/section', 'how-it-works' ); ?>
	<?php get_template_part( 'template-parts/section', 'media' ); ?>
	<?php get_template_part( 'template-parts/section', 'contacts' ); ?>
</main>

<?php get_template_part( 'template-parts/product', 'popup' ); ?>

<?php
get_footer();
