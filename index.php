<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


get_header();


?>


<section class="section">
	<div class="container">

		<h1><?php the_archive_title( '', '' ); ?></h1>

		<?php get_template_part( 'parts/breadcrumbs' ); ?>

		<?php the_archive_description( '', '' ); ?>

		<?php if ( have_posts() ) : ?>

			<div class="row">

				<?php

					while ( have_posts() ) :

						the_post();

						include get_theme_file_path( 'views/entry-archive.php' );

					endwhile; ?>

				?>

			</div>

			<?php the_posts_pagination(); ?>

		<?php else : include get_theme_file_path( 'views/no-entries.php' ); endif; ?>
	

	</div>
</section>


<?php get_footer();