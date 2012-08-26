<?php

/**
 * Admin Page Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Page Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Admin_Settings_Page' ) ) :
/**
 * Class for handling Settings Pages, per WordPress Settings API.
 * It handles Custom Menu Pages, Submenu Pages, and Theme Options Pages.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 * 
 * @link    http://codex.wordpress.org/Settings_API
 */
class River_Admin_Settings_Page extends River_Admin_Config_Validator {
    
    /** Constructor & Destructor **********************************************/
    
    /**
     * Let's create this Settings Page
     * 
     * NOTE:  ONLY INSTANTIATE ONE SETTINGS PAGE AT A TIME!
     * 
     * @since 0.0.0
     * 
     * @param array     $config Configuration for the new settings page                 
     */
    public function __construct( $config ) {
        
        // Validate the incoming $config. If valid, continue; else return.
        if( ! $this->config_is_valid($config) )
            return;
        
        $this->hooks();
        
        // Options are not defined in the options database yet.
        if ( ! get_option( $this->settings_group ) )
            $this->init_settings();      

    }
    
    /** Setup Functions *******************************************************/   
    
    /**
     * Hooks and Filters
     * 
     * @since   0.0.0
     * 
     * @uses    add_action()
     * @uses    'admin_menu' hook
     */
    private function hooks() {
        add_action( 'admin_menu',   array( &$this, 'page_request_handling' ), 10 );
        
        // Add the settings page
        add_action( 'admin_menu',   array( &$this, 'maybe_add_main_menu' ), 5 );
        add_action( 'admin_menu',   array( &$this, 'maybe_add_first_submenu' ), 5 );         
        add_action( 'admin_menu',   array( &$this, 'maybe_add_submenu' ) );      
        add_action( 'admin_menu',   array( &$this, 'maybe_add_theme_page' ) ); 

        
        // Register the setting - ASSUMES ONE PER PAGE
        add_action( 'admin_init',       array( &$this, 'register_setting' ) ); 
        add_action( 'admin_init',       array( $this, 'uploader_setup' ) );
        
        add_action( 'wp_ajax_river_ajax_save',  array( &$this, 'ajax_save_callback' ) );
    
    }
    
    /**
     * Page request handler
     * 
     * Handles resetting the settings in the options database
     * 
     * @return
     */
    public function page_request_handling() {
        
        if ( ! river_is_menu_page( $this->page_id ) )
            return;
        
        if ( isset( $_REQUEST['page'] ) ) {
            
            if( isset( $_REQUEST['reset'] ) ) {
                
                update_option( $this->settings_group, $this->defaults ) ?
                    '' :
                    river_admin_redirect( $this->page_id, array( 'error' => 'true' ) );                  
            } 
        }
    }

    /** Steps to add the pages, menus, and settings ***************************/ 
    
    /**
     * Add a new top level Custom Main Settings Page (to house the submenus) 
     * 
     * @since   0.0.0
     * 
     * @uses    add_menu_page()
     * @link    http://codex.wordpress.org/Function_Reference/add_menu_page
     */    
    public function maybe_add_main_menu() {
        
        if ( ( 'main_page' == $this->page_type ) && 
                isset( $this->page['main_menu'] ) && 
                is_array( $this->page['main_menu'] ) ) { 
            
            // Add the menu separator
            if( isset( $this->page['main_menu']['separator'] ))
                river_add_admin_menu_separator( 
                        $this->page['main_menu']['separator']['position'], 
                        $this->page['main_menu']['separator']['capability'] );
          
            
            $this->page_hook = add_menu_page(
                    // $page_title (str) Text in <title> tag
                    $this->page['main_menu']['page_title'],
                    // $menu_title (str) Text shown on the admin menu
                    $this->page['main_menu']['menu_title'],
                    // $capability (str) must be 'manage_options'
                    $this->page['main_menu']['capability'],
                    // $menu_slug (str) This menu's slug name
                    $this->page_id,
                    // $function Callback to display the page content
                    array( &$this, 'display_page_callback' ),
                    // $icon_url (str) Menu icon's url
                    $this->page['main_menu']['icon_url'],
                    // $position (int) Position in the WP Admin menu order
                    $this->page['main_menu']['position']
                    );
        }
        
    }
    
