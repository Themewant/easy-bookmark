<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class ESBMRK_Templates {

    private static $_instance = null;
	
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

    public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

    public function init() {
        // Register the custom page template in the dropdown
        add_filter('theme_page_templates', [$this, 'esbmrk_register_templates']);

        add_filter('template_include', [$this, 'esbmrk_include_templates']);
	}
    

    public function esbmrk_include_templates($template) {

            // Check if the custom template exists in your plugin directory
            $plugin_template = ESBMRK_PL_PATH . 'public/templates/easy_bookmark_listing.php';
            $theme_template = get_stylesheet_directory() . '/easy-bookmark-booking/easy_bookmark_listing.php';
            $child_theme_template = get_template_directory() . '/easy-bookmark-booking/easy_bookmark_listing.php';
    
            $template_slug = get_page_template_slug();

            if($template_slug == 'easy_bookmark_listing.php'){
                if (file_exists($theme_template)) {
                    $template = $theme_template;
                } elseif (file_exists($child_theme_template)) {
                    $template = $child_theme_template;
                } else {
                    $template = $plugin_template;
                }
        
                if (file_exists($template)) {
                    // Use the custom template
                    return $template;
                }
            }

        
        // Return the default template if the custom template is not found
        return $template;
    }

    public function esbmrk_register_templates($templates) {
        // Add your custom template to the list of templates
        $templates['easy_bookmark_listing.php'] = __( 'Easy Bookmark Listing', 'easy-bookmark' );
        return $templates;
    }

}
ESBMRK_Templates::instance();