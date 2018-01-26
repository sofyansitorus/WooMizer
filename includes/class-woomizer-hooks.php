<?php
/**
 * Class that handle hooks to modify the the woocommerce.
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.2.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
 */

/**
 * Woomizer_Hooks class.
 *
 * @since      1.2.0
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
final class Woomizer_Hooks {

	/**
	 * Hold an instance of the class
	 *
	 * @since    1.2.0
	 * @var \Woomizer
	 */
	private static $_instance = null;

	/**
	 * Prefix for setting.
	 *
	 * @since 3.4.0
	 * @var tring
	 */
	private $setting_prefix = 'woomizer_setting';

	/**
	 * Call this method to get singleton
	 *
	 * @since    1.2.0
	 * @return Woomizer
	 */
	public static function init() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor for your shipping class
	 *
	 * @since    1.2.0
	 */
	private function __construct() {

		// Filter settings arguments.
		add_filter( 'customize_dynamic_setting_args', array( $this, 'dynamic_setting_args' ), 99, 2 );

		/**
		 * Hook to filter woocommerce_sale_flash.
		 *
		 * @setting flash_sale_loop
		 * @setting flash_sale_single
		 */
		add_filter( 'woocommerce_sale_flash', array( $this, 'sale_flash' ), 99 );

		/**
		 * Hook to filter woocommerce_product_add_to_cart_text.
		 *
		 * @setting add_to_cart_button_simple
		 * @setting add_to_cart_button_variable
		 * @setting add_to_cart_button_grouped
		 */
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'product_add_to_cart_text' ), 99, 2 );

		/**
		 * Hook to filter loop_shop_columns.
		 *
		 * @setting product_grid
		 */
		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ), 99 );

		/**
		 * Hook to filter loop_shop_per_page.
		 *
		 * @setting product_grid
		 */
		add_filter( 'loop_shop_per_page', array( $this, 'loop_shop_per_page' ), 99 );

		/**
		 * Hook to filter woocommerce_product_single_add_to_cart_text.
		 *
		 * @setting add_to_cart_button
		 */
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'product_single_add_to_cart_text' ), 99, 2 );

		/**
		 * Hook to filter woocommerce_product_tabs.
		 *
		 * @setting product_tabs
		 */
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ), 99 );

		/**
		 * Hook to action woocommerce_cart_collaterals.
		 *
		 * @setting display_cross_sells
		 */
		add_action( 'woocommerce_cart_collaterals', array( $this, 'cart_collaterals' ), 0 );

		// Filter submit order button text.
		/**
		 * Hook to filter woocommerce_order_button_text.
		 *
		 * @setting submit_order_button
		 */
		add_filter( 'woocommerce_order_button_text', array( $this, 'order_button_text' ), 99 );

	}

	/**
	 * Filter the customizer dynamic settings.
	 *
	 * @since 1.2.0
	 * @param array  $args Customiser setting arguments.
	 * @param string $id Customiser setting ID.
	 * @return array
	 */
	public function dynamic_setting_args( $args, $id ) {
		if ( 0 === strpos( $id, $this->setting_prefix ) && isset( $args['default'] ) && is_array( $args['default'] ) ) {
			$args['default'] = wp_json_encode( $args['default'] );
		}
		return $args;
	}

	/**
	 * Filter flash sale text for all products.
	 *
	 * @since 1.2.0
	 * @param string $text Current flash sale text.
	 * @return string
	 */
	public function sale_flash( $text ) {

		static $is_single_defined = false;

		switch ( true ) {
			case ! $is_single_defined && is_product() && is_main_query():
				$is_single_defined = true;
				$custom_text       = get_theme_mod( $this->setting_prefix( 'flash_sale_single' ) );
				break;

			default:
				$custom_text = get_theme_mod( $this->setting_prefix( 'flash_sale_loop' ) );
				break;
		}

		if ( empty( $custom_text ) ) {
			return $text;
		}
		return '<span class="onsale">' . esc_html( $custom_text ) . '</span>';
	}

	/**
	 * Filter add to cart button text for product loop.
	 *
	 * @since 1.2.0
	 * @param string      $text Current button text.
	 * @param \WC_Product $product Current product object.
	 * @return string
	 */
	public function product_add_to_cart_text( $text, $product ) {

		$custom_text = get_theme_mod( $this->setting_prefix( 'add_to_cart_button_' ) . $product->get_type() );

		if ( empty( $custom_text ) ) {
			return $text;
		}

		switch ( $product->get_type() ) {
			case 'simple':
				if ( $product->is_purchasable() && $product->is_in_stock() ) {
					$text = $custom_text;
				}
				break;
			case 'variable':
				if ( $product->is_purchasable() ) {
					$text = $custom_text;
				}
				break;
			case 'grouped':
				$text = $custom_text;
				break;
		}
		return $text;
	}

	/**
	 * Set number of products per row.
	 *
	 * @since 1.1.1
	 * @param int $col Current number of products per row.
	 * @return int
	 */
	public function loop_columns( $col ) {
		$grids = explode( 'x', get_theme_mod( $this->setting_prefix( 'product_grid' ) ) );
		if ( count( $grids ) === 2 && absint( $grids[0] ) ) {
			return $grids[0];
		}
		return $col;
	}

	/**
	 * Set number of products per page.
	 *
	 * @since 1.1.1
	 * @param int $per_page Current number of products per page.
	 * @return int
	 */
	public function loop_shop_per_page( $per_page ) {
		$grids = explode( 'x', get_theme_mod( $this->setting_prefix( 'product_grid' ) ) );
		if ( count( $grids ) === 2 && absint( $grids[0] ) && absint( $grids[0] ) ) {
			return $grids[0] * $grids[1];
		}
		return $per_page;
	}

	/**
	 * Filter add to cart button text for product single.
	 *
	 * @since 1.2.0
	 * @param string      $text Current button text.
	 * @param \WC_Product $product Current product object.
	 * @return string
	 */
	public function product_single_add_to_cart_text( $text, $product ) {
		$custom_text = get_theme_mod( $this->setting_prefix( 'add_to_cart_button' ) );
		if ( empty( $custom_text ) ) {
			return $text;
		}
		return $custom_text;
	}

	/**
	 * Filter the default product tabs.
	 *
	 * @since 1.2.0
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

		$options = get_theme_mod( $this->setting_prefix( 'product_tabs' ), array() );

		if ( $options && ! is_array( $options ) ) {
			$options = json_decode( $options, true );
			if ( json_last_error() !== JSON_ERROR_NONE ) {
				return $tabs;
			}
		}

		if ( ! $options || ! is_array( $options ) ) {
			return $tabs;
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
	 * Cart collaterals action hooks.
	 *
	 * @since 1.2.0
	 */
	public function cart_collaterals() {
		// Toggle cross sells.
		$display_cross_sells = get_theme_mod( $this->setting_prefix( 'display_cross_sells' ) );
		if ( ! is_customize_preview() && 'none' === $display_cross_sells ) {
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		}
	}

	/**
	 * Filter submit order button text.
	 *
	 * @since 1.2.0
	 * @param string $text Current order button text.
	 * @return string
	 */
	public function order_button_text( $text ) {
		$custom_text = get_theme_mod( $this->setting_prefix( 'submit_order_button' ) );
		if ( empty( $custom_text ) ) {
			return $text;
		}
		return $custom_text;
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

}
