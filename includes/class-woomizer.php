<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.0.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
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
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer {
	/**
	 * Constructor for your shipping class
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		$this->init_customizer();
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'woomizer', false, basename( WOOMIZER_PATH ) . '/languages' );
	}


	/**
	 * Load customizer.
	 *
	 * @since    1.0.0
	 */
	private function init_customizer() {
		// Initialize the Woomizer_Customize class.
		$woomizer_customize = new Woomizer_Customize();

		// Setup the Theme Customizer settings and controls.
		add_action( 'customize_register', array( $woomizer_customize, 'register' ) );

		// Enqueue live preview javascript in Theme Customizer admin screen.
		add_action( 'customize_preview_init', array( $woomizer_customize, 'live_preview' ) );

		// Filter settings arguments.
		add_filter( 'customize_dynamic_setting_args', array( $woomizer_customize, 'dynamic_setting_args' ), 99, 2 );

		// Filter product tabs.
		add_filter( 'woocommerce_product_tabs', array( $woomizer_customize, 'product_tabs' ), 99 );

		// Filter add to cart button text for product single.
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $woomizer_customize, 'single_add_to_cart_btn_text' ), 99, 2 );

		// Filter add to cart button text for product loop.
		add_filter( 'woocommerce_product_add_to_cart_text', array( $woomizer_customize, 'loop_add_to_cart_btn_text' ), 99, 2 );

	}
}
