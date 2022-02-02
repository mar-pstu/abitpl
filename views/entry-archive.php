<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


?>


<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry', null ); ?> >
	<div class="inner">

		<a class="thumbnail" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'archive', [ 'class' => 'wp-post-thumbnail' ] ); ?>
			<time class="small" datetime="<?php the_time( 'c' ); ?>"><?php the_time( get_option( 'date_format', 'j F Y' ) ); ?></time>
		</a>

		<h3 class="title">
			<a href="<?php the_permalink(); ?>"><?php the_title( '', '', true ); ?></a>
		</h3>

		<?php the_tags( '<ul class="tags"><li>', '</li><li>', '</li></ul>' ); ?>

		<?php the_excerpt(); ?>

		<a class="permalink" href="<?php the_permalink(); ?>">
			<?php _e( 'Подробнее', ABITPL_TEXTDOMAIN ); ?>
		</a>

	</div>
</article>