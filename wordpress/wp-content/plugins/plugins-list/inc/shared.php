<?php
/**
 * Shared Functions
 *
 * A group of functions shared across my plugins, for consistency.
 *
 * @package plugins-list
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 *
 * @version  1.1
 * @param    string $links  Current links.
 * @param    string $file   File in use.
 * @return   string         Links, now with settings added.
 */
function pll_plugin_meta( $links, $file ) {

	if ( false !== strpos( $file, 'plugins-list.php' ) ) {

		$links = array_merge(
			$links,
			array( '<a href="https://github.com/dartiss/plugins-list">' . __( 'Github', 'plugins-list' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/plugins-list">' . __( 'Support', 'plugins-list' ) . '</a>' ),
			array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'plugins-list' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/plugins-list/reviews/?filter=5" title="' . __( 'Rate the plugin on WordPress.org', 'plugins-list' ) . '" style="color: #ffb900">' . str_repeat( '<span class="dashicons dashicons-star-filled" style="font-size: 16px; width:16px; height: 16px"></span>', 5 ) . '</a>' ),
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'pll_plugin_meta', 10, 2 );

/**
 * WordPress Fork Check
 *
 * Deactivate the plugin if an unsupported fork of WordPress is detected.
 *
 * @version 1.0
 */
function pll_fork_check() {

	// Check for a fork.

	if ( function_exists( 'calmpress_version' ) || function_exists( 'classicpress_version' ) ) {

		// Grab the plugin details.

		$plugins = get_plugins();
		$name    = $plugins[ PLUGINS_LIST_PLUGIN_BASE ]['Name'];

		// Deactivate this plugin.

		deactivate_plugins( PLUGINS_LIST_PLUGIN_BASE );

		// Set up a message and output it via wp_die.

		/* translators: 1: The plugin name. */
		$message = '<p><b>' . sprintf( __( '%1$s has been deactivated', 'plugins-list' ), $name ) . '</b></p><p>' . __( 'Reason:', 'plugins-list' ) . '</p>';
		/* translators: 1: The plugin name. */
		$message .= '<ul><li>' . __( 'A fork of WordPress was detected.', 'plugins-list' ) . '</li></ul><p>' . sprintf( __( 'The author of %1$s will not provide any support until the above are resolved.', 'plugins-list' ), $name ) . '</p>';

		$allowed = array(
			'p'  => array(),
			'b'  => array(),
			'ul' => array(),
			'li' => array(),
		);

		wp_die( wp_kses( $message, $allowed ), '', array( 'back_link' => true ) );
	}
}

add_action( 'admin_init', 'pll_fork_check' );
