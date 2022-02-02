<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


get_header();


?>


<section class="section">
	<div class="container">
		

		<?php if ( have_posts() ) : ?>

			<h1><?php the_title( '', '', true ); ?></h1>

			<?php get_template_part( 'parts/breadcrumbs' ); ?>

			<div class="content">
				<?php the_content(); ?>
			</div>

			<?php get_template_part( 'parts/pager' ); ?>

		<?php else : include get_theme_file_path( 'views/no-entries.php' ); endif; ?>

	</div>
</section>


<?php get_footer();