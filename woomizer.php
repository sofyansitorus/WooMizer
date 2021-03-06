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
 * Description:       WooCommerce customizer with live preview.
 * Version:           1.2.1
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

/**
 * Check if WooCommerce is active
 */
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	return;
}// End if().

if ( ! function_exists( 'woomizer_init' ) ) {

	// Defines plugin named constants.
	define( 'WOOMIZER_FILE', __FILE__ );
	define( 'WOOMIZER_PATH', plugin_dir_path( WOOMIZER_FILE ) );
	define( 'WOOMIZER_URL', plugin_dir_url( WOOMIZER_FILE ) );
	define( 'WOOMIZER_VERSION', '1.2.1' );
	define( 'WOOMIZER_PREFIX', 'woomizer' );

	// Include the dependencies.
	require_once WOOMIZER_PATH . 'includes/helpers.php';
	require_once WOOMIZER_PATH . 'includes/class-woomizer-hooks.php';
	require_once WOOMIZER_PATH . 'includes/class-woomizer-setting.php';
	require_once WOOMIZER_PATH . 'includes/class-woomizer.php';

	/**
	 * Initialize the Woomizer class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function woomizer_init() {

		// Initialize main class.
		Woomizer::init();
	}
	woomizer_init();
}// End if().
