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
		$this->init_settings();
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
	protected function init_settings() {}

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

		$setting = $passed_args[0];

		// Handle if $setting is instance of WP_Customize_Setting.
		if ( $setting instanceof WP_Customize_Setting ) {
			if ( $setting->id !== $this->autoprefix( $setting->id ) ) {

				// Remove invalid ID customizer setting.
				$this->wp_customize->remove_setting( $setting->id );

				$id = $this->autoprefix( $setting->id );

				$args = array();

				$keys = array_keys( get_class_vars( get_class( $setting ) ) );

				foreach ( $keys as $key ) {
					$args[ $key ] = $setting->{$key};
				}

				$class = get_class( $setting );

				$setting = new $class( $this->wp_customize, $id, $args );

				// Add the customizer setting.
				$this->wp_customize->add_setting( $setting );

				return;
			}

			// Add the customizer setting.
			$this->wp_customize->add_setting( $setting );
			return;
		}

		// Check if $setting is string.
		if ( ! is_string( $setting ) ) {
			return;
		}

		$args = ( isset( $passed_args[1] ) && is_array( $passed_args[1] ) ) ? $passed_args[1] : array();

		$setting = $this->autoprefix( $setting );

		// Add the customizer setting.
		$this->wp_customize->add_setting( $setting, $args );
	}

	/**
	 * Wrap \WP_Customize_Manager::add_control method for id autoprefix.
	 *
	 * @since 1.1.0
	 */
	public function add_control() {
		$passed_args = func_get_args();

		if ( empty( $passed_args ) ) {
			return;
		}

		$control = $passed_args[0];

		// Handle if $control is instance of WP_Customize_Control.
		if ( $control instanceof WP_Customize_Control ) {

			if ( $control->id !== $this->autoprefix( $control->id ) ) {

				// Remove invalid ID customizer control.
				$this->wp_customize->remove_control( $control->id );

				// Modify value for WP_Customize_Control::id.
				$control->id = $this->autoprefix( $control->id );

				// Modify value for WP_Customize_Control::settings.
				$settings = array();
				foreach ( $control->settings as $key => $setting ) {
					if ( ! empty( $setting ) ) {
						$settings[ $key ] = $setting;
						continue;
					}
					switch ( $key ) {
						case 'default':
							$settings[ $key ] = $control->manager->get_setting( $control->id );
							break;

						default:
							$settings[ $this->autoprefix( $key ) ] = $control->manager->get_setting( $this->autoprefix( $key ) );
							break;
					}
				}
				$control->settings = $settings;
			}

			// Modify value for WP_Customize_Control::section.
			if ( empty( $control->section ) ) {
				$control->section = $this->get_section_id();
			}
			$control->section = $this->autoprefix( $control->section );

			// Add the customizer control.
			$this->wp_customize->add_control( $control );
			return;
		}

		// Check if $control is string.
		if ( ! is_string( $control ) ) {
			return;
		}

		$args = ( isset( $passed_args[1] ) && is_array( $passed_args[1] ) ) ? $passed_args[1] : array();

		// Modify value for WP_Customize_Control::id.
		$control = $this->autoprefix( $control );

		// Modify value for WP_Customize_Control::section.
		if ( empty( $args['section'] ) ) {
			$args['section'] = $this->get_section_id();
		}
		$args['section'] = $this->autoprefix( $args['section'] );

		// Add the customizer control.
		$this->wp_customize->add_control( $control, $args );

	}

	/**
	 * Wrap \WP_Customize_Selective_Refresh::add_partial method for id autoprefix.
	 *
	 * @since 1.1.0
	 */
	public function add_partial() {
		$passed_args = func_get_args();

		if ( empty( $passed_args ) ) {
			return;
		}

		$customize_partial_id = $passed_args[0];

		$customize_partial_args = ( isset( $passed_args[1] ) && is_array( $passed_args[1] ) ) ? $passed_args[1] : array();

		if ( $customize_partial_id instanceof WP_Customize_Partial && $customize_partial_id->id !== $this->autoprefix( $customize_partial_id->id ) ) {
			$id   = $this->autoprefix( $customize_partial_id->id );
			$args = get_object_vars( $customize_partial_id );
			$this->wp_customize->selective_refresh->add_partial( $id, $args );
			return;
		}

		if ( ! $customize_partial_id instanceof WP_Customize_Partial ) {
			$customize_partial_id = $this->autoprefix( $customize_partial_id );
			$this->wp_customize->selective_refresh->add_partial( $customize_partial_id, $customize_partial_args );
		}
	}

}
