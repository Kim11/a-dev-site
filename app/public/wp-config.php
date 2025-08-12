<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '``r%a[@eb[hR]#>.y;GS6:Pn<!ZY1d~2=x6p&],<JUI{>V+hH,1lB1!1w-bun8c1' );
define( 'SECURE_AUTH_KEY',   'z#h^D.A@1lhuopxja5Sv:hmfE!8:j/awxY`l+m^4=C>8p%N]>==dYIl_@-MqghEV' );
define( 'LOGGED_IN_KEY',     'P4,iuX_&hB&0`HZP(id_Q*lXx@+hesI6so77E2r:g|#xcsU Vl>ny*s6ANVfsN/i' );
define( 'NONCE_KEY',         'k+pOX9irZ~8D%FZoh/KGWm)[/[zNHglyr+G, B.}/{HYJMb$s?<39SZn<Va}m@t-' );
define( 'AUTH_SALT',         'X,1CYlIcxqUXa3jgR{KCrWYI;BACNC0o4vR/x;O7;,/kJpd.JNv*j++>4mM`z=,f' );
define( 'SECURE_AUTH_SALT',  'Z4j$Nu))73}d.yula,UZ(}<oy}B1/&-|bL@m^2l95w?q0UzpO`GZp}R%sAJmlA)u' );
define( 'LOGGED_IN_SALT',    'jz0#w3&1+J|y1l0/Aq$>F/Y!l|mp(bu4-fQrWPVFCz-: rd`eP#CU{P_byv(ixe3' );
define( 'NONCE_SALT',        'Sw4Uf[.qB`Y=]3=a{7J82!LLUK9~N,[s,>]2V*mERnKZ?dT1KewyY:?3&k-;LBwC' );
define( 'WP_CACHE_KEY_SALT', 'x>9qkGMBhm}#5MG4VgXm$7oNG@:qnp)tFGf>;#B!:+&/Q[{$BXF%0d;eVLhqoec}' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
