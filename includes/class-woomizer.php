<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.1.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.1.0
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
final class Woomizer {

	/**
	 * Hold an instance of the class
	 *
	 * @since    1.1.0
	 * @var \Woomizer
	 */
	private static $_instance = null;

	/**
	 * Call this method to get singleton
	 *
	 * @since    1.1.0
	 * @return Woomizer
	 */
	public static function init() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor for your shipping class
	 *
	 * @since    1.1.0
	 */
	private function __construct() {
		// Load plugin textdomain.
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Filter to add plugin action links.
		add_action( 'plugin_action_links_' . plugin_basename( WOOMIZER_FILE ), array( $this, 'plugin_action_links' ), 99, 2 );

		// Setup the Theme Customizer settings and controls.
		add_action( 'customize_register', array( $this, 'register' ), 99 );

		// Enqueue live preview scripts and styles.
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ), 99 );

		// Enqueue front scripts and styles.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ), 99 );

		// Filter settings arguments.
		Woomizer_Hooks::init();
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'woomizer', false, basename( WOOMIZER_PATH ) . '/languages' );
	}

	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.1.1
	 *
	 * @param  array $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$links = array_merge(
			array(
				'<a href="' . esc_url( admin_url( 'customize.php' ) ) . '">' . __( 'Customize', 'woomizer' ) . '</a>',
			),
			$links
		);

		return $links;
	}

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * @since 1.1.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	public function register( $wp_customize ) {

		require_once WOOMIZER_PATH . 'includes/class-woomizer-setting.php';

		$setting = new Woomizer_Setting( $wp_customize );

		// Start default panel.
		$setting->add_panel();

		// Start product_loop section.
		$setting->add_section( 'product_loop' );

		$setting->add_setting(
			'flash_sale_loop',
			array(
				'default' => __( 'Sale!', 'woomizer' ),
				'control' => true,
			)
		);

		$setting->add_setting(
			'add_to_cart_button_simple',
			array(
				'default' => __( 'Add to Cart', 'woomizer' ),
				'control' => true,
			)
		);

		$setting->add_setting(
			'add_to_cart_button_variable',
			array(
				'default' => __( 'Select options', 'woomizer' ),
				'control' => true,
			)
		);

		$setting->add_setting(
			'add_to_cart_button_grouped',
			array(
				'default' => __( 'View products', 'woomizer' ),
				'control' => true,
			)
		);

		$setting->add_setting(
			'product_grid',
			array(
				'transport' => 'refresh',
				'control'   => array(
					'type' => 'product_grid',
				),
			)
		);

		// Start product_single section.
		$setting->add_section( 'product_single' );

		$setting->add_setting(
			'flash_sale_single',
			array(
				'default' => __( 'Sale!', 'woomizer' ),
				'control' => true,
			)
		);

		$setting->add_setting(
			'add_to_cart_button',
			array(
				'default' => __( 'Add to Cart', 'woomizer' ),
				'control' => true,
			)
		);

		$setting->add_setting(
			'product_tabs',
			array(
				'control' => array(
					'type' => 'product_tabs',
				),
				'partial' => array(
					'selector'            => '.woocommerce-tabs.wc-tabs-wrapper',
					'container_inclusive' => true,
					'render_callback'     => function() {
						global $product;

						// Try to create new $product object if it was string product slug.
						if ( ! empty( $product ) && is_string( $product ) ) {
							$product = get_page_by_path( $product, OBJECT, 'product' );
							if ( $product ) {
								$product = wc_get_product( $product->ID );
							}
						}

						woocommerce_output_product_data_tabs();
					},
				),
			)
		);

		// Start cart section.
		$setting->add_section( 'cart' );

		$setting->add_setting(
			'display_cross_sells',
			array(
				'control' => array(
					'label'   => __( 'Display Cross Sells', 'woomizer' ),
					'type'    => 'radio',
					'choices' => array(
						''     => __( 'Yes', 'woomizer' ),
						'none' => __( 'No', 'woomizer' ),
					),
				),
			)
		);

		// Start checkout section.
		$setting->add_section( 'checkout' );

		$setting->add_setting(
			'submit_order_button',
			array(
				'default' => __( 'Place order', 'woomizer' ),
				'control' => true,
				'partial' => array(
					'selector'            => 'form.woocommerce-checkout',
					'container_inclusive' => true,
					'render_callback'     => function() {
						wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => WC()->checkout() ) );
					},
				),
			)
		);

		// Build the customizer.
		$setting->build();

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
	public function customize_preview_init() {

		// Choose which css file will be enqueued based on environtment.
		$css_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/css/customize-preview.css' ) : WOOMIZER_URL . 'assets/css/customize-preview.min.css';

		// Enqueue js script.
		wp_enqueue_style(
			'woomizer-customize-preview', // Give the script a unique ID.
			$css_file, // Define the path to the JS file.
			array(), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			false // Specify whether to put in footer (leave this false).
		);

		// Choose which js file will be enqueued based on environtment.
		$js_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/customize-preview.js' ) : WOOMIZER_URL . 'assets/js/customize-preview.min.js';

		// Enqueue js script.
		wp_enqueue_script(
			'woomizer-customize-preview', // Give the script a unique ID.
			$js_file, // Define the path to the JS file.
			array( 'jquery', 'customize-preview' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			false // Specify whether to put in footer (leave this true).
		);
	}

	/**
	 * Enqueue scripts and styles for WP Theme Customizer
	 *
	 * @since 1.1.0
	 */
	public function customize_controls_enqueue_scripts() {

		// Choose which js file will be enqueued based on environtment.
		$js_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/customize-controls.js' ) : WOOMIZER_URL . 'assets/js/customize-controls.min.js';

		// Enqueue js script.
		wp_enqueue_script(
			'woomizer-customize-controls', // Give the script a unique ID.
			$js_file, // Define the path to the JS file.
			array( 'jquery', 'customize-controls' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);

		// Localize the script data.
		wp_localize_script(
			'woomizer-customize-controls',
			'woomizer_customize_controls_params',
			array(
				'url' => array(
					'product_loop'   => woomizer_preview_url( 'product_loop' ),
					'product_single' => woomizer_preview_url( 'product_single' ),
					'cart'           => woomizer_preview_url( 'cart' ),
					'checkout'       => woomizer_preview_url( 'checkout' ),
				),
			)
		);
	}

}
