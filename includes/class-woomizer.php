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

		// Setup the Theme Customizer settings and controls.
		add_action( 'customize_register', array( $this, 'register' ), 99 );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );

		// Filter settings arguments.
		add_filter( 'customize_dynamic_setting_args', array( $this, 'dynamic_setting_args' ), 99, 2 );

		// Filter product tabs.
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ), 99 );

		// Filter add to cart button text for product single.
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'single_add_to_cart_btn_text' ), 99, 2 );

		// Filter add to cart button text for product loop.
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'loop_add_to_cart_btn_text' ), 99, 2 );

		// Filter add to cart button text for product loop.
		add_filter( 'woocommerce_sale_flash', array( $this, 'global_sale_flash' ), 99 );

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
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * @since 1.0.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	public function register( $wp_customize ) {

		// Load customizer setting sections dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/sections/class-woomizer-*.php' ) as $filename ) {
			include_once $filename;
		}

		// Load customizer setting controls dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/controls/class-woomizer-control-*.php' ) as $filename ) {
			include $filename;
		}

		// Register customizer settings panel.
		$panel = new Woomizer_Panel( $wp_customize );

		// Register customizer settings section: woomizer_section_global.
		$section_product_loop = new Woomizer_Section_Global(
			$wp_customize,
			'section_global',
			array(
				'title' => __( 'Global', 'woomizer' ),
			)
		);

		// Register customizer settings section: woomizer_section_product_loop.
		$section_product_loop = new Woomizer_Section_Product_Loop(
			$wp_customize,
			'section_product_loop',
			array(
				'title' => __( 'Product Loop', 'woomizer' ),
			)
		);

		// Register customizer settings section: woomizer_section_product_single.
		$section_product_loop = new Woomizer_Section_Product_Single(
			$wp_customize,
			'section_product_single',
			array(
				'title' => __( 'Product Single', 'woomizer' ),
			)
		);

		// Register customizer settings section: woomizer_section_cart.
		$section_product_loop = new Woomizer_Section_Cart(
			$wp_customize,
			'section_cart',
			array(
				'title' => __( 'Cart', 'woomizer' ),
			)
		);

	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 * @since 1.0.0
	 */
	public function customize_preview_init() {

		// Choose which css file will be enqueued based on environtment.
		$css_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/css/live-preview.css' ) : WOOMIZER_URL . 'assets/css/live-preview.min.css';

		// Enqueue js script.
		wp_enqueue_style(
			'woomizer-live-preview', // Give the script a unique ID.
			$css_file, // Define the path to the JS file.
			array(), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			false // Specify whether to put in footer (leave this false).
		);

		// Choose which js file will be enqueued based on environtment.
		$js_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/live-preview.js' ) : WOOMIZER_URL . 'assets/js/live-preview.min.js';

		// Enqueue js script.
		wp_enqueue_script(
			'woomizer-live-preview', // Give the script a unique ID.
			$js_file, // Define the path to the JS file.
			array( 'jquery', 'customize-preview' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			false // Specify whether to put in footer (leave this true).
		);

		// Localize the script data.
		wp_localize_script(
			'woomizer-live-preview',
			'woomizer_live_preview_params',
			array(
				'prefix'       => WOOMIZER_PREFIX,
				'cart_page_id' => get_option( 'woocommerce_cart_page_id', '0' ),
			)
		);

	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 * @since 1.0.0
	 */
	public static function enqueue_scripts() {

		// DIsable if customizer preview.
		if ( is_customize_preview() ) {
			return;
		}

		// Choose which css file will be enqueued based on environtment.
		$css_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/css/woomizer.css' ) : WOOMIZER_URL . 'assets/css/woomizer.min.css';

		// Enqueue js script.
		wp_enqueue_style(
			'woomizer', // Give the script a unique ID.
			$css_file, // Define the path to the JS file.
			array(), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			false // Specify whether to put in footer (leave this false).
		);

		// Choose which js file will be enqueued based on environtment.
		$js_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/woomizer.js' ) : WOOMIZER_URL . 'assets/js/woomizer.min.js';

		// Enqueue js script.
		wp_enqueue_script(
			'woomizer', // Give the script a unique ID.
			$js_file, // Define the path to the JS file.
			array( 'jquery' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);

		// Localize the script data.
		wp_localize_script(
			'woomizer',
			'woomizer_params',
			array(
				'prefix'          => WOOMIZER_PREFIX,
				'toggle_elements' => $this->get_toggle_elements(),
			)
		);

	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 * @since 1.1.0
	 */
	public static function get_toggle_elements() {
		return array(
			'cart_display_cross_sells' => array(
				'selector' => '.cart-collaterals .cross-sells',
				'value'    => get_theme_mod( 'woomizer_cart_display_cross_sells' ),
			),
		);
	}
	/**
	 * Filter the default product tabs.
	 *
	 * @since 1.1.0
	 * @param array $tabs Current product tabs.
	 * @return array
	 */
	public function product_tabs( $tabs ) {
		global $product, $post;

		$tab_keys = array(
			'description',
			'additional_information',
			'reviews',
		);

		$options = get_theme_mod( 'woomizer_product_single_tabs', array() );

		if ( ! is_array( $options ) ) {
			$options = json_decode( $options, true );
		}

		foreach ( $tab_keys as $tab_key ) {
			if ( ! isset( $tabs[ $tab_key ] ) ) {
				continue;
			}
			if ( isset( $options[ $tab_key . '_hidden' ] ) && 'yes' === $options[ $tab_key . '_hidden' ] ) {
				unset( $tabs[ $tab_key ] );
				continue;
			}
			if ( ! empty( $options[ $tab_key . '_title' ] ) ) {
				switch ( $tab_key ) {
					case 'reviews':
						$tabs[ $tab_key ]['title'] = ( false !== strpos( $options[ $tab_key . '_title' ], '%d' ) && $product instanceof WC_Product ) ? sprintf( $options[ $tab_key . '_title' ], $product->get_review_count() ) : $options[ $tab_key . '_title' ];
						break;

					default:
						$tabs[ $tab_key ]['title'] = $options[ $tab_key . '_title' ];
						break;
				}
			}
		}
		return $tabs;
	}

	/**
	 * Filter add to cart button text for product single.
	 *
	 * @since 1.1.0
	 * @param string      $text Current button text.
	 * @param \WC_Product $product Current product object.
	 * @return string
	 */
	public function single_add_to_cart_btn_text( $text, $product ) {
		$custom_text = get_theme_mod( 'woomizer_product_single_add_to_cart_btn_text' );
		if ( ! empty( $custom_text ) ) {
			return $custom_text;
		}
		return $text;
	}

	/**
	 * Filter add to cart button text for product loop.
	 *
	 * @since 1.1.0
	 * @param string      $text Current button text.
	 * @param \WC_Product $product Current product object.
	 * @return string
	 */
	public function loop_add_to_cart_btn_text( $text, $product ) {
		switch ( $product->get_type() ) {
			case 'external':
				return $text;
				break;
			case 'simple':
				$custom_text = get_theme_mod( 'woomizer_product_loop_add_to_cart_btn_text_simple' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() && $product->is_in_stock() ) {
					return $custom_text;
				}
				break;
			case 'variable':
				$custom_text = get_theme_mod( 'woomizer_product_loop_add_to_cart_btn_text_variable' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() ) {
					return $custom_text;
				}
				break;
			case 'grouped':
				$custom_text = get_theme_mod( 'woomizer_product_loop_add_to_cart_btn_text_grouped' );
				if ( ! empty( $custom_text ) ) {
					return $custom_text;
				}
				break;
		}

		return $text;
	}

	/**
	 * Filter flash sale text for all products.
	 *
	 * @since 1.1.0
	 * @param string $text Current flash sale text.
	 * @return string
	 */
	public function global_sale_flash( $text ) {
		$custom_text = get_theme_mod( 'woomizer_global_flash_sale_text' );
		return '<span class="onsale">' . esc_html( $custom_text ) . '</span>';
	}

	/**
	 * Filter the settings arguments for woomizer_product_single_tabs.
	 *
	 * @since 1.1.0
	 * @param array  $args Customiser setting arguments.
	 * @param string $id Customiser setting ID.
	 * @return array
	 */
	public function dynamic_setting_args( $args, $id ) {
		if ( 'woomizer_product_single_tabs' === $id && isset( $args['default'] ) && is_array( $args['default'] ) ) {
			$args['default'] = wp_json_encode( $args['default'] );
		}
		return $args;
	}
}