    /**
     * Adds the First Submenu Settings Page to the Parent Settings Menu
     * 
     * @since   0.0.0
     * 
     * @uses    add_submenu_page()
     * @link    http://codex.wordpress.org/Function_Reference/add_submenu_page
     */
    public function maybe_add_first_submenu() {
       
           
        if ( ( 'main_page' == $this->page_type ) && 
                isset( $this->page['first_submenu'] ) && 
                is_array( $this->page['first_submenu'] ) ) {             
            
            $this->page_hook = add_submenu_page (
                    // $parent_slug Parent menu's slug name
                    $this->page_id,
                    // $page_title Text in <title> tag
                    $this->page['first_submenu']['page_title'],
                    // $menu_title Text shown on the admin menu
                    $this->page['first_submenu']['menu_title'], 
                    // $capability must be 'manage_options'
                    $this->page['first_submenu']['capability'],
                    // $menu_slug This submenu's slug name
                    $this->page_id,
                    // $function Callback function to display this page
                    array( &$this, 'display_page_callback' )
                    );
            
            // Load the styles and scripts
            $this->after_page_add_hooks();             
        }
    }
    
    /**
     * Adds a Submenu Settings Page to the Parent Settings Menu
     * 
     * @since   0.0.0
     * 
     * @uses    add_submenu_page()
     * @link    http://codex.wordpress.org/Function_Reference/add_submenu_page
     */
    public function maybe_add_submenu() {
  
            
        if ( ( 'sub_page' == $this->page_type ) && 
                isset( $this->page['submenu'] ) && 
                is_array( $this->page['submenu'] ) ) {  
            
            $this->page_hook = add_submenu_page (
                    // $parent_slug Parent menu's slug name
                    $this->page['submenu']['parent_slug'],
                    // $page_title Text in <title> tag
                    $this->page['submenu']['page_title'],
                    // $menu_title Text shown on the admin menu
                    $this->page['submenu']['menu_title'], 
                    // $capability must be 'manage_options'
                    $this->page['submenu']['capability'],
                    // $menu_slug This submenu's slug name
                    $this->page_id,
                    // $function Callback function to display this page
                    array( &$this, 'display_page_callback' )
                    );
            
            // Load the styles and scripts
            $this->after_page_add_hooks();              
        }
    }
    
    /**
     * Adds a Theme Settings Page to the WordPress Appearance menu
     * 
     * @since   0.0.0
     * 
     * @uses    add_theme_page()
     * @link    http://codex.wordpress.org/Function_Reference/add_theme_page
     */
    public function maybe_add_theme_page() {
        
        if ( ( 'theme_page' == $this->page_type ) && 
                isset( $this->page['theme'] ) && 
                is_array( $this->page['theme'] ) ) {          
        
            $this->page_hook = add_theme_page (
                    // $page_title Text in <title>
                    $this->page['theme']['page_title'],
                    // $menu_title Text shown on the admin menu
                    $this->page['theme']['menu_title'], 
                    // $capability must be 'manage_options'
                    $this->page['theme']['capability'],
                    // $menu_slug This submenu's slug name
                    $this->page_id,
                    // $function Callback function to display this page
                    array( &$this, 'display_page_callback' )
                    ); 

            // Load the styles and scripts
            $this->after_page_add_hooks();  
        }
       
    }
    
    
    /**
     * Hooks to load after the page has been added, as we need to wait until
     * we have a page hook
     * 
     * @since 0.0.0
     */
    private function after_page_add_hooks() {
        // Load scripts and styles    
        add_action( "admin_print_scripts-{$this->page_hook}", 
                array( &$this, 'load_scripts' ) );
        add_action( "admin_print_styles-{$this->page_hook}", 
                array( &$this, 'load_styles' ) ); 

        // Load the Help Tab
        add_action( 'load-' . $this->page_hook,  array( $this, 'help_tab' ) ); 
        
    }
    
