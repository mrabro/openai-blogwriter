<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://twitter.com/mrabro
 * @since      1.0.0
 *
 * @package    Openai_Blog_Writer
 * @subpackage Openai_Blog_Writer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Openai_Blog_Writer
 * @subpackage Openai_Blog_Writer/includes
 * @author     Rafi Abro <mrafiabro@hotmail.com>
 */
class Openai_Blog_Writer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'openai-blog-writer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
