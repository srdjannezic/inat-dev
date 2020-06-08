<?php
/**
 * The home template file.
 *
 * @package Sydney
 */

get_header(); 

$layout = sydney_blog_layout();

$counter = 0;
?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area col-md-9 <?php echo esc_attr($layout); ?> section primary-section">

		<div id="blog-category">
			<div id="blog-category-header">
				<h4>Categories</h4>
				<i class="fa fa-chevron-right show-on-mobile" aria-hidden="true"></i>
			</div>
			<?php
			$categories = get_categories();
			?>
			<ul id="blog-category-list">
				<?php foreach ($categories as $cat) : 
					//var_dump($cat);
					$link = get_category_link($cat->cat_ID );
					//var_dump($link);
				?>
					<li><a href="<?= $link ?>"><p><?php echo $cat->name ?></p></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php sydney_yoast_seo_breadcrumbs(); ?>
		
		<main id="main" class="post-wrap" role="main">

		<?php if ( have_posts() ) : ?>

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

		<?php
			the_posts_pagination( array(
				'mid_size'  => 1,
			) );
		?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php 
	if ( ( $layout == 'classic-alt' ) || ( $layout == 'classic' ) ) :
	get_sidebar();
	endif;
?>
<?php get_footer(); ?>
