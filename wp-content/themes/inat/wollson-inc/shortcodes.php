<?php

/*
Author: Srdjan Nezic
*/

function get_posttypes($atts){
	ob_start();
	$type = isset($atts['type']) ? $atts['type'] : false;
	$per_row = isset($atts['per_row']) ? $atts['per_row'] : 0;
	$per_page = isset($atts['per_page']) ? $atts['per_page'] : false;
	$category = isset($atts['category']) ? $atts['category'] : false;
	$show_cat = isset($atts['show_cat']) ? true : false;
	$row_col_counter = 0;
	$args = array();

	if($type) $args['post_type'] = $type;
	if($per_page) $args['posts_per_page'] = $per_page;
	if($category) $args['category__in'] = $category;

	$col_class = "col-md-4";

	if($per_row == 1) $col_class = 'col-md-12';
	if($per_row == 2) $col_class = 'col-md-6';
	if($per_row == 3) $col_class = 'col-md-4';
	if($per_row == 4) $col_class = 'col-md-3';
	if($per_row == 5) $col_class = 'col-md-25';
	if($per_row == 6) $col_class = 'col-md-2';

	//if($show_cat == true) $col_class = "";

	$query = new WP_Query($args);
	$counter = 0;
	$row_counter = 0;
	if($query->have_posts()){
		while($query->have_posts()) : $query->the_post();  
		$location = get_post_meta(get_the_ID(),'wpcf-location',true);
		$networks = get_post_meta(get_the_ID(),'wpcf-url');
		
		if($row_counter % $per_row == 0) {
			$row_col_counter = 0;
			if($counter > 0) {
				echo "</div>";
			}
			echo "<div class='row speakers-row'>";
		}

		//var_dump(get_the_ID());
		
		if($show_cat == true) {
			//var_dump('expression');
		$cat_name = wp_get_post_terms(get_the_ID(),'speaker-category')[0]->name;
			//var_dump($cat_name);
			if($cat_name != $speaker_category){

				if($counter > 0) {
					$row_counter = 0;
				?>
					</div>
					</div>
				<?php 
				} 
				?>
				<?php if($row_counter % $per_row == 0) { ?>
					<div class='row'>
				<?php } ?>
				<div class="cat-column">
				<h3><?= $cat_name ?></h3>

			<?php 
			$speaker_category = $cat_name;
			} 
		}
			?>
			
				<div class="<?= $col_class; ?> speaker-col-<?= $row_col_counter ?> item-<?= $type; ?> item-<?= $type . "-" . get_the_ID() ?>">
					<div class="item-thumb" data-toggle="modal" data-backdrop="static"  data-target="#Modal<?= get_the_ID() ?>" backdrop>
						<?= get_the_post_thumbnail(); ?>
						<!-- <img src="<?= get_stylesheet_directory_uri() ?>/images/view.png" class="view-mask" />	 -->
					</div>
					<h3 class="item-title" data-toggle="modal" data-backdrop="static"  data-target="#Modal<?= get_the_ID() ?>" backdrop><?= get_the_title(); ?></h3>
					<div class="item-excerpt"><?= get_the_excerpt(); ?></div>
					<div class="item-location-box"><span class="item-location"><?= $location ?></span></div>
					<?php 
					if($type == "speaker" || $type == "member") : ?>
							<!-- Modal -->
							<div id="Modal<?= get_the_ID() ?>" class="modal fade speakerModal" role="dialog">
							  <div class="modal-dialog">
							    <!-- Modal content-->
							    <div class="modal-content">
							      <div class="modal-header">
							      	<button type="button" class="close" data-dismiss="modal">&times;</button>
							        <div class="item-thumb"><?= get_the_post_thumbnail(); ?></div>

							        <div class="item-text">
							        	<h3 class="item-title"><?= get_the_title(); ?></h3> 
							        	<div class="item-excerpt"><?= get_the_excerpt(); ?><br><!-- <span class="item-location"><?= $location ?></span> --></div>
							        	<div class="item-networks">
							        		<?php foreach ($networks as $network) {
							        			$network_icon = "globe";
							        			if(strpos($network, 'facebook') !== false) $network_icon = 'facebook';
							     				if(strpos($network, 'linkedin') !== false) $network_icon = 'linkedin';
							        			if(strpos($network, 'tumblr') !== false) $network_icon = 'tumblr';
							        			if(strpos($network, 'instagram') !== false) $network_icon = 'instagram';
							        			if(strpos($network, 'twitter') !== false) $network_icon = 'twitter';
							        			?>
							        			<span class="network"><a href="<?= $network ?>" target="_blank"><i class="fa fa-<?= $network_icon ?>" aria-hidden="true"></i></a></span>
							        			<?php
							        		} ?>
							        	</div>
							      	</div>
							      </div>
							      <div class="modal-body">
							        <?php the_content() ?>
							      </div>
							    </div>

							  </div>
							</div>
					<?php endif; ?>
			</div>
		<?php
		$counter ++;
		$row_counter++;
		$row_col_counter++;
		endwhile;
		wp_reset_postdata();
	}
	echo "</div>";
	return ob_get_clean();

}

add_shortcode('get_posttype','get_posttypes');
?>