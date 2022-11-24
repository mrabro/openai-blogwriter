<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://twitter.com/mrabro
 * @since      1.0.0
 *
 * @package    Openai_Blog_Writer
 * @subpackage Openai_Blog_Writer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Openai_Blog_Writer
 * @subpackage Openai_Blog_Writer/admin
 * @author     Rafi Abro <mrafiabro@hotmail.com>
 */
class Openai_Blog_Writer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Openai_Blog_Writer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Openai_Blog_Writer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/openai-blog-writer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Openai_Blog_Writer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Openai_Blog_Writer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/openai-blog-writer-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function openai_settings_page() {
		add_options_page( 'OpenAI Blog Writer', 'OpenAI Blog Writer', 'manage_options', 'openai_blog_writer', array($this, 'openai_options_page') );
	}
	
	public function openai_register_settings() {
		register_setting( 'pluginPage', 'openai_settings' );
		add_settings_section(
			'openai_pluginPage_section', 
			__( 'OpenAI API KEY', 'openai' ), 
			array($this, 'openai_settings_section_callback'), 
			'pluginPage'
		);

		add_settings_field( 
			'openai_text_field_0', 
			__( 'API KEY', 'openai' ), 
			array($this, 'openai_text_field_0_render'), 
			'pluginPage', 
			'openai_pluginPage_section' 
		);
	}
	
	public function openai_settings_section_callback(  ) { 
		echo __( 'You can set your OpenAI API Key here.', 'openai' );
	}

	public function openai_text_field_0_render(  ) { 
		$options = get_option( 'openai_settings' ); ?>
		<input type='text' name='openai_settings[openai_text_field_0]' value='<?php echo isset($options['openai_text_field_0']) ? $options['openai_text_field_0'] : ''; ?>'>
		<?php
	}

	function openai_options_page(  ) { 
		?>
		<form action='options.php' method='post'>
			<h2>OpenAI Blog Writer</h2>
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>
		</form>
		<?php
	}
}
