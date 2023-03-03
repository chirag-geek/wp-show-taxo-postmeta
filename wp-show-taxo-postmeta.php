<?php
/**
 * Plugin Name: Show Default Postmeta
 * Description: Select a post categories and tags and based on that perform a certain operation after the elementor pro form widget is submitted.
 * Plugin URI:  https://geekcodelab.com/
 * Version:     1.0.0
 * Author:      Geek Code Lab
 * Author URI:  https://geekcodelab.com/
 * Text Domain: wp-taxo-show-default-postmeta
 */

defined( 'ABSPATH' ) || exit;

if (!defined("WPSDP_PLUGIN_DIR_PATH"))

	define("WPSDP_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("WPSDP_PLUGIN_URL"))
    
    define("WPSDP_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));
    
define("WPSDP_BUILD", '1.0.0');

// register or enqueue a scripts at admin settings page
add_action('admin_enqueue_scripts','wpsdp_plugin_admin_scripts');
function wpsdp_plugin_admin_scripts(){
    wp_enqueue_style('wpsdp-jq-admin-style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), WPSDP_BUILD);
    wp_enqueue_script('wpsdp-jq-admin-script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array(), WPSDP_BUILD);
    
    wp_enqueue_style('wpsdp-admin-style', plugins_url() . '/' . basename(dirname(__FILE__)) . '/assets/css/wpsdp-admin-style.css', array(), WPSDP_BUILD);
    wp_enqueue_script('wpsdp-admin-script', plugins_url() . '/' . basename(dirname(__FILE__)) . '/assets/js/wpsdp-admin-script.js', array( 'jquery' ), WPSDP_BUILD);
}

require_once( plugin_dir_path( __FILE__ ) . '/includes/wpsdp-postmeta.php' );
$data = new WPDSP_Postmeta();