<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
add_action('wp_enqueue_scripts', 'esbrmk_wp_enqueue_scripts');
function esbrmk_wp_enqueue_scripts (){
    

    wp_register_style( 'easy-bookmark-style', ESBMRK_PL_URL . 'public/assets/css/bookmark.css', array(), ESBMRK_VERSION );
    wp_enqueue_style( 'easy-bookmark-style' );

    wp_register_script( 'easy-bookmark-script', ESBMRK_PL_URL . 'public/assets/js/public.js', array('jquery'), ESBMRK_VERSION, true );
    wp_enqueue_script( 'easy-bookmark-script' );


    // Adding defer attribute to the script
    add_filter( 'script_loader_tag', 'esbmrk_add_defer_attribute', 10, 2 );
    function esbmrk_add_defer_attribute( $tag, $handle ) {
        if ( 'easy-bookmark-script' !== $handle ) {
            return $tag;
        }
        return str_replace( ' src', ' defer="defer" src', $tag );
    }

    wp_localize_script(
        'easy-bookmark-script', 
        'ebm_ajax',
            [
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'adminURL'         => admin_url(),
                'nonce'            => wp_create_nonce('esbmrk_bookmark_nonce'),
                'version'          => ESBMRK_VERSION,
                'pluginURL'        => plugin_dir_url( __FILE__ ),
                'empty_label'      => __( 'Your bookmark is empty!', 'easy-bookmark' ),
                'empty_short'      => __( 'Empty Bookmark!', 'easy-bookmark' ),
            ]
    );
}


