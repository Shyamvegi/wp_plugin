<?php
/*
* Plugin Name: Custom Table Plugin
* Plugin URI: https://customtableplugin.com
* Description: A plugin to demonstrate custom table operations in WordPress.
* Version: 1.0
* Requires at least: 5.0
* Requires PHP: 7.0
* Author: Shyam V
* Author URI: https://Shyam.com
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: custom-table
* Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CUSTOM_TABLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once CUSTOM_TABLE_PLUGIN_DIR . 'includes/custom-table-functions.php';
require_once CUSTOM_TABLE_PLUGIN_DIR . 'includes/custom-table-shortcodes.php';
require_once CUSTOM_TABLE_PLUGIN_DIR . 'includes/class-custom-table-api.php';

register_activation_hook( __FILE__, 'custom_table_create_table' );

add_action( 'init', 'custom_table_init' );
add_action( 'rest_api_init', [ 'Custom_Table_API', 'register_routes' ] );

/**
 * Initialize the plugin by adding shortcodes.
 */
function custom_table_init() {
    add_shortcode( 'custom_table_form', 'custom_table_shortcode_form' );
    add_shortcode( 'custom_table_list', 'custom_table_shortcode_list' );
}
