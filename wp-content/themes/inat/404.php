<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Sydney
 */

get_header(); ?>

	<div id="primary" class="content-area fullwidth">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found primary-section">
				<div class="col-md-6">
					<header class="page-header">
						<h1 class="big-title">404</h1>
						<h3>PAGE NOT FOUND</h3>
						<p>Sorry, looks like something went wrong.</p>
					</header><!-- .page-header -->
				</div>
				<div class="col-md-6">
					<img src="<?= get_stylesheet_directory_uri(); ?>/images/icon-robot.svg" class="icon-robot" />
				</div>
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
