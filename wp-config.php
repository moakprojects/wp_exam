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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_exam');

/** MySQL database username */
define('DB_USER', 'testUser');

/** MySQL database password */
define('DB_PASSWORD', 'Ny0mika!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Y@QSUSIrtuc=r$K#*(gckftM)f((<u-Kx;E{}Eb/Rr?Nqb+ub]cFt[cWVM=QXvB6');
define('SECURE_AUTH_KEY',  '4>G q2le;/%q(LWq_.4e/^;YZtEA _VW/cpWMgKK6xG/QGeRE]A)b42PRT&S#]yI');
define('LOGGED_IN_KEY',    '1:{;f!Nr7%-DJLl4EtHt2W+>6hyGtZKzj/KIo!Q,Le/7k,e7c<e8/5|R`NxO*zlb');
define('NONCE_KEY',        'J]:Z1H7vaH}G6|=-Zq1BZ!eod4V&]cD0tQZ$9pW}[cQ>g8_tsN%i(e2S}P2>[p*Q');
define('AUTH_SALT',        '_e(l9F~.6n[0H9&fkx (-C4 rHuEh#NhVc^H&iyh>][4Hoo`vxQ+Y2srWz1(nxF6');
define('SECURE_AUTH_SALT', 'Zuy&x1C)tLnTL=k7QZ$lMD=KfVM[v|JR:H+*~W(Z*dvZ,{:ZS$vPq_extg>SqI8?');
define('LOGGED_IN_SALT',   'U+O+~X*!`% GO{ VTRgvl$~+s((M.^z4A< `<y:8f/5(T`KP`{DBubt/mq,7Ev=X');
define('NONCE_SALT',       'l{5`S?Ipuk;[ZVvY]aC}o!ZW0 3Ng5Mm*Vxy>v-^A1`DFOm.$@8yi-jSUX)_-J{:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
