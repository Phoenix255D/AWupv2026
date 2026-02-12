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
define( 'DB_NAME', 'woodmartdb' );

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
define( 'AUTH_KEY',         'd_/kM|8=D@$*&#N2)F!9IeEiZC4P7E!p=qfO7fgIl4`=1wCe]H|H>4d26fzOtJj+' );
define( 'SECURE_AUTH_KEY',  '%&/:T>[8t_X.udw%.~G7D%$2(fs^k~(54M[uPM <J.DS9 97~tSTQaJG]_v:hp33' );
define( 'LOGGED_IN_KEY',    'A*Co6]d|H{!rOb@uMR1WdB)Zh4s~1ta/Z9h8/6[8I}1d1Y4k+=[/6gB~s0ov;`m8' );
define( 'NONCE_KEY',        'kD(V$+B|F3d?;}H#;*unZ*vsIOW#C0cAN;Xd%,h`YpCk:++9LGJF?BPv]#Cb{&KJ' );
define( 'AUTH_SALT',        'pl]5IHEw4cw]%mns0k%e3OYY|=Jc_Z!%cO=gkY]!3TPk4Ixk*:]lMgx24oQeLqt|' );
define( 'SECURE_AUTH_SALT', 'k,-<%pbkC*@3G+ox<Cr`*RT%^tG0f&s`sU}q )_i8mS]zV{&i_J=^,;@;YU2?Tn~' );
define( 'LOGGED_IN_SALT',   'OXK}K))-eJU57OgLti}1?$JL`}J`+T+}((mjL?qWeKL9-=F:b.w!$8S3(T+/^}z[' );
define( 'NONCE_SALT',       '^BiI-l4<H4P3Fh@xV?epk]az/1llh B!ubGZCL*F!=fmiuw,0sE.W7qT_(@at`9h' );

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

