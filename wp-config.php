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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'nisar' );

define('FS_METHOD','direct');

/** Database username */
define( 'DB_USER', 'kasal' );

/** Database password */
define( 'DB_PASSWORD', 'Shahid*110' );

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
define( 'AUTH_KEY',         '%A(hEK%=:xyj>rHM>]hZ8zL5]U6@bXG7^%f)s.w0r0U}*v=xgMl1|W(|&.* a+93' );
define( 'SECURE_AUTH_KEY',  'E%= 8WEHK7Hu#<+s;R7@qvVaX{pP}B92HTM;hM2^71n|0vdWXti0*6[r#Tb0~!YD' );
define( 'LOGGED_IN_KEY',    'v9{rFzeXpx6wM<o]TwCGqYT-K)Gm8vV&5g:]ScH;jmn-D;pspP<&_fZj~5v9/e#h' );
define( 'NONCE_KEY',        '$!1OngtaB*k8h+D? ,wuDBoJgf 5vuvd_2KC0CJqzt/5C{$Dh{9N|!+XUM!b%lbi' );
define( 'AUTH_SALT',        '$L?27Sd[fl&W]FOfd6ZGO%lJbAaVH&m~yn06GmqXlh!::bWU=7CHlDriM7Xz;6>0' );
define( 'SECURE_AUTH_SALT', 'fqJ?_3s1pS=s&FK&u6((52]iN$&+/C]{?,)qo.6J>]=o#{bX8XM,GIgik_u]iB<V' );
define( 'LOGGED_IN_SALT',   '@`FO7v~@VFu.kQo^03I{p|7<@b?dr~ro,L5J5s<y3DA0;WFu6)lBGyQoUw!-Pqei' );
define( 'NONCE_SALT',       '%?K) 3LAMUH54c:uP%{]RSGvkGp`iw>Tz}o+/a0Q0Cc,_{.e J6BYLGP1.Dom1<8' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';
die('akhterc');

