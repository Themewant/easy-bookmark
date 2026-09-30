<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class ESBMRK_Admin {
	
    private static $_instance = null;

	/**
	 * Instance
	 * Ensures only one instance of the class is loaded or can be loaded.
	 */

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		
        add_action( "wp_ajax_nopriv_esbmrk_add_to_easy_bookmark", array ( $this, 'esbmrk_add_to_easy_bookmark' ) );
        add_action( "wp_ajax_esbmrk_add_to_easy_bookmark",        array ( $this, 'esbmrk_add_to_easy_bookmark' ) );

        

        add_action( "wp_ajax_nopriv_esbmrk_delete_from_easy_bookmarked", array ( $this, 'esbmrk_delete_from_easy_bookmarked' ) );
        add_action( "wp_ajax_esbmrk_delete_from_easy_bookmarked",        array ( $this, 'esbmrk_delete_from_easy_bookmarked' ) );

        add_action( "wp_ajax_nopriv_esbmrk_delete_all_from_easy_bookmarked", array ( $this, 'esbmrk_delete_all_from_easy_bookmarked' ) );
        add_action( "wp_ajax_esbmrk_delete_all_from_easy_bookmarked",        array ( $this, 'esbmrk_delete_all_from_easy_bookmarked' ) );

	}


    public function esbmrk_add_to_easy_bookmark() {

        // Verify nonce for security
        if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'esbmrk_bookmark_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce']);
        }

        if (isset($_POST['postId'])) {
    
            // Sanitize the post ID
            $new_post_id = intval($_POST['postId']);
    
            // Initialize an empty array for bookmarked post IDs
            $bookmarked_post_ids = array();
    
            $bookmarked_post_ids = esbmrk_get_bookmark_cookie_ids();
    
            // Add the new post ID to the array if it's not already bookmarked
            if (!in_array($new_post_id, $bookmarked_post_ids)) {
                array_push($bookmarked_post_ids, $new_post_id);
            }
    
            // Encode the array into a JSON string and set the cookie
            esbmrk_set_bookmark_cookie( wp_json_encode($bookmarked_post_ids) );
    
            // Send success response with the updated bookmarked post IDs
            wp_send_json_success(array(
                'message' => 'Post successfully bookmarked',
                'bookmarkedPosts' => $bookmarked_post_ids
            ));
        }
    
        // If no post ID is provided, send an error response
        wp_send_json_error(array(
            'message' => 'Invalid post ID'
        ));
    
        // Terminate script execution
        wp_die();
    }

   
    public function esbmrk_delete_from_easy_bookmarked() {

        // Verify nonce for security
        if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'esbmrk_bookmark_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce']);
        }


        $esbmrk_options = get_option('esbmrk_options');
        $empty_bookmark_label = isset($esbmrk_options['empty_bookmark_label']) ? $esbmrk_options['empty_bookmark_label'] : -1;


        if (isset($_POST['postId'])) {
            // Sanitize post ID
            $post_id = intval($_POST['postId']);
    
            // Initialize an empty array for bookmarked post IDs
            $bookmarked_post_ids = array();
    
            // Check if the cookie exists
            if (isset($_COOKIE['easy_bookmarked'])) {
                $bookmarked_post_ids = esbmrk_get_bookmark_cookie_ids();

                if (is_array($bookmarked_post_ids)) {
                    // Search and remove the post ID from the bookmarked list
                    $key = array_search($post_id, $bookmarked_post_ids);
                    if ($key !== false) {
                        unset($bookmarked_post_ids[$key]); // Remove the ID from the array
    
                        // Reindex the array
                        $bookmarked_post_ids = array_values($bookmarked_post_ids);
    
                        // Set the cookie again with the updated values (for 30 days)
                        esbmrk_set_bookmark_cookie( wp_json_encode($bookmarked_post_ids) );
    
                        // Send success response with the updated bookmarked post IDs
                        wp_send_json_success(array(
                            'message' => 'Post successfully removed from bookmarked',
                            'updated_bookmarked' => $bookmarked_post_ids,
                            'empty_bookmark_label' => $empty_bookmark_label,
                        ));
                    } else {
                        wp_send_json_error(array(
                            'message' => 'Post ID not found in bookmarked list.'
                        ));
                    }
                } else {
                    wp_send_json_error(array(
                        'message' => 'Failed to retrieve bookmarked posts.'
                    ));
                }
            } else {
                wp_send_json_error(array(
                    'message' => 'No bookmarked posts found.'
                ));
            }
        } else {
            wp_send_json_error(array(
                'message' => 'No post ID provided.'
            ));
        }
    
        // Terminate script execution
        wp_die();
    }
    

    public function esbmrk_delete_all_from_easy_bookmarked() {

        // Verify nonce for security
        if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'esbmrk_bookmark_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce']);
        }

        // Check if the cookie exists
        if (isset($_COOKIE['easy_bookmarked'])) {

            // Set the cookie with an expiration time in the past to delete it
            esbmrk_set_bookmark_cookie( '', time() - HOUR_IN_SECONDS );

            // Send success response after deletion
            wp_send_json_success(array(
                'message' => 'All posts successfully removed from bookmarked.',
            ));
        } 
        
        // If the delete request wasn't properly sent
        wp_send_json_error(array(
            'message' => 'Failed to delete bookmarked posts.'
        ));
    
        // Terminate script execution
        wp_die();
    }
    
}

ESBMRK_Admin::instance();