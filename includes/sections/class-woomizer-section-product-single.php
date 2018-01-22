<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.1.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes/settings
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
 * @since      1.1.0
 * @package    Woomizer
 * @subpackage Woomizer/includes/settings
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Section_Product_Single extends Woomizer_Section {

	/**
	 * Adding panel in WordPress customizer.
	 *
	 * @since 1.1.0
	 */
	protected function add_settings() {

		// Adding setting for woomizer_product_single_add_to_cart_btn_text.
		$this->add_setting(
			'woomizer_product_single_add_to_cart_btn_text',
			array(
				'default'   => __( 'Add to Cart', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$this->add_control(
			'woomizer_product_single_add_to_cart_btn_text',
			array(
				'label' => __( 'Add to cart button text', 'woomizer' ),
			)
		);

		// Adding setting for woomizer_product_single_tabs.
		$this->add_setting(
			'product_single_tabs',
			array(
				'default'           => array(
					'description_hidden'            => 'no',
					'description_title'             => __( 'Description', 'woomizer' ),
					'additional_information_hidden' => 'no',
					'additional_information_title'  => __( 'Additional Information', 'woomizer' ),
					'reviews_hidden'                => 'no',
					// Translators: Reviews count.
					'reviews_title'                 => __( 'Reviews (%d)', 'woomizer' ),
				),
				'transport'         => 'postMessage',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'stripslashes_deep',
			)
		);

		$this->add_control(
			new Woomizer_Control_Product_Tabs(
				$this->wp_customize,
				'product_single_tabs',
				array(
					'label' => __( 'Products Tabs', 'woomizer' ),
				)
			)
		);
		$this->add_partial(
			'product_single_tabs',
			array(
				'selector'        => '.woocommerce-tabs.wc-tabs-wrapper',
				'render_callback' => array( $this, 'render_callback_product_single_tabs' ),
			)
		);
	}

	/**
	 * Render callback for partial refresh setting: woomizer_product_single_tabs.
	 *
	 * @since 1.1.0
	 */
	public function render_callback_product_single_tabs() {
		global $product;

		// Try to create new $product object if it was string product slug.
		if ( ! empty( $product ) && is_string( $product ) ) {
			$product = get_page_by_path( $product, OBJECT, 'product' );
			if ( $product ) {
				$product = wc_get_product( $product->ID );
			}
		}

		woocommerce_output_product_data_tabs();
	}
}
