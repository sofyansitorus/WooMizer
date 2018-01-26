<?php
/**
 * Custom WordPress Customize Control classes for product grid
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.1.1
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes/controls
 */

/**
 * Woomizer_Control_Product_Grid classes
 *
 * @since      1.1.1
 * @package    Woomizer
 * @subpackage Woomizer/includes/controls
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Control_Product_Grid extends Woomizer_Customize_Control {
	/**
	 * Control's Type.
	 *
	 * @since 1.1.1
	 * @var string
	 */
	public $type = 'product_grid';

	/**
	 * Render content.
	 *
	 * @since      1.1.1
	 */
	public function render_content() {
		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>
		<div class="woomizer-product-grid-wrap">
			<input
			id="<?php echo esc_attr( $this->id ); ?>"
			type="text"
			class="woomizer-product-grid-input"
			value="<?php echo esc_attr( $this->value() ); ?>"
			<?php $this->link(); ?>
			readonly/>
		</div>
		<?php
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 1.1.1
	 */
	public function enqueue() {
		$css_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/css/control-product-grid.css' ) : WOOMIZER_URL . 'assets/css/control-product-grid.min.css';
		wp_enqueue_style(
			'woomizer-control-product-grid', // Give the script a unique ID.
			$css_file, // Define the path to the JS file.
			array(), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			false // Specify whether to put in footer (leave this false).
		);

		$js_file = ( defined( 'WOOMIZER_DEV' ) && WOOMIZER_DEV ) ? add_query_arg( array( 't' => time() ), WOOMIZER_URL . 'assets/js/control-product-grid.js' ) : WOOMIZER_URL . 'assets/js/control-product-grid.min.js';
		wp_enqueue_script(
			'woomizer-control-product-grid', // Give the script a unique ID.
			$js_file, // Define the path to the JS file.
			array( 'jquery' ), // Define dependencies.
			WOOMIZER_VERSION, // Define a version (optional).
			true // Specify whether to put in footer (leave this true).
		);
	}
}
