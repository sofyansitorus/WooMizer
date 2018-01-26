<?php
/**
 * Extend WordPress Customize Control classes
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.2.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
 */

/**
 * Woomizer_Customize_Control classes
 *
 * @since      1.2.0
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Customize_Control extends WP_Customize_Control {

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 *
	 * @since 3.4.0
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'customize-control customize-control-' . $this->type . 'woomizer-control woomizer-control-' . $this->type;

		printf( '<li id="%s" class="%s">', esc_attr( $id ), esc_attr( $class ) );
		$this->render_content();
		echo '</li>';
	}

}
