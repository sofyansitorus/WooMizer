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

		$excludes = array( 'and', 'or', 'to', 'in', 'at', 'in' );

		foreach ( $words as $key => $word ) {
			$word = strtolower( $word );
			if ( strlen( $word ) === 1 || preg_match( '/^\d/', $word ) || in_array( $word, $excludes, true ) ) {
				continue;
			}
			$words[ $key ] = ucwords( $word );
		}

		return implode( ' ', $words );
	}
}
