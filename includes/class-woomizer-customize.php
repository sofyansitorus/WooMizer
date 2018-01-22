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
class Woomizer_Customize {

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * @since 1.0.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	public function register( $wp_customize ) {

		// Load dependencies.
		$this->load_dependencies();

		$this->init_panel( $wp_customize );
		$this->init_section_product_loop( $wp_customize );
		$this->init_section_product_single( $wp_customize );
	}

	/**
	 * Initialize customizer settings panel
	 *
	 * @since 1.1.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	private function init_panel( $wp_customize ) {
		$obj = new Woomizer_Panel( $wp_customize );
	}

	/**
	 * Initialize customizer settings section: product_loop.
	 *
	 * @since 1.1.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	private function init_section_product_loop( $wp_customize ) {
		$obj = new Woomizer_Section_Product_Loop( $wp_customize );
	}

	/**
	 * Initialize customizer settings section: product_single.
	 *
	 * @since 1.1.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	private function init_section_product_single( $wp_customize ) {
		$obj = new Woomizer_Section_Product_Single( $wp_customize );
	}

	/**
	 * Filter the default product tabs.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
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
				$custom_text = get_theme_mod( 'woomizer_products_loop_add_to_cart_btn_text_simple' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() && $product->is_in_stock() ) {
					return $custom_text;
				}
				break;
			case 'variable':
				$custom_text = get_theme_mod( 'woomizer_products_loop_add_to_cart_btn_text_variable' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() ) {
					return $custom_text;
				}
				break;
			case 'grouped':
				$custom_text = get_theme_mod( 'woomizer_products_loop_add_to_cart_btn_text_grouped' );
				if ( ! empty( $custom_text ) ) {
					return $custom_text;
				}
				break;
		}

		return $text;
	}

	/**
	 * Filter the settings arguments for woomizer_product_single_tabs.
	 *
	 * @since 1.0.0
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
	public static function live_preview() {
		$file_name = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/live-preview.js' ) : WOOMIZER_URL . 'assets/js/live-preview.min.js';
		wp_enqueue_script(
			'woomizer-live-preview', // Give the script a unique ID.
			$file_name, // Define the path to the JS file.
			array( 'jquery', 'customize-preview' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);
	}

	/**
	 * Load customizer setting dependencies.
	 *
	 * @since 1.1.0
	 */
	private function load_dependencies() {
		// Load customizer setting panels dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/panels/class-woomizer-*.php' ) as $filename ) {
			include_once $filename;
		}

		// Load customizer setting sections dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/sections/class-woomizer-*.php' ) as $filename ) {
			include_once $filename;
		}

		// Load customizer setting controls dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/controls/class-woomizer-control-*.php' ) as $filename ) {
			include $filename;
		}
	}
}
