<?php
/**
 * Base class customizer setting fields
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.1.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
 */

/**
 * Woomizer_Setting classes
 *
 * @since      1.1.0
 * @package    Woomizer
 * @subpackage Woomizer/includes
 * @author     Sofyan Sitorus <sofyansitorus@gmail.com>
 */
class Woomizer_Setting {

	/**
	 * Humanize slug to make them readable.
	 *
	 * @since 1.1.0
	 * @param string $slug Slug string that will be humanized.
	 * @return string
	 */
	protected function humanize( $slug ) {
		$words = preg_split( '/(_|-)/', $slug );

		if ( empty( $words ) ) {
			return $slug;
		}

		$excludes = apply_filters( 'woomizer_setting_humanize', array( 'a', 'and', 'or', 'to', 'in', 'at', 'in', 'of' ) );

		foreach ( $words as $key => $word ) {
			if ( preg_match( '/^\d/', $word ) ) {
				continue;
			}
			if ( in_array( strtolower( $word ), $excludes, true ) ) {
				$words[ $key ] = strtolower( $word );
				continue;
			}
			$words[ $key ] = ucwords( strtolower( $word ) );
		}

		return implode( ' ', $words );
	}

	/**
	 * Add plugin prefix if not exist.
	 *
	 * @since 1.1.0
	 * @param string $string String that will be prefixed.
	 * @return string
	 */
	protected function autoprefix( $string ) {
		if ( is_array( $string ) ) {
			$string = implode( '_', $string );
		}

		if ( 0 === strpos( $string, WOOMIZER_PREFIX ) ) {
			return $string;
		}

		return WOOMIZER_PREFIX . '_' . trim( $string, '_' );
	}
}
