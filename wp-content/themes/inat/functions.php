<?php

function  inat_enqueue_styles(){
	wp_enqueue_style('reset',get_stylesheet_directory_uri().'/helpers/reset.css');
	//wp_enqueue_style('font',get_stylesheet_directory_uri().'/font/font.css');
	wp_enqueue_style('updates',get_stylesheet_directory_uri().'/css/updates.css');
	wp_enqueue_style('mobile',get_stylesheet_directory_uri().'/css/mobile.css');
	wp_register_style( 'bootstrap-css' , get_stylesheet_directory_uri() . '/css/bootstrap.css', '' , '3.3.7', 'all' );
	wp_register_script( 'bootstrap-js' , get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
	wp_register_script( 'countdown-js' , get_stylesheet_directory_uri() . '/js/jquery.countdown.js', array( 'jquery' ), '3.3.7', true );
	wp_enqueue_script( 'bootstrap-js' );
	wp_enqueue_script( 'countdown-js' );
	wp_enqueue_style( 'bootstrap-css' );	
	wp_enqueue_script('main',get_stylesheet_directory_uri().'/js/main.js');
}

if(wp_is_mobile()){
	show_admin_bar(false);
}

add_action('wp_enqueue_scripts','inat_enqueue_styles');
require_once('wollson-inc/helpers.php');
require_once('wollson-inc/shortcodes.php');

add_filter( 'the_content', 'wpautop' );
add_filter( 'the_excerpt', 'wpautop' );

function get_agenda_posts(){
	ob_start();
	$args = array(
		'post_type'=>'agenda-item',
        'orderby' => 'meta_value_num',
        'meta_key' => '_expiration-date',
        'order' => 'ASC',
        'posts_per_page' => -1
	);
	$query = new WP_Query($args);

	$time_colors = array('#63c2c7','#f39c93','#e82e8a','#8291c7');
	?>
	<div id="agenda-tab-container">
	<?php

	$counter = 0;
	$temp_date = "";
	$active = "";
	if($query->have_posts()){
		?>
		<ul class="agenda-tabs">
		<?php
		while($query->have_posts()) : $query->the_post();  
			$expiration = get_post_meta(get_the_ID(),'_expiration-date',true);
			$date = date('M d',$expiration);
			$full_day_name = date('l',$expiration);
			$url_date = strtolower(str_replace(" ", "-", $date));
			$class = 'item-'.$counter;
			if($counter == 0) $active = "active";
			else $active = "";
			if($temp_date != $date){
				?>
				<li class="tab-header-item <?= $active ?>" data-target="<?= $class ?>" data-date="<?= $url_date ?>"><h4><?= $date; ?></h4><p class="day-name"><?= $full_day_name ?></p></li>
				<?php
			}

			?>
			
			<?php
			$counter ++; 
			$temp_date = $date;
		endwhile;
		?>
		</ul>
		<?php
	}

	if($query->have_posts()){
		$temp_date = "";
		$counter = 0;

		$time_counter = 0;

		?>
		<div class="tab-items">
		<?php
		while($query->have_posts()) : $query->the_post();  
			$expiration = get_post_meta(get_the_ID(),'_expiration-date',true); 
			$date = date('M d',$expiration);
			$time = date('H:i', $expiration);
			$class = 'item-'.$counter;

			$speakers = get_post_meta(get_the_ID(),'_custom_post_type_onomies_relationship',false);
			
			$location = get_post_meta(get_the_ID(),'wpcf-location',true);
			$time_start = get_post_meta(get_the_ID(),'wpcf-time-start',true);
			$time_end = get_post_meta(get_the_ID(),'wpcf-time-end',true);
			//var_dump($speakers);

			if($counter == 0) $active = "active";
			else $active = "";

			if($temp_date != $date){
				$time_counter = 0;
				if($counter > 0) {
					?>
					</div>
					<?php
				}
				?>

				<div class="tab-item <?= $class; ?> <?= $active ?>">
				<?php
			}

			if($time_counter % 4 == 0) $time_counter = 0;
			$time_color = $time_colors[$time_counter];

			?>
			<div class="row">
				<div class="col-md-3 agenda-time">
					<?php if(my_wp_is_mobile()) : ?>
					<h2><?php echo get_the_title(); ?></h2>
					<?php 		
					endif;

					//var_dump($expiration);
					echo '<h1 style="color:'.$time_color.' !important">';  
						if($time_start) echo $time_start;
						if($time_end) echo "-" . $time_end;
					echo '</h1>';
					echo '<h4>' . $location . '</h1>'; 
					?>
				</div>
				<div class="col-md-9 agenda-right">
					<div class="agenda-header">
						<h2><?php if(!my_wp_is_mobile()) echo get_the_title(); ?></h2>
						<?php  the_content(); ?>
					</div>
					<div class="agenda-footer">
					
					<table class="agenda-speaker">
					<?php 
					foreach ($speakers as $speaker_id) {
						$speaker = get_post($speaker_id);
						$name = $speaker->post_title;
						$excerpt = $speaker->post_excerpt;
						$image = get_the_post_thumbnail($speaker_id);	
						$location = get_post_meta($speaker_id,'wpcf-location',true);			
						?>		
							<tr>
							<td>
								<?php echo $image; ?>
							</td>
							<td>
								<p><?= $name; ?></p>
								<span><?= $excerpt; ?></span>
								<div class="item-location-box"><span class="item-location"><?= $location ?></span></div>
							</td>
							</tr>
						<?php						
					}
					?>
					</table>
					</div>
				</div>
			</div>
		<?php
		$counter ++;
		$time_counter ++;
		$temp_date = $date;
		endwhile;
		?>
		</div>
		<?php
		}
	?>
	</div> 
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode('get_agenda_posts','get_agenda_posts');
?>