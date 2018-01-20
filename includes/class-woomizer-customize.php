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
class Woomizer_Customize {

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * @since 1.0.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	public static function register( $wp_customize ) {

		// Load custom controls dependencies.
		$this->load_custom_controls();

		// Adding panel in WordPress customizer.
		$wp_customize->add_panel(
			'woomizer_panel',
			array(
				'title'      => __( 'Woomizer', 'woomizer' ),
				'capability' => 'edit_theme_options',
			)
		);

		$this->add_section_products_loop( $wp_customize );
		$this->add_section_product_single( $wp_customize );
	}

	/**
	 * Filter the default product tabs.
	 *
	 * @since 1.0.0
	 * @param array $tabs Current product tabs.
	 * @return array
	 */
	public function product_tabs( $tabs ) {
		global $product;

		// Try to create new $product object if it was string product slug.
		if ( ! empty( $product ) && is_string( $product ) ) {
			$product = get_page_by_path( $product, OBJECT, 'product' );
			if ( $product ) {
				$product = wc_get_product( $product->ID );
			}
		}

		$tab_keys = array(
			'description',
			'additional_information',
			'reviews',
		);

		$options = get_theme_mod( 'woomizer_product_single_tabs', array() );

		if ( ! is_array( $options ) ) {
			$options = json_decode( $options, true );
		}

		foreach ( $tab_keys as $tab_key ) {
			if ( ! isset( $tabs[ $tab_key ] ) ) {
				continue;
			}
			if ( isset( $options[ $tab_key . '_hidden' ] ) && 'yes' === $options[ $tab_key . '_hidden' ] ) {
				unset( $tabs[ $tab_key ] );
				continue;
			}
			if ( ! empty( $options[ $tab_key . '_title' ] ) ) {
				switch ( $tab_key ) {
					case 'reviews':
						$tabs[ $tab_key ]['title'] = ( false !== strpos( $options[ $tab_key . '_title' ], '%d' ) && $product instanceof WC_Product ) ? sprintf( $options[ $tab_key . '_title' ], $product->get_review_count() ) : $options[ $tab_key . '_title' ];
						break;

					default:
						$tabs[ $tab_key ]['title'] = $options[ $tab_key . '_title' ];
						break;
				}
			}
		}
		return $tabs;
	}

	/**
	 * Filter add to cart button text for product single.
	 *
	 * @since 1.0.0
	 * @param string      $text Current button text.
	 * @param \WC_Product $product Current product object.
	 * @return string
	 */
	public function single_add_to_cart_btn_text( $text, $product ) {
		$custom_text = get_theme_mod( 'woomizer_product_single_add_to_cart_btn_text' );
		if ( ! empty( $custom_text ) ) {
			return $custom_text;
		}
		return $text;
	}

	/**
	 * Filter add to cart button text for product loop.
	 *
	 * @since 1.0.0
	 * @param string      $text Current button text.
	 * @param \WC_Product $product Current product object.
	 * @return string
	 */
	public function loop_add_to_cart_btn_text( $text, $product ) {
		switch ( $product->get_type() ) {
			case 'external':
				return $text;
				break;
			case 'simple':
				$custom_text = get_theme_mod( 'woomizer_products_loop_add_to_cart_btn_text_simple' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() && $product->is_in_stock() ) {
					return $custom_text;
				}
				break;
			case 'variable':
				$custom_text = get_theme_mod( 'woomizer_products_loop_add_to_cart_btn_text_variable' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() ) {
					return $custom_text;
				}
				break;
			case 'grouped':
				$custom_text = get_theme_mod( 'woomizer_products_loop_add_to_cart_btn_text_grouped' );
				if ( ! empty( $custom_text ) ) {
					return $custom_text;
				}
				break;
		}

		return $text;
	}

	/**
	 * Filter the settings arguments for woomizer_product_single_tabs.
	 *
	 * @since 1.0.0
	 * @param array  $args Customiser setting arguments.
	 * @param string $id Customiser setting ID.
	 * @return array
	 */
	public function dynamic_setting_args( $args, $id ) {
		if ( 'woomizer_product_single_tabs' === $id && isset( $args['default'] ) && is_array( $args['default'] ) ) {
			$args['default'] = wp_json_encode( $args['default'] );
		}
		return $args;
	}

