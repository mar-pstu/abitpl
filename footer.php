<?php


namespace abitpl;


if ( ! defined( 'ABSPATH' ) ) { exit; };


$copytext = get_theme_mod( 'footercopytext' );


?>

			</main>

			<div class="wrapper__item wrapper__item--basement basement" id="fbasementooter">

				<?php get_sidebar(); ?>

				<footer class="footer" id="footer">
					<div class="container small">
						<div class="row">

							<?php if ( ! empty( $copytext ) ) : ?>
								<p id="footer-copyright" class="col mr-auto copyright"><?php printf( $copytext, date( 'Y' ) ); ?></p>
							<?php endif; ?>
							
							<p class="col ml-auto developer">
								<?php _e( 'Разработка: <a href="https://ссt.pstu.edu/">ЦКТ ПДТУ</a>', ABITPL_TEXTDOMAIN ); ?>
							</p>

						</div>
					</div>
				</footer>
			</div>
			
			<?php wp_footer(); ?>

		</div>
	</body>
</html>