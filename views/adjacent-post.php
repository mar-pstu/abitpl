<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$post_class = [ 'pager__item', 'item' ];


if ( isset( $key ) && ! empty( $key ) ) {
	$post_class[] = $key;
}


?>


<a <?php post_class( $post_class, get_the_ID() ); ?> href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

	<div class="arrow">
	
		<?php if ( isset( $label ) && ! empty( $label ) ) : ?>
			<div class="sr-only"><?php echo $label; ?></div>
		<?php endif; ?>
	
	</div>
	
	<h4 class="title h4"><?php the_title( '', '', true ); ?></h4>

</a>