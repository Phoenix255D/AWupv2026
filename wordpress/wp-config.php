<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpressdb' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'aa23bc747d86edd99fe548a6d5644d785178a80b981ece64' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('FS_METHOD', 'direct');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '95ww[IX*B,^y7%D2/|vn N!^PFu+FM.lzy:a}Lb9c`q#63A3I&soX/hQEdy4r1|a' );
define( 'SECURE_AUTH_KEY',  'x*S5w4e@Uvi:aYSpm4@Go:2M-zP{0*I^XqKLxBZxz7/cG(#s+t/,}w0@ @;I4q8*' );
define( 'LOGGED_IN_KEY',    'HF>AQNl=|GlL8e4nN7c(x%R?0! ZZgpQt>t(H7JX~O3Gy^>h5%Geh(V1&02)lglj' );
define( 'NONCE_KEY',        ';A3^RI_u!9QQT44en`~o,,L.p[s#}^`MRY8$|^Uxui0tKVwxGwlE<H~j#4gq4$<q' );
define( 'AUTH_SALT',        'M!JMFXxJ|%atOowEd#l?SOPHZ{H?&navkQ=P^/_hicLC?A&sb?6UzsEq>-t%6)7/' );
define( 'SECURE_AUTH_SALT', 'fUDL0,{L?*?8+,{hwS:^EYAtoTnNq@_l@uY-&{t7(H>w!)J}#%|5QKHlAn0}.g0A' );
define( 'LOGGED_IN_SALT',   '@ah@z]~T)~yM(3Z496o2BHq246nDq}2[=7]Ry`c@kT&~i30J{%`2MU>T!9J0aPf*' );
define( 'NONCE_SALT',       'S.Ryw-b*Q.P_/uZZ*v* X>%1L!9v3rv&dr<Vdule6/<7lK/g#e%zOs/O@4CiP/F&' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
