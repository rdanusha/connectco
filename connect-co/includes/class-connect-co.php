<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Connect_Co
 * @subpackage Connect_Co/includes
 * @author     Anusha Priyamal <rdanusha@gmail.com>
 */
class Connect_Co {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Connect_Co_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $connect_co    The string used to uniquely identify this plugin.
	 */
	protected $connect_co;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CONNECT_CO_VERSION' ) ) {
			$this->version = CONNECT_CO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->connect_co = 'connect-co';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Connect_Co_Loader. Orchestrates the hooks of the plugin.
	 * - Connect_Co_i18n. Defines internationalization functionality.
	 * - Connect_Co_Admin. Defines all hooks for the admin area.
	 * - Connect_Co_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-co-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-co-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-connect-co-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-connect-co-public.php';

		$this->loader = new Connect_Co_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Connect_Co_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Connect_Co_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Connect_Co_Admin( $this->get_connect_co(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin, 'display_data_in_order_details' );
		$this->loader->add_action( 'admin_post_save_connect_co_settings', $plugin_admin, 'save_connect_co_settings' );
		$this->loader->add_action( 'admin_post_save_connect_co_order_meta', $plugin_admin, 'save_connect_co_order_meta' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'connect_co_admin_notifications' );
		$this->loader->add_action( 'manage_edit-shop_order_columns', $plugin_admin, 'set_custom_edit_shop_order_columns' );
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'custom_shop_order_column' , 10, 2);

        $this->loader->add_action('wp_ajax_submit_order_to_connect_co', $plugin_admin, 'submit_order_to_connect_co_ajx');
        $this->loader->add_action('wp_ajax_nopriv_submit_order_to_connect_co', $plugin_admin, 'submit_order_to_connect_co_ajx');

        $this->loader->add_action('wp_ajax_calculate_connect_co_delivery_cost', $plugin_admin, 'calculate_connect_co_delivery_cost_ajx');
        $this->loader->add_action('wp_ajax_nopriv_calculate_connect_co_delivery_cost', $plugin_admin, 'calculate_connect_co_delivery_cost_ajx');

        $this->loader->add_action('wp_ajax_check_cash_on_delivery_availability', $plugin_admin, 'check_cash_on_delivery_availability_ajx');
        $this->loader->add_action('wp_ajax_nopriv_check_cash_on_delivery_availability', $plugin_admin, 'check_cash_on_delivery_availability_ajx');

        $this->loader->add_action('wp_ajax_check_delivery_methods_availability', $plugin_admin, 'check_delivery_methods_availability_ajx');
        $this->loader->add_action('wp_ajax_nopriv_check_delivery_methods_availability', $plugin_admin, 'check_delivery_methods_availability_ajx');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Connect_Co_Public( $this->get_connect_co(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_connect_co() {
		return $this->connect_co;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Connect_Co_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
