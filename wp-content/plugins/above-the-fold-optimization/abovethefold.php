<?php
/**
 * Page Speed Optimization for SEO
 *
 * WordPress optimization toolkit with a focus on SEO. This plugin enables to achieve a Google PageSpeed 100 Score. Supports most optimization, minification and full page cache plugins.
 *
 * @link              https://optimization.team/
 * @since             1.0
 * @package           abovethefold
 *
 * @wordpress-plugin
 * Plugin Name:       Page Speed Optimization
 * Plugin URI:        https://optimization.team/
 * Description:       WordPress optimization toolkit with a focus on SEO. This plugin enables to achieve a Google PageSpeed 100 Score. Supports most optimization, minification and full page cache plugins.
 * Version:           2.9.10
 * Author:            o10n-x
 * Author URI:        https://optimization.team/
 * Text Domain:       abovethefold
 * Domain Path:       /languages
 */

define('WPABTF_VERSION', '2.9.10');
define('WPABTF_URI', plugin_dir_url(__FILE__));
define('WPABTF_PATH', plugin_dir_path(__FILE__));
define('WPABTF_SELF', __FILE__);

# cache directory
define('ABTF_CACHE_DIR', trailingslashit(WP_CONTENT_DIR) . 'cache/abtf/');
define('ABTF_CACHE_URL', trailingslashit(WP_CONTENT_URL) . 'cache/abtf/');

if (! defined('WPINC')) {
    die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin dashboard hooks and optimization hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/abovethefold.class.php';

/**
 * Begins execution of optimization.
 *
 * The plugin is based on hooks, starting the plugin from here does not impact load speed.
 *
 * @since    1.0
 */
function run_Abovethefold()
{
    $GLOBALS['Abovethefold'] = new Abovethefold();
    $GLOBALS['Abovethefold']->run();
}
run_Abovethefold();
