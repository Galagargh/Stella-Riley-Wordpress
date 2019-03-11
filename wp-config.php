<?php

define('WP_ALLOW_REPAIR', true);
define('FS_METHOD', 'direct');
define('DISALLOW_FILE_EDIT', true);
define('WP_HOME', 'http://stellariley.apps-1and1.net/');
define('WP_SITEURL', 'http://stellariley.apps-1and1.net/');











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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db725989960' );

/** MySQL database username */
define( 'DB_USER', 'dbo725989960' );

/** MySQL database password */
define( 'DB_PASSWORD', 'mAUsYUO4ysU0cil' );

/** MySQL hostname */
define( 'DB_HOST', 'db725989960.db.1and1.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*GF~.-DXqi1!|iK:>YPm8 fL.1&+FR](z|]-k^g*nHc3l<LTzt@V|^nJh*`Z.F(=');
define('SECURE_AUTH_KEY',  'G%qCRz$?YJd7@j#>oQH&7j<Ev!8|Yuc.:9l2dF1Oq6,)Lee)!j?9a,>6(+y?7/-Q');
define('LOGGED_IN_KEY',    'b>*O-Xdf>^|(WG~Hl6^rY4OT%0_Pw[|)qhKjh92<7{#)fi4dS-ohudhC:w9<Lhpz');
define('NONCE_KEY',        'UXt~s5<mfK-vi6dQ*WcE@*~Pnh@Ygtg@+&yw=}Rw>:R5 Pz$ESG:0di)7 /4PT,)');
define('AUTH_SALT',        'wZe,L%1s%JXe>Twn) ~r! wMo/+Cj6b/}EXNOT $&C]_A5cr|l_@r9pa=Yk *0+$');
define('SECURE_AUTH_SALT', 'a K7ceIA$ZXBDtI&ZG%z YU5fZ0MD;|0a|F;N-M81A&cD=HojXi-uv~>)njdx+#7');
define('LOGGED_IN_SALT',   '~fNLEM-+^dX:U0GMSQ+whATs- :O=S{v)1W1N_g/iyv sZXtuMALeS .#_ISMR8r');
define('NONCE_SALT',       ']u oU2B+o4a0*=%>DcQhyKW5t&bx^n3[3YHmOlN<on*he-!9 Z+d-:[r)OC fOHw');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ricuqpw4rn';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
