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
class Woomizer_Panel extends Woomizer_Setting {

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
	 * Customizer panel arguments.
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
	 * @param string                $panel_id Customizer panel ID.
	 * @param array                 $args Customizer panel arguments.
	 */
	public function __construct( WP_Customize_Manager $wp_customize, $panel_id = null, $args = array() ) {
		// Set value for $wp_customize property.
		$this->wp_customize = $wp_customize;

		// Set value for $panel_id property.
		$this->panel_id = $panel_id;
		if ( empty( $this->panel_id ) ) {
			$this->panel_id = WOOMIZER_PREFIX;
		}
		$this->panel_id = $this->autoprefix( $this->panel_id );

		// Set value for $args property.
		$this->args = wp_parse_args(
			$args,
			array(
				'priority'   => 1111,
				'capability' => 'edit_theme_options',
			)
		);

		if ( empty( $this->args['title'] ) ) {
			$this->args['title'] = $this->humanize( $this->panel_id );
		}

		// Add panel to customizer settings.
		$this->add_panel();
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
	 * Add customizer settings panel.
	 *
	 * @since 1.1.0
	 */
	protected function add_panel() {
		$this->wp_customize->add_panel(
			$this->panel_id,
			$this->args
		);
	}
}
