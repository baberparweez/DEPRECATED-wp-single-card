<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/baberparweez
 * @since      1.0.0
 *
 * @package    Single_Card
 * @subpackage Single_Card/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Single_Card
 * @subpackage Single_Card/admin
 * @author     Baber Parweez <baberparweez@gmail.com>
 */
class Single_Card_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Single_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Single_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/single-card-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Single_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Single_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/single-card-admin.js', array('jquery'), $this->version, false);
	}

	public function admin_create_menu()
	{

		if (!isset($GLOBALS['admin_page_hooks']['single_card_options'])) {
			add_menu_page('Single Card', 'Single Card', 'manage_options', 'single_card_options', array($this, 'single_card_options_page'), plugin_dir_url(__FILE__) . 'images/icon.png', 61);
		} else {
			remove_menu_page('single_card_options');
			add_menu_page('Single Card', 'Single Card', 'manage_options', 'single_card_options', array($this, 'single_card_options_page_supplement'), plugin_dir_url(__FILE__) . 'images/icon.png', 61);
		}
	}

	public function  single_card_options_page()
	{ }

	/**
	 * Creats VC Element to render the Single Card
	 *
	 * @since    1.0.0
	 */
	public function vc_single_card()
	{

		$value = array();

		$post_types = array('post', 'page', 'offers');

		foreach ($post_types as $post_type) {
			$args = array(
				'posts_per_page' => -1,
				'post_type' => $post_type,
			);
			$posts_array = get_posts($args);

			$posts = array();

			foreach ($posts_array as $post) {
				array_push($posts, $post->ID);
			}

			$value[$post_type] = $posts;
		}

		$card_options = array(
			'Post types Card' => 'posts_card',
			'Custom Card' => 'custom_card',
		);
		$card_description = 'Please select whether you would like to show all page/post options or if you would like to create a custom card';


		vc_map(array(
			"name" => "Display Single Card",
			"description" => "Display single card",
			"base" => "display_single_card",
			// 'icon' => $GLOBALS['asc__CDN']['template'] . '/images/vc/wf.png',
			"class" => "",
			"category" => "Content",
			"params" => array(
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Card type',
					'param_name' => 'card_type',
					'value' => $card_options,
					'description' => $card_description,
				),
				array(
					'type' => 'dropdown_multi',
					'holder' => 'div',
					'class' => '',
					'heading' => 'Article',
					'param_name' => 'article_id',
					'value' => $value,
					'std' => '', // this is required when using `dropdown_multi` to empty the field on creation
					'dependency' => array(
						'element' => 'card_type',
						'value' => 'posts_card'
					),
				),
				array(
					'type' => 'attach_image',
					'heading' => 'Custom image',
					'param_name' => 'custom_image',
					'dependency' => array(
						'element' => 'card_type',
						'value' => 'custom_card'
					),
				),
				array(
					'type' => 'textfield',
					'heading' => 'Custom title',
					'param_name' => 'custom_title',
					'dependency' => array(
						'element' => 'card_type',
						'value' => 'custom_card'
					),
				),
				array(
					'type' => 'textfield',
					'heading' => 'Custom link',
					'param_name' => 'custom_link',
					'dependency' => array(
						'element' => 'card_type',
						'value' => 'custom_card'
					),
				),
				array(
					'type' => 'textfield',
					'heading' => 'Button text',
					'param_name' => 'btn_text',
					'dependency' => array(
						'element' => 'card_type',
						'value' => 'custom_card'
					),
				),
				array(
					'type' => 'textarea',
					'heading' => 'Custom excerpt',
					'param_name' => 'custom_excerpt',
					'dependency' => array(
						'element' => 'card_type',
						'value' => 'custom_card'
					),
				),
			)
		));
	}
}
