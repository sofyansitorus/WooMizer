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

		if ( 0 === strpos( WOOMIZER_PREFIX, $string ) ) {
			return $string;
		}

		return WOOMIZER_PREFIX . '_' . trim( $string, '_' );
	}
}
