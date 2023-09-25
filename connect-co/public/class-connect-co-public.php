<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/public
 * @author     Anusha Priyamal <rdanusha@gmail.com>
 */
class Connect_Co_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $connect_co    The ID of this plugin.
	 */
	private $connect_co;

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
	 * @param      string    $connect_co       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $connect_co, $version ) {

		$this->connect_co = $connect_co;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Co_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Co_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->connect_co, plugin_dir_url( __FILE__ ) . 'css/connect-co-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Co_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Co_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->connect_co, plugin_dir_url( __FILE__ ) . 'js/connect-co-public.js', array( 'jquery' ), $this->version, false );

	}

}