    /**
     * Registers the setting for this Settings Page
     * Note:    This is only called for Parent Menu or Theme Options Page.
     *          Submenu pages are linked to the Parent Menu. 
     * 
     * @since   0.0.0
     * 
     * @uses    register_setting()
     * @link    http://codex.wordpress.org/Function_Reference/register_setting
     */      
    public function register_setting() {

        // Register the setting
        register_setting(
                // Settings group name.  It has to match the group name in
                // the settings_fields(), as all the settings are stored in
                // the database using this name.
                $this->settings_group, 
                $this->settings_group );
        
        /**
         * Instead of a callback, we are hooking into the sanitize_option_{$option}
         * filter to both validate and sanitize the options.
         */
        $this->add_sanitize_option_filter();
        
        // Add the sections
        $this->add_sections();
        
        // Define the form fields
        $this->add_settings_field();
       
    }   
    
    /**
     * Adds groups of settings on the settings page
     * 
     * @since   0.0.0
     * 
     * @uses    add_settings_section()
     * @link    http://codex.wordpress.org/Function_Reference/add_settings_section
     */    
    private function add_sections() {
        
        foreach ( $this->sections as $id => $title ) {
                      
            add_settings_section( 
                    // $id (str) "id" attribute of tags
                    $id, 
                    // $title (str) Section's title
                    $title, 
                    // $callback To display the section's content
                    array( &$this, 'display_section_callback' ),
                    // $page (str) $menu_page slug on which to display this section 
                    $this->page_id
                    );
        }        
        
    }
    
    /**
     * Define the form fields for the Settings Page and sections by 
     * adding all the settings to the appropriate section
     *
     * @since   0.0.0
     * @link    http://codex.wordpress.org/Function_Reference/add_settings_field  
     */
    private function add_settings_field() {

        foreach ( $this->default_settings as $id => $setting ) {
           
            //$setting['id'] = $id;
	
            /**
             * Parse the setting through the default_settings
             * If setting is not set, it'll be set to the default.
             */
            $setting = wp_parse_args( 
                    $setting, 
                    $this->default_structure['default_settings']['default_field'] 
                    );

            // callback args
            $args = array(
                    'id'            => $id,
                    'label_for'     => $id,
                    'title'         => $setting['title'],
                    'desc'          => $setting['desc'],
                    'default'       => $setting['default'],
                    'type'          => $setting['type'],                
                    'choices'       => $setting['choices'],
                    'class'         => $setting['class'],
                    'section_id'    => $setting['section_id'],
                    'style'         => $setting['style'],
                    'placeholder'   => $setting['placeholder']
            );
		           
            add_settings_field( 
                    // $id (str) tags attribute
                    $id, 
                    // $title (str) field title
                    $setting['title'], 
                    // $callback
                    array( $this, 'display_setting_callback' ), 
                    // $page (str) Menu page on which to display this field 
                    $this->page_id, 
                    // $section (str) section for this field
                    $setting['section_id'],
                    // $args (array) Args passed to the $callback function
                    $args 
                    );

        }

    }    

    /**
     * Initialize settings - settings are not in the Options database yet.
     * Let's build up the option/value pair and then store them.
     * 
     * @since 0.0.0
     * 
     * @uses update_option()
     * @link http://codex.wordpress.org/Function_Reference/update_option
     */
    private function init_settings() {
       
        if ( empty( $this->defaults ) )
            return FALSE;
        /**
         * update_option() first checks to see if the option already exists
         * in the options database.  If no, then it adds the new option; 
         * else, it updates the existing option.
         */
        return update_option( $this->settings_group, $this->defaults ); 

    } 
    

    /**
     * If defined in the $config file, build and render the Help Tab for
     * this Settings Page.
     * 
     * @uses help_tab()
     * @return
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_help_tab
     */
    public function help_tab() {
        
        $screen = get_current_screen();
        
        /*
         * Check if current screen is My Admin Page
         * Don't add help tab if it's not
         */
        if ( $screen->id != $this->page_hook )
            return;        
        
        if ( isset( $this->form['help_file'] ) && file_exists( $this->form['help_file'] ))           
            require( $this->form['help_file'] );
            
    }    

