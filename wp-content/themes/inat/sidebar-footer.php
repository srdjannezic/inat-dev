<?php
/**
 *
 * @package Sydney
 */
?>


	<?php //Set widget areas classes based on user choice
		$widget_areas = get_theme_mod('footer_widget_areas', '3');
		if ($widget_areas == '3') {
			$cols = 'col-md-4';
		} elseif ($widget_areas == '4') {
			$cols = 'col-md-3';
		} elseif ($widget_areas == '2') {
			$cols = 'col-md-6';
		} else {
			$cols = 'col-md-12';
		}
	?>

	<div id="sidebar-footer" class="footer section secondary-section" role="complementary">
		<div class="container row">
			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="sidebar-column">
					<?php dynamic_sidebar( 'footer-1'); ?>
				</div>
			<?php endif; ?>	
		</div>	
	</div>