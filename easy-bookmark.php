<?php
/**
 * Plugin Name: Easy Bookmark
 * Description: Let visitors bookmark posts and pages and revisit them anytime from a dedicated page or widget.
 * Plugin URI:  https://themewant.com/
 * Author:      Themewant
 * Author URI:  https://themewant.com/
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Version:     1.0.3
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: easy-bookmark
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ESBMRK_PRO')) {
    
    define( 'ESBMRK_VERSION', '1.0.3' );
    define( 'ESBMRK_PL_ROOT', __FILE__ );
    define( 'ESBMRK_PL_URL', plugins_url( '/', ESBMRK_PL_ROOT ) );
    define( 'ESBMRK_PL_PATH', plugin_dir_path( ESBMRK_PL_ROOT ) );
    define( 'ESBMRK_DIR_URL', plugin_dir_url( ESBMRK_PL_ROOT ) );
    define( 'ESBMRK_PLUGIN_BASE', plugin_basename( ESBMRK_PL_ROOT ) );
    
    include 'admin/includes/activation.php';
    include 'admin/includes/class.templates.php';
    include 'class.easy-bookmark.php';

    register_activation_hook(__FILE__, 'esbmrk_create_listing_page');
    ESBMRK_Plugin::instance();
    
}


  
	

  
	









