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
class Woomizer_Hooks {
	/**
	 * Filter the default product tabs.
	 *
	 * @since 1.1.0
	 * @param array $tabs Current product tabs.
	 * @return array
	 */
	public function product_tabs( $tabs ) {
		global $product, $post;

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
	 * @since 1.1.0
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
	 * @since 1.1.0
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
				$custom_text = get_theme_mod( 'woomizer_product_loop_add_to_cart_btn_text_simple' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() && $product->is_in_stock() ) {
					return $custom_text;
				}
				break;
			case 'variable':
				$custom_text = get_theme_mod( 'woomizer_product_loop_add_to_cart_btn_text_variable' );
				if ( ! empty( $custom_text ) && $product->is_purchasable() ) {
					return $custom_text;
				}
				break;
			case 'grouped':
				$custom_text = get_theme_mod( 'woomizer_product_loop_add_to_cart_btn_text_grouped' );
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
	 * @since 1.1.0
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
}
