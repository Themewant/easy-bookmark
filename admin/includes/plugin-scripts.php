<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
add_action('admin_enqueue_scripts', 'esbmrk_admin_enqueue_scripts');
function esbmrk_admin_enqueue_scripts (){

    wp_register_style( 'easy_bookmark-admin-style', ESBMRK_PL_URL . 'admin/assets/css/admin-style.css', array(), ESBMRK_VERSION, 'all' );
    wp_enqueue_style( 'easy_bookmark-admin-style' );

    wp_register_script( 'easy_bookmark-admin-script', ESBMRK_PL_URL . 'admin/assets/js/admin.js', array('jquery'), ESBMRK_VERSION, true );
    wp_enqueue_script( 'easy_bookmark-admin-script' );

}

