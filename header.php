<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


?>


<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?> >
	
	<?php get_template_part( 'parts/head' ); ?>

	<body <?php body_class( '' ); ?> >
		<div class="wrapper" id="wrapper">

			<div class="wrapper__item wrapper__item--firstscreen firstscreen" id="firstscreen">
				<header class="header" id="header">
					<div class="container">
						
						<?php

							the_custom_logo();
						
							if ( has_nav_menu( 'main' ) ) {
								wp_nav_menu( [
									'theme_location'  => 'main',
									'menu'            => 'main',
									'container'       => false,
									'menu_id'         => 'menu',
									'menu_class'      => 'menu',
									'echo'            => true,
									'depth'           => 1,
									'fallback_cb'     => '__return_empty_string',
								] );
								get_template_part( 'parts/burger' );
							}

						?>

					</div>
				</header>

				<?php if ( is_front_page() ) : get_template_part( 'parts/section', 'jumbotron' ); endif; ?>
				
			</div>

			<main class="wrapper__item wrapper__item--main main" id="main">