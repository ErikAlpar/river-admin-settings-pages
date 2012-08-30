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
abstract class River_Admin_Settings_Page extends River_Settings_Config {
 
    
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
    public function create( $config ) {
        
        // Validate the incoming $config. If valid, continue; else return.
        if( ! $this->load_config($config) )
            return;
        
        $this->hooks();
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
        
        add_action( "wp_ajax_river_{$this->settings_group}", array( &$this, 'ajax_save_callback' ) );                   
               
    }
    
    /**
     * Page request handler
     * 
     * Handles resetting the settings in the options database
     * 
     * @since 0.0.1
     * 
     * @return
     */
    public function page_request_handling() {
        
        if ( ! river_is_menu_page( $this->page_id ) )
            return;
        
        if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == $this->page_id ) {
            
            if( isset( $_REQUEST['reset'] ) ) {

                $options = get_option( $this->settings_group );
                
                if( $options !== $this->defaults )       
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
        add_action( "load-{$this->page_hook}",  array( $this, 'help_tab' ) ); 
        
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
        
        if ( ! get_option( $this->settings_group ) )
            $this->init_settings(); 
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
        
        $options = get_option( $this->settings_group );
         
        /**
         * Only update the options database if the default settings are
         * not equal to what is already in the db
         */
        return $options === $this->defaults ? TRUE : 
            update_option( $this->settings_group, $this->defaults ); 

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
     * @since   0.0.3
     * 
     */
    abstract public function display_section_callback();

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
     * AJAX Save Callback to save all the form's settings|options.
     * 
     * Note:    This callback is not linked to the class object.  Therefore, we
     *          cannot call $this->settings_group.  To compensate, $this->settings_group
     *          is stored $_POST['type'].
     * 
     * @since 0.0.3
     */
    function ajax_save_callback() {
        
        $response = '';              
         
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
                
                $new_value = $passed_data[$settings_group];

                if( !isset( $new_value ) || ! is_array( $new_value ) || empty( $new_value ) ) {
                    die( $response );
                    return;
                } 
                
                // Get the current options db values
                $options = get_option( $settings_group );                

                // Compare $new_value keys against the defaults.  If there are
                // differences, something is wrong. Just return & report the error.
                $key_diff = array_diff_key( $new_value, $options );
                if ( $key_diff ) {
                    die( $response );
                    return;
                }
                
                // If the new values are identical to the current options db
                // no need to save.
                if ( $new_value === $options ) {
                    $response = 'save';
                } else {  
                    $response = update_option( $settings_group, $new_value ) ? 'save' : 'error';
                    
                    /**
                     * Check if the new_value is identical to what is
                     * currently in the options db (this is set in class-settings-sanitizer).
                     * If yes, then update_option will return FALSE, because
                     * it did not update.  FALSE in this case is NOT an error.
                     * Therefore, change $response to TRUE.
                     */                    
                    if( isset( $GLOBALS['river-is-seetings-identical-to-db'] ) &&
                           $GLOBALS['river-is-seetings-identical-to-db'] && 
                            'error' == $response )
                        $response = 'nosave';
                    
                }
            }
        }
        
        // Pass the response back to AJAX
        die( $response );
    }    
  
    /** Helper Functions ******************************************************/
    
    /**
     * Build and render the Help Tab for this Settings Page.
     * 
     * This method must be defined in the extended class(es)
     * 
     * @since 0.0.3
     */ 
    abstract public function help_tab();    
    
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
    public function replace_thickbox_text( $translated_text, $original_text, $domain ) {
        
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
        
        wp_register_style( 'river_admin_css', RIVER_ADMIN_URL . '/assets/css/admin-river.css' );  
        wp_enqueue_style( 'river_admin_css' );
        
        // Media Uploader Stylesheet
        wp_enqueue_style( 'thickbox' );
        
    }
    
    /**
     * Enqueue the script files
     * 
     * @since   0.0.3
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
        wp_register_script( 'river-admin-ajax', RIVER_ADMIN_URL . '/assets/js/river-admin-ajax.js' );

        wp_enqueue_script('jquery');
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'media-upload' );
        
        wp_enqueue_script( 'river-admin' );
        wp_enqueue_script( 'jscolor' );
        wp_enqueue_script( 'river-admin-ajax' );
        
        if ( function_exists( 'wp_create_nonce' ) ) 
            $river_nonce = wp_create_nonce( $this->settings_group . '-options-update' );        

        // Variables to pass to the riverAdminAjax script
        $pass_to_script = array(
            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
            'resetRequest'      => isset($_REQUEST['reset']) ? $_REQUEST['reset'] : '',
            'formID'            => $this->form['id'],
            'settingsGroup'     => $this->settings_group,
            'pageID'            => $this->page['id'],
            'riverNonce'        => $river_nonce
        );
        wp_localize_script( 'river-admin-ajax', 'riverAdminAjax', $pass_to_script );
        
    }
    
  
} // end of class

endif; // end of class_exists