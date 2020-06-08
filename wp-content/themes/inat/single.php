<?php
/**
 * The template for displaying all single posts.
 *
 * @package Sydney
 */

get_header(); ?>

	<?php if (get_theme_mod('fullwidth_single')) { //Check if the post needs to be full width
		$fullwidth = 'fullwidth';
	} else {
		$fullwidth = '';
	} ?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area col-md-9 <?php echo $fullwidth; ?>">

		<?php sydney_yoast_seo_breadcrumbs(); ?>

		<main id="main" class="post-wrap" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>
			<div class="row">
			<?php //sydney_post_navigation(); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
			</div>

			<div class="row">
				<div id="related-posts-box" class="primary-section">
				<h3>Related Posts</h3>
				<?php
					echo do_shortcode('[carousel-horizontal-posts-content-slider]');
				?>
				</div>
			</div>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
		<?php /*
		<div class="posts-layout">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php 
				if($counter % 3 == 0) : 
				if($counter > 0) : ?>
				</div>
				<?php endif; ?>
				<div class="article-row">
				<?php endif; ?>
				<?php
					if ( $layout != 'classic-alt' ) {
						get_template_part( 'content', get_post_format() );
					} else {
						get_template_part( 'content', 'classic-alt' );
					}
				?>				

			<?php 
				$counter ++;
			endwhile; ?>
			</div>
		</div>
		*/
		?>
	</div><!-- #primary -->

<?php get_footer(); ?>
