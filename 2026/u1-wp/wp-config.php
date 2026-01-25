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
define( 'DB_USER', 'word' );

/** Database password */
define( 'DB_PASSWORD', 'word' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '>k-ANSPm!|?Z[Dx!tK|hm>aX^wt00~(reQpddp5(2)/zK5 +Ik>Z@HvZ>VLW{[GO' );
define( 'SECURE_AUTH_KEY',  'xmhDHe?gmO77%UE+YLUYm4VIuUr$Y1l=5)T,!iH,(ZG=4~0b/c<4]}z5|9OCiBPl' );
define( 'LOGGED_IN_KEY',    'Tg[^ggCUk`P-J0^*uTu[<d&>aEZ#Jg<-9JU?0fTKZ!6g$!lK{U_q0e:ZjYUBWHuX' );
define( 'NONCE_KEY',        ')G}52djwB5By|`bJMtb|D5ElMQ$VfL%#1gn;I^-Ws}k.yuA^94?e7VKLqh(vvLmx' );
define( 'AUTH_SALT',        '^OoOcxa%Jj-q$v{u4X?mJNa|sk~QY?DnX)[C>+`q;>6aQ%fW^a^fm`u^H_]ZPNug' );
define( 'SECURE_AUTH_SALT', '}/4y}.6h4wY^x(y*|/=diMcBiVQIl4d=Rur`g+;|jh!dSUCy Jlq1dxB#!Vghx)Y' );
define( 'LOGGED_IN_SALT',   'RNV(`zQN6&gZB_[CY</z},U=P]3K{pEA-F(SV2KHM;SXRRyt}/Ewp$<?z!Y%VeM@' );
define( 'NONCE_SALT',       'KK<edLjhE:dp=8--jtD{xj1-#IIFxzOo!/(_-24x[`s!ZwWy/TjX(|Cx6ki0>{Jd' );

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

