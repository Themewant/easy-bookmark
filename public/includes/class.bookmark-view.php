<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
class ESBMRK_View {
	
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
        add_action( "wp_ajax_nopriv_esbmrk_get_easy_bookmarked_posts_list_html", array ( $this, 'esbmrk_get_easy_bookmarked_posts_list_html' ) );
        add_action( "wp_ajax_esbmrk_get_easy_bookmarked_posts_list_html",        array ( $this, 'esbmrk_get_easy_bookmarked_posts_list_html' ) );
	}

    public function esbmrk_get_easy_bookmark_saved_ids(){

        $bookmarked_post_ids = esbmrk_get_bookmark_cookie_ids();

        return $bookmarked_post_ids;

    }

    public function esbmrk_get_easy_bookmarked_posts_list_html() {

        // Verify nonce for security
        if (empty($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'esbmrk_bookmark_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce']);
        }

        $bookmarked_post_ids = $this->esbmrk_get_easy_bookmark_saved_ids();
        $per_page = isset($_POST['perPage']) ? sanitize_text_field(wp_unslash($_POST['perPage'])) : -1;
            
        

        if(count($bookmarked_post_ids) > 0){
            $bookmarked_post_args = array(
                'posts_per_page' => $per_page,
                'post_type'      => 'post',
                'post__in'       => $bookmarked_post_ids
            );

            $bookmarked_post_query = new WP_Query($bookmarked_post_args);
            $esbmrk_options = get_option('esbmrk_options');
            $listing_page_slug = isset($esbmrk_options['bookmark_listing_page']) ? $esbmrk_options['bookmark_listing_page'] : 'easy-bookmark-listing';
            ?>
           
            <div class="esbmrk-action-bar">
                <a href="<?php echo esc_url( site_url($listing_page_slug) ); ?>" class="esbmrk-action-btn esbmrk_view_all_trigger"><?php esc_html_e( 'View All', 'easy-bookmark' ); ?></a>
                <a href="#" class="esbmrk-action-btn esbmrk_delete_all_trigger"><?php esc_html_e( 'Delete All', 'easy-bookmark' ); ?><span class="esbmrk-icon"></span></a>
                
            </div>
            <div class="esbmrk-posts">
                <?php
                if($bookmarked_post_query->have_posts()){
                
                    while ($bookmarked_post_query->have_posts()) {
                        $bookmarked_post_query->the_post(); 
                        $post_id = get_the_ID();
                        $show_book_mark_btn = false;
                        ?>

                            <article class="esbmrk-article">
                                <label class="esbmrk-form-control esbmrk-checkbox-label esbmrk-remove-btn">
                                    <span class="esbmrk-icon esbmrk-icon-close"> </span><input type="checkbox" name="checkbox" data-post-id="<?php echo esc_attr( $post_id )?>"/>
                                </label>
                                <div class="esbmrk-article-inner">
                                    <div class="esbmrk-col-left">
                                        <?php the_post_thumbnail( 'thumbnail', array('class' => 'esbmrk-article-thumbnail') ) ?>
                                    </div>
                                    <div class="esbmrk-col-right">
                                        <a href="<?php the_permalink(); ?>" class="esbmrk-article-title" target="_blank"><?php the_title(); ?></a>
                                        <div class="esbmrk-article-metas">
                                            <span class="esbmrk-meta"><span class="esbmrk-meta-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg></span><span class="meta-value"><?php echo get_the_date( 'd M, Y' ); ?></span></span>
                                            <span class="esbmrk-meta"><span class="esbmrk-meta-icon esbmrk-meta-icon-user"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z"/></svg></span><span class="meta-value"><?php echo esc_html(get_the_author_meta('user_login')); ?></span></span>
                                        </div>
                                    </div>
                                </div>
                            </article>

                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
            

        // Terminate script execution
        wp_die();
    }

    public function esbmrk_get_easy_bookmarked_posts_grid_html() {
        $bookmarked_post_ids = $this->esbmrk_get_easy_bookmark_saved_ids();
        $esbmrk_options = get_option('esbmrk_options');
        $per_page = isset($esbmrk_options['posts_per_page_bookmark_listing']) ? $esbmrk_options['posts_per_page_bookmark_listing'] : -1;
        $empty_bookmark_label = isset($esbmrk_options['empty_bookmark_label']) ? $esbmrk_options['empty_bookmark_label'] : __( 'Your bookmark is empty!', 'easy-bookmark' );
            $bookmarked_post_args = array(
                'posts_per_page' => $per_page,
                'post_type'      => 'post',
                'post__in'       => $bookmarked_post_ids,
                'paged'          => ( get_query_var('paged') ) ? get_query_var('paged') : 1,
            );
            $bookmarked_post_query = new WP_Query($bookmarked_post_args);
            ?>
            
            <div class="esbmrk-posts">
                <?php
                if(count($bookmarked_post_ids) > 0 && $bookmarked_post_query->have_posts()){ ?>
                    <div class="esbmrk-posts-grid">
                    <?php
                    while ($bookmarked_post_query->have_posts()) {
                        $bookmarked_post_query->the_post();
                        $post_id = get_the_ID();
                        $show_book_mark_btn = false;
                        ?>
                            <article class="esbmrk-article">
                                <label class="esbmrk-form-control esbmrk-checkbox-label esbmrk-remove-btn">
                                    <span class="esbmrk-icon esbmrk-icon-close"></span><input type="checkbox" name="checkbox" data-post-id="<?php echo esc_attr( $post_id )?>"/>
                                </label>
                                <div class="esbmrk-article-inner">
                                    <div class="esbmrk-col-left">
                                        <a href="<?php the_permalink(); ?>" target="_blank"> <?php the_post_thumbnail( 'medium', array('class' => 'esbmrk-article-thumbnail') ) ?> </a>
                                    </div>
                                    <div class="esbmrk-col-right">                                        
                                        <a href="<?php the_permalink(); ?>" class="esbmrk-article-title" target="_blank"><?php the_title(); ?></a>
                                        <div class="esbmrk-article-metas">
                                            <span class="esbmrk-meta"><span class="esbmrk-meta-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg></span><span class="meta-value"><?php echo get_the_date( 'd M, Y' ); ?></span></span>
                                            <span class="esbmrk-meta"><span class="esbmrk-meta-icon esbmrk-meta-icon-user"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z"/></svg></span><span class="meta-value"><?php echo esc_html(get_the_author_meta('user_login')); ?></span></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php
                    } ?>
                </div>
                    <?php
                } else {
                    ?>
                        <div class="esbmrk-empty-posts"><?php echo wp_kses_post( $empty_bookmark_label ) ?></div>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
            <?php if(count($bookmarked_post_ids) > 0) echo esc_html($this->esbmrk_get_easy_bookmark_pagination($bookmarked_post_query)); ?>
            
            <?php
    }

    public function esbmrk_get_easy_bookmark_counter($args = array()){
        $bookmarked_post_ids = $this->esbmrk_get_easy_bookmark_saved_ids();
        $total_bookmarked_count = count($bookmarked_post_ids);    
        $counter_box_esbmrk_icon = isset($args['counter_box_esbmrk-icon']) ? $args['counter_box_esbmrk-icon'] : '';
        $per_page = isset($args['posts_per_page']) ? $args['posts_per_page'] : -1;
        $esbm_icon_text = isset($args['esbm_icon_text']) ? $args['esbm_icon_text'] : '';
        $enable_count = isset($args['enable_count']) ? $args['enable_count'] : '';
        $count_position = isset($args['count_position']) ? $args['count_position'] : '';
        $esbm_icon_text_cls = (!empty($args['esbm_icon_text'])) ? 'esbm_icon_text_yes' : 'esbm_icon_text_no' ;
        $count_position_cls = ($count_position != 'top') ? 'esbm_position_right' : 'esbm_position_top';
        ?>
        <div class="esbmrk-counter-box">
            <div class="esbmrk-counter" data-per-page="<?php echo esc_attr( $per_page ) ?>">
                <?php 
                    if ($enable_count != 'no') { 
                    if ($count_position != 'right') { 
                ?>
                    <span class="esbmrk-total-marked <?php echo esc_attr($esbm_icon_text_cls); ?>"> <?php echo esc_html($total_bookmarked_count); ?> </span>
                <?php 
                    } 
                } 
                ?>                
                <span class="esbmrk-icon">
                    <?php 
                    if(!empty($counter_box_esbmrk_icon) && class_exists('\Elementor\Icons_Manager')){
                        \Elementor\Icons_Manager::render_icon( $counter_box_esbmrk_icon, [ 'aria-hidden' => 'true' ] );
                    } else {
                        ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M0 48C0 21.5 21.5 0 48 0l0 48 0 393.4 130.1-92.9c8.3-6 19.6-6 27.9 0L336 441.4 336 48 48 48 48 0 336 0c26.5 0 48 21.5 48 48l0 440c0 9-5 17.2-13 21.3s-17.6 3.4-24.9-1.8L192 397.5 37.9 507.5c-7.3 5.2-16.9 5.9-24.9 1.8S0 497 0 488L0 48z"/></svg>
                        <?php
                    }
                ?>

            </span>
                <?php if(!empty($esbm_icon_text)){ ?>
                    <em class="esb-count-title">
                    <?php echo esc_html($esbm_icon_text); ?> 
                    <?php 
                    if ($enable_count != 'no') { 
                        if ($count_position != 'top') { ?>
                            <sup class="esbmrk-total-marked <?php echo esc_attr($count_position_cls); ?>"> <?php echo esc_html($total_bookmarked_count); ?> </sup>
                        <?php }
                    }
                    ?>                    
                    </em>
                <?php } ?>
            </div> 
    
            <div id="esbmrk_popup_modal" class="modal">
                <!-- Modal content -->
                <div class="esbmrk-modal-content">
                    <div class="esbmrk-modal-header">
                    <span class="esbmrk-modal-close"> </span>
                    </div>
                    <div class="esbmrk-deleteing-loader">
                        <img src="<?php echo esc_url(ESBMRK_PL_URL . 'public/assets/img/loader.gif'); ?>" alt="loader-image">
                    </div>
                    <div class="esbmrk-modal-body">
                        <div class="esbmrk-empty-posts">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5 2H19C19.5523 2 20 2.44772 20 3V22.1433C20 22.4194 19.7761 22.6434 19.5 22.6434C19.4061 22.6434 19.314 22.6168 19.2344 22.5669L12 18.0313L4.76559 22.5669C4.53163 22.7136 4.22306 22.6429 4.07637 22.4089C4.02647 22.3293 4 22.2373 4 22.1433V3C4 2.44772 4.44772 2 5 2ZM18 4H6V19.4324L12 15.6707L18 19.4324V4Z"></path></svg>
                            <h5> <?php echo esc_html__('Saved articles', 'easy-bookmark'); ?> </h5>
                            <p> <?php echo esc_html__('You have not yet added any article to your bookmarks!', 'easy-bookmark'); ?> </p>
                            <a href="<?php echo esc_url(get_home_url()); ?>" class="esbmark-empty-btn"> <?php echo esc_html__('Browse articles', 'easy-bookmark'); ?> </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
            
        <?php
    }
    
    public function esbmrk_get_easy_bookmark_button($post_id){

        $esbmrk_options = get_option('esbmrk_options');
        $show_bookmark_label_switch = isset($esbmrk_options['show_bookmark_label_switch']) ? $esbmrk_options['show_bookmark_label_switch'] : '';
        $bookmark_label = isset($esbmrk_options['bookmark_label']) && !empty($esbmrk_options['bookmark_label']) ? $esbmrk_options['bookmark_label']: __( 'Bookmark', 'easy-bookmark' );
        $bookmarked_label = isset($esbmrk_options['bookmarked_label']) && !empty($esbmrk_options['bookmarked_label']) ? $esbmrk_options['bookmarked_label']: __( 'Bookmarked', 'easy-bookmark' );
        $bookmark_label_el = '';
        $bookmarked_label_el = '';

        if($show_bookmark_label_switch == 'on'){
            $bookmark_label_el = '<span class="esbmrk-bookmark-label">'.esc_html($bookmark_label).'</span>';
            $bookmarked_label_el = '<span class="esbmrk-bookmark-label">'.esc_html($bookmarked_label).'</span>';
        }

        $bookmark_tooltip = isset($esbmrk_options['bookmark_tooltip']) && !empty($esbmrk_options['bookmark_tooltip']) ? $esbmrk_options['bookmark_tooltip'] : __( 'Add to Bookmark', 'easy-bookmark' );
        $bookmarked_tooltip = isset($esbmrk_options['bookmarked_tooltip']) && !empty($esbmrk_options['bookmarked_tooltip']) ? $esbmrk_options['bookmarked_tooltip'] : __( 'Added to Bookmark', 'easy-bookmark' );


        $bookmarked_post_ids = array();

        $bookmarked_post_ids = esbmrk_get_bookmark_cookie_ids();

        $bookmark_class = '';
        $title = $bookmark_tooltip;
        if(in_array($post_id, $bookmarked_post_ids)){
            $bookmark_class = 'esbmrk-marked';
            $title = $bookmarked_tooltip;
            $bookmark_label_el = $bookmarked_label_el;
        }

        $html = '<a
        href="#"
        data-post-id="'.esc_attr($post_id).'"
        class="esbmrk-add-to-bookmark-btn '. esc_attr($bookmark_class) .'"
        data-bookmark-label="'.esc_attr($bookmark_label).'"
        data-bookmarked-label="'.esc_attr($bookmarked_label).'"
        data-bookmark-tooltip="'.esc_attr($bookmark_tooltip).'"
        data-bookmarked-tooltip="'.esc_attr($bookmarked_tooltip).'" aria-label="'.esc_attr__('Bookmark this post as saved', 'easy-bookmark').'">
            <span class="esbmrk-icon"></span>
            <span class="esbmrk-tooltip">'. esc_html($title) .'</span>
            '.$bookmark_label_el.'
        </a>';

        return $html;
        
    }

    public function esbmrk_add_to_easy_bookmark_button($content) {

        // Get the post ID
        $post_id = get_the_ID();

        // Define allowed post types
        $allowed_post_types = ['post'];

        $esbmrk_options = get_option('esbmrk_options');
        $show_bookmark_in_archive_switch = isset($esbmrk_options['show_bookmark_in_archive_switch']) ? $esbmrk_options['show_bookmark_in_archive_switch'] : 'off';
        $auto_integration_location = isset($esbmrk_options['auto_integration_location']) ? $esbmrk_options['auto_integration_location'] : 'after_content';

        if(isset($esbmrk_options['allowed_post_type_for_bookmark']) && !empty($esbmrk_options['allowed_post_type_for_bookmark'])){
            $allowed_post_types = $esbmrk_options['allowed_post_type_for_bookmark'];
        }

        


        // Check if the current post type is in the allowed post types
        if ( in_array( get_post_type( $post_id ), $allowed_post_types ) ) {

                $bookmark_btn = $this->esbmrk_get_easy_bookmark_button($post_id);
                $bookmark_btn = '<div class="esbmrk-btn-wrapper">'. $bookmark_btn .'</div>';

            // If the page is an archive, taxonomy, category, or tag page
            if ( is_archive() || is_tax() || is_category() || is_tag() ) {
                if ($show_bookmark_in_archive_switch == 'on') {
                    if($auto_integration_location == 'after_content'){
                        return $content . $bookmark_btn;
                    }else if ($auto_integration_location == 'before_content'){
                        return $bookmark_btn . $content;
                    }
                    
                }
            }else{
                
                return $content . $bookmark_btn;
            }
        }

        // Return title unchanged if post type is not 'allowed post type'
        return $content;
    }
    
    public function esbmrk_get_easy_bookmark_pagination($query = null){
        // Use the global $wp_query if no custom query is provided
        if ( !$query ) {
            global $wp_query;
            $query = $wp_query;
        }

        // Check if there are enough posts to paginate
        if ( $query->max_num_pages <= 1 ) {
            return;
        }

        $big = 999999999; // Need an unlikely integer

        // Generate pagination links
        $pages = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var('paged') ),
            'total'     => $query->max_num_pages,
            'prev_text' => esc_html__('&laquo; Previous', 'easy-bookmark'),
            'next_text' => esc_html__('Next &raquo;', 'easy-bookmark'),
            'type'      => 'array', // Output as array to customize
        ) );

        if ( is_array( $pages ) ) {
            echo '<nav class="esbmrk-pagination"><ul class="esbmrk-pagination-list">';
            foreach ( $pages as $page ) { ?>
                <li> <?php echo wp_kses_post($page) ?> </li>
            <?php }
            echo '</ul></nav>';
        }
    }
    
}

