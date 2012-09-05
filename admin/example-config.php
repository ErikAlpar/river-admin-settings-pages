<?php

/**
 * This file provides an example of a configuration file showing the structure
 * of each section of the config as well as each type of setting|HTML element
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Example Config
 * @since       0.0.4
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

/**
 * DO NOT LOAD THIS PAGE
 */  

/** Header  *******************************************************************/    

/**
 * The beginning of the config file defines the id, settings_group (which is the
 * name of the options group to be stored in the options database), type of
 * settings page, form definitions, page definitions, and the default settings.
 */
   $config = array(
        // ID of the config file, should match page_config['id']
        'id'                    => '',
        // Unique settings group name
        'settings_group'        => '',
        // Valid Choices are:  main_page, sub_page, theme_page
        'type'                  => '', 
        'form'  => array(
            'id'                => '',
            // Displayed under the page title
            'version'           => '',
            // Save button text
            'button_save_text'  => __( 'Save All Changes', 'river' ),
            'button_reset_text' => __( 'Reset All Options', 'river' ),
            // File URL and filename to the help PHP file
            'help_file'         => '',
        ),
        'page' => array(
            // id for this settings page
            'id'                => '',
            // Define the menus and pages for the corresponding $config['type']
            /** ONLY USE main_menu and first_submenu for main_page type *******/
            'main_menu' => array(
                'page_title'    => '',
                'menu_title'    => '',
                'capability'    => 'manage_options',
                'menu_slug'     => '',
                'icon_url'      => '',
                'position'      => '',
                'separator'     => array(
                    'position'  => '',
                    'capability'=> 'edit_theme_options',
                ),
            ),
            'first_submenu'   => array(
                'parent_slug'   => 'river',
                'page_title'    => '',
                'menu_title'    => '',
                'capability'    => 'manage_options',
                'menu_slug'     => '',  
            ),  
            /** ONLY USE submenu for sub_page type ****************************/
            'submenu'   => array(
                'parent_slug'   => '',
                'page_title'    => '',
                'menu_title'    => '',
                'capability'    => 'manage_options',
                'menu_slug'     => '',  
            ),
            /** ONLY USE theme for theme_page type ****************************/
            'theme' => array(
                'page_title'    => '',
                'menu_title'    => '',
                'capability'    => 'manage_options',
                'menu_slug'     => '',                    
            ),            
        ),
        'sections' => array(
            // Must have at least one section
            'section1'          => __( 'Section 1 Name Here', 'river'),
            'section2'          => __( 'Section 2 Name Here', 'river')
        ), 
        // Holds all the settings
        'default_fields' => array()
    );


/** Example Settings by Field Type ********************************************/
   
   /**
    * Copy and paste the fields you need into your config file.  Then edit as
    * needed.
    */
   
/** checkbox ******************************************************************/      
    // Example 'checkbox' for a HTML checkbox field
    $config['default_fields']['example_checkbox'] = array(        
        // settings ID for settings array & HTML element
        'id'                => 'example_checkbox',
        // element's label
        'title'             => __( 'Example Checkbox Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the Checkbox input.', 'river' ),
        // default value MUST be integer and 0 or 1
        'default'           => 1,        
        // HTML field type
        'type'              => 'checkbox',        
        // section these are assigned to
        'section_id'        => 'section1',
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'zero_one',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'zero_one',
        // (opt) sets an inline style
        'style'             => '',
    ); 
    
/** colorpicker ***************************************************************/
    // Example 'colorpicker' for a HTML colorpicker field
    $config['default_fields']['example_colorpicker'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_colorpicker',
        // element's label
        'title'             => __( 'Example Colorpicker Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Click on the field to select the new color.', 'river' ),
        // default value
        'default'           => 'FFFFFF',        
        // HTML field type
        'type'              => 'colorpicker',        
        // section these are assigned to
        'section_id'        => 'section2',
        
        /** These are the Optional Arguments & do not have to be included *****/        
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Sets a placeholder in the form's text field
        'placeholder'       => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'hex',
        // (opt) sets an inline style
        'style'             => '',
    );
    
/** email *********************************************************************/    
    // Example 'email' for a HTML text field
    $config['default_fields']['example_email'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_email',
        // element's label
        'title'             => __( 'Example Email Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the Email input.', 'river' ),
        // default value
        'default'           => '',        
        // HTML field type
        'type'              => 'email',        
        // section these are assigned to
        'section_id'        => 'section1',
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Sets a placeholder in the form's text field
        'placeholder'       => __( 'youremail@domain.com', 'river'),
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'email',
        // (opt) sets an inline style
        'style'             => '',
    ); 
    
/** heading *******************************************************************/     
   // Example 'heading' for a HTML <h4> tag
    $config['default_fields']['example_heading'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_heading',
        // This is the text between the <h4> tags
        'desc'              => __( 'Example Heading', 'river' ),      
        // HTML field type
        'type'              => 'heading',        
        // section these are assigned to
        'section_id'        => 'section1',
    );
    
