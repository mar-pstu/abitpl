<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$title = trim( get_theme_mod( 'error404title' ) );
$description = trim( apply_filters( 'the_content', get_theme_mod( 'error404description' ) ) );


get_header();


?>


<section class="section">
	<div class="container">
		
		<?php if ( ! empty( $title ) ) : ?>
			<h1 id="error404-title"><?php echo $title; ?></h1>
		<?php endif; ?>
		
		<?php if ( ! empty( $description ) ) : ?>
			<div id="error404-description" class="description"><?php echo $description; ?></div>
		<?php endif; ?>

		<a class="permalink" href="<?php echo home_url( '/', null ); ?>">
			<?php _e( 'На главную', ABITPL_TEXTDOMAIN ); ?>
		</a>

	</div>
</section>


<?php get_footer();