    /**
     * 
     * http://return-true.com/2010/01/using-ajax-in-your-wordpress-theme-admin/
     */
    public function page_head() {
        
     
        ?>

        <script type="text/javascript">
            
            jQuery(document).ready( function($) {
             
                var form = $( 'form#<?php echo $this->form['id']; ?>' );
                var settingsGroup = '<?php echo $this->settings_group ?>';
                var reset = false;
                var data;
                var resetPopup = $( '#river-popup-reset' )
                
                <?php if( isset( $_REQUEST['reset'] ) ) { ?>
                    reset = true;                   
                <?php } ?>
                    
                if ( reset ) {

                    var popup = $( '#river-popup-reset' );   

                    resetPopup.fadeIn();

                    window.setTimeout(function() {
                        resetPopup.fadeOut();
                        reset = false;                        
                    }, 2000);                    
                }
                
                //Update Message popup
                $.fn.center = function () {
                    this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
                    this.css( "left", 250 );
                    return this;
                }

                $('#river-popup-save').center();
                resetPopup.center();
                $(window).scroll(function() {
                    $('#river-popup-save').center();
                    resetPopup.center();

                });
                
                form.children('footer').find('input.reset-button').on("click", function() {
                    url = '?page=<?php echo $this->page_id?>&reset=true';
                    window.location =  url;                   
                    //location.reload();
                    //ajaxSave( 'reset', form.serialize() );
                });
                
                //Save everything else
                form.submit( function() {
                    
                    var unchecked = '';                   
                    var loc = window.location.search.split('&');
                    
                    //if url has '&reset=true', remove it
                    if ( $.isArray(loc) && loc.length >= 2  ) {
                        river.pushState( loc[0] );
                    }
                    
                    // Get the unchecked checkboxes, i.e. to add to the data string
                    form.find( 'input[type=checkbox]' ).not( ':checked' ).each( function() {
                        unchecked += '&' + settingsGroup + '%5B' + $(this).attr('id') + '%5D=0';
                    });
                    		                    
                    //data =  $(this).serialize();
                    ajaxSave( 'save', 'settings_group=' + settingsGroup + '&' + 
                        $(this).serialize() + unchecked );

		    return false;
                });
                
                ajaxSave = function( action, inputData ) {

                    $( '.ajax-loading-img').fadeIn();                  
                    <?php // Nonce Security
                    if ( function_exists( 'wp_create_nonce' ) ) 
                        $river_nonce = wp_create_nonce( $this->settings_group . '-options-update' );
                    ?>                    
                    data = {
                        /**
                         * We are straying from the standard 'GET' or 'POST'
                         * settings here for 'type' to redefine it for passing
                         * this page's settings_group.
                         */
                        type: '<?php echo $this->settings_group ?>',
                        data: inputData,
                        action: 'river_ajax_save',
                        _ajax_nonce: '<?php echo $river_nonce; ?>'
                    }                    
                    //$.post( 'options.php', data, function(response) {
                    $.post( ajaxurl, data, function(response) {
                        var loading = $( '.ajax-loading-img' );
                        var popup;
                        if( 'save' == action && response) {
                            popup = $( '#river-popup-save' );
                              
                        } else if ( ! response ) {
                            popup = $( '#river-popup-error' );
                        }
                        popup.fadeIn();

                        loading.fadeOut();

                        window.setTimeout(function() {
                           popup.fadeOut();
                        }, 2000);
                    });
                       
                }                

            });
        </script>  

        <?php         
        
    } 
    
    /**
     * AJAX Save Callback to save all the form's settings|options.
     * 
     * Note:    This callback is not linked to the class object.  Therefore, we
     *          cannot call $this->settings_group.  To compensate, $this->settings_group
     *          is stored $_POST['type'].
     */
    function ajax_save_callback() {
        
        $response = FALSE;              
         
        if( isset( $_POST['type'] ) ) {
            
            $settings_group = $_POST['type'];
        
            // check security with nonce.
            if ( function_exists( 'check_ajax_referer' ) )
                check_ajax_referer( $settings_group . '-options-update', '_ajax_nonce' );

            $data = maybe_unserialize( $_POST['data'] );

            // Check if the data is an array
            if ( is_array( $data ) ) {
                $passed_data = $data;
            // No, so parse it out.
            } else {
                parse_str( $data, $passed_data );
            }
            
            // One more security check
            if ( $settings_group == $passed_data['settings_group'] ) {
                
                $options = get_option( $settings_group );

                $newvalue = wp_parse_args(
                            $passed_data[$settings_group], 
                            $options );  
                
                // Time to update the option database and report the response
                if( is_array( $newvalue ) & ! empty( $newvalue ) )
                    $response = update_option( $settings_group, $newvalue ) ? TRUE : FALSE;
            }

        }
        
        // Pass the response back to AJAX
        die( $response );
    }

