<?php

/**
 * Example Metabox Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Metabox Example
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Metabox_Example' ) ) :
/**
 * Class for handling a Metabox.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 * 
 * @link    http://codex.wordpress.org/Settings_API
 */
class River_Metabox_Example extends River_Admin_Metabox {
    
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
    public function __construct() { 
        
        $config = array(
            'id'                => 'river_example',
            'title'             => 'River Example Metabox',
            'post_type'         => 'all',
            'context'           => 'advanced',
            'priority'          => 'default',
            'callback_args'     => '',
            'default_fields'    => array(),
        );
        
        // Example 'checkbox' for a HTML checkbox field
        $config['default_fields']['river_example_checkbox'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_example_checkbox',
            // element's label
            'title'             => __( 'Example Checkbox Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Checkbox input.', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 1,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => '',
        );
                
        $config['default_fields']['river_example_colorpicker'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_colorpicker',
            // element's label
            'title'             => __( 'Example Colorpicker Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Click on the field to select the new color.', 'river' ),
            // default value
            'default'           => 'FFFFFF',        
            // HTML field type
            'type'              => 'colorpicker',        
            // section these are assigned to
            'section_id'        => '',
        );
        
        $config['default_fields']['river_example_datepicker'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_datepicker',
            // element's label
            'title'             => __( 'Example Date Picker', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Click to select a date.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'datepicker',        
            // section these are assigned to
            'section_id'        => '',
        );        
        
        $config['default_fields']['river_example_email'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_email',
            // element's label
            'title'             => __( 'Example Email Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Email input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'email',        
            // section these are assigned to
            'section_id'        => '',
        );        
        
        $config['default_fields']['river_example_imgselect'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_imgselect',
            // element's label
            'title'             => __( 'Main Layout', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'D is the default set in the Theme Settings.', 'river' ),
            // element's default value
            'default'           => 'default',
            // HTML element type
            'type'              => 'imgselect',
            'section_id'        => '',
            'choices' => array(
                'default' => array(
                    'name'      => 'default',
                    'value'     => __( 'Default', 'river' ),
                    'args'      => array(
                        'type'      => 'img',                  
                        'value'     => RIVER_ADMIN_URL . '/assets/images/default.gif',
                        // Image title
                        'title'     => 'Default', 
                        // Image alt
                        'alt'       => 'Default',
                    ),
                ),
                'layout1' => array(
                    'name'      => 'layout1',
                    'value'     => __( 'Layout 1', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        // Image source URL and filename.  This is the image that
                        // is shown instead of the radio button                    
                        'value'     => RIVER_ADMIN_URL . '/assets/images/content.gif',
                        // Image title
                        'title'     => 'Content', 
                        // Image alt
                        'alt'       => '',                    
                    ),
                ),
                'layout2' => array(
                    'name'      => 'layout2',
                    'value'     => __( 'Layout 2', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/content-sidebar.gif',
                        'title'     => 'Content-Sidebar',          
                        'alt'       => 'Content-Sidebar',                    
                    ),
                ),
                'layout3' => array(
                    'name'      => 'layout3',
                    'value'     => __( 'Layout 3', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/sidebar-content.gif',
                        'title'     => 'Sidebar-Content',          
                        'alt'       => 'Sidebar-Content',                    
                    ),
                ),
                'layout4' => array(
                    'name'      => 'layout4',
                    'value'     => __( 'Layout 4', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/content-sidebar-sidebar.gif',
                        'title'     => 'Content-Sidebar-Sidebar',          
                        'alt'       => 'Content-Sidebar-Sidebar',                    
                    ),
                ),
                'layout5' => array(
                    'name'      => 'layout5',
                    'value'     => __( 'Layout 5', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/sidebar-content-sidebar.gif',
                        'title'     => 'Sidebar-Content-Sidebar',          
                        'alt'       => '',                    
                    ),
                ),
                'layout6' => array(
                    'name'      => 'layout6',
                    'value'     => __( 'Layout 6', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/sidebar-sidebar-content.gif',
                        'title'     => 'Sidebar-Sidebar-Content',          
                        'alt'       => 'Sidebar-Sidebar-Content',                    
                    ),
                ),            
            ),
            'style' => 'display: inline;',
        ); 

        $config['default_fields']['river_example_mc'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_mc',
            // element's label
            'title'             => __( 'Example Multicheck', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the multicheck.', 'river' ),
            // default value MUST be '' or an array, as shown
            'default'           => array( 'box1' => 'box1' ),        
            // HTML field type
            'type'              => 'multicheck',        
            // section these are assigned to
            'section_id'        => 'section1',
            // Define the choices
            'choices' => array(
                'box1'  => array(
                    'name'      => 'box1',
                    'value'     => __( 'Choice 1', 'river' ),
                    'args'      => '',
                ),
                'box2' => array(
                    'name'      => 'box2',
                    'value'     => __( 'Choice 2', 'river' ),
                    'args'      => '',
                ),
                'box3' => array(
                    'name'      => 'box3',
                    'value'     => __( 'Choice 3', 'river' ),
                    'args'      => '',
                ),            
                'box4' => array(
                    'name'      => 'box4',
                    'value'     => __( 'Choice 4', 'river' ),
                    'args'      => '',
                ),
            ),
        );
        
        $config['default_fields']['river_example_radio'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_radio',
            // element's label
            'title'             => __( 'Example Radio', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Radio.', 'river' ),
            // default value MUST be a string and set to once of the choice keys
            'default'           => 'radio1',        
            // HTML field type
            'type'              => 'radio',        
            // section these are assigned to
            'section_id'        => 'section1',
            // Define the choices, minimum of 2+
            'choices' => array(
                'radio1'  => array(
                    'name'      => 'radio1',
                    'value'     => __( 'Choice 1', 'river' ),
                    'args'      => '',
                ),
                'radio2' => array(
                    'name'      => 'radio2',
                    'value'     => __( 'Choice 2', 'river' ),
                    'args'      => '',
                ),
                'radio3' => array(
                    'name'      => 'radio3',
                    'value'     => __( 'Choice 3', 'river' ),
                    'args'      => '',
                ),
            ),
        );

        $config['default_fields']['river_example_select'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_select',
            // element's label
            'title'             => __( 'Example Select', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the drop-down.', 'river' ),
            // default value MUST be a string and set to once of the choices
            'default'           => 'noselect',        
            // HTML field type
            'type'              => 'select',        
            // section these are assigned to
            'section_id'        => 'section1',
            // Define the choices (options), minimum of 2+
            'choices' => array(
                // Inserts a blank option
                'noselect'  => array(
                    'name'      => 'noselect',
                    'value'     => '',
                    'args'      => '',
                ),
                'select1'  => array(
                    'name'      => 'select1',
                    'value'     => __( 'Choice 1', 'river' ),
                    'args'      => '',
                ),
                'select2' => array(
                    'name'      => 'select2',
                    'value'     => __( 'Choice 2', 'river' ),
                    'args'      => '',
                ),
                'select3' => array(
                    'name'      => 'select3',
                    'value'     => __( 'Choice 3', 'river' ),
                    'args'      => '',
                ),
            ),
        ); 

        $config['default_fields']['river_example_text'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_text',
            // element's label
            'title'             => __( 'Example Text Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the text input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => '',
        );
        
        $config['default_fields']['river_example_textarea'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_textarea',
            // element's label
            'title'             => __( 'Example Textarea Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Textarea input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => '',
        ); 
        
        
        $config['default_fields']['river_example_timepicker'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_timepicker',
            // element's label
            'title'             => __( 'Example Time Picker', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Click to select a time.', 'river' ),
            // default value
            'default'           => '', 
            // HTML field type
            'type'              => 'timepicker',        
            // section these are assigned to
            'section_id'        => '',
            'args'  => array(
                /** Options ***************************************************/
                
                // The character to use to separate hours and minutes. (default: ':')
                'time_separator'                => ':',
                //Define whether or not to show a leading zero for hours < 10. (default: true)
                'show_leading_zero'             => 'true',
                // Define whether or not to show a leading zero for minutes < 10. (default: true)
                'show_minutes_leading_zero'     => 'true',
                // Define whether or not to show AM/PM with selected time. (default: false)
                'show_period'                   => 'true',
                // Define if the AM/PM labels on the left are displayed. (default: true)
                'show_period_labels'            => 'true',
                // The character to use to separate the time from the time period.
                'period_separator'              => ' ',
                // Define an alternate input to parse selected time to
                'alt_field'                     => '#alternate_input',
                // Used as default time when input field is empty or for inline timePicker
                // (set to 'now' for the current time, '' for no highlighted time,
                // default value: 'now')
                'default_time'                  => 'now',
                
                /** trigger options *******************************************/
                // Define when the timepicker is shown.
                // 'focus': when the input gets focus, 'button' when the button trigger element is clicked,
                // 'both': when the input gets focus and when the button is clicked.
                'show_on'                       => 'focus',
                // jQuery selector that acts as button trigger. ex: '#trigger_button'
                'button'                        => 'null',
                
                /** Localization **********************************************/
                // Define the locale text for "Hours"
                'hour_text'                     => __( 'Hour', 'river' ),
                // Define the locale text for "Minutes"
                'minute_text'                   => __( 'Minutes', 'river' ),
                // Define the locale text for periods
                'am_pm_text' => array(
                    'am'                        => __( 'AM', 'river' ),
                    'pm'                        => __( 'PM', 'river' ),
                ),
                
                /** Position **************************************************/
                // Corner of the dialog to position, used with the jQuery UI 
                // Position utility if present.
                'my_position'                   => 'left top',
                // Corner of the input to position
                'at_position'                   => 'left bottom',
                
                /** custom hours and minutes **********************************/
                'hours' => array(
                    // First hour to display
                    'starts'                    => 6,
                    // Last hour to display
                    'ends'                      => 17
                ),
                'minutes' => array(
                    // First minute to display
                    'starts'                    => 0,
                    // Last minute to display
                    'ends'                      => 59,
                    // Interval of displayed minutes. 1 = display every minute.
                    // 5 = display every 6 minutes.
                    'interval'                  => 1,
                ),
                // Number of rows for the input tables, minimum 2, makes more 
                // sense if you use multiple of 2
                'rows'                          => 4,
                // Define if the hours section is displayed or not. 
                // Set to 0 to get a minute only dialog
                'show_hours'                    => 'true',
                // Define if the minutes section is displayed or not. 
                // Set to 0 to get an hour only dialog
                'show_minutes'                  => 'true',
                
                /** buttons ***************************************************/
                // shows an OK button to confirm the edit
                'show_close_button'             => 'true',
                // Text for the confirmation button (ok button)
                'close_button_text'             => __( 'Done', 'river' ),
                // Shows the 'now' button
                'show_now_button'               => 'true',
                // Text for the now button
                'now_button_text'               => __( 'Select Current Time', 'river' ),
                // Shows the deselect time button
                'show_deselect_button'          => 'true',
                // Text for the deselect button
                'deselect_button_text'          => __( 'Deselect All', 'river' ),
            ),
        );        
        
        $config['default_fields']['river_example_upload-image'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_upload-image',
            // element's label
            'title'             => __( 'Example upload-image Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Click to select the image.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'upload-image',        
            // section these are assigned to
            'section_id'        => '',
            'placeholder'       => __( 'Click to select the image.', 'river' ),
        ); 
        
        $config['default_fields']['river_example_url'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_example_url',
            // element's label
            'title'             => __( 'Example URL Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the URL input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'url',        
            // section these are assigned to
            'section_id'        => '',
            'placeholder'       => __( 'http://domain.com', 'river'),
        ); 
        
       // Example 'wysiwyg', which is the WordPress visual editor (i.e. TinyMCE)
        $config['default_fields']['example_wysiwyg'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_wysiwyg',
            // element's label
            'title'             => __( 'Example wysiwyg', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the text input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'wysiwyg',        
            // section these are assigned to
            'section_id'        => 'section1',
            // options array
            // @link http://codex.wordpress.org/Function_Reference/wp_editor
            'args'  => array(
                // use wpautop, default is TRUE
                'wpautop'       => TRUE,
                // Whether to display media insert/upload buttons, default is TRUE
                'media_buttons' => TRUE,
                // The name assigned to the generated textarea and passed parameter 
                // when the form is submitted. (may include [] to pass data as array)
                // default: $editor_id
                'textarea_name' => 'example_wysiwyg',
                // The number of rows to display for the textarea
                'textarea_rows' => get_option('default_post_edit_rows', 10),
                // The tabindex value used for the form field, default: none
                'tabindex'      => '',
                // Additional CSS styling applied for both visual and HTML editors 
                // buttons, needs to include <style> tags, can use "scoped"
                'editor_css'    => '',
                // Any extra CSS Classes to append to the Editor textarea
                'editor_class'  => '',
                // Whether to output the minimal editor configuration used 
                // in PressThis.  default: FALSE
                'teeny'         => FALSE,
                // Whether to replace the default fullscreen editor with DFW (needs 
                // specific DOM elements and css).  default: FALSE
                'dfw'           => FALSE,
                // Load TinyMCE, can be used to pass settings directly to TinyMCE 
                // using an array(). default: TRUE
                'tinymce'       => TRUE,
                // Load Quicktags, can be used to pass settings directly to 
                // Quicktags using an array(). default: TRUE
                'quicktags'     => TRUE,
            ),
        );         
        
        $this->create( $config );
    } 
        
} // end of class


add_action( 'after_setup_theme', 'river_add_example_mb' );
/**
 * Adds River top-level item in admin menu.
 *
 * Calls the river_admin_menu hook at the end - all submenu items should be
 * attached to that hook to ensure correct ordering.
 *
 * @since 0.0.0
 *
 * @return null Returns null if River menu is disabled, or disabled for current user
 */
function river_add_example_mb() {

    // Oops not viewing Admin. Just return
    if ( ! is_admin() )
        return;
  
    /**
     * Let's do some checks to ensure we should proceed
     */
    if ( ! current_theme_supports( 'river-metabox-example' ) )
        return; // Programmatically disabled  

    $mb = new River_Metabox_Example();

}

endif; // end of class_exists