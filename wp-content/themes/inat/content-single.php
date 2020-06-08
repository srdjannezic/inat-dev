<?php
/**
 * @package Sydney
 */
?>
<div id="single-post-header" class="secondary-section">
	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'post_feat_image' ) != 1 ) ) : ?>
		<div class="entry-thumb">
			<?php the_post_thumbnail('large-thumb'); ?>
			<div class="row">
			<?php the_title( '<h1 class="title-post">', '</h1>' ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<div class="row">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('sydney_inside_top_post'); ?>

	<header class="entry-header">
		
		<div class="meta-post">
			<?php sydney_all_cats(); ?>
		</div>

		<?php if (get_theme_mod('hide_meta_single') != 1 ) : ?>
		<div class="single-meta row author-container">
			<div class="author-box">
				<?php the_author_image(); ?>
				<p class="single-post-author"><?php the_author(); ?></p> 
				<p class="single-post-date"><?php the_date('d.m.Y'); ?></p>
			</div>

		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		/*
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );
		*/
		?>
	</div><!-- .entry-content -->
	<div id="secondary" class="primary-section">
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
	</div>
	<footer class="entry-footer">
		<?php sydney_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php do_action('sydney_inside_bottom_post'); ?>

</article><!-- #post-## -->
</div>