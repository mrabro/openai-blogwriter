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

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/block.build.js', array( 'jquery', 'wp-blocks' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/openai-blog-writer-settings.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, "admin", array("ajax"=>admin_url( 'admin-ajax.php' ), "base_url" => get_site_url()));


	}

	public function openai_settings_page() {
		add_options_page( 'OpenAI Blog Writer', 'OpenAI Blog Writer', 'manage_options', 'openai_blog_writer', array($this, 'openai_options_page') );
	}
	
	public function openai_register_settings() {
		register_setting( 'OpenAIpluginPage', 'openai_settings' );
		add_settings_section(
			'openai_OpenAIpluginPage_section', 
			__( 'OpenAI API KEY', 'openai' ), 
			array($this, 'openai_settings_section_callback'), 
			'OpenAIpluginPage'
		);

		add_settings_field( 
			'openai_text_field_0', 
			__( 'API KEY', 'openai' ), 
			array($this, 'openai_text_field_0_render'), 
			'OpenAIpluginPage', 
			'openai_OpenAIpluginPage_section' 
		);

		// // Register BlockTypes
		// register_block_type( "openai/blog-outlines", array(
		// 	'editor_script' => $this->plugin_name
		// ) );
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
			settings_fields( 'OpenAIpluginPage' );
			do_settings_sections( 'OpenAIpluginPage' );
			submit_button();
			?>
		</form>
		<?php

		$options = get_option( 'openai_settings' );
		if(isset($options['openai_text_field_0']) && strlen($options['openai_text_field_0']) > 0){ 
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
			?>
			<a href="TB_inline?width=700&height=550&inlineId=openai-blog-modal" class="thickbox">Start Generating Blogs</a> | 
			<a href="TB_inline?width=700&height=550&inlineId=openai-image-modal" class="thickbox">Generate Images</a>
			<div id="openai-blog-modal" style="display:none;">
				<h2>OpenAI Labs Blog Writer</h2>
				<form name="generate_blog" id="openai_form">
					<table>
						<tr>
							<th><label for="topic">Topic</label></th>
							<td><input type="text" name="openai[topic]" id="openai_title" placeholder="Enter your Topic" style="width:200px" required><span data-tooltip="i.e. What is WordPress">(?)</span></td>
						</tr>
						<tr>
							<th><label for="length">Maximum length</label></th>
							<td><input type="number" name="openai[tokens]" placeholder="length of Blog (Tokens)" style="width:200px" value="10"><span data-tooltip="The maximum number of tokens to generate in the completion. The token count of your prompt plus max_tokens cannot exceed the model's context length. Most models have a context length of 2048 tokens (except for the newest models, which support 4096).">(?)</span></td>
						</tr>
						<tr>
							<th><label for="temprature">Temperature</label></th>
							<td><input name="openai[temperature]" type="number" max="1" min="0" step="0.1" value="0.7" style="width:200px"><span data-tooltip="Higher values means the model will take more risks. Try 0.9 for more creative applications, and 0 (argmax sampling) for ones with a well-defined answer.">(?)</span></td>
						</tr>
						<tr>
							<th><label for="model">Model</label></th>
							<td>
								<select name="openai[model]" id="">
									<option value="text-davinci-002">text-davinci-002</option>
									<option value="text-davinci-001">text-davinci-001</option>
									<option value="text-curie-001">text-curie-001</option>
									<option value="text-ada-001">text-ada-001</option>
								</select><span data-tooltip="Select your required model to generate text">(?)</span>
								<p class="description">Ref: <a target="_blank" href="https://beta.openai.com/docs/api-reference/completions/create">OpenAI Docs</a></p>
							</td>
						</tr>
						<tr>
							<th colspan="2"><?php echo submit_button("Generate"); ?><span class="openai_spinner spinner"></span></th>
						</tr>
						<tr>
							<th><label for="result">Result</label></th>
							<td>
								<textarea name="openai_result" id="openai_result" cols="70" rows="10"></textarea>
							</td>
						</tr>
					</table>
				</form>
				
			</div>
			<div id="openai-image-modal" style="display:none;">
				<h2>OpenAI Labs Image Generator</h2>
				<form name="generate_blog" id="openai_image_form">
					<table>
						<tr>
							<th><label for="prompt">Prompt</label></th>
							<td><input type="text" name="openai[prompt]" id="prompt" placeholder="Enter your prompt" style="width:200px" required><span data-tooltip="i.e. Swimming black cat in the river">(?)</span></td>
						</tr>
						<tr>
							<th><label for="number">Number of Images</label></th>
							<td><input type="number" name="openai[n]" placeholder="Number of Images" style="width:200px" value="1"><span data-tooltip="can be 1 - 10">(?)</span></td>
						</tr>
						<tr>
							<th><label for="size">Size</label></th>
							<td>
								<select name="openai[size]" id="">
									<option value="256x256">256x256</option>
									<option value="512x512">512x512</option>
									<option value="1024x1024">1024x1024</option>
								</select><span data-tooltip="Select your preferred size for image">(?)</span>
								<p class="description">Ref: <a target="_blank" href="https://beta.openai.com/docs/guides/images/introduction">OpenAI Docs</a></p>
							</td>
						</tr>
						<tr>
							<th colspan="2"><?php echo submit_button("Generate"); ?><span class="openai_image_spinner spinner"></span></th>
						</tr>
						<tr>
							<th><label for="result">Result</label></th>
							<td>
								<div class="openai_images">

								</div>
							</td>
						</tr>
					</table>
				</form>
			</div>
		<?php
		}
	}

	function generate_blog(){
		$response = array('status' => false, 'msg' => 'Something went wrong');
		if(isset($_REQUEST['openai']) && is_array($_REQUEST['openai']) && isset($_REQUEST['openai']['topic'])){
			$data = OpenAI_BlogWriter::generateBlog($_REQUEST['openai']);
			error_log(print_r($data,true));
			if(isset($data->id) && isset($data->choices) && is_array($data->choices)){
				$response['status'] = true;
				$response['msg'] = "success";
				$response['blog'] = isset($data->choices[0]->text) ? $data->choices[0]->text : "";
			}
		}
		wp_send_json($response, 200);
	}
	
	function generate_image(){
		$response = array('status' => false, 'msg' => 'Something went wrong');
		if(isset($_REQUEST['openai']) && is_array($_REQUEST['openai']) && isset($_REQUEST['openai']['prompt'])){
			$data = OpenAI_BlogWriter::generateImages($_REQUEST['openai']);
			error_log(print_r($data,true));
			if(isset($data->data) && isset($data->data[0]->url) && is_array($data->data)){
				$response['status'] = true;
				$response['msg'] = "success";
				$response['images'] = isset($data->data) ? $data->data : "";
			}
		}
		wp_send_json($response, 200);
	}

	function openai_save_post(){
		$response = array('status' => false, 'msg' => 'Something went wrong');
		if(isset($_REQUEST['title']) && isset($_REQUEST['post'])){
			$wordpress_post = array(
			'post_title' => $_REQUEST['title'],
			'post_content' => $_REQUEST['post'],
			'post_status' => 'draft',
			'post_type' => 'post'
			);
			$post_id = wp_insert_post( $wordpress_post );
			if($post_id){
				$response['status'] = true;
				$response['msg'] = "success";
				$response['post'] = get_edit_post_link($post_id);
			}
		}

		wp_send_json($response, 200);
	}

	// function fetch_outlines(){
	// 	$response = array('status' => false, 'msg' => 'Something went wrong');
	// 	if(!isset($_REQUEST['topic'])){
	// 		wp_send_json( $response, 200 );
	// 	}
	// 	$topic = $_REQUEST['topic'];
	// 	$data = OpenAI_BlogWriter::getOutlines($topic);
	// 	if(isset($data->id) && isset($data->choices) && is_array($data->choices)){
	// 		// $response['status'] = true;
	// 		// $response['msg'] = "success";
	// 		// $response['choices'] = $data->choices[0]->text;
	// 		wp_send_json($data->choices[0]->text, 200);
	// 	}
	// 	wp_send_json($response, 200);
	// }
}
