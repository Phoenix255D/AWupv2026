<?php
/**
 * Plugins List
 *
 * @package           plugins-list
 * @author            David Artiss
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:       Plugins List
 * Plugin URI:        https://wordpress.org/plugins/plugins-list/
 * Description:       Allows you to insert a list of the WordPress plugins you are using into any post/page.
 * Version:           2.7
 * Requires at least: 4.6
 * Requires PHP:      7.4
 * Author:            David Artiss
 * Author URI:        https://artiss.blog
 * Text Domain:       plugins-list
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Make sure the functions that provides us with the plugin data is available.

if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Define globals to hold the plugin base file name as well as the default output format.

if ( ! defined( 'PLUGINS_LIST_PLUGIN_BASE' ) ) {
	define( 'PLUGINS_LIST_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'DEFAULT_PLUGIN_LIST_FORMAT' ) ) {
	define( 'DEFAULT_PLUGIN_LIST_FORMAT', '<li>{{LinkedTitle}} by {{LinkedAuthor}}.</li>' );
}

// Include the shared functions.

require_once plugin_dir_path( __FILE__ ) . 'inc/shared.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/generate-plugin-data.php';
