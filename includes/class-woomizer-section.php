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
 * @since      1.1.0
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Section extends Woomizer_Setting {

	/**
	 * WP_Customize_Manager object.
	 *
	 * @since 1.1.0
	 * @var \WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * Customizer section ID.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $section_id;

	/**
	 * Customizer section arguments.
	 *
	 * @since 1.1.0
	 * @var array
	 */
	protected $args;

	/**
	 * Class constructor.
	 *
	 * @since 1.1.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 * @param string                $section_id Customizer section ID.
	 * @param array                 $args Customizer section arguments.
	 */
	public function __construct( WP_Customize_Manager $wp_customize, $section_id, $args = array() ) {
		$this->wp_customize = $wp_customize;
		$this->section_id   = $section_id;
		$this->args         = wp_parse_args(
			$args,
			array(
				'priority'   => 10,
				'capability' => 'edit_theme_options',
				'title'      => $this->humanize( $this->section_id ),
			)
		);
		$this->add_section();
		$this->add_settings();
	}

	/**
	 * Add customizer settings section.
	 *
	 * @since 1.1.0
	 */
	protected function add_section() {
		$this->wp_customize->add_section(
			$this->section_id,
			$this->args
		);
	}

	/**
	 * Get section ID value.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_section_id() {
		return $this->section_id;
	}

	/**
	 * Initialize the customizer setting fields. This method must be overrided by child class.
	 *
	 * @since 1.1.0
	 */
	protected function add_settings() {}
}
