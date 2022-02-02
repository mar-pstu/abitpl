<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$logo_usedby = ( bool ) get_theme_mod( 'sideabarlogousedby' );
$socials_usedby = ( bool ) get_theme_mod( 'sideabarsocialsusedby' );


?>


<aside class="aside aside" id="aside">
	<div class="container mb-1">
		<div class="row">

			<?php if ( $logo_usedby || $socials_usedby ) : ?>
				<div class="widget">

					<?php if ( $logo_usedby && has_custom_logo() ) : ?>
						<figure id="aside-logo" class="ml-0 mr-0 mt-0">
							<?php the_custom_logo(); ?>
						</figure>
					<?php endif; ?>

					<?php

						if ( $socials_usedby ) {
							get_template_part( 'parts/socials' );
						}

					?>

				</div>
			<?php endif; ?>

			<?php

				if ( is_active_sidebar( 'basement' ) ) {
					dynamic_sidebar( 'basement' );
				}

			?>

		</div>
	</div>
</aside>