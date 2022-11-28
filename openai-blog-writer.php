<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://twitter.com/mrabro
 * @since             1.0.0
 * @package           Openai_Blog_Writer
 *
 * @wordpress-plugin
 * Plugin Name:       OpenAI Blog Writer
 * Plugin URI:        https://twitter.com/mrabro
 * Description:       Blog writing plugin with OpenAI API integration
 * Version:           1.0.0
 * Author:            Rafi Abro
 * Author URI:        https://twitter.com/mrabro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       openai-blog-writer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OPENAI_BLOG_WRITER_VERSION', '1.0.0' );
define( 'OPENAI_DIR', dirname( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-openai-blog-writer-activator.php
 */
function activate_openai_blog_writer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-openai-blog-writer-activator.php';
	Openai_Blog_Writer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-openai-blog-writer-deactivator.php
 */
function deactivate_openai_blog_writer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-openai-blog-writer-deactivator.php';
	Openai_Blog_Writer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_openai_blog_writer' );
register_deactivation_hook( __FILE__, 'deactivate_openai_blog_writer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-openai-blog-writer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_openai_blog_writer() {

	$plugin = new Openai_Blog_Writer();
	$plugin->run();

}
run_openai_blog_writer();


// Setting link
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'openai_plugin_page_settings_link');
function openai_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=openai_blog_writer' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}