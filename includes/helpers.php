<?php
/**
 * File for function helpers
 *
 * @link       https://github.com/sofyansitorus
 * @since      1.2.0
 *
 * @package    Woomizer
 * @subpackage Woomizer/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Humanize slug to make them readable.
 *
 * @since 1.2.0
 * @param string $slug Slug string that will be humanized.
 * @param string $prefix Prefix that will be removed.
 * @return string
 */
function woomizer_humanize( $slug, $prefix = WOOMIZER_PREFIX ) {

	// Remove autoprefixed string.
	$slug = preg_replace( '/^' . $prefix . '_/', '', $slug );

	// Split slug by dash and underscore as array.
	$words = preg_split( '/(_|-)/', $slug );

	// Check if array words is empty.
	if ( empty( $words ) ) {
		return $slug;
	}

	// Define ignored words.
	$ignores = apply_filters( 'woomizer_humanize_ignores', array( 'a', 'and', 'or', 'to', 'in', 'at', 'in', 'of' ) );
	foreach ( $words as $key => $word ) {

		// Check if the word is ignored.
		if ( in_array( strtolower( $word ), $ignores, true ) ) {
			$words[ $key ] = strtolower( $word );
			continue;
		}

		// Check if the word first character is numeric.
		if ( preg_match( '/^\d/', $word ) ) {
			$words[ $key ] = $word;
		} else {
			$words[ $key ] = ucwords( strtolower( $word ) );
		}
	}

	// Return joined words with space.
	return implode( ' ', $words );
}

/**
 * Add prefix if not exist.
 *
 * @since 1.2.0
 * @param string $string String that will be prefixed.
 * @param string $prefix Prefix that will be placed.
 * @return string
 */
function woomizer_autoprefix( $string, $prefix = WOOMIZER_PREFIX ) {
	if ( is_array( $string ) ) {
		$string = implode( '_', $string );
	}
	if ( 0 === strpos( $string, $prefix ) ) {
		return $string;
	}
	return $prefix . '_' . trim( $string, '_' );
}

/**
 * Add prefix if not exist.
 *
 * @since 1.2.0
 * @param string $string String that will converted to class case.
 * @return string
 */
function woomizer_class_case( $string ) {
	return implode( '_', array_map( 'ucwords', explode( '_', $string ) ) );
}

/**
 * Get preview URL on customizer section expanded.
 *
 * @since 1.2.0
 * @param string $section Expanded section ID.
 * @return string
 */
function woomizer_preview_url( $section ) {
	$url = '';
	switch ( $section ) {
		case 'product_loop':
			$id  = woocommerce_get_page_id( 'shop' );
			$url = ! empty( $id ) ? get_permalink( $id ) : '';
			break;
		case 'product_single':
			$ids = get_posts(
				array(
					'fields'        => 'ids',
					'no_found_rows' => true,
					'numberposts'   => 1,
					'post_type'     => 'product',
					'meta_query'    => array(
						'relation' => 'OR',
						array( // Simple products type.
							'key'     => '_sale_price',
							'value'   => 0,
							'compare' => '>',
							'type'    => 'numeric',
						),
						array( // Variable products type.
							'key'     => '_min_variation_sale_price',
							'value'   => 0,
							'compare' => '>',
							'type'    => 'numeric',
						),
					),
				)
			);
			if ( empty( $ids ) ) {
				$ids = get_posts(
					array(
						'fields'        => 'ids',
						'no_found_rows' => true,
						'numberposts'   => 1,
						'post_type'     => 'product',
					)
				);
			}
			$url = ! empty( $ids ) && ! is_singular( 'product' ) ? get_permalink( $ids[0] ) : '';
			break;
		case 'cart':
			$id  = woocommerce_get_page_id( 'cart' );
			$url = ! empty( $id ) ? get_permalink( $id ) : '';
			break;
		case 'checkout':
			$id  = woocommerce_get_page_id( 'checkout' );
			$url = ! empty( $id ) ? get_permalink( $id ) : '';
			break;
	}
	return apply_filters( 'woomizer_preview_url', $url, $section );
}
