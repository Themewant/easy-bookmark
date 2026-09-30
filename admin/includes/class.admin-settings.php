<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if ( !class_exists('ESBMRK_ADMIN_SETTINGS')) {
    class ESBMRK_ADMIN_SETTINGS {

        private $esbmrk_options;
    
        function __construct(){   
            
            add_action( 'admin_menu', [$this, 'esbmrk_menus_register'] );
            add_action( 'admin_init', [$this, 'esbmrk_register_plugin_settings'] );   
    
        }   
    
        public function esbmrk_menus_register (){
            add_menu_page( 
                __('Easy Bookmark', 'easy-bookmark'), 
                __('Easy Bookmark', 'easy-bookmark'), 
                'manage_options', 
                'easy-bookmark', 
                array($this, 'esbmrk_plugin_page'), 
                ESBMRK_PL_URL . 'admin/assets/img/bookmark-icon.png',
                100
            );
        }
    
        public function esbmrk_register_plugin_settings() {

            function esbmrk_sanitize_options($input) {
                $sanitized = [];
                
                // Define allowed HTML tags and attributes.
                $allowed_html = array(
                    'a'      => array(
                        'href'  => array(),
                        'title' => array(),
                        'target' => array(),
                    ),
                    'b'      => array(),
                    'strong' => array(),
                    'i'    => array(
                        'class' => array(),
                        'id'    => array(),
                    ),
                    'em'     => array(),
                    'div'    => array(
                        'class' => array(),
                        'id'    => array(),
                    ),
                    'span'   => array(
                        'class' => array(),
                    ),
                    'p'      => array(),
                    'ul'     => array(),
                    'li'     => array(),
                );
            
                // Loop through each field in the input array.
                foreach ($input as $key => $value) {
                    if (is_array($value)) {
                        // Recursively sanitize arrays.
                        $sanitized[$key] = array_map(function($item) use ($allowed_html) {
                            return wp_kses($item, $allowed_html);
                        }, $value);
                    } elseif (is_numeric($value)) {
                        // Sanitize numeric values.
                        $sanitized[$key] = absint($value);
                    } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
                        // Sanitize URLs.
                        $sanitized[$key] = esc_url_raw($value);
                    } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        // Sanitize email addresses.
                        $sanitized[$key] = sanitize_email($value);
                    } else {
                        // Allow specific HTML for text fields.
                        $sanitized[$key] = wp_kses($value, $allowed_html);
                    }
                }
            
                return $sanitized;
            }
            
            register_setting(
                'esbmrk_options_group', // option_group
                'esbmrk_options',       // option_name
                'esbmrk_sanitize_options' // sanitization callback
            );


            add_settings_section(
                'esbmrk_setting_section', // id    
                '', // title    
                array( $this, 'esbmrk_register_plugin_settings_section' ), // callback    
                'easy-bookmark-settings' // page    
            );  
    
    
            add_settings_field(
    
                'esbmrk_general_settings', // id
    
                __( 'General Settings', 'easy-bookmark' ), // title
    
                array( $this, 'esbmrk_general_settings' ), // callback
    
                'easy-bookmark-settings', // page
    
                'esbmrk_setting_section' // section
    
            );
    
        }

        public function esbmrk_get_all_page_slugs_and_names() {
            // Get all pages
            $pages = get_posts(array(
                'post_type'   => 'page',
                'post_status' => 'publish',
                'numberposts' => -1 // Get all posts
            ));
            
            // Initialize an empty array to store page slugs and names
            $page_data = array('easy-bookmark-listing' => 'Easy Bookmark Listing');
        
            // Loop through each page
            foreach ($pages as $page) {
                
                $page_data[$page->post_name] = $page->post_title; // Slug of the page
                
            }
        
            return $page_data;
        }
    
        public function esbmrk_get_settings_fields() {

            $pages = $this->esbmrk_get_all_page_slugs_and_names();

            $esbmrk_register_plugin_settings_fields = array();
    
            $settings_fields = array(
                array(
                    'id'    => 'label_sections_start',
                    'name'  => 'label_sections_start',
                    'type'  => 'section_start',
                    'label' => __( 'Bookmark Label:', 'easy-bookmark' )
                ),
                array(
                    'id'    => 'show_bookmark_label_switch',
                    'name'  => 'show_bookmark_label_switch',
                    'type'  => 'checkbox',
                    'label' => __( 'Show Bookmark Label', 'easy-bookmark' ),
                    'value' => 'on'
                ),
                array(
                  'id'    => 'bookmark_label',
                  'name'  => 'bookmark_label',
                  'type'  => 'text',
                  'label' => __( 'Bookmark Label', 'easy-bookmark' )
                ),
                array(
                  'id'    => 'bookmarked_label',
                  'name'  => 'bookmarked_label',
                  'type'  => 'text',
                  'label' => __( 'Bookmarked Label', 'easy-bookmark' )
                ),
                array(
                    'id'    => 'bookmark_tooltip',
                    'name'  => 'bookmark_tooltip',
                    'type'  => 'text',
                    'label' => __( 'Bookmark Tooltip', 'easy-bookmark' )
                  ),
                array(
                    'id'    => 'bookmarked_tooltip',
                    'name'  => 'bookmarked_tooltip',
                    'type'  => 'text',
                    'label' => __( 'Bookmarked Tooltip', 'easy-bookmark' )
                ),
                array(
                    'id'    => 'empty_bookmark_label',
                    'name'  => 'empty_bookmark_label',
                    'type'  => 'textarea',
                    'label' => __( 'Bookmark Empty Message', 'easy-bookmark' )
                ),
                array(
                  'id'    => 'integration_sections_start',
                  'name'  => 'integration_sections_start',
                  'type'  => 'section_start',
                  'label' => __( 'Integration:', 'easy-bookmark' )
                ),
                
                array(
                  'id'    => 'auto_integration_location',
                  'name'  => 'auto_integration_location',
                  'type'  => 'select',
                  'label' => __( 'Auto Integration', 'easy-bookmark' ),
                  'options' => array(
                    'after_content' => __( 'After Content', 'easy-bookmark' ),
                    'before_content' => __( 'Before Content', 'easy-bookmark' ),
                    'disable' => __( 'Disable Auto Integration', 'easy-bookmark' ),
                  )
                ),
                array(
                    'id'    => 'bookmark_shortcode',
                    'name'  => 'bookmark_shortcode',
                    'type'  => 'html',
                    'label' => __( 'Shortcode', 'easy-bookmark' ),
                    'value' => 'Counter: [esbmrk_counter] <br> Bookmark Button: [esbmrk_button] <br> Bookmark Listing: [esbmrk_post_grid]'
                  ),
                array(
                  'id'    => 'bookmark_shortcode_php',
                  'name'  => 'bookmark_shortcode_php',
                  'type'  => 'string',
                  'label' => __( 'Shortcode in PHP Script', 'easy-bookmark' ),
                  'value' => '<?php echo do_shortcode("[esbmrk_button]"); ?>'
                ),
                array(
                  'id'    => 'allowed_post_type_for_bookmark',
                  'name'  => 'allowed_post_type_for_bookmark',
                  'type'  => 'select',
                  'label' => __( 'Allowed Post Type', 'easy-bookmark' ),
                  'multiple'  => true,
                  'options' => array(
                    'post' => __( 'Post', 'easy-bookmark' ),
                    'page' => __( 'Page', 'easy-bookmark' )
                  )
                ),
                array(
                  'id'    => 'show_bookmark_in_archive_switch',
                  'name'  => 'show_bookmark_in_archive_switch',
                  'type'  => 'checkbox',
                  'label' => __( 'Bookmark in Archive', 'easy-bookmark' ),
                  'value' => 'on'
                ),
                array(
                    'id'    => 'posts_per_page_bookmark_listing',
                    'name'  => 'posts_per_page_bookmark_listing',
                    'type'  => 'number',
                    'label' => __( 'Posts Per Page for Bookmark Listing', 'easy-bookmark' )
                ),
                array(
                    'id'    => 'bookmark_listing_page',
                    'name'  => 'bookmark_listing_page',
                    'type'  => 'select',
                    'label' => __( 'Bookmark Listing Page', 'easy-bookmark' ),
                    'multiple'  => false,
                    'options' => $pages
                ),
            );
    
    
            $esbmrk_register_plugin_settings_fields['settings_fields'] = $settings_fields;
    
            return $esbmrk_register_plugin_settings_fields;
        }

        public function esbmrk_register_plugin_settings_section (){}

        public function esbmrk_plugin_page (){
            
            $this->esbmrk_options = get_option( 'esbmrk_options' ); 
            
            ?>
            <div class="easy-bookmark-settings-tabs-wrapper">
                <h1 class="section-title"><?php esc_html_e( 'Easy Bookmark Settings', 'easy-bookmark' ); ?></h1>

                <?php settings_errors(); ?>

                <form method="POST" action="options.php">
                    <div class="tabs easy-bookmark-settings-tabs">
                        <ul id="tabs-nav">
                            <li class="active"><a href="#tab1"><?php esc_html_e( 'General Settings', 'easy-bookmark' ); ?></a></li>
                            <li><a href="#tab2"><?php esc_html_e( 'About Us', 'easy-bookmark' ); ?></a></li>
                        </ul> <!-- END tabs-nav -->
                        <div class="tab-contents-wrapper">
                            <div id="tab1" class="tab-content">
                                <?php
                                    settings_fields( 'esbmrk_options_group' );	
                                    do_settings_sections( 'easy-bookmark-settings' );
                                    ?>
                                    <div class="form-footer"><?php submit_button(); ?></div> 
                            </div>
                            <div id="tab2" class="tab-content" style="display: none;">
                                <h1><?php esc_html_e( 'About Us', 'easy-bookmark' ); ?></h1>
                                <p><strong>Themewant</strong> is a leading software company specializing in the development of high-quality WordPress plugins and themes. With a deep understanding of the WordPress ecosystem, we provide powerful and customizable solutions to enhance the functionality and appearance of websites across various industries. Our team of experienced developers and designers are committed to delivering innovative and user-friendly products that meet the diverse needs of our clients.</p>

                                <p>As proud authors on <a href="https://themeforest.net/">ThemeForest</a>, we have a strong reputation for creating premium WordPress themes and plugins that are widely trusted and used by developers, designers, and businesses around the globe. Our products are designed with performance, security, and ease of use in mind, ensuring that both beginners and advanced users can benefit from them.</p>

                                <p>At <a href="https://themewant.com/">Themewant</a>, we focus on providing excellent customer support, ensuring that our users have a smooth experience from installation to deployment. Whether you are building an eCommerce store, corporate website, or personal blog, we have the perfect WordPress solution for you.</p>
                            </div>
                        </div>
                    </div> <!-- END tabs -->
                </form>
            </div>

            
            
    
    
            <?php
        }
    
        public function esbmrk_general_settings() {
    
            $esbmrk_register_plugin_settings_fields = $this->esbmrk_get_settings_fields();
            $esbmrk_options = get_option( 'esbmrk_options' );            
    
            foreach ($esbmrk_register_plugin_settings_fields['settings_fields'] as $field) {

                $field_value = isset($field['value']) ? $field['value'] : '';
                $field_name = isset($field['name']) ? $field['name'] : '';
                $field_label = isset($field['label']) ? $field['label'] : '';
                $field_type = isset($field['type']) ? $field['type'] : '';

                if(isset($field['default'])){
                    $field_value = $field['default'];
                }

                $field_value = isset($esbmrk_options[$field_name]) ? $esbmrk_options[$field_name] :  $field_value;

                $allowed_html = array(
                    'br' => array(),
                    'p' => array(),
                    'a' => array(),
                    'span' => array(),
                    'div' => array(),
                    'strong' => array(),
                    'h4' => array(),
                    'h5' => array(),
                    'h6' => array()
                );


                if($field_type == 'section_start'){
                    ?>
                    <h3 class="settings-section"><?php echo esc_html($field_label) ?></h3>
                    <?php
                }else{
                    if( $field_type == 'html' ){

                        echo '<div class="settings-item"><label>'. esc_html($field['label']) .'</label>';
                        echo wp_kses( $field_value, $allowed_html );
                        echo '</div>';
                    }else if( $field_type == 'string' ){
    
                        $htmlentities = htmlentities($field_value);
    
                        echo '<div class="settings-item"><label>'. esc_html($field_label) .'</label>';
                        echo wp_kses( $htmlentities, $allowed_html );
                        echo '</div>';
                    }else if( $field_type == 'select' ){
    
                        $options = $field['options'];
                        $multiple = '';

                        
                        // Get saved value(s) from esbmrk_options
                        $saved_value = isset($esbmrk_options[$field_name]) ? $esbmrk_options[$field_name] : '';


                        // Handle multiple select case
                        if (isset($field['multiple']) && $field['multiple'] == true) {
                            $multiple = 'multiple';
                            $select_field_name = 'esbmrk_options['.$field_name.'][]';
                            // Ensure saved value is always an array when multiple is allowed
                            if (!is_array($saved_value)) {
                                $saved_value = [$saved_value];
                            }
                        } else {
                            $select_field_name = 'esbmrk_options['.$field_name.']';
                        }

                        // Output the select field
                        echo '<div class="settings-item"><label>'. esc_html($field_label) .'</label><select name="'.esc_attr($select_field_name).'" '.esc_attr($multiple).'>';

                        // Loop through the options and add selected attribute
                        foreach ($options as $key => $option) {
                            if ($multiple) {
                                // Check if the option is in the array of selected values for multiple select
                                $selected = in_array($key, $saved_value) ? 'selected' : '';
                            } else {
                                // Check if the option is the saved value for single select
                                $selected = ($key == $saved_value) ? 'selected' : '';
                            }

                            // Output the option with selected attribute
                            printf('<option value="%s" %s>%s</option>', esc_html($key), esc_html($selected), esc_html($option));
                        }

                        echo '</select></div>';    
                    }else if ($field_type == 'textarea') {
                        // Get the saved value from options
                        $textarea_value = isset($esbmrk_options[$field_name]) ? $esbmrk_options[$field_name] : $field_value;

                        // Output the textarea field
                        echo '<div class="settings-item"><label>' . esc_html($field_label) . '</label>';
                        echo '<textarea name="esbmrk_options[' . esc_attr($field_name) . ']" rows="5" cols="50">' . esc_textarea($textarea_value) . '</textarea>';
                        echo '</div>';
                    }else{

                        if ($field_type == 'checkbox') {
                            // Get the saved value from options
                            $saved_value = isset($esbmrk_options[$field_name]) ? $esbmrk_options[$field_name] : '';
                            
                            // Check if the saved value matches the checkbox value and add 'checked' attribute
                            $checked = ($saved_value == $field_value) ? 'checked' : '';
                        }

                        printf(
                            '<div class="settings-item">
                                <label>' . esc_html($field_label) . '</label>
                                <input type="' . esc_html($field_type) . '" 
                                       name="esbmrk_options[' . esc_html($field_name) . ']" 
                                       id="esbmrk_general_settings" 
                                       value=\'%s\' %s>
                            </div>', 
                            esc_html($field_value), esc_html($checked)
                        );
    
                    }
                }                    
            }              
        } 
    }
    new ESBMRK_ADMIN_SETTINGS();
}
