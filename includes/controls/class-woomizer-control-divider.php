<?php
/**
 * Custom WordPress Customize Control classes for divider
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.0.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes/controls
 */

/**
 * Woomizer_Control_Divider classes
 *
 * @since      1.0.0
 * @package    Woomizer
 * @subpackage Woomizer/includes/controls
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Control_Divider extends Woomizer_Customize_Control {
	/**
	 * Control's Type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $type = 'divider';

	/**
	 * Render content.
	 *
	 * @since      1.0.0
	 */
	public function render_content() {
		$input_id         = '_customize-input-' . $this->id;
		$description_id   = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
		?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo wp_kses( $this->description ); ?></span>
			<?php endif; ?>
			<hr>
		<?php
	}
}
