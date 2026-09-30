<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
/**
 * Template Name: Easy Bookmark Listing
 * Description: A Page template for Easy Bookmarked Posts.
 */
get_header();


    $view = ESBMRK_View::instance();

    $posts_grid_html = $view->esbmrk_get_easy_bookmarked_posts_grid_html();

    echo esc_html($posts_grid_html);


get_footer();
