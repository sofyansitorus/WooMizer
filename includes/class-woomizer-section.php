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
	 * Customizer panel ID.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $panel_id;

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
		// Set value for $wp_customize property.
		$this->wp_customize = $wp_customize;

		// Set value for $section_id property.
		$this->section_id = $this->autoprefix( $section_id );

		// Set value for $args property.
		$this->args = wp_parse_args(
			$args,
			array(
				'priority'   => 10,
				'capability' => 'edit_theme_options',
				'title'      => $this->humanize( $this->section_id ),
			)
		);

		// Set value for $panel_id property.
		if ( empty( $this->args['panel'] ) ) {
			$this->args['panel'] = WOOMIZER_PREFIX;
		}
		$this->args['panel'] = $this->autoprefix( $this->args['panel'] );

		$this->panel_id = $this->args['panel'];

		// Add section to customizer settings.
		$this->add_section();

		// Add customizer settings fields.
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
	 * Get panel ID value.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_panel_id() {
		return $this->panel_id;
	}

	/**
	 * Initialize the customizer setting fields. This method must be overrided by child class.
	 *
	 * @since 1.1.0
	 */
	protected function add_settings() {}

	/**
	 * Wrap \WP_Customize_Manager::add_setting method for id autoprefix.
	 *
	 * @since 1.1.0
	 */
	public function add_setting() {
		$passed_args = func_get_args();

		if ( empty( $passed_args ) ) {
			return;
		}

		$setting_id = $passed_args[0];

		$setting_args = ( isset( $passed_args[1] ) && is_array( $passed_args[1] ) ) ? $passed_args[1] : array();

		if ( $setting_id instanceof WP_Customize_Setting ) {
			$setting_id->id = $this->autoprefix( $setting_id->id );
		}

		if ( ! $setting_id instanceof WP_Customize_Setting ) {
			$setting_id = $this->autoprefix( $setting_id );
		}

		$this->wp_customize->add_setting( $setting_id, $setting_args );
	}

}
