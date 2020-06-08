<?php
/**
 * @package Sydney
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="item-header">
	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'index_feat_image' ) != 1 ) ) : ?>
		<div class="entry-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('sydney-large-thumb'); ?></a>
		</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<a href="%s" rel="bookmark"><h5>', esc_url( get_permalink() ) ), '</h5></a>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-post">
		<?php if ( (get_theme_mod('full_content_home') == 1 && is_home() ) || (get_theme_mod('full_content_archives') == 1 && is_archive() ) ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-post -->
	</div>
	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() && get_theme_mod('hide_meta_index') != 1 ) : ?>
		<div class="meta-post">
			<span class="blog-author"><?php the_author(); ?></span> 
			<span><?php echo get_the_date('d.m.Y'); ?></span>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	<?php //sydney_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->