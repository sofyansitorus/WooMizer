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
	 * Class instance
	 *
	 * @since    1.1.0
	 * @var \Woomizer
	 */
	private static $_instance = null;

	/**
	 * Constructor for your shipping class
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		$this->load_dependencies();
		$this->register_hooks();
		$this->init_customizer();
	}

	/**
	 * Get Instance
	 *
	 * @since    1.1.0
	 */
	public static function init() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
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
	 * @since    1.1.0
	 */
	private function load_dependencies() {
		// Load dependencies.
		require_once WOOMIZER_PATH . 'includes/class-woomizer-setting.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-panel.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-section.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-customize.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-hooks.php';
	}

	/**
	 * Register hooks.
	 *
	 * @since    1.1.0
	 */
	private function register_hooks() {

		// Initialize the Woomizer_Hooks class.
		$obj_hooks = new Woomizer_Hooks();

		// Filter settings arguments.
		add_filter( 'customize_dynamic_setting_args', array( $obj_hooks, 'dynamic_setting_args' ), 99, 2 );

		// Filter product tabs.
		add_filter( 'woocommerce_product_tabs', array( $obj_hooks, 'product_tabs' ), 99 );

		// Filter add to cart button text for product single.
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $obj_hooks, 'single_add_to_cart_btn_text' ), 99, 2 );

		// Filter add to cart button text for product loop.
		add_filter( 'woocommerce_product_add_to_cart_text', array( $obj_hooks, 'loop_add_to_cart_btn_text' ), 99, 2 );

		// Filter add to cart button text for product loop.
		add_filter( 'woocommerce_sale_flash', array( $obj_hooks, 'global_sale_flash' ), 99 );

	}

	/**
	 * Load customizer.
	 *
	 * @since    1.0.0
	 */
	private function init_customizer() {

		// Initialize the Woomizer_Customize class.
		$obj_customize = new Woomizer_Customize();

		// Setup the Theme Customizer settings and controls.
		add_action( 'customize_register', array( $obj_customize, 'register' ) );

		// Enqueue live preview javascript in Theme Customizer admin screen.
		add_action( 'customize_preview_init', array( $obj_customize, 'live_preview' ) );

	}
}
