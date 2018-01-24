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
 * @subpackage Woomizer/includes/sections
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
 * @subpackage Woomizer/includes/sections
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Section_Product_Loop extends Woomizer_Section {

	/**
	 * Adding panel in WordPress customizer.
	 *
	 * @since 1.1.0
	 */
	protected function init_settings() {

		// Adding setting for woomizer_product_loop_flash_sale_text.
		$this->add_setting(
			'product_loop_flash_sale_text',
			array(
				'default'   => __( 'Sale!', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$this->add_control(
			'product_loop_flash_sale_text',
			array(
				'label' => __( 'Flash sale text', 'woomizer' ),
			)
		);

		// Adding setting for woomizer_product_loop_add_to_cart_btn_text.
		$this->add_setting(
			'product_loop_add_to_cart_btn_text'
		);
		$this->add_control(
			new Woomizer_Control_Divider(
				$this->wp_customize,
				'product_loop_add_to_cart_btn_text',
				array(
					'label' => 'Add to Cart Button Text',
				)
			)
		);

		// Adding setting for woomizer_product_loop_add_to_cart_btn_text_simple.
		$this->add_setting(
			'product_loop_add_to_cart_btn_text_simple',
			array(
				'default'   => __( 'Add to Cart', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$this->add_control(
			'product_loop_add_to_cart_btn_text_simple',
			array(
				'label' => __( 'Simple Product', 'woomizer' ),
			)
		);

		// Adding setting for woomizer_product_loop_add_to_cart_btn_text_variable.
		$this->add_setting(
			'product_loop_add_to_cart_btn_text_variable',
			array(
				'default'   => __( 'Select options', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$this->add_control(
			'product_loop_add_to_cart_btn_text_variable',
			array(
				'label' => __( 'Variable Product', 'woomizer' ),
			)
		);

		// Adding setting for woomizer_product_loop_add_to_cart_btn_text_grouped.
		$this->add_setting(
			'product_loop_add_to_cart_btn_text_grouped',
			array(
				'default'   => __( 'View products', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$this->add_control(
			'product_loop_add_to_cart_btn_text_grouped',
			array(
				'label' => __( 'Grouped Product', 'woomizer' ),
			)
		);
	}
}
