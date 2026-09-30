<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if( !function_exists('esbmrk_counter_shortcode') ){

    add_shortcode( 'esbmrk_counter', 'esbmrk_counter_shortcode' );

    function esbmrk_counter_shortcode( $atts ) {

        if(defined('REST_REQUEST')) return;


        $html = '';

        ob_start();
    

        $allowed_html = array(
            'div' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
            ),
        );

        $ESBMRK_View = new ESBMRK_View();

        echo wp_kses($ESBMRK_View->esbmrk_get_easy_bookmark_counter(), $allowed_html);
        
        
        $html = ob_get_contents();
	            ob_end_clean();
	    return $html;

    }

    
}

if( !function_exists('esbmrk_button_shortcode') ) {

    add_shortcode( 'esbmrk_button', 'esbmrk_button_shortcode' );

    function esbmrk_button_shortcode( $atts ) {

        // Prevent shortcode rendering during REST API requests
        if(defined('REST_REQUEST')) return;

        // Initialize output
        $html = '';

        // Start output buffering
        ob_start();

        // Allow only specific HTML tags with attributes
        $allowed_html = array(
            'div' => array(
                    'class' => array(),
            ),
            'a' => array(
                'href' => array(),
                'title' => array(),
                'class' => array(),
                'post-id' => array(),
                'data-post-id' => array(),
                'data-bookmark-label' => array(),
                'data-bookmarked-label' => array(),
                'data-bookmark-tooltip' => array(),
                'data-bookmarked-tooltip' => array(),
            ),
            'span' => array(
                'class' => array(),
            ),
            'svg' => array(
                'xmlns' => array(),
                'viewbox' => array(), // Allow the viewBox attribute
                'class' => array(), // Optional: Allow class for SVG
            ),
            'path' => array(
                'd' => array(),
            ),
        );

        // Get the button HTML from your class method
        $ESBMRK_View = new ESBMRK_View();

        $btn = $ESBMRK_View->esbmrk_get_easy_bookmark_button(get_the_ID());
        $btn = '<div class="esbmrk-btn-wrapper">'.$btn.'</div>';

        // Echo or store the generated button HTML
        echo wp_kses($btn, $allowed_html);

        // Capture the buffer content
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}

if( !function_exists('esbmrk_post_grid_shortcode') ){

    add_shortcode( 'esbmrk_post_grid', 'esbmrk_post_grid_shortcode' );

    function esbmrk_post_grid_shortcode( $atts ) {

        //if(defined('REST_REQUEST')) return;

        $allowed_html = array(
            'div' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
            ),
            'ul' => array(
                'class' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'a' => array(
                'class' => array(),
            ),
        );

        $ESBMRK_View = new ESBMRK_View();

        // Return placeholder if Elementor is in edit mode
        if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
        
            echo wp_kses($ESBMRK_View->esbmrk_get_easy_bookmarked_posts_grid_html(), $allowed_html);

            return;
        }


        ob_start();
    

        echo wp_kses($ESBMRK_View->esbmrk_get_easy_bookmarked_posts_grid_html(), $allowed_html);

        
	    return ob_get_clean();

    }

    
}