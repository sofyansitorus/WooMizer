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

		// Register customizer settings panel.
		$panel = new Woomizer_Panel( $wp_customize, 'woomizer' );

		// Register customizer settings section: woomizer_section_product_loop.
		$section_product_loop = new Woomizer_Section_Product_Loop(
			$wp_customize,
			'woomizer_section_product_loop',
			array(
				'title' => __( 'Product Loop', 'woomizer' ),
				'panel' => $panel->get_panel_id(),
			)
		);

		// Register customizer settings section: woomizer_section_product_single.
		$section_product_loop = new Woomizer_Section_Product_Single(
			$wp_customize,
			'woomizer_section_product_single',
			array(
				'title' => __( 'Product Single', 'woomizer' ),
				'panel' => $panel->get_panel_id(),
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