$ESBMRK_View = ESBMRK_View::instance();

$esbmrk_options = get_option('esbmrk_options');

$auto_integration_location = isset($esbmrk_options['auto_integration_location']) && !empty($esbmrk_options['auto_integration_location']) ? $esbmrk_options['auto_integration_location'] : 'after_content';


// Add Bookmark Button To Title
if ($auto_integration_location == 'after_content' || $auto_integration_location == 'before_content') {
    add_action('wp', function () use ($auto_integration_location, $ESBMRK_View) {
        // Ensure we're not on the admin side and not making AJAX requests
        if (!is_admin() && !wp_doing_ajax()) {
            // Apply the filter for singular posts/pages and archive pages
            //if (is_singular() || is_archive()) {

                
                    add_filter('the_content', function ($content) use ($ESBMRK_View) {
                        // Ensure this is in the main loop
                        if (in_the_loop() && !is_admin()) {
                            return $ESBMRK_View->esbmrk_add_to_easy_bookmark_button($content);
                        }
                        return $content;
                    }, 10);

                    add_filter('the_excerpt', function ($content) use ($ESBMRK_View) {
                        // Ensure this is in the main loop
                        if (in_the_loop() && !is_admin()) {
                            return $ESBMRK_View->esbmrk_add_to_easy_bookmark_button($content);
                        }
                        return $content;
                    }, 10);
                
        //  }
        }
    });
}
