<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.connectco.lk/
 * @since             1.0.0
 * @package           Connect_Co
 *
 * @wordpress-plugin
 * Plugin Name:       Connect Co. for WooCommerce
 * Plugin URI:        http://wordpress.org/plugins/connect-co/
 * Description:       Simplify your shipping experience with Connect Co.'s Woo Commerce plug-in. Through this plug-in you will be directly integrated with Connect Co.'s Console, which will allow you to automate order creation and allow you to let go of time consuming and error prone manual processes.
 * Version:           1.0.0
 * Author:            Connect Co (Pvt) Ltd
 * Author URI:        http://www.connectco.lk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connect-co
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
define( 'CONNECT_CO_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-connect-co-activator.php
 */
function activate_connect_co() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-co-activator.php';
	Connect_Co_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-connect-co-deactivator.php
 */
function deactivate_connect_co() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-co-deactivator.php';
	Connect_Co_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_connect_co' );
register_deactivation_hook( __FILE__, 'deactivate_connect_co' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-connect-co.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_connect_co() {

	$plugin = new Connect_Co();
	$plugin->run();

}
run_connect_co();
