<?php
/*
Plugin Name: Infinite Scroll and Load More Ajax Pagination 
Plugin URI:  http://pixellanguage.com/wordpress-infinite-and-load-more-pagination-plugin/
Description: Really cool plugin to make your blog with ajax pagination pagination, with plugin site will have infinite and load more pagination capability.
Version:     1.0
Author:      Rajan Shriwastava
Author URI:  http://pixellanguage.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: pix
*/

define('INFINITE_LOAD_PATH',plugin_dir_path( __FILE__ ));
define('INFINITE_LOAD_URL',plugins_url( '',__FILE__));

/*
Register default value
*/

function pix_activate_infinite_load() {

    $default=array(
		'enable_infinite_load'=>'',
		'navigation_selector'=>'nav.navigation',
		'next_selector'=>'nav.navigation a.next',
		'content_selector'=>'#main',
		'item_selector'=>'article.post',
		'pagination_type'=>'infinite',
		'loading_image'=>INFINITE_LOAD_URL.'/images/loader.gif',
		'button_label'=>__('Load More','pix'),
		'button_class'=>'',
		'finished_msg'=>__('End of loading','pix')
	);
	
	$pix_infinite_load=get_option('pix_infinite_load');
	if(empty($pix_infinite_load))
	update_option('pix_infinite_load',$default);
}
register_activation_hook( __FILE__, 'pix_activate_infinite_load' );


/*
Add javascript and css for front end
*/
function pix_enqueue_script() {
	
	$enable_infinite_load=pix_get_option('enable_infinite_load');
	if(!empty($enable_infinite_load)){
		wp_enqueue_style( 'pix-infiniteload', INFINITE_LOAD_URL . '/css/infiniteload.css' );
	
		wp_enqueue_script( 'pix-infiniteload-lib', INFINITE_LOAD_URL . '/js/jquery.infiniteload.js', array('jquery'),false,true);
		wp_enqueue_script( 'pix-infiniteload', INFINITE_LOAD_URL . '/js/infiniteload.js', array('jquery','pix-infiniteload-lib'),false,true);
		wp_localize_script( 'pix-infiniteload', 'pix_infiniteload', array('navSelector'=>pix_get_option('navigation_selector'),'contentSelector'=>pix_get_option('content_selector'),'nextSelector'=>pix_get_option('next_selector'),'itemSelector'=>pix_get_option('item_selector'),'paginationType'=>pix_get_option('pagination_type'),'loadingImage'=>pix_get_option('loading_image'),'loadingButtonLabel'=>pix_get_option('button_label'),'loadingButtonClass'=>pix_get_option('button_class'),'loadingFinishedText'=>pix_get_option('finished_msg')) );
	}
}

add_action( 'wp_enqueue_scripts', 'pix_enqueue_script' );


/*
Create admin menu
*/
add_action( 'admin_menu', 'pix_admin_menu' );

function pix_admin_menu() {

	$pix_menu=add_menu_page( __('Pix Plugins','pix'), __('Pix Plugins','pix'), 'manage_options', 'pix-plugins',NULL,INFINITE_LOAD_URL.'/images/logo.png');
	$pix_infinite_load_menu=add_submenu_page( 'pix-plugins',__('Infinite Load','pix'),__('Infinite Load','pix'), 'manage_options', 'pix-infinite-load', 'pix_infinite_load_settings' );
	remove_submenu_page( 'pix-plugins', 'pix-plugins' );
	add_action( 'admin_print_scripts-' . $pix_infinite_load_menu, 'pix_add_admin_assets' );

}


/*
Admin menu settings
*/
function pix_infinite_load_settings()
{

	?>
    <div class="wrap">
		<h2><?php _e('General Settings','pix');?></h2>

            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <table class="form-table">
                <?php
                settings_fields( 'pix-infinite-load-settings' );
                do_settings_sections( 'pix-infinite-load-settings' );
                ?>
                <tr valign="top">
                    <th scope="row"><?php _e('Enable Infinite Load','pix'); ?></th>
                    <td>
                    <label class="pix-enable <?php echo (pix_get_option('enable_infinite_load')=='true')?'selected':'';  ?>"><span><?php _e('Enable','pix') ?></span></label>
                    <label class="pix-disable <?php echo (pix_get_option('enable_infinite_load')!='true')?'selected':'';  ?>"><span><?php _e('Disable','pix') ?></span></label>
                    <input name="pix_infinite_load[enable_infinite_load]" type="checkbox" class="pix-hidden" value="true" <?php checked(pix_get_option('enable_infinite_load'),'true',true); ?> />
                    </td>
                 </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Navigation Selector','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[navigation_selector]" type="text" class="regular-text" value="<?php echo pix_get_option('navigation_selector'); ?>"/>
                    <div><?php _e('Div containing your posts navigation (pagination)','pix'); ?></div>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Next Selector','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[next_selector]" type="text" class="regular-text" value="<?php echo pix_get_option('next_selector'); ?>"/>
                    <div><?php _e('Link to next page of content (Next Page link selector)','pix'); ?></div>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Item Selector','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[item_selector]" type="text" class="regular-text" value="<?php echo pix_get_option('item_selector'); ?>"/>
                    <div><?php _e('Div containing individual post','pix'); ?></div>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Content Selector','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[content_selector]" type="text" class="regular-text" value="<?php echo pix_get_option('content_selector'); ?>"/>        
                    <div><?php _e('Div containg your posts','pix'); ?></div>
                    </td>
                 </tr>     
                 
                 <tr valign="top">
                    <th scope="row"><?php _e('Pagination Type','pix'); ?></th>
                    <td>
                        <?php $pix_pagination_type=pix_get_option('pagination_type');?>
                        <select name="pix_infinite_load[pagination_type]" >
                            <option value="infinite" <?php selected($pix_pagination_type,'infinite',true); ?>><?php _e('Infinite','pix');?></option>
                            <option value="more" <?php selected($pix_pagination_type,'more',true); ?>><?php _e('Load More Button','pix');?></option>
                        </select>
                        <div><?php _e('Select type of pagination','pix'); ?></div>
                    </td>
                 </tr>
                 
                 <tr valign="top"  <?php echo $pix_pagination_type=='more'?'style="display:none"':'';  ?>>
                    <th scope="row"><?php _e('Loading Image','pix'); ?></th>
                    <td>
                     <input type="text" name="pix_infinite_load[loading_image]" class="regular-text" value="<?php echo pix_get_option('loading_image');?>" size="25" />
                     <input type='button' class="button-primary pix-upload" value="Upload Image" />
                     <div><?php _e('Loader image to display when posts are retrieving','pix'); ?></div>
                     <div class="pix-upload-snap"><img src="<?php echo pix_get_option('loading_image');?>"/></div>                 
                    </td>
                 </tr>
                 <tr valign="top" <?php echo $pix_pagination_type=='infinite'?'style="display:none"':'';  ?>>
                    <th scope="row"><?php _e('Button Label','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[button_label]" type="text" class="regular-text" value="<?php echo pix_get_option('button_label'); ?>"/>
                    <div><?php _e('Add Load More Button Text (Default: Load More)','pix'); ?></div>
                    </td>
                 </tr>
                 <tr valign="top"  <?php echo $pix_pagination_type=='infinite'?'style="display:none"':'';  ?>>
                    <th scope="row"><?php _e('Button Custom Class','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[button_class]" type="text" class="regular-text" value="<?php echo pix_get_option('button_class'); ?>"/>
                    <div><?php _e('Add custom class to customize button style (Use space for multiple class)','pix'); ?></div>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Finished Message','pix'); ?></th>
                    <td>
                    <input name="pix_infinite_load[finished_msg]" type="text" class="regular-text" value="<?php echo pix_get_option('finished_msg'); ?>"/>
                    <div><?php _e('Text to display when no addition post are available','pix'); ?></div>
                    </td>
                 </tr>
               </table>
                <?php submit_button(); ?>
            
            </form>

</div>
    
    <?php 

}

/*
Add javascript and css files for admin settings
*/
function pix_add_admin_assets()
{
	wp_enqueue_style( 'pix-admin', INFINITE_LOAD_URL . '/css/admin.css' );
	
	wp_enqueue_media();
	wp_enqueue_script( 'pix-admin', INFINITE_LOAD_URL . '/js/admin.js', array('jquery'));

}

/*
Admin settings options
*/
add_action( 'admin_init', 'pix_admin_options_settings' );

function pix_admin_options_settings()
{
	register_setting( 'pix-infinite-load-settings', 'pix_infinite_load' );
}

/*
Custom function to get plugin options
*/
$pix_infinite_load_settings=get_option('pix_infinite_load');
function pix_get_option($option)
{
	global $pix_infinite_load_settings;
	
	if(empty($option))
	return false;
	
	if(!empty($pix_infinite_load_settings[$option]))
	return $pix_infinite_load_settings[$option];
	else
	return false;
	
}

?>