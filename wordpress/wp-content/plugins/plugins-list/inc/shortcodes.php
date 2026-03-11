<?php
/**
 * Shared Functions
 *
 * A group of functions shared across my plugins, for consistency.
 *
 * @package plugin-slug
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Shortcode Function
 *
 * Extract shortcode parameters and call main function to output results
 *
 * @uses     pll_get_plugins_list    Get the list of plugins
 *
 * @param    string $paras           Shortcode parameters.
 * @return   string                  Output.
 */
function pll_add_shortcode( $paras ) {

	$atts = shortcode_atts(
		array(
			'format'        => '',
			'show_inactive' => '',
			'show_active'   => '',
			'show_recent'   => '',
			'cache'         => '',
			'nofollow'      => '',
			'target'        => '',
			'by_author'     => '',
			'chars'         => '',
			'words'         => '',
			'emoji'         => '',
			'end'           => '',
		),
		$paras
	);

	// Pass the shortcode parameters onto a function to generate the plugins list.

	$output = pll_get_plugins_list( $atts['format'], $atts['show_inactive'], $atts['show_active'], $atts['show_recent'], $atts['cache'], $atts['nofollow'], $atts['target'], $atts['by_author'], $atts['chars'], $atts['words'], $atts['emoji'], $atts['end'] );

	return $output;
}

add_shortcode( 'plugins_list', 'pll_add_shortcode' );

/**
 * Number of plugins shortcode
 *
 * Shortcode to return number of plugins
 *
 * @uses     pll_get_plugin_number   Get the number of plugins
 *
 * @param    string $paras           Shortcode parameters.
 * @return   string                  Output.
 */
function pll_add_number_shortcode( $paras ) {

	$atts = shortcode_atts(
		array(
			'active'   => 'true',
			'inactive' => 'false',
			'cache'    => 5,
		),
		$paras
	);

	$output = pll_get_plugin_number( $atts['active'], $atts['inactive'], $atts['cache'] );

	return $output;
}

add_shortcode( 'plugins_number', 'add_add_number_shortcode' );
