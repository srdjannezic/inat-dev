<?php
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
define( 'DB_NAME', 'inat' );

/** MySQL database username */
define( 'DB_USER', 'wollsontester' );

/** MySQL database password */
define( 'DB_PASSWORD', 'bfdt_L43' );

/** MySQL hostname */
define( 'DB_HOST', '188.93.127.85' );

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
define( 'AUTH_KEY',         'R3u  ]Cv-806}&9&AI$e;x2@A4,Tp1z($k zy+)Sk1G!6EWONeZLVvks-W!Nol>.' );
define( 'SECURE_AUTH_KEY',  'ew- _abX+/^Ee!}bmHKWc&:#.N?6Uu4:c^t;(*/Ri(V&$mrwfvA^!%Li0%NDuqoy' );
define( 'LOGGED_IN_KEY',    'zE1|S4GVyN=,#9OiCLLj-GS=f?NO9^x0^^%m]MM$(v=b8u`h_oE@1$I8ptE-nZl4' );
define( 'NONCE_KEY',        'D2+[?)GK |JIIq ggs9kDPA;c1AME`2x;5~v}w=>;%(!?~<-~FVlF7y&ABjieJDZ' );
define( 'AUTH_SALT',        '),~gT.@!D=+NgAW4kk+:~C!j(by4y)*AcWxcEw02.@PC*sG?YqD/{Jk=y<J3&>EO' );
define( 'SECURE_AUTH_SALT', 'XSP%6~xvq!Xo+IWv%KD?]}jrRhM-7,{U@-?e+Jk31>P:Yd;<9K^{D)bc@>,)f+<v' );
define( 'LOGGED_IN_SALT',   'k;0rbk(Vvk %5w^ 4DCl}?[si7AfJNk?4+ls-cNH/@L&Q~U/CW=f,O$@$`~=Vf5!' );
define( 'NONCE_SALT',       '*6AtAM&SwU%r3]Tc=xIeDFnj.w.&8VDJ(6n+:]sg!02*5+k`I8V$+EN7x159<U=2' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpxf_';

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
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
