<?php
/**
 * Wrapper for WP_Customize_Manager class to simplify to build the customizer settings.
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.2.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
 */

/**
 * Woomizer_Setting classes
 *
 * @since      1.2.0
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Setting {

	/**
	 * Customizer manager.
	 *
	 * @since 1.2.0
	 * @var WP_Customize_Manager
	 */
	public $manager;

	/**
	 * All settings data tied to the woomizer.
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $data = array(
		'panels'   => array(),
		'sections' => array(),
		'settings' => array(),
		'controls' => array(),
		'partials' => array(),
	);

	/**
	 * Prefix for panel.
	 *
	 * @since 1.2.0
	 * @var tring
	 */
	private $panel_prefix = 'woomizer_panel';

	/**
	 * Current panel ID defined.
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $current_panel_id;

	/**
	 * Prefix for section.
	 *
	 * @since 1.2.0
	 * @var tring
	 */
	private $section_prefix = 'woomizer_section';

	/**
	 * Current section ID defined.
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $current_section_id;

	/**
	 * Prefix for setting.
	 *
	 * @since 1.2.0
	 * @var tring
	 */
	private $setting_prefix = 'woomizer_setting';

	/**
	 * Current setting ID defined.
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $current_setting_id;

	/**
	 * Current control ID defined.
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $current_control_id;

	/**
	 * Current partial ID defined.
	 *
	 * @since 1.2.0
	 * @var array
	 */
	private $current_partial_id;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param array                $args    {
	 *     Optional. Arguments to override class property defaults.
	 *     @type string  $panel_prefix          Customizer panel prefix.
	 *     @type string  $section_prefix        Customizer section prefix.
	 *     @type string  $setting_prefix        Customizer setting prefix.
	 *     @type string  $current_panel_id      Current customizer panel ID.
	 *     @type string  $current_section_id    Current customizer section ID.
	 *     @type string  $current_setting_id    Current customizer setting ID.
	 *     @type string  $current_control_id    Current customizer control ID.
	 *     @type string  $current_partial_id    Current customizer partial ID.
	 * }
	 */
	public function __construct( $manager, $args = array() ) {

		$this->load_dependencies();

		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( in_array( $key, array( 'manager', 'data' ), true ) ) {
				continue;
			}
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
	}

	/**
	 * Add customizer settings panel.
	 *
	 * @since 1.2.0
	 * @param WP_Customize_Panel|string $id   Customize Panel object, or Panel ID.
	 * @param array                     $args {
	 *  Optional. Array of properties for the new Panel object. Default empty array.
	 *  @type int          $priority              Priority of the panel, defining the display order of panels and sections.
	 *                                            Default 160.
	 *  @type string       $capability            Capability required for the panel. Default `edit_theme_options`
	 *  @type string|array $theme_supports        Theme features required to support the panel.
	 *  @type string       $title                 Title of the panel to show in UI.
	 *  @type string       $description           Description to show in the UI.
	 *  @type string       $type                  Type of the panel.
	 *  @type callable     $active_callback       Active callback.
	 * }
	 */
	public function add_panel( $id = null, $args = array() ) {

		if ( empty( $id ) ) {
			$id = WOOMIZER_PREFIX;
		}

		if ( $id instanceof WP_Customize_Panel ) {

			$this->current_panel_id = $this->panel_prefix( $id->id );

			$this->data['panels'][ $this->current_panel_id ] = $id;

		} elseif ( is_string( $id ) ) {

			$this->current_panel_id = $this->panel_prefix( $id );

			if ( ! isset( $args['title'] ) ) {
				$args['title'] = $this->panel_auto_title( $this->current_panel_id );
			}

			$this->data['panels'][ $this->current_panel_id ] = $args;

		}

	}

	/**
	 * Add a customize section.
	 *
	 * @since 1.2.0
	 * @since 4.5.0 Return added WP_Customize_Section instance.
	 *
	 * @param WP_Customize_Section|string $id   Customize Section object, or Section ID.
	 * @param array                       $args {
	 *    Optional. Array of properties for the new Section object. Default empty array.
	 *  @type int          $priority              Priority of the section, defining the display order of panels and sections.
	 *                                            Default 160.
	 *  @type string       $panel                 The panel this section belongs to (if any). Default empty.
	 *  @type string       $capability            Capability required for the section. Default 'edit_theme_options'
	 *  @type string|array $theme_supports        Theme features required to support the section.
	 *  @type string       $title                 Title of the section to show in UI.
	 *  @type string       $description           Description to show in the UI.
	 *  @type string       $type                  Type of the section.
	 *  @type callable     $active_callback       Active callback.
	 *  @type bool         $description_hidden    Hide the description behind a help icon, instead of inline above the first control. Default false.
	 * }
	 */
	public function add_section( $id, $args = array() ) {

		if ( $id instanceof WP_Customize_Section ) {

			$this->current_section_id = $this->section_prefix( $id->id );

			$this->data['sections'][ $this->current_section_id ] = $id;

		} elseif ( is_string( $id ) ) {

			$this->current_section_id = $this->section_prefix( $id );

			if ( ! empty( $args['panel'] ) ) {
				$args['panel'] = $this->panel_prefix( $args['panel'] );
			}

			if ( empty( $args['panel'] ) && $this->current_panel_id ) {
				$args['panel'] = $this->current_panel_id;
			}

			if ( ! isset( $args['title'] ) ) {
				$args['title'] = $this->section_auto_title( $this->current_section_id );
			}

			$this->data['sections'][ $this->current_section_id ] = $args;

		}

	}

	/**
	 * Add a customize setting.
	 *
	 * @since 1.2.0
	 * @since 4.5.0 Return added WP_Customize_Setting instance.
	 *
	 * @param WP_Customize_Setting|string $id   Customize Setting object, or ID.
	 * @param array                       $args {
	 *  Optional. Array of properties for the new WP_Customize_Setting. Default empty array.
	 *
	 *  @type string       $type                  Type of the setting. Default 'theme_mod'.
	 *                                            Default 160.
	 *  @type string       $capability            Capability required for the setting. Default 'edit_theme_options'
	 *  @type string|array $theme_supports        Theme features required to support the panel. Default is none.
	 *  @type string       $default               Default value for the setting. Default is empty string.
	 *  @type string       $transport             Options for rendering the live preview of changes in Theme Customizer.
	 *                                            Using 'refresh' makes the change visible by reloading the whole preview.
	 *                                            Using 'postMessage' allows a custom JavaScript to handle live changes.
	 *                                            @link https://developer.wordpress.org/themes/customize-api
	 *                                            Default is 'refresh'
	 *  @type callable     $validate_callback     Server-side validation callback for the setting's value.
	 *  @type callable     $sanitize_callback     Callback to filter a Customize setting value in un-slashed form.
	 *  @type callable     $sanitize_js_callback  Callback to convert a Customize PHP setting value to a value that is
	 *                                            JSON serializable.
	 *  @type bool         $dirty                 Whether or not the setting is initially dirty when created.
	 * }
	 */
	public function add_setting( $id, $args = array() ) {

		if ( empty( $this->current_section_id ) ) {
			return;
		}

		if ( $id instanceof WP_Customize_Setting ) {

			$this->current_setting_id = $this->setting_prefix( $id->id );

			$this->data['settings'][ $this->current_setting_id ] = $id;

		} elseif ( is_string( $id ) ) {

			$this->current_setting_id = $this->setting_prefix( $id );

			if ( empty( $args['transport'] ) ) {
				$args['transport'] = 'postMessage';
			}

			if ( isset( $args['control'] ) ) {
				if ( $args['control'] instanceof WP_Customize_Control ) {
					$this->add_control( $args['control'] );
				} elseif ( is_string( $args['control'] ) ) {
					$this->add_control( $args['control'] );
				} else {
					if ( is_array( $args['control'] ) || $args['control'] ) {
						if ( ! is_array( $args['control'] ) ) {
							$args['control'] = array();
						}
						$this->add_control( $this->current_setting_id, $args['control'] );
					}
				}
				unset( $args['control'] );
			}

			if ( isset( $args['partial'] ) ) {
				if ( $args['partial'] instanceof WP_Customize_Partial ) {
					$this->add_partial( $args['partial'] );
				} elseif ( is_string( $args['partial'] ) ) {
					$this->add_partial( $args['partial'] );
				} else {
					if ( is_array( $args['partial'] ) || $args['partial'] ) {
						if ( ! is_array( $args['partial'] ) ) {
							$args['partial'] = array();
						}
						$this->add_partial( $this->current_setting_id, $args['partial'] );
					}
				}
				unset( $args['partial'] );
			}

			$this->data['settings'][ $this->current_setting_id ] = $args;

		}

	}

	/**
	 * Add a customize control.
	 *
	 * @since 1.2.0
	 * @since 4.5.0 Return added WP_Customize_Control instance.
	 *
	 * @param WP_Customize_Control|string $id   Customize Control object, or ID.
	 * @param array                       $args {
	 *  Optional. Array of properties for the new Control object. Default empty array.
	 *
	 *  @type array        $settings              All settings tied to the control. If undefined, defaults to `$setting`.
	 *                                            IDs in the array correspond to the ID of a registered `WP_Customize_Setting`.
	 *  @type string       $setting               The primary setting for the control (if there is one). Default is 'default'.
	 *  @type string       $capability            Capability required to use this control. Normally derived from `$settings`.
	 *  @type int          $priority              Order priority to load the control. Default 10.
	 *  @type string       $section               The section this control belongs to. Default empty.
	 *  @type string       $label                 Label for the control. Default empty.
	 *  @type string       $description           Description for the control. Default empty.
	 *  @type array        $choices               List of choices for 'radio' or 'select' type controls, where values
	 *                                            are the keys, and labels are the values. Default empty array.
	 *  @type array        $input_attrs           List of custom input attributes for control output, where attribute
	 *                                            names are the keys and values are the values. Default empty array.
	 *  @type bool         $allow_addition        Show UI for adding new content, currently only used for the
	 *                                            dropdown-pages control. Default false.
	 *  @type string       $type                  The type of the control. Default 'text'.
	 *  @type callback     $active_callback       Active callback.
	 * }
	 */
	public function add_control( $id, $args = array() ) {

		if ( $id instanceof WP_Customize_Control ) {

			$this->current_control_id = $this->setting_prefix( $id->id );

			$this->data['controls'][ $this->current_control_id ] = $id;

		} elseif ( is_string( $id ) ) {

			$this->current_control_id = $this->setting_prefix( $id );

			if ( ! empty( $args['section'] ) ) {
				$args['section'] = $this->section_prefix( $args['section'] );
			}

			if ( empty( $args['section'] ) && $this->current_section_id ) {
				$args['section'] = $this->current_section_id;
			}

			if ( ! isset( $args['label'] ) ) {
				$args['label'] = $this->control_auto_label( $this->current_control_id );
			}

			$this->data['controls'][ $this->current_control_id ] = $args;

		}

	}

	/**
	 * Adds a partial.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_Customize_Partial|string $id   Customize Partial object, or Panel ID.
	 * @param array                       $args {
	 *  Optional. Array of properties for the new Partials object. Default empty array.
	 *
	 *  @type string   $type                  Type of the partial to be created.
	 *  @type string   $selector              The jQuery selector to find the container element for the partial, that is, a partial's placement.
	 *  @type array    $settings              IDs for settings tied to the partial.
	 *  @type string   $primary_setting       The ID for the setting that this partial is primarily responsible for
	 *                                        rendering. If not supplied, it will default to the ID of the first setting.
	 *  @type string   $capability            Capability required to edit this partial.
	 *                                        Normally this is empty and the capability is derived from the capabilities
	 *                                        of the associated `$settings`.
	 *  @type callable $render_callback       Render callback.
	 *                                        Callback is called with one argument, the instance of WP_Customize_Partial.
	 *                                        The callback can either echo the partial or return the partial as a string,
	 *                                        or return false if error.
	 *  @type bool     $container_inclusive   Whether the container element is included in the partial, or if only
	 *                                        the contents are rendered.
	 *  @type bool     $fallback_refresh      Whether to refresh the entire preview in case a partial cannot be refreshed.
	 *                                        A partial render is considered a failure if the render_callback returns
	 *                                        false.
	 * }
	 */
	public function add_partial( $id, $args = array() ) {

		if ( $id instanceof WP_Customize_Partial ) {

			$this->current_partial_id = $this->setting_prefix( $id->id );

			$this->data['partials'][ $this->current_control_id ] = $id;

		} elseif ( is_string( $id ) ) {

			$this->current_partial_id = $this->setting_prefix( $id );

			$this->data['partials'][ $this->current_control_id ] = $args;
		}
	}

	/**
	 * Build the customizer settings.
	 *
	 * @since 1.2.0
	 */
	public function build() {

		$data = apply_filters( 'woomizer_customizer_data', $this->data );

		// Build customizer panels.
		foreach ( $data['panels'] as $panel_id => $args ) {
			if ( $args instanceof WP_Customize_Panel ) {
				$this->manager->add_panel( $args );
				continue;
			}

			if ( ! empty( $args['type'] ) && class_exists( 'Woomizer_Panel_' . woomizer_class_case( $args['type'] ) ) ) {
				$class = 'Woomizer_Panel_' . woomizer_class_case( $args['type'] );
				$this->manager->add_panel( new $class( $this->manager, $panel_id, $args ) );
				continue;
			}

			$this->manager->add_panel( new Woomizer_Customize_Panel( $this->manager, $panel_id, $args ) );
		}

		// Build customizer sections.
		foreach ( $data['sections'] as $section_id => $args ) {
			if ( $args instanceof WP_Customize_Section ) {
				$this->manager->add_section( $args );
				continue;
			}

			if ( ! empty( $args['type'] ) && class_exists( 'Woomizer_Section_' . woomizer_class_case( $args['type'] ) ) ) {
				$class = 'Woomizer_Section_' . woomizer_class_case( $args['type'] );
				$this->manager->add_section( new $class( $this->manager, $section_id, $args ) );
				continue;
			}

			$this->manager->add_section( new Woomizer_Customize_Section( $this->manager, $section_id, $args ) );
		}

		// Build customizer settings.
		foreach ( $data['settings'] as $setting_id => $args ) {
			if ( $args instanceof WP_Customize_Setting ) {
				$this->manager->add_setting( $args );
				continue;
			}

			if ( ! empty( $args['type'] ) && class_exists( 'Woomizer_Setting_' . woomizer_class_case( $args['type'] ) ) ) {
				$class = 'Woomizer_Setting_' . woomizer_class_case( $args['type'] );
				$this->manager->add_setting( new $class( $this->manager, $setting_id, $args ) );
				continue;
			}

			$this->manager->add_setting( new Woomizer_Customize_Setting( $this->manager, $setting_id, $args ) );
		}

		// Build customizer controls.
		foreach ( $data['controls'] as $control_id => $args ) {
			if ( $args instanceof WP_Customize_Control ) {
				$this->manager->add_control( $args );
				continue;
			}

			if ( ! empty( $args['type'] ) && class_exists( 'Woomizer_Control_' . woomizer_class_case( $args['type'] ) ) ) {
				$class = 'Woomizer_Control_' . woomizer_class_case( $args['type'] );
				$this->manager->add_control( new $class( $this->manager, $control_id, $args ) );
				continue;
			}

			$this->manager->add_control( new Woomizer_Customize_Control( $this->manager, $control_id, $args ) );
		}

		// Build customizer partials.
		foreach ( $data['partials'] as $partial_id => $args ) {
			if ( $args instanceof WP_Customize_Partial ) {
				$this->manager->selective_refresh->add_partial( $args );
				continue;
			}

			if ( ! empty( $args['type'] ) && class_exists( 'Woomizer_Partial_' . woomizer_class_case( $args['type'] ) ) ) {
				$class = 'Woomizer_Partial_' . woomizer_class_case( $args['type'] );
				$this->manager->selective_refresh->add_partial( new $class( $this->manager->selective_refresh, $partial_id, $args ) );
				continue;
			}

			$this->manager->selective_refresh->add_partial( new Woomizer_Customize_Partial( $this->manager->selective_refresh, $partial_id, $args ) );
		}

	}

	/**
	 * Generate panel prefix.
	 *
	 * @since 1.2.0
	 * @param string $id Customizer panel ID.
	 * @return string
	 */
	private function panel_prefix( $id ) {
		return woomizer_autoprefix( $id, $this->panel_prefix );
	}

	/**
	 * Generate section prefix.
	 *
	 * @since 1.2.0
	 * @param string $id Customizer section ID.
	 * @return string
	 */
	private function section_prefix( $id ) {
		return woomizer_autoprefix( $id, $this->section_prefix );
	}

	/**
	 * Generate setting prefix.
	 *
	 * @since 1.2.0
	 * @param string $id Customizer setting ID.
	 * @return string
	 */
	private function setting_prefix( $id ) {
		return woomizer_autoprefix( $id, $this->setting_prefix );
	}

	/**
	 * Generate panel title.
	 *
	 * @since 1.2.0
	 * @param string $id Customizer panel ID.
	 * @return string
	 */
	private function panel_auto_title( $id ) {
		return woomizer_humanize( str_replace( $this->panel_prefix, '', $id ) );

	}

	/**
	 * Generate section title.
	 *
	 * @since 1.2.0
	 * @param string $id Customizer section ID.
	 * @return string
	 */
	private function section_auto_title( $id ) {
		return woomizer_humanize( str_replace( $this->section_prefix, '', $id ) );

	}

	/**
	 * Generate control label.
	 *
	 * @since 1.2.0
	 * @param string $id Customizer control ID.
	 * @return string
	 */
	private function control_auto_label( $id ) {
		return woomizer_humanize( str_replace( $this->setting_prefix, '', $id ) );
	}

	/**
	 * Load class dependencies.
	 *
	 * @since 1.2.0
	 */
	private function load_dependencies() {
		require_once WOOMIZER_PATH . 'includes/class-woomizer-customize-panel.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-customize-section.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-customize-setting.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-customize-control.php';
		require_once WOOMIZER_PATH . 'includes/class-woomizer-customize-partial.php';

		// Load customizer panels dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/panels/class-woomizer-panel-*.php' ) as $filename ) {
			require_once $filename;
		}

		// Load customizer sections dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/sections/class-woomizer-section-*.php' ) as $filename ) {
			require_once $filename;
		}

		// Load customizer settings dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/settings/class-woomizer-setting-*.php' ) as $filename ) {
			require_once $filename;
		}

		// Load customizer controls dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/controls/class-woomizer-control-*.php' ) as $filename ) {
			require_once $filename;
		}

		// Load customizer partials dependencies.
		foreach ( glob( WOOMIZER_PATH . 'includes/partials/class-woomizer-partial-*.php' ) as $filename ) {
			require_once $filename;
		}
	}

}
