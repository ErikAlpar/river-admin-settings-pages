<?php

/**
 * Validates the incoming Settings Page configuration file.  It also
 * validates and sanitizes the default settings.
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Config Validator Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Admin_Config_Validator' ) ) :
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
abstract class River_Admin_Config_Validator extends River_Settings_Sanitizer {
    
    /** Class Parameters ******************************************************/
    
    /**
     * Associative array to define the default config structures
     * 
     * @since 0.0.0
     * @var array
     */
    protected $default_structure; 
    
    /**
     * Associative array to define the default choices structure
     * 
     * @since 0.0.0
     * @var array
     */
    protected $default_choices = array(
            'name'      => '',
            'value'     => '',
            'args'      => '',           
    );
    
    /**
     * Associative array to define the IMG args for default choices structure
     * 
     * @since 0.0.0
     * @var array
     */    
    protected $default_choices_img = array(
            'type'      => 'img',
            'value'     => '',          // src
            'title'     => '',          
            'alt'       => '',        
    );
    
    /**
     * Associative array to define the URL args for default choices structure
     * 
     * @since 0.0.0
     * @var array
     */    
    protected $default_choices_url = array(
            'type'      => 'url',
            'value'       => '',        
    );
    
    /**
     * Available Setting Page Types
     * 
     * @since 0.0.0
     * @var array 
     */
    protected $available_types = array( 'main_page', 'sub_page', 'theme_page' );
    
    /** Class Methods *********************************************************/    
    
    /**
     * Validate each portion of the $config array, load up the properties, and
     * then report results.
     * 
     * @since 0.0.0
     * 
     * @param array     $config Incoming config to validate
     * @return boolean  TRUE if the config is valid & loaded; else FALSE;
     */
    protected function config_is_valid( $config ) {
        
        if( ! $config['id'] || ! $config['page_config']['id'] )        
            wp_die( sprintf( __( 'Settings page ID is not defined in %s or %s', 
                    'river' ), '$config[\'id\']',  
                    '$config[\'page_config\'][\'id\']' ) );        

        if( array_key_exists( $config['type'], River_Sanitizer::$available_types ) )
            wp_die( sprintf( __( 'Invalid page type in %s', 
                    'river' ), '$config[\'type\']' ) ); 

        
        // Setup the filter defaults
        $this->setup_filter_defaults();
        
        // If $default_structure is not set, do it now.
        $this->structure_is_set();
        
        $this->page_id          = $this->page_id        ? $this->page_id        : $this->validate_page_id( $config['id'] );
        
        $this->settings_group   = $this->settings_group ? $this->settings_group : $this->validate_settings_group( $config['settings_group'] );        
        $this->page_type        = $this->page_type      ? $this->page_type      : $this->validate_page_type( $config['type'] );
        
        // Return if there's no page ID, settings group, or page type
        if( ! $this->page_id || ! $this->settings_group || ! $this->page_type )
            return FALSE;

        $this->form             = $this->form           ? $this->form           : $this->validate_form( $config['form'] );
        $this->page             = $this->page           ? $this->page           : $this->validate_page( $config['page_config'] );
        $this->sections         = $this->sections       ? $this->sections       : $this->validate_sections( $config['sections'] );
        $this->default_settings = $this->default_settings ? $this->default_settings : $this->validate_default_settings( $config['default_settings'] );
        $this->defaults         = $this->defaults       ? $this->defaults       : $this->validate_defaults( $config['default_settings'] );    
        
        // Check that everything is loaded. Return either TRUE or FALSE.
        return ($this->form && $this->page && $this->sections && $this->default_settings && $this->defaults ) ? TRUE : FALSE;

    }
    

    /**
     * Validates the given Page ID, which must be a string and be set
     * 
     * @since 0.0.0
     * 
     * @param string    $id Page ID to validate
     * @return string   Returns either the incoming $id or ''
     */
    protected function validate_page_id( $id ) {
                
        return isset( $id ) && is_string( $id ) ? $id : '';
        
    }
    
    /**
     * Validates the given settings group, which must be a string and be set
     * 
     * @since 0.0.0
     * 
     * @param type      $settings Settings Group to validate
     * @return string   Returns either the incoming $settings or ''
     */
    protected function validate_settings_group( $settings ) {
        
        return isset( $settings ) && is_string( $settings ) ? $settings : '';
    }
    
    /**
     * Validates the given page type, which must be set, be a string, and be
     * in the default structure definition
     * 
     * @since 0.0.0
     * 
     * @param type      $type Page Type to validate
     * @return string   Returns either the incoming $type or ''
     */    
    protected function validate_page_type( $type ) {
        
        if( ! isset( $type ) || ! is_string( $type ) )
            return '';
        
        return in_array( $type, $this->available_types ) ? $type : '';
        
    }
    
    /**
     * Validates the given form, which must be set and be an array.  To ensure
     * the structure is complete, we pass it through with wp_parse_args()
     * 
     * @since 0.0.0
     * 
     * @uses wp_parse_args()
     * 
     * @param type      $form Form to validate
     * @return string   Returns either the incoming $form or ''
     */      
    protected function validate_form( $form ) {
        
        return isset( $form ) && is_array( $form ) ? wp_parse_args(
                        $form, $this->default_structure['form'] ) : '';          
    }
        
    /**
     * Validate the given page, which must be set and be an array. Each portion
     * of the array is also validated.  To ensure the structure is complete, 
     * we pass it through with wp_parse_args()
     * 
     * @since 0.0.0
     * 
     * @uses wp_parse_args()
     * 
     * @param type      $page Page to validate
     * @return string   Returns either the incoming $page or ''
     */     
    protected function validate_page( $page ) {

        $valid = array();
        
        // If $default_structure is not set, do it now.
        $this->structure_is_set();        
        
        $valid['id'] = $page['id'];
        
        // Validate and then load up the page configurations
        if ( ( 'main_page' == $this->page_type ) && 
                isset( $page['main_menu'] ) && is_array( $page['main_menu'] ) &&
                isset( $page['first_submenu'] ) && is_array( $page['first_submenu'] )) {
            
            $valid['main_menu'] = wp_parse_args(
                    $page['main_menu'], 
                    $this->default_structure['page_config']['main_menu'] );
            
            $valid['first_submenu'] = wp_parse_args(
                    $page['first_submenu'], 
                    $this->default_structure['page_config']['first_submenu'] );             
            
        } elseif ( ( 'sub_page' == $this->page_type ) && 
                isset( $page['submenu'] ) && is_array( $page['submenu'] ) ) {

            $valid['submenu'] = wp_parse_args(
                    $page['submenu'], 
                    $this->default_structure['page_config']['submenu'] );              
            
        } elseif ( ( 'theme_page' == $this->page_type ) && 
                isset( $page['theme'] ) && is_array( $page['theme'] ) ) {
            
            $valid['theme'] = wp_parse_args(
                    $page['theme'], 
                    $this->default_structure['page_config']['theme'] );              
            
        } else {
            wp_die( sprintf( __( 'Oops need to specify all the %s parameters', 
                        'river' ), '$config[\'page_config\']' ) ); 
            return '';
        }  
        
        return $valid;
    }
    
    /**
     * Validate the given sections, which must be set and be an array.
     * To ensure the structure is complete, we pass it through with wp_parse_args()
     * 
     * @since 0.0.0
     * 
     * @uses wp_parse_args()
     * 
     * @param type      $sections Sections to validate
     * @return string   Returns either the incoming $sectione or ''
     */       
    protected function validate_sections( $sections ) {
        
        // If $default_structure is not set, do it now.
        $this->structure_is_set();
        
        // Load up the sections
        if ( isset( $sections ) && is_array( $sections ) ) {
            
            return wp_parse_args(
                    $sections, 
                    $this->default_structure['sections'] ); 
        } else {
            wp_die( sprintf( __( 'Oops need to specify at least one section in %s', 
                        'river' ), '$config[\'sections\']' ) ); 
            return '';            
        }
        
        // We should never get here.
        return '';
    }
    
    /**
     * Validate the given default settings.  As this is a multi-dimensional
     * associative array, we do a deep validation. To ensure the structure is 
     * complete, we pass it through with wp_parse_args()
     * 
     * @since 0.0.0
     * 
     * @uses wp_parse_args()
     * 
     * @param type      $defaults Default Settings to validate
     * @return string   Returns either the incoming $defaults or ''
     */    
    protected function validate_default_settings( $defaults ) {

        
        // If $default_structure is not set, do it now.
        $this->structure_is_set();
        
        $valid = array();
        
        // Loop through each of the default_settings
        foreach( $defaults as $key => $setting ) { 
            
            if ( 'heading' != $setting['type']  )
                $this->defaults[$key] = $setting['default'];            

            // If no sanitizer filter was defined, need to attach one
            // by the HTML element 'type'
            if( ! isset( $setting['sanitizer_filter'] ) ) {
                
                foreach( $this->sanitizer_filters as $filter => $type ) {

                    // if this setting's type is in the sanitizer array,
                    // save it
                    if( in_array( $setting['type'], $type ) ) {                    
                        $setting['sanitizer_filter'] = $filter;
                        break; // found it, break out of loop
                    }
                }
            }
            
            // If no validatorr filter was defined, need to attach one
            // by the HTML element 'type'
            if( ! isset( $setting['validator_filter'] ) ) {
                
                foreach( $this->validator_filters as $filter => $type ) {

                    // Check the type and class against the validator filters
                    if( in_array( $setting['type'], $type ) || 
                            ( isset( $setting['class'] ) && in_array( $setting['class'], $type ) ) ) {                    
                        $setting['validator_filter'] = $filter;
                        break; // found it, break out of loop
                    }
                }
            }            
            
            // Make sure the choices are structured properly
            if( isset( $setting['choices'] ) && is_array( $setting['choices'] ) ) {
                
                foreach( $setting['choices'] as $ckey => $choice ) {

                    // First check to see if 'args' is set
                    if( isset( $choice['args'] ) && 
                            is_array( $choice['args'] ) ) {
                        
                        // We'll set it up as a switch so that we can easily
                        // add more types here
                        switch ( $choice['args']['type'] ) {
                            
                            case 'img':
                                $setting['choices'][$ckey]['args'] = wp_parse_args(
                                        $choice['args'],
                                        $this->default_choices_img
                                );
                                break;
                            
                            case 'url':    
                                $setting['choices'][$ckey]['args'] = wp_parse_args(
                                        $choice['args'],
                                        $this->default_choices_url
                                );                                
                                break;
                            default:
                                
                        }    
                    }
                       
                    $setting['choices'][$ckey] = wp_parse_args(
                            $choice,
                            $this->default_choices
                    );                        
                    
                }
                
            }            

            // Load into default_settings    
            $valid[$key] = wp_parse_args(
                $setting, 
                $this->default_structure['default_settings']['default_field'] ); 
        }  
        
        // Just in case something went wrong with the default settings, set
        // $this->defaults to null
        $this->defaults = $valid ? $this->defaults : null;
        return $valid;
        
    }
    
    /**
     * Validate the given default settings. Build the name/value pairs for
     * the associative array.
     * 
     * @since 0.0.0
     * 
     * @uses wp_parse_args()
     * 
     * @param type      $defaults Default Settings to validate
     * @return string   Returns either the name/value array or ''
     */      
    protected function validate_defaults( $defaults ) {

        $valid = array();
        
        // Loop through each of the default_settings
        foreach( $defaults as $key => $setting ) { 
            
            if ( 'heading' != $setting['type']  )
                $valid[$key] = $setting['default']; 
        }
        
        return $valid ;
    }
    
    /**
     * Determine if the $this->default_structure is set.  If no, set it.
     * 
     * @since 0.0.0
     * 
     * @return bool     TRUE If the default structure is set; else FALSE
     */
    protected function structure_is_set() {
        
        // If $default_structure is not set, do it now.
        isset( $this->default_structure ) ? '' : $this->setup_structures();
        
        return isset( $this->default_structure ) ? TRUE : FALSE;
    }
    
    /**
     * Setup the pre-defined default structures for this class
     * 
     * @since   0.0.0
     * @return array    Associative array
     */
    protected function setup_structures() {
        
        $this->default_structure =  apply_filters(
                'river_settings_default_structure',
                array(
                    'settings_group'        => RIVER_SETTINGS,
                    'type'                  => '', 
                    'form'  => array(
                        'id'                => 'river',
                        // Displayed under the page title
                        'version'           => '',
                        // Site URL
                        'site_url'          => 'http://coderiverlabs.com',
                        // Changelog URL
                        'changelog_url'     => '/themes/river/docs/#changelog',
                        // Docs URL
                        'docs_url'          => '/themes/river/docs/',
                        // Forum URL
                        'forum_url'         => '/support/forums/',
                        // Save button text
                        'button_save_text'  => __( 'Save All Changes', 'river' ),
                        'notices'           => array(
                            'saved_notice'  => __( 'Settings Saved', 'river'),
                            'reset_notice'  => __( 'Settings Reset to Default', 'river'),
                            'error_notice'  => __( 'Error saving settings', 'river'),
                        ),
                        'help_file'         => '',
                    ),
                    'page_config' => array(
                        // id for this settings page
                        'id'                => '',
                        'main_menu' => array(
                            'page_title'    => 'River Settings',
                            'menu_title'    => 'River',
                            'capability'    => 'manage_options',
                            'menu_slug'     => 'river',
                            'icon_url'      => '',
                            'position'      => '58.996',
                            'separator'     => array(
                                'position'  => '58.995',
                                'capability'=> 'edit_theme_options',
                            ),                    
                        ),
                        'first_submenu'   => array(
                            'parent_slug'   => 'river',
                            'page_title'    => __( 'River Settings', 'river' ),
                            'menu_title'    => __( 'River Settings', 'river' ),
                            'capability'    => 'manage_options',
                            'menu_slug'     => 'river_settings',  
                        ),
                        'submenu'   => array(
                            'parent_slug'   => 'river',
                            'page_title'    => '',
                            'menu_title'    => '',
                            'capability'    => 'manage_options',
                            'menu_slug'     => '',  
                        ),
                        'theme' => array(
                            'page_title'    => '',
                            'menu_title'    => '',
                            'capability'    => 'manage_options',
                            'menu_slug'     => '',                    
                        ),
                    ),
                    'sections' => array(),
                    'default_settings' => array(
                        // id for this setting and it's array
                        'default_field' => array(
                            // settings ID for settings array & HTML element
                            'id'                => 'default_field',
                            // element's label
                            'title'             => __( 'Default Field', 'river' ),
                            // (opt) description displayed under the element
                            'desc'              => __( 'This is a default description.', 'river' ),
                            // element's default value
                            'default'           => '',
                            // HTML element type
                            'type'              => 'text',
                            // section these are assigned to
                            'section_id'        => 'general',
                            // (opt) choice options for selectable elements, eg.
                            // select box, radio buttons, etc.
                            'choices'           => '',
                            // (opt) add a custom class to the HTML element
                            'class'             => '', 
                            'placeholder'       => '',                    
                            // Sanitization filter: 
                            // (optional) binary, integer, no_html, safe_html, unfiltered_html
                            'sanitizer_filter'  => 'no_html',
                            'validator_filter'  => 'string',
                            // CSS Styling to be included in the HTML element
                            'style'             => '',
                        ),
                    )
                )
            );  
     
    }     

} // end of class

endif; // end of class_exists
