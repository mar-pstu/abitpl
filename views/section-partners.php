<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


?>


<section class="section section--partners partners" id="partners">
	<div class="container">

		<?php if ( isset( $title ) && ! empty( $title ) ) : ?>
			<h2 id="partners-title" class="sr-only"><?php echo $title; ?></h2>
		<?php endif; ?>
		
		<?php if ( isset( $content ) && ! empty( $content ) ) : ?>
			<div class="row slider-content" id="partners-slider">
				<?php echo $content; ?>
			</div>
			<div class="d-flex center">
				<button class="slider-arrow prev" id="partners-slider-next"><span class="sr-only"><?php _e( 'Предыдущий слайд', ABITPL_TEXTDOMAIN ); ?></span></button>
				<button class="slider-arrow next" id="partners-slider-prev"><span class="sr-only"><?php _e( 'Следующий слайд', ABITPL_TEXTDOMAIN ); ?></span></button>
			</div>
		<?php endif; ?>

	</div>
</section>