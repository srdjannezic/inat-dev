<?php
/*
Plugin Name: RPS Include Content
Plugin URI: http://redpixel.com/
Description: Adds the ability to include content on the current post or page from another. 
Version: 1.1.10
Author: Red Pixel Studios
Author URI: http://redpixel.com/
License: GPL3
Textdomain: rps-include-content
*/

/* 	Copyright (C) 2012 - 2017  Red Pixel Studios  (email : support@redpixel.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Adds the ability to include content on the current post or page from another.
 *
 * @package rps-include-content
 * @author Red Pixel Studios
 * @version 1.1.10
 */
 
if ( ! class_exists( 'RPS_Include_Content', false ) ) :

class RPS_Include_Content {

	/**
	 * The current version of the plugin for internal use.
	 * Be sure to keep this updated as the plugin is updated.
	 *
	 * @since 1.0
	 */
	const PLUGIN_VERSION = '1.1.10';
	
	/**
	 * The plugin's name for use in printing to the user.
	 *
	 * @since 1.0
	 */
	const PLUGIN_NAME = 'RPS Include Content';
	
	/**
	 * A unique prefix that identifies the plugin. Used for storing
	 * database options, naming interface elements, and so on.
	 *
	 * @since 1.0
	 */
	const PLUGIN_PREFIX = 'rps_include_content_';

	private static $current_page;

	public function __construct() {
		add_action( 'init', array( &$this, 'cb_init' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'cb_enqueue_styles_scripts' ) );
		add_action( 'admin_menu', array( $this, '_admin_menu' ) );
		add_action( 'admin_post_' . self::PLUGIN_PREFIX . 'action-save_settings', array( $this, '_admin_action_save_settings' ) );
		add_action( 'admin_init', array( $this, '_add_plugin_caps' ));
		register_activation_hook( __FILE__, array( $this, '_setup_default_settings' ));
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, '_add_settings_link' ) );
		add_action( 'plugins_loaded', array( $this, '_plugins_loaded' ) );
	}

	public function cb_init() {
		add_shortcode( 'rps-include', array( &$this, 'cb_include_shortcode' ) );
		add_shortcode( 'rps-include-content', array( &$this, 'cb_include_shortcode' ) );
		
		wp_register_style( 'rps-include-content', plugins_url( 'rps-include-content.css', __FILE__ ), false, '1.0.0' );
	}

	public function cb_enqueue_styles_scripts() {
		if ( current_user_can( 'edit_pages' ) || current_user_can( 'edit_posts' ) )
			wp_enqueue_style( 'rps-include-content' );
	}
	
	public function _setup_default_settings(){
		$current_blog_id = get_current_blog_id();
		// init Hover setting
		if ( is_multisite() )
			update_blog_option( $current_blog_id, '_' . self::PLUGIN_PREFIX . 'disable_hover', true );
		else
			update_option( '_' . self::PLUGIN_PREFIX . 'disable_hover', true );
				
		// init Private Content setting
		if ( is_multisite() )
			update_blog_option( $current_blog_id, '_' . self::PLUGIN_PREFIX . 'private_content', true );
		else
			update_option( '_' . self::PLUGIN_PREFIX . 'private_content', true );
		
		// Be sure to flush rewrite rules, in case the rewrite slugs changed.
		if ( is_multisite() )
			update_blog_option( $current_blog_id, '_' . self::PLUGIN_PREFIX . 'flush_rewrite_rules', 1 );
		else
			update_option( '_' . self::PLUGIN_PREFIX . 'flush_rewrite_rules', 1 );
	}
	
	public function _add_plugin_caps() {
    	$role = get_role( 'administrator' );
    	if ( $role )
			$role->add_cap( 'manage_rps_include_content' ); 
	}

	public function _add_settings_link( $links ) {
	    $settings_link = '<a href="options-general.php?page=rps_include_content_options_page">' . __( 'Settings', 'rps-include-content' ) . '</a>';
	  	array_push( $links, $settings_link );
	  	return $links;
	}
		
	/**
	 * Load the text domain for l10n and i18n.
	 *
	 * @since 1.1.2
	 */
	public function _plugins_loaded() {
		load_plugin_textdomain( 'rps-include-content', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . '/lang/' );
	}

	public function cb_include_shortcode( $atts, $content = null ) {
		global $shortcode_tags;
		
		// specify allowed values for shortcode attributes
		$allowed_titletag = array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6'
		);

		$allowed_content = array(
			'content',
			'excerpt',
			'lede',
			'full',
			'none'
		);
		
		$current_blog_id = get_current_blog_id();
		
		$disable_hover = ( is_multisite() ) ? get_blog_option( $current_blog_id, '_' . self::PLUGIN_PREFIX . 'disable_hover', 'hover' ) : get_option( '_' . self::PLUGIN_PREFIX . 'disable_hover', 'hover' );
		$private_content = (is_multisite() ) ? get_blog_option( $current_blog_id, '_' . self::PLUGIN_PREFIX . 'private_content', 'private' ) : get_option( '_' . self::PLUGIN_PREFIX . 'private_content', 'private' );
		
		// specify defaults for shortcode attributes
		$defaults = array(
			'blog' => 1,
			'post' => 0,
			'page' => 0,
			'title' => false,
			'titletag' => 'h2',
			'titlelink' => false,
			'content' => 'content',
			'filter' => false,
			'shortcodes' => true,
			'embeds' => false,
			'more_text' => __( 'Read more &hellip;', 'rps-include-content' ),
			'length' => 55,
			'allow_shortcodes' => '',
			'hover' => ( !$disable_hover ) ? true : false,
			'private' => ( $private_content ) ? true : false,
			'featured_image' => false,
			'featured_image_size' => 'post-thumbnail',
			'featured_image_wrap' => false,
			'featured_image_wrap_class' => 'post-thumbnail',
		);

		extract( shortcode_atts( $defaults, $atts ) );
		
		// convert string values to lowercase and trim
		$title = trim( strtolower( $title ) );
		$titletag = trim( strtolower( $titletag ) );
		$content = trim( strtolower( $content ) );
		$allow_shortcodes = trim( strtolower( $allow_shortcodes ) );
		$allow_shortcodes_array = ( ! empty( $allow_shortcodes ) ) ? explode( ',', $allow_shortcodes ) : array();
		$allow_shortcodes_array = array_map( 'trim', $allow_shortcodes_array );

		// type cast strings as necessary
		$blog = absint( $blog );
		$post = absint( $post );
		$page = absint( $page );
		$title = ( $title == 'true' ) ? true : false;
		$titlelink = ( $titlelink == 'true' ) ? true : false;
		$filter = ( $filter == 'true' ) ? true : false;
		$embeds = ( $embeds == 'true' ) ? true : false;
		$shortcodes = ( $shortcodes == 'true' ) ? true : false;
		$length = ( $length == '0' || $length == '' ) ? 55 : $length;
		$hover = ( $hover == 'true' ) ? true : false;
		$private = ( $private == 'true' ) ? true : false;
		$featured_image = ( $featured_image == 'true' ) ? true : false;
		$featured_image_wrap = ( $featured_image_wrap == 'true' ) ? true : false;
		
		// handle if page attribute used instead of post
		$post = ( $post === 0 && $page !== 0 ) ? $page : $post;

		// test for allowed values and sanitize as necessary
		if ( !in_array( $titletag, $allowed_titletag ) ) $titletag = $defaults['titletag'];
		if ( !in_array( $content, $allowed_content ) ) $content = $defaults['content'];
		$featured_image_size = sanitize_text_field( $featured_image_size );
		$featured_image_wrap_class = sanitize_html_class( $featured_image_wrap_class, '' );

		if ( $post === 0 )
			return $this->error_msg( __( 'Post must be a non-zero integer.', 'rps-include-content' ) );

		// add the calling blog/post to the array
		if ( empty( self::$included ) ) :
			$current_blog_info = $this->get_current_blog_info();
			self::$included[] = $current_blog_info['blog'] . ',' . $current_blog_info['post'];
		endif;
		

		$the_post = ( is_multisite() ) ? get_blog_post( $blog, $post ) : get_post( $post );
		
		if ( in_array( $blog . ',' . $post, self::$included ) ) :
			$i = 0;
			$looped_list = '<ol>';
			foreach( self::$included as $looped ) {
				$looped_blog_post = explode( ',', $looped );
				$looped_blog_id = $looped_blog_post[0];
				$looped_post_id = $looped_blog_post[1];
				$looped_blog = ( is_multisite() ) ? get_blog_details( $looped_blog_id )->blogname : get_bloginfo( 'name' );
				$looped_post = ( is_multisite() ) ? get_blog_post( $looped_blog_id, $looped_post_id  ) : get_post( $looped_post_id );
				$looped_post_permalink = ( is_multisite() ) ? get_blog_permalink( $looped_blog_id, $looped_post_id ) : get_permalink( $looped_post_id );

				if ( $i == 0 ) :
					$looped_list .= '<li>' . $looped_blog . ' &raquo; ' . $looped_post->post_title . ' (this ' . get_post_type( $looped_post_id ) . ')</li>';
				else :
					$looped_list .= '<li><a href="' . $looped_post_permalink . '">'. $looped_blog . ' &raquo; ' . $looped_post->post_title . '</a></li>';
				endif;

				$i++;
			}
			$looped_list .= '</ol>';
			return $this->error_msg( '<strong>' . __( 'An infinite content loop detected involving the following:', 'rps-include-content' ) . '</strong>' . $looped_list );
		endif;

		if ( $the_post == null ) 
			return $this->error_msg( __( 'Blog', 'rps-include-content' ) . ' <strong>' . $blog . '</strong>:'. __( 'Post', 'rps-include-content' ) . '<strong>' . $post . '</strong> ' . __( 'could not be found.', 'rps-include-content' ) );
		
		/**
		 * If the post status is something other than publish or private then bail.
		 */
		if ( $the_post->post_status != 'publish' and $the_post->post_status != 'private' )
			return;
								
		$the_blog = ( is_multisite() ) ? get_blog_details( $blog )->blogname : get_bloginfo( 'name' );
		
		$the_permalink = ( is_multisite() ) ? get_blog_permalink( $blog, $post ) : get_permalink( $post );
		$the_edit_link = ( is_multisite() ) ? $this->get_edit_post_link( $post, $blog ) : $this->get_edit_post_link( $post );
		
		$the_header = ( count( self::$included ) === 1 ) ? '<div class="rps-include-content-label" title="' . __( 'Included Content', 'rps-include-content' ) . '">i</div><div class="rps-include-content-links"><a href="' . $the_permalink . '" title="' . __( 'View', 'rps-include-content' ) . ' ' . $the_blog . ' &raquo; ' . $the_post->post_title . '">' . __( 'View', 'rps-include-content' ) . '</a><a href="' . $the_edit_link . '" title="' . __( 'Edit', 'rps-include-content' ) . ' ' . $the_blog . ' &raquo; ' . $the_post->post_title . '">' . __( 'Edit', 'rps-include-content' ) . '</a></div>' : '';

		self::$included[] = $blog . ',' . $post;
		
		/**
		 * If the title is set to show then wrap it in the selected tag.
		 * If the titlelink is set to show then add it.
		 */
		$the_title = '';
		
		/**
		 * If the content is set to none then set the title to true since we need to show something.
		 */
		if ( $content == 'none' )
			$title = true;
		
		if ( $title and ! $titlelink ) :
		
			$the_title = '<' . $titletag . '>' . $the_post->post_title . '</' . $titletag . '>';
		
		elseif ( $title and $titlelink ) :
		
			$the_title = '<' . $titletag . '><a href="' . $the_permalink . '">' . $the_post->post_title . '</a></' . $titletag . '>';
		
		endif;
		
		$more_link = '<p class="more-link-container"><a href="'. $the_permalink .'#more-' . $the_post->ID . '" class="more-link">' . $more_text . '</a></p>';
		
		/**
		 * Check to see if the post content is password protected and if so display the password form.
		 * A logged in user will also see the password form since that is the default behavior.
		 */
		if ( post_password_required( $the_post ) and $content != 'none' ) :
		
			$the_article = get_the_password_form();
		
		/**
		 * The user must have permissions to read_private_pages and read_private_posts.
		 */	
		elseif ( ! $private and $the_post->post_status == 'private' and ( ( ! current_user_can( 'read_private_posts' ) ) or ( ! current_user_can( 'read_private_pages' ) and $page !== 0 ) ) ) :
		
			/**
			 * Display a helpful message about the private content if the user is logged in or not.
			 */
			if ( is_user_logged_in() ) :
				$the_article = __( 'You do not have permission to view this content.', 'rps-include-content' );
			else :
				$the_article = __( 'You must be logged in to view this content.', 'rps-include-content' );
			endif;
			
		elseif ( $content == 'excerpt' ) :
		
			$the_article = $the_post->post_excerpt;
			
		elseif ( $content == 'lede' ) :
		
			$the_article = wp_trim_words($the_post->post_content, $length, $more_link);

		elseif ( $content == 'none' ) :
		
			$the_article = '';
		
		elseif ( $content == 'full' ) :

			$the_article = $the_post->post_content;
						
		else :
		
			if ( $position = stripos( $the_post->post_content, "<!--more-->" ) ) :
			
				$content_sub_string = substr($the_post->post_content, 0, $position);
				$length = str_word_count($content_sub_string, 0);
				$the_article = wp_trim_words($the_post->post_content, $length, $more_link);
				
			else :
			
				$the_article = $the_post->post_content;
				
			endif;
			
		endif;

		if ( count( $allow_shortcodes_array ) ) :
		
			$shortcodes_hooks = array();
			foreach( $allow_shortcodes_array as $allow_shortcode ) :
			
				if( !shortcode_exists($allow_shortcode) )
					break;
				
				$shortcodes_hooks[$allow_shortcode] = $shortcode_tags[$allow_shortcode];
				remove_shortcode($allow_shortcode);
				
			endforeach;
						
			// Strip shortcodes if set to false.
			$the_article = strip_shortcodes( $the_article );
			
			foreach( $shortcodes_hooks as $key => $value ) :
				
				add_shortcode($key, $value);
			
			endforeach;

		elseif ( ! $shortcodes ) :
		
			$the_article = strip_shortcodes( $the_article );
			
		endif;
		
		// Handle oEmbeds
		if ( $embeds ) {
			if ( isset( $GLOBALS['wp_embed'] ) and ! empty( $GLOBALS['wp_embed'] ) ) {
				global $wp_embed;
				$the_article = $wp_embed->autoembed( $the_article );		
			}
		}
				
		// Assemble the content to be output by merging the title with the selected post parts.
		$the_content = $the_title;
		$the_content .= ( $featured_image ) ? $this->get_the_post_thumbnail( $blog, $the_post->ID, $featured_image_size, $featured_image_wrap, $featured_image_wrap_class ) : '';
		$the_content .= ( $filter ) ? do_shortcode( shortcode_unautop( wpautop( convert_chars( wptexturize( $the_article ) ) ) ) ) : do_shortcode( $the_article );
		
		array_pop( self::$included );
		
		return apply_filters( 'rps_include_content_html',( ( current_user_can( 'manage_rps_include_content' ) and $hover ) and ( current_user_can( 'edit_pages' ) or current_user_can( 'edit_posts') ) ) ? $this->output_msg( $the_content, 'rps-include-content-source', $the_header ) : $the_content );
	}
	
	/**
	 * Retrieves the featured image for the included post.
	 *
	 * @return 		string 		HTML image tag string
	 * @author Eric Kyle
	 * @since 1.1.6
	 */
	private function get_the_post_thumbnail( $blog, $post, $size = '', $wrap = false, $class = '' ) {
		
		$output = '';
		global $current_blog;
		
		if ( is_multisite() and $current_blog->blog_id !== $blog ) {
						
			switch_to_blog( $blog );
			
			$other_blog_details = get_blog_details( $blog );

			$output = str_ireplace( $current_blog->domain . $current_blog->path, $other_blog_details->domain . $other_blog_details->path, get_the_post_thumbnail( $post, $size ) );
			
			restore_current_blog();
		
		}
		else {

			$output = get_the_post_thumbnail( $post, $size );

		}
		
		if ( ! empty( $output ) ) {
			
			$class_attribute = ( ! empty( $class ) ) ? ' class="' . $class . '"' : '';
			$output = ( $wrap ) ? '<div' . $class_attribute . '>' . $output . '</div>' : $output;
		
		}
		
		return $output;
		
	}

	private function get_current_blog_info() {
		global $post;
		self::$current_page = $post;
		$blog_id = ( function_exists( 'get_current_blog_id' ) ) ? get_current_blog_id() : 1;
		$post_id = $post->ID;
		return array( 'blog' => $blog_id, 'post' => $post_id );
	}
	
	private function get_edit_post_link( $post, $blog = '' ) {
		$the_edit_post_path = 'post.php?post=' . $post . '&amp;action=edit';
		$the_admin_url = get_admin_url( $blog );
		return $the_admin_url . $the_edit_post_path;
	}
	
	private function error_msg( $e ) {
		return ( ( current_user_can( 'manage_rps_include_content' ) and $hover ) and ( current_user_can( 'edit_pages' ) or current_user_can( 'edit_posts') ) ) ? $this->output_msg( $e, 'rps-include-content-source rps-error' ) : '';
	}
	
	private function output_msg( $content, $classes = '', $header = '' ) {
		$the_output = '<div class="' . esc_attr( $classes ) . '">' . $header . '<div class="rps-include-content">';
		$the_output .= $content;
		$the_output .= '</div></div>';
		return $the_output;
	}
	
	static private $included = array();
	
	/**
	 * Add the plugin's admin pages.
	 *
	 * @since 1.0
	 */
	public function _admin_menu() {
		add_options_page( sprintf( __( '%1$s Settings', 'rps-include-content' ), self::PLUGIN_NAME ), 'RPS Include Content', 'manage_options', self::PLUGIN_PREFIX . 'options_page', array( $this, '_admin_page_options' ) );
	}
	
	/**
	 * An admin page for the plugin's settings.
	 *
	 * @since 1.0
	 */
	public function _admin_page_options() { ?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2><?php echo sprintf( __( '%1$s Settings', 'rps-include-content' ), self::PLUGIN_NAME ); ?></h2>
			
			<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
				<table class="form-table">				
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Default Settings', 'rps-include-content' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Default Settings', 'rps-include-content' ); ?></span></legend>
								<label for="<?php echo esc_attr( self::PLUGIN_PREFIX . 'disable_hover' ); ?>">
									<input type="checkbox" id="<?php echo esc_attr( self::PLUGIN_PREFIX . 'disable_hover' ); ?>" name="<?php echo esc_attr( self::PLUGIN_PREFIX . 'disable_hover' ); ?>" value="hover" <?php echo ((esc_attr(( ( is_multisite() ) ? get_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'disable_hover' ) : get_option( '_' . self::PLUGIN_PREFIX . 'disable_hover' ) )) ) ? "checked" : "") ?> /> <?php _e( 'Remove the interface that appears when hovering over included content', 'rps-include-content' ); ?>
								</label>
								<br>
								<label for="<?php echo esc_attr( self::PLUGIN_PREFIX . 'private_content' ); ?>">
									<input type="checkbox" id="<?php echo esc_attr( self::PLUGIN_PREFIX . 'private_content' ); ?>" name="<?php echo esc_attr( self::PLUGIN_PREFIX . 'private_content' ); ?>" value="private" <?php echo ((esc_attr(( ( is_multisite() ) ? get_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'private_content' ) : get_option( '_' . self::PLUGIN_PREFIX . 'private_content' ) )) ) ? "checked" : "") ?> /> <?php _e( 'Show private included content without restriction', 'rps-include-content' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
				</table>
				
				<p class="submit">
					<input type="hidden" name="action" value="<?php echo esc_attr( self::PLUGIN_PREFIX . 'action-save_settings' ); ?>" />
					<?php wp_nonce_field( self::PLUGIN_PREFIX . 'action-save_settings' ); ?>
					<input type="submit" value="<?php echo esc_attr( __( 'Save Changes', 'rps-include-content' ) ); ?>" class="button-primary" />
				</p>
			</form>
			
			<p style="text-align: center;">
				<a href="http://redpixel.com" target="_blank" title="<?php echo esc_attr( sprintf( __( '%1$s developed by %2$s', 'rps-include-content' ), self::PLUGIN_NAME, 'Red Pixel Studios' ) ); ?>">
					<img src="<?php echo plugins_url( 'redpixel.png', __FILE__ ); ?>" alt="Red Pixel Studios" />
				</a>
			</p>
		</div>
	<?php }
	
	/**
	 * Save the plugin's settings.
	 *
	 * @since 1.0
	 */
	public function _admin_action_save_settings() {
		check_admin_referer( self::PLUGIN_PREFIX . 'action-save_settings' );
		
		$fields = stripslashes_deep( $_REQUEST );
		
		// Hover setting
		if(isset($fields[ self::PLUGIN_PREFIX . 'disable_hover' ]))
			if ( is_multisite() )
				update_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'disable_hover', true );
			else
				update_option( '_' . self::PLUGIN_PREFIX . 'disable_hover', true );
		else
			if ( is_multisite() )
				update_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'disable_hover', false );
			else
				update_option( '_' . self::PLUGIN_PREFIX . 'disable_hover', false );
		
		// Private Content setting
		if(isset($fields[ self::PLUGIN_PREFIX . 'private_content' ]))
			if ( is_multisite() )
				update_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'private_content', true );
			else
				update_option( '_' . self::PLUGIN_PREFIX . 'private_content', true );
		else
			if ( is_multisite() )
				update_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'private_content', false );
			else
				update_option( '_' . self::PLUGIN_PREFIX . 'private_content', false );
		
		// Be sure to flush rewrite rules, in case the rewrite slugs changed.
		if ( is_multisite() )
			update_blog_option( get_current_blog_id(), '_' . self::PLUGIN_PREFIX . 'flush_rewrite_rules', 1 );
		else
			update_option( '_' . self::PLUGIN_PREFIX . 'flush_rewrite_rules', 1 );
		
		wp_redirect( wp_get_referer() );
	}

}
if ( ! isset( $rps_include_content ) ) $rps_include_content = new RPS_Include_Content;

endif;

?>