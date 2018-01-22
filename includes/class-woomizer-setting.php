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
class Woomizer_Setting {

	/**
	 * WP_Customize_Manager object.
	 *
	 * @since 1.0.0
	 * @var \WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * @since 1.0.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	public function __construct( WP_Customize_Manager $wp_customize ) {
		$this->wp_customize = $wp_customize;
		$this->init();
	}

	/**
	 * Initialize the customizer setting. This method must be overrided by child class.
	 *
	 * @since 1.0.0
	 */
	protected function init() {}
}