/** imgselect *****************************************************************/    
    // Example 'imgselect', which is a HTML radio buttons but with an image
    // shown instead of the radio button.  Ways to use may be for page layout
    // selection, footer layout, etc.
    $config['default_fields']['example_imgselect'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_imgselect',
        // element's label
        'title'             => __( 'Example imgselect', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the imgselect.', 'river' ),
        // default value MUST be a string and set to once of the choice keys
        'default'           => 'imgselect1',        
        // HTML field type
        'type'              => 'imgselect',        
        // section these are assigned to
        'section_id'        => 'section1',
        // Define the choices for the images, i.e. at least 2+
        'choices' => array(
            'imgselect1'  => array(
                'name'      => 'imgselect1',
                'value'     => __( 'Choice 1', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    // Image source URL and filename.  This is the image that
                    // is shown instead of the radio button
                    'value'     => '',
                    // Image title
                    'title'     => '',          
                    // Image alt
                    'alt'       => '',                    
                ),
            ),
            'imgselect2' => array(
                'name'      => 'imgselect2',
                'value'     => __( 'Choice 2', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    // Image source URL and filename.  This is the image that
                    // is shown instead of the radio button
                    'value'     => '',
                    // Image title
                    'title'     => '',          
                    // Image alt
                    'alt'       => '',                    
                ),
            ),
            'imgselect3' => array(
                'name'      => 'imgselect3',
                'value'     => __( 'Choice 3', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    // Image source URL and filename.  This is the image that
                    // is shown instead of the radio button
                    'value'     => '',
                    // Image title
                    'title'     => '',          
                    // Image alt
                    'alt'       => '',                    
                ),
            ),
        ),
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'string_choices',
        // (opt) sets an inline style
        'style'             => 'display: inline;',
    ); 
    
/** multicheck ****************************************************************/    
    // Example 'multicheck' for multiple HTML checkbox fields
    $config['default_fields']['example_multicheck'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_multicheck',
        // element's label
        'title'             => __( 'Example Multicheck', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the multicheck.', 'river' ),
        // default value MUST be '' or an array, as shown
        'default'           => array( 'box1' => 'Choice 1', 'box3' => 'Choice 3' ),        
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
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'string_choices',
        // (opt) sets an inline style
        'style'             => '',
    );     
    
/** radio *********************************************************************/    
    // Example 'radio' for a HTML radio fields
    $config['default_fields']['example_radio'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_radio',
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
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'string_choices',
        // (opt) sets an inline style
        'style'             => '',
    );     

/** select ********************************************************************/   
    // Example 'select' for a HTML select and its option fields
    $config['default_fields']['example_select'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_select',
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
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'string_choices',
        // (opt) sets an inline style
        'style'             => '',
    );
     
   
/** text **********************************************************************/   
   
   // Example 'text' for a HTML text field
    $config['default_fields']['example_text'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_text',
        // element's label
        'title'             => __( 'Example Text Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the text input.', 'river' ),
        // default value
        'default'           => '',        
        // HTML field type
        'type'              => 'text',        
        // section these are assigned to
        'section_id'        => 'section1',
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Sets a placeholder in the form's text field
        'placeholder'       => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'string',
        // (opt) sets an inline style
        'style'             => '',
    );

/** textarea ******************************************************************/    
    // Example 'textarea' for a HTML textarea field
    $config['default_fields']['example_textarea'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_textarea',
        // element's label
        'title'             => __( 'Example Textarea Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the Textarea input.', 'river' ),
        // default value
        'default'           => '',        
        // HTML field type
        'type'              => 'textarea',        
        // section these are assigned to
        'section_id'        => 'section1',
        
        /** These are the Optional Arguments & do not have to be included *****/        
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Sets a placeholder in the form's text field
        'placeholder'       => '',
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'string',
        // (opt) sets an inline style
        'style'             => '',
    );    
    
/** upload-image **************************************************************/    
    // Example 'upload-image' for a HTML text field + image previewer
    // Uses WordPress media loader and .jscolor
    $config['default_fields']['example_upload-image'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_upload-image',
        // element's label
        'title'             => __( 'Example upload-image Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Click to select the image.', 'river' ),
        // default value
        'default'           => '',        
        // HTML field type
        'type'              => 'upload-image',        
        // section these are assigned to
        'section_id'        => 'section2',
        
        /** These are the Optional Arguments & do not have to be included *****/        
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Sets a placeholder in the form's text field
        'placeholder'       => __( 'Click to select the image.', 'river' ),
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'url',
        // (opt) sets an inline style
        'style'             => '',
    ); 
    
        
/** url ***********************************************************************/    
    // Example 'url' for a HTML text field
    $config['default_fields']['example_url'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_url',
        // element's label
        'title'             => __( 'Example URL Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the URL input.', 'river' ),
        // default value
        'default'           => '',        
        // HTML field type
        'type'              => 'url',        
        // section these are assigned to
        'section_id'        => 'section1',
        
        /** These are the Optional Arguments & do not have to be included *****/
        
        // (opt) add a custom class to the HTML element
        'class'             => '',
        // (opt) Sets a placeholder in the form's text field
        'placeholder'       => __( 'http://domain.com', 'river'),
        // (opt) Specify the sanitization filter here; else it's set
        // automatically in the Settings Sanitizer Class
        'sanitizer_filter'  => 'no_html',
        // (opt) Specify the validation filter here; else it's set
        // automatically in the Settings Sanitizer Class        
        'validator_filter'  => 'url',
        // (opt) sets an inline style
        'style'             => '',
    );    
     