<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/sofyansitorus
 * @since             1.0.0
 * @package           Woomizer
 *
 * @wordpress-plugin
 * Plugin Name:       Woomizer
 * Plugin URI:        https://github.com/sofyansitorus/Woomizer
 * Description:       WooCommerce customizer with live preview
 * Version:           1.0.0
 * Author:            Sofyan Sitorus
 * Author URI:        https://github.com/sofyansitorus
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woomizer
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.6
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Defines plugin named constants.
define( 'WOOMIZER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOOMIZER_URL', plugin_dir_url( __FILE__ ) );
define( 'WOOMIZER_VERSION', '1.0.0' );

/**
 * Check if WooCommerce is active
 */
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	return;
}// End if().

if ( ! function_exists( 'woomizer_init' ) ) {
	/**
	 * Initialize the Woomizer class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function woomizer_init() {

		// Include the dependencies.
		if ( ! class_exists( 'Woomizer' ) ) {
			require_once WOOMIZER_PATH . 'includes/class-woomizer-customize.php';
			require_once WOOMIZER_PATH . 'includes/class-woomizer.php';
		}

		new Woomizer();
	}
	woomizer_init();
}// End if().
