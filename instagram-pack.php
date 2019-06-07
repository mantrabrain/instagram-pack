<?php
/**
 * Plugin Name:       Instagram Pack
 * Plugin URI:        https://wordpress.org/plugins/instagram-pack/
 * Description:       Display your instagram feed using this [instagram_pack_feed] shortcode.
 * Version:           1.0.0
 * Author:            Mantrabrain
 * Author URI:        https://mantrabrain.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       instagram-pack
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define INSTAGRAM_PACK_PLUGIN_FILE.
if (!defined('INSTAGRAM_PACK_FILE')) {
    define('INSTAGRAM_PACK_FILE', __FILE__);
}

// Define INSTAGRAM_PACK_VERSION.
if (!defined('INSTAGRAM_PACK_VERSION')) {
    define('INSTAGRAM_PACK_VERSION', '1.0.0');
}

// Define INSTAGRAM_PACK_PLUGIN_URI.
if (!defined('INSTAGRAM_PACK_PLUGIN_URI')) {
    define('INSTAGRAM_PACK_PLUGIN_URI', plugins_url('', INSTAGRAM_PACK_FILE));
}

// Define INSTAGRAM_PACK_PLUGIN_DIR.
if (!defined('INSTAGRAM_PACK_PLUGIN_DIR')) {
    define('INSTAGRAM_PACK_PLUGIN_DIR', plugin_dir_path(INSTAGRAM_PACK_FILE));
}


// Include the main Instagram_Pack class.
if (!class_exists('Instagram_Pack')) {
    include_once dirname(__FILE__) . '/includes/class-instagram-pack.php';
}


/**
 * Main instance of Instagram_Pack.
 *
 * Returns the main instance of Instagram_Pack to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Instagram_Pack
 */
function instagram_pack_instance()
{
    return Instagram_Pack::instance();
}

// Global for backwards compatibility.
$GLOBALS['instagram-pack-instance'] = instagram_pack_instance();