	/**
	 * Add settings field for products_loop section
	 *
	 * @since 1.0.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	private function add_section_products_loop( $wp_customize ) {

		// Adding new section: woomizer_section_products_loop.
		$wp_customize->add_section(
			'woomizer_section_products_loop',
			array(
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'title'       => __( 'Products Loop', 'woomizer' ),
				'description' => __( 'Products loop customization', 'woomizer' ),
				'panel'       => 'woomizer_panel',
			)
		);

		// Adding setting for woomizer_products_loop_add_to_cart_btn_text.
		$wp_customize->add_setting(
			'woomizer_products_loop_add_to_cart_btn_text'
		);
		$wp_customize->add_control(
			new Woomizer_Control_Divider(
				$wp_customize,
				'woomizer_products_loop_add_to_cart_btn_text',
				array(
					'label'   => 'Add to Cart Button Text',
					'section' => 'woomizer_section_products_loop',
				)
			)
		);

		// Adding setting for woomizer_products_loop_add_to_cart_btn_text_simple.
		$wp_customize->add_setting(
			'woomizer_products_loop_add_to_cart_btn_text_simple',
			array(
				'default'   => __( 'Add to Cart', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$wp_customize->add_control(
			'woomizer_products_loop_add_to_cart_btn_text_simple',
			array(
				'label'   => __( 'Simple Product', 'woomizer' ),
				'section' => 'woomizer_section_products_loop',
			)
		);

		// Adding setting for woomizer_products_loop_add_to_cart_btn_text_variable.
		$wp_customize->add_setting(
			'woomizer_products_loop_add_to_cart_btn_text_variable',
			array(
				'default'   => __( 'Select options', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$wp_customize->add_control(
			'woomizer_products_loop_add_to_cart_btn_text_variable',
			array(
				'label'   => __( 'Variable Product', 'woomizer' ),
				'section' => 'woomizer_section_products_loop',
			)
		);

		// Adding setting for woomizer_products_loop_add_to_cart_btn_text_grouped.
		$wp_customize->add_setting(
			'woomizer_products_loop_add_to_cart_btn_text_grouped',
			array(
				'default'   => __( 'View products', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$wp_customize->add_control(
			'woomizer_products_loop_add_to_cart_btn_text_grouped',
			array(
				'label'   => __( 'Grouped Product', 'woomizer' ),
				'section' => 'woomizer_section_products_loop',
			)
		);
	}

	/**
	 * Add settings field for product_single section
	 *
	 * @since 1.0.0
	 * @param \WP_Customize_Manager $wp_customize Customize manager class.
	 */
	private function add_section_product_single( $wp_customize ) {

		// Adding new section: woomizer_section_product_single.
		$wp_customize->add_section(
			'woomizer_section_product_single',
			array(
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'title'       => __( 'Single Product', 'woomizer' ),
				'description' => __( 'Single Product customization', 'woomizer' ),
				'panel'       => 'woomizer_panel',
			)
		);

		// Adding setting for woomizer_product_single_add_to_cart_btn_text.
		$wp_customize->add_setting(
			'woomizer_product_single_add_to_cart_btn_text',
			array(
				'default'   => __( 'Add to Cart', 'woomizer' ),
				'transport' => 'postMessage',
				'type'      => 'theme_mod',
			)
		);
		$wp_customize->add_control(
			'woomizer_product_single_add_to_cart_btn_text',
			array(
				'label'   => __( 'Add to cart button text', 'woomizer' ),
				'section' => 'woomizer_section_product_single',
			)
		);

		// Adding setting for woomizer_product_single_tabs.
		$wp_customize->add_setting(
			'woomizer_product_single_tabs',
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

		$wp_customize->add_control(
			new Woomizer_Control_Product_Tabs(
				$wp_customize,
				'woomizer_product_single_tabs',
				array(
					'label'   => 'Products Tabs',
					'section' => 'woomizer_section_product_single',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'woomizer_product_single_tabs',
			array(
				'selector'        => '.woocommerce-tabs.wc-tabs-wrapper',
				'render_callback' => 'woocommerce_output_product_data_tabs',
			)
		);
	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 * @since 1.0.0
	 */
	public static function live_preview() {
		$file_name = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/live-preview.js' ) : WOOMIZER_URL . 'assets/js/live-preview.min.js';
		wp_enqueue_script(
			'woomizer-live-preview', // Give the script a unique ID.
			$file_name, // Define the path to the JS file.
			array( 'jquery', 'customize-preview' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);
	}

	/**
	 * Load custom controls.
	 *
	 * @since 1.0.0
	 */
	private function load_custom_controls() {
		foreach ( glob( WOOMIZER_PATH . 'controls/class-woomizer-control-*.php' ) as $filename ) {
			include $filename;
		}
	}
}
