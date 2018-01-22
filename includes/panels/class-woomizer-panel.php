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
 * @subpackage Woomizer/includes/panels
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
 * @subpackage Woomizer/includes/panels
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Panel extends Woomizer_Setting {

	/**
	 * Adding panel in WordPress customizer.
	 *
	 * @since 1.1.0
	 */
	protected function init() {
		$this->wp_customize->add_panel(
			'woomizer_panel',
			array(
				'title'      => __( 'Woomizer', 'woomizer' ),
				'capability' => 'edit_theme_options',
			)
		);
	}
}
