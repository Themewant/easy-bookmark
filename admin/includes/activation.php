<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
// Create the "Easy Bookmark Listing" page upon plugin activation
if(!function_exists('esbmrk_create_listing_page')){
    function esbmrk_create_listing_page() {
        // Page title
        $page_title = 'Easy Bookmark Listing';
    
        // Check if the page already exists using WP_Query
        $args = array(
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'title'          => $page_title,
            'posts_per_page' => 1,
            'fields'         => 'ids' // Get only the page ID to optimize the query
        );
        
        $query = new WP_Query($args);
        
        // If the page doesn't exist, create it
        if (!$query->have_posts()) {
            $page_data = array(
                'post_title'    => $page_title,
                'post_content'  => '', // Default content, you can update this
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id(),
            );
            $page_id = wp_insert_post($page_data);
    
            // Assign the custom page template if creation was successful
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', 'easy_bookmark_listing.php');
            }
        }
    
        // Reset post data
        wp_reset_postdata();
    }
}






