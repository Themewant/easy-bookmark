<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if ( ! class_exists( 'ESBMRK_Plugin' ) ) {
    class ESBMRK_Plugin {

        private static $_instance = null;

        /**
         * Instance
         *
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @since 1.0.0
         *
         * @access public
         * @static
         *
         * @return ESBMRK_Plugin An instance of the class.
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
            add_action( 'plugins_loaded', [ $this, 'init' ] );
            add_action( 'plugins_loaded', [ $this, 'includes' ] );
        }

        /**
         * Initialize the plugin
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function init() {

            // Check if Elementor is installed and activated
            if ( did_action( 'elementor/loaded' ) ) {
                // Add Plugin actions
                add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
                add_action( 'elementor/elements/categories_registered', [ $this, 'esbrmk_add_category' ] );
                add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'esbrmk_elementor_icon_loader' ], 99999 );
            }
            
            add_filter( 'plugin_action_links_' . ESBMRK_PLUGIN_BASE, [ $this, 'esbrmk_plugin_action_links' ], 10, 4 );
            
        }



        public function includes() {

            include 'includes/cookie.php';

			include 'admin/includes/plugin-scripts.php';
            include 'admin/includes/class.admin-settings.php';
            include 'admin/includes/class.bookmark-admin.php';
            include 'admin/includes/shortcodes.php';
            
            include 'public/includes/plugin-scripts.php';
            include 'public/includes/class.bookmark-view.php';
            
        }

        public function esbrmk_plugin_action_links( $plugin_actions, $plugin_file, $plugin_data, $context ) {
            $new_actions = array();
            $new_actions['easy_bookmark_plugin_actions_setting'] = sprintf(
                '<a href="%1$s" target="_self">%2$s</a>',
                esc_url( admin_url( 'admin.php?page=easy-bookmark' ) ),
                esc_html__( 'Settings', 'easy-bookmark' )
            );
            return array_merge( $new_actions, $plugin_actions );
        }

        public function esbrmk_elementor_icon_loader() {
            wp_register_style( 'easy_bookmark_elementor_esbmrk-icon',
            ESBMRK_PL_URL . 'public/assets/css/elementor-icon.css', false, ESBMRK_VERSION );
            wp_enqueue_style( 'easy_bookmark_elementor_esbmrk-icon' );

        }
        public function esbrmk_add_category( $elements_manager ) {
            $elements_manager->add_category(
                'easy_bookmark_category',
                [
                    'title' => esc_html__( 'Easy Bookmark Addons', 'easy-bookmark' ),
                    'esbmrk-icon'  => 'fa fa-smile-o',
                ]
            );
        }
        public function init_widgets() {
            require_once( ESBMRK_PL_PATH . '/public/includes/widget.php' );
            \Elementor\Plugin::instance()->widgets_manager->register( new \ESBMRK_Widget() );
        }
    }
}