    /** Callbacks *************************************************************/    
    
    /**
     * Display/Render the page's HMTL
     * 
     * @since   0.0.0
     * 
     */
    public function display_page_callback() {

        require( RIVER_ADMIN_DIR . '/views/display-page.php');        
        
    } 
    
    /**
     * Display a subheader on each section, such as a description or more
     * information
     * 
     * @since   0.0.0
     * 
     */
    public function display_section_callback() {
        
        
        
    }

    /**
     * Display the setting option on its section and page
     * 
     * @since   0.0.0
     * @param array     $args The option to display
     */
    public function display_setting_callback( $setting ) {
   
        river_admin_display_settings( $this->settings_group, $setting );
    }
    
    /**
     * Callback function for register_setting() to sanitize options' values.
     * 
     * Validate and whitelist user-input data before updating the options
     * database.  Only whitelisted options are passed back to the database,
     * and user-input data for all whitelisted options are santized.
     *
     * @since 0.0.0
     * 
     * @param array     $input  Incoming raw user-input data
     * @return array    $input  Sanitized user-input data passed back to the
     *                          database
     * 
     * @link http://codex.wordpress.org/Data_Validation
     * @link http://wordpress.stackexchange.com/questions/61024/default-wordpress-settings-api-data-sanitization
     */
    public function sanitizer_callback( $input ) {
//        return $input;
        
        $old_value = get_option( $this->settings_group );

        return $this->validate_sanitize( $input, $old_value );
  
    } 
  
    /** Helper Functions ******************************************************/
    
    /**
     * Media Uploader setup
     * 
     * @since   0.0.0
     * 
     * @global type $pagenow
     * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
     */
    public function uploader_setup() {
	global $pagenow;
        
	if ( ( 'media-upload.php' == $pagenow ) || 
                ( 'async-upload.php' == $pagenow )) 
            add_filter( 'gettext', array( $this, 'replace_thickbox_text' ), 1, 3 );
	
    }    
    
    /**
     * To avoid confusion for the user, let's replace out Thickbox's button
     * text "Insert into Post" with something that makes more sense.
     * 
     * @since   0.0.0
     * 
     * @param string $translated_text
     * @param string $original_text
     * @return string Changed button text
     * 
     * @link http://codex.wordpress.org/Function_Reference/wp_get_referer
     * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
     */
    function replace_thickbox_text( $translated_text, $original_text, $domain ) {
        
        if ( 'Insert into Post' == $original_text ) {

            if ( '' != strpos( wp_get_referer(), $this->page_id )  )
                return __('Load this one', 'river' );
        }

        return $translated_text;
    
    }    

    
    /**
     * Enqueue the Style files
     * 
     * @since   0.0.0
     * 
     * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style
     */
    public function load_styles() {
        
        add_action( 'admin_head', array( &$this, 'page_head' ), 10 );
        

        wp_register_style( 'river_admin_css', RIVER_ADMIN_URL . '/assets/css/admin-river.css' );  
        wp_enqueue_style( 'river_admin_css' );
        
        // Media Uploader Stylesheet
        wp_enqueue_style( 'thickbox' );
        
    }
    
    /**
     * Enqueue the script files
     * 
     * @since   0.0.0
     * 
     * @uses    RIVER_ADMIN_URL
     * @uses    RIVER_VERSION
     * @uses    wp_enqueue_script()
     * @uses    wp_register_script()
     * @link    http://codex.wordpress.org/Function_Reference/wp_register_script
     */
    public function load_scripts() {
        
        wp_register_script( 
                'river-admin', 
                RIVER_ADMIN_URL . '/assets/js/river-admin.js', 
                array( 'jquery', 'media-upload', 'thickbox' ), 
                '', 
                true); 
        // @link http://jscolor.com/
        wp_register_script( 'jscolor', RIVER_ADMIN_URL . '/assets/js/jscolor.js', '', '', true);           

        wp_enqueue_script('jquery');
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'media-upload' );
        
        wp_enqueue_script( 'river-admin' );
        wp_enqueue_script( 'jscolor' );
        
    }
    
  
} // end of class

endif; // end of class_exists