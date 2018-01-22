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
class Woomizer_Section_General extends Woomizer_Section {


	/**
	 * Adding panel in WordPress customizer.
	 *
	 * @since 1.1.0
	 */
	protected function init_settings() {

		// Adding setting for woomizer_general_flash_sale_text.
		$this->add_setting(
			'general_flash_sale_text',
			array(
				'default'   => __( 'Sale!', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$this->add_control(
			'general_flash_sale_text',
			array(
				'label'   => __( 'Flash sale text', 'woomizer' ),
				'section' => $this->get_section_id(),
			)
		);
	}
}
