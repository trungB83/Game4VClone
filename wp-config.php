<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_myblog' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '3F-GEGGI@m=q H i{wOhJBy*54f`IVX7*ZaQ2pRQpCg`3/V$SI4H$~0?*)y;,$H,' );
define( 'SECURE_AUTH_KEY',  '~ |q/`]>zaG ;Gi|z(I|c.ciKQ$Nr_B-0L[hP2_kWKi)f@$VbB*>D @cW_Y3{N:B' );
define( 'LOGGED_IN_KEY',    '#-*yfY*TBzV5gt~v*;INs6_}6&}/%ADO+g~>BaFAK|:#w$e1d69TP(F3F8Mz[o5t' );
define( 'NONCE_KEY',        'zD(ZI@{&(3gO+Seh`~Tm{Rrgfq[<4Nblcn6.bxq3+~.P^::@#7%A[$zU4qJe$[Bf' );
define( 'AUTH_SALT',        'S}RmLIf0%[:*q/p-Z~H$$]95cFj}3n3l5p@g?|P)AnGTnAX~xg<9FG=v#D#bymuR' );
define( 'SECURE_AUTH_SALT', 'OT}JrZ[eaMM(`A/Jk&EB_S:bH<K=:oi#{ZUMJ!kTtrZiv2y7Di `>%X/PBa}@,sC' );
define( 'LOGGED_IN_SALT',   '{x7MDl)s?h?7@$6Yn5|)//x{;2}iWRrR:lYuX`l7=e0SR87C+zhaBYjWQG&#bF*}' );
define( 'NONCE_SALT',       'r@<*lvQPL%_>%)*o~yl#(iI:fH*UiJW$<&YS} 0SQ?<*y*q9&U]g78b-{+)xhME|' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
