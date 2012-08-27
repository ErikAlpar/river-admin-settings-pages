<?php

/**
 * River Admin Settings Pages Configurations
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin River
 * @since       0.0.1
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

/**
 * Pages:
 *      1. River Options
 *          a. Framework
 *          b. General
 *      2. SEO
 *      3. Hook Manager
 *      4. Sidebar Manager
 */
  
function river_settings_menu_page() {
    
   $config = array(
        'id'                    => 'river',
        'settings_group'        => 'river_settings',
        'type'                  => 'main_page', 
        'form'  => array(
            'id'                => 'river-form',
            // Displayed under the page title
            'version'           => RIVER_VERSION,
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
            'button_reset_text' => __( 'Reset All Options', 'river' ),
            'help_file'         => RIVER_ADMIN_DIR . '/views/river-settings-help.php',
        ),
        'page_config' => array(
            // id for this settings page
            'id'                => 'river',
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
            )            
        ),
        'sections' => array(
            'general'           => __( 'General', 'river'),
            'appearance'        => __( 'Appearance', 'river')
        ), 
        'default_settings' => array()
    );


    /** General Section *******************************************************/
    $config['default_settings']['example_text'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_text',
        // element's label
        'title'             => __( 'Example Text Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the text input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'text',
        // section these are assigned to
        'section_id'        => 'general',
        'placeholder'       => __( 'Default value', 'river'),
    );
    
    $config['default_settings']['example_email'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_email',
        // element's label
        'title'             => __( 'Example Email Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the text input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'text',
        // section these are assigned to
        'section_id'        => 'general',
        'class'             => 'email',
        'placeholder'       => __( 'youremail@domain.com', 'river'),
    ); 
    
    $config['default_settings']['example_url'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_url',
        // element's label
        'title'             => __( 'Example URL Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the text input.', 'river' ),
        // element's default value
        'default'           => 'http://coderiverlabs.com',
        // HTML element type
        'type'              => 'text',
        // section these are assigned to
        'section_id'        => 'general',
        'class'             => 'url',        
        'placeholder'       => __( 'http://domain.com', 'river'),
    );     
    
    $config['default_settings']['example_textarea'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_textarea',
        // element's label
        'title'             => __( 'Example Textarea Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the textarea input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'textarea',
        // section these are assigned to
        'section_id'        => 'general',
        'placeholder'       => __( 'Default value', 'river'),
    );    

    $config['default_settings']['checkbox'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'checkbox',
        // element's label
        'title'             => __( 'Example Checkbox', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the checkbox.', 'river' ),
        // element's default value
        'default'           => 1,
        // HTML element type
        'type'              => 'checkbox',
        // section these are assigned to
        'section_id'        => 'general',
    ); 
    
    $config['default_settings']['multicheck'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'multicheck',
        // element's label
        'title'             => __( 'Example Multi-Checkbox', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the multi checkbox.', 'river' ),
        // element's default value
        'default'           => array( 'box1' => 'Choice 1', 'box3' => 'Choice 3' ),
        // HTML element type
        'type'              => 'multicheck',
        // section these are assigned to
        'section_id'        => 'general',
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

    $config['default_settings']['example_heading'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_heading',
        // element's label
        'title'             => '',
        // (opt) description displayed under the element
        'desc'              => __( 'Example Heading', 'river' ),
        // HTML element type
        'type'              => 'heading',
        // section these are assigned to
        'section_id'        => 'general',
    );

    $config['default_settings']['example_radio'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_radio',
        // element's label
        'title'             => __( 'Example Radio', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the radio buttons.', 'river' ),
        // element's default value
        'default'           => 'choice2',        
        // HTML element type
        'type'              => 'radio',
        // section these are assigned to
        'section_id'        => 'general',
        'choices' => array(
            'choice1' => array(
                'name'      => 'choice1',
                'value'     => __( 'Choice 1', 'river' ),
                'args'      => '',
            ),
            'choice2' => array(
                'name'      => 'choice2',
                'value'     => __( 'Choice 2', 'river' ),
                'args'      => '',
            ),
            'choice3' => array(
                'name'      => 'choice3',
                'value'     => __( 'Choice 3', 'river' ),
                'args'      => '',
            ), 
        ),
    );    
    
    $config['default_settings']['example_select'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_select',
        // element's label
        'title'             => __( 'Example Select', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the drop-down.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'select',
        // section these are assigned to
        'section_id'        => 'general',
        'choices' => array(
            'choice1' => array(
                'name'      => 'choice1',
                'value'     => __( 'Other Choice 1', 'river' ),
                'args'      => '',
            ),
            'choice2' => array(
                'name'      => 'choice2',
                'value'     => __( 'Other Choice 2', 'river' ),
                'args'      => '',
            ),
            'choice3' => array(
                'name'      => 'choice3',
                'value'     => __( 'Other Choice 3', 'river' ),
                'args'      => '',
            ), 
        ),       
    ); 


    /** Appearance Section ****************************************************/

    $config['default_settings']['main_layout'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'main_layout',
        // element's label
        'title'             => __( 'Main Layout', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the img select.', 'river' ),
        // element's default value
        'default'           => 'layout2',
        // HTML element type
        'type'              => 'imgselect',
        // section these are assigned to
        'section_id'        => 'appearance',
        'choices' => array(
            'layout1' => array(
                'name'      => 'layout1',
                'value'     => __( 'Layout 1', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/1c.png',
                    'title'     => 'Content',          
                    'alt'       => '',                    
                ),
            ),
            'layout2' => array(
                'name'      => 'layout2',
                'value'     => __( 'Layout 2', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/2cl.png',
                    'title'     => 'Content-Sidebar',          
                    'alt'       => 'Content-Sidebar',                    
                ),
            ),
            'layout3' => array(
                'name'      => 'layout3',
                'value'     => __( 'Layout 3', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/2cr.png',
                    'title'     => 'Sidebar-Content',          
                    'alt'       => 'Sidebar-Content',                    
                ),
            ),
            'layout4' => array(
                'name'      => 'layout4',
                'value'     => __( 'Layout 4', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/3cl.png',
                    'title'     => 'Content-Sidebar-Sidebar',          
                    'alt'       => 'Content-Sidebar-Sidebar',                    
                ),
            ),
            'layout5' => array(
                'name'      => 'layout5',
                'value'     => __( 'Layout 5', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/3cm.png',
                    'title'     => 'Sidebar-Content-Sidebar',          
                    'alt'       => '',                    
                ),
            ),
            'layout6' => array(
                'name'      => 'layout6',
                'value'     => __( 'Layout 6', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/3cr.png',
                    'title'     => 'Sidebar-Sidebar-Content',          
                    'alt'       => 'Sidebar-Sidebar-Content',                    
                ),
            ),            
        ),
        'style' => 'display: inline;',
    ); 
    
    $config['default_settings']['footer_layout'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'footer_layout',
        // element's label
        'title'             => __( 'Footer Layout', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the img select.', 'river' ),
        // element's default value
        'default'           => 'layout1',
        // HTML element type
        'type'              => 'imgselect',
        // section these are assigned to
        'section_id'        => 'appearance',
        'choices' => array(
            'layout1' => array(
                'name'      => 'layout1',
                'value'     => __( 'Layout 1', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-0.png',
                    'title'     => 'Content',          
                    'alt'       => '',                    
                ),
            ),
            'layout2' => array(
                'name'      => 'layout2',
                'value'     => __( 'Layout 2', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-1.png',
                    'title'     => 'Content-Sidebar',          
                    'alt'       => 'Content-Sidebar',                    
                ),
            ),
            'layout3' => array(
                'name'      => 'layout3',
                'value'     => __( 'Layout 3', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-2.png',
                    'title'     => 'Sidebar-Content',          
                    'alt'       => 'Sidebar-Content',                    
                ),
            ),
            'layout4' => array(
                'name'      => 'layout4',
                'value'     => __( 'Layout 4', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-3.png',
                    'title'     => 'Content-Sidebar-Sidebar',          
                    'alt'       => 'Content-Sidebar-Sidebar',                    
                ),
            ),
            'layout5' => array(
                'name'      => 'layout5',
                'value'     => __( 'Layout 5', 'river' ),
                'args'      => array(
                    'type'      => 'img',
                    'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-4.png',
                    'title'     => 'Sidebar-Content-Sidebar',          
                    'alt'       => '',                    
                ),
            ),           
        ),
        'style' => 'display: inline;',
    );    
    
    $config['default_settings']['header_colorpicker'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'header_colorpicker',
        // element's label
        'title'             => __( 'Header Background Color', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Click on the field to select the new color.', 'river' ),
        // element's default value
        'default'           => 'FFFFFF',
        // HTML element type
        'type'              => 'colorpicker',
        // section these are assigned to
        'section_id'        => 'appearance',
    );    
    
    $config['default_settings']['header_logo'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'header_logo',
        // element's label
        'title'             => __( 'Header Logo', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter the URL to your logo for the theme header.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'upload-image',
        // section these are assigned to
        'section_id'        => 'appearance',
        'class'             => 'url',          
    );   

    $config['default_settings']['favicon'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'favicon',
        // element's label
        'title'             => __( 'Favicon', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter the URL to your custom favicon. It should be 16x16 pixels in size.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'upload-image',
        // section these are assigned to
        'section_id'        => 'appearance',
        'class'             => 'url',          
    ); 


    $config['default_settings']['custom_css'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'custom_css',
        // element's label
        'title'             => __( 'Custom Styles', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter any custom CSS here to apply it to your theme.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'textarea',
        // section these are assigned to
        'section_id'        => 'appearance',
        'class'             => 'code',
        'sanitizer_filter'  => 'no_html',
        'placeholder'       => __( 'Default value', 'river'),        

    );
                
   return $config;
}                


function river_seo_submenu() {   
    
   $config = array(
        'id'                    => 'river_seo',
        'settings_group'        => 'river_seo_settings',
        'type'                  => 'sub_page', 
        'form'  => array(
            'id'                => 'river-seo',
            // Displayed under the page title
            'version'           => RIVER_VERSION,
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
        ),
        'page_config' => array(
            // id for this settings page
            'id'                => 'river_seo',
            'submenu'   => array(
                'parent_slug'   => 'river',
                'page_title'    => __( 'SEO', 'river' ),
                'menu_title'    => __( 'SEO', 'river' ),
                'capability'    => 'manage_options',
                'menu_slug'     => 'river_seo',  
            ) 
        ),
        'sections' => array(
            'general'           => __( 'General', 'river'),
            'appearance'        => __( 'Appearance', 'river')
        ), 
        'default_settings' => array()
    );
                 

    /** General Section *******************************************************/
    $config['default_settings']['example_text'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_text',
        // element's label
        'title'             => __( 'Example Text Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the text input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'text',
        // section these are assigned to
        'section_id'        => 'general',
        'placeholder'       => __( 'Default value', 'river'),        
    );
    
    $config['default_settings']['example_textarea'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_textarea',
        // element's label
        'title'             => __( 'Example Textarea Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the textarea input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'textarea',
        // section these are assigned to
        'section_id'        => 'general',
        'placeholder'       => __( 'Default value', 'river'),        
    );    

    $config['default_settings']['checkbox'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'checkbox',
        // element's label
        'title'             => __( 'Example Checkbox', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the checkbox.', 'river' ),
        // element's default value
        'default'           => 1,
        // HTML element type
        'type'              => 'checkbox',
        // section these are assigned to
        'section_id'        => 'general',
    ); 

    $config['default_settings']['example_heading'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_heading',
        // element's label
        'title'             => '',
        // (opt) description displayed under the element
        'desc'              => __( 'Example Heading', 'river' ),
        // HTML element type
        'type'              => 'heading',
        // section these are assigned to
        'section_id'        => 'general',
    );

    $config['default_settings']['example_radio'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_radio',
        // element's label
        'title'             => __( 'Example Radio', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the radio buttons.', 'river' ),
        // element's default value
        'default'           => '',        
        // HTML element type
        'type'              => 'radio',
        // section these are assigned to
        'section_id'        => 'general',
        'choices' => array(
            'choice1' => array(
                'name'      => 'choice1',
                'value'     => __( 'Choice 1', 'river' ),
                'args'      => '',
            ),
            'choice2' => array(
                'name'      => 'choice2',
                'value'     => __( 'Choice 2', 'river' ),
                'args'      => '',
            ),
            'choice3' => array(
                'name'      => 'choice3',
                'value'     => __( 'Choice 3', 'river' ),
                'args'      => '',
            ), 
        ),
    );    
    
    $config['default_settings']['example_select'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_select',
        // element's label
        'title'             => __( 'Example Select', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the drop-down.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'select',
        // section these are assigned to
        'section_id'        => 'general',
        'choices' => array(
            'choice1' => array(
                'name'      => 'choice1',
                'value'     => __( 'Other Choice 1', 'river' ),
                'args'      => '',
            ),
            'choice2' => array(
                'name'      => 'choice2',
                'value'     => __( 'Other Choice 2', 'river' ),
                'args'      => '',
            ),
            'choice3' => array(
                'name'      => 'choice3',
                'value'     => __( 'Other Choice 3', 'river' ),
                'args'      => '',
            ), 
        ),       
    ); 


    /** Appearance Section ****************************************************/
    
    $config['default_settings']['header_logo'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'header_logo',
        // element's label
        'title'             => __( 'Header Logo', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter the URL to your logo for the theme header.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'upload-image',
        // section these are assigned to
        'section_id'        => 'appearance',
    );   

    $config['default_settings']['favicon'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'favicon',
        // element's label
        'title'             => __( 'Favicon', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter the URL to your custom favicon. It should be 16x16 pixels in size.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'upload-image',
        // section these are assigned to
        'section_id'        => 'appearance',
    ); 


    $config['default_settings']['custom_css'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'custom_css',
        // element's label
        'title'             => __( 'Custom Styles', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter any custom CSS here to apply it to your theme.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'textarea',
        // section these are assigned to
        'section_id'        => 'appearance',
        'class'             => 'code',
        'sanitizer_filter'  => 'no_html',
    ); 

    return $config;
}

function river_theme_options() {   
    
   $config = array(
        'id'                    => 'river_theme_options',
        'settings_group'        => 'river_theme_settings',
        'type'                  => 'theme_page', 
        'form'  => array(
            'id'                => 'river-theme-options',
            // Displayed under the page title
            'version'           => RIVER_VERSION,
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
        ),
        'page_config' => array(
            // id for this settings page
            'id'                => 'river_theme_options',
            'theme' => array(
                'page_title'    => __( 'River Theme Options', 'river' ),
                'menu_title'    => __( 'River Theme Options', 'river' ),
                'capability'    => 'manage_options',
                'menu_slug'     => 'river_theme_options',                    
            ),            
        ),
        'sections' => array(
            'general'           => __( 'General', 'river'),
            'appearance'        => __( 'Appearance', 'river')
        ), 
        'default_settings' => array()
    );             

    /** General Section *******************************************************/
    $config['default_settings']['example_text'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_text',
        // element's label
        'title'             => __( 'Example Text Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the text input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'text',
        // section these are assigned to
        'section_id'        => 'general',
        'placeholder'       => __( 'Default value', 'river'),        
    );
    
    $config['default_settings']['example_textarea'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_textarea',
        // element's label
        'title'             => __( 'Example Textarea Input', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the textarea input.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'textarea',
        // section these are assigned to
        'section_id'        => 'general',
        'placeholder'       => __( 'Default value', 'river'),        
    );    

    $config['default_settings']['checkbox'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'checkbox',
        // element's label
        'title'             => __( 'Example Checkbox', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the checkbox.', 'river' ),
        // element's default value
        'default'           => 1,
        // HTML element type
        'type'              => 'checkbox',
        // section these are assigned to
        'section_id'        => 'general',
    ); 

    $config['default_settings']['example_heading'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_heading',
        // element's label
        'title'             => '',
        // (opt) description displayed under the element
        'desc'              => __( 'Example Heading', 'river' ),
        // HTML element type
        'type'              => 'heading',
        // section these are assigned to
        'section_id'        => 'general',
    );

    $config['default_settings']['example_radio'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_radio',
        // element's label
        'title'             => __( 'Example Radio', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the radio buttons.', 'river' ),
        // element's default value
        'default'           => '',        
        // HTML element type
        'type'              => 'radio',
        // section these are assigned to
        'section_id'        => 'general',
        'choices' => array(
            'choice1' => array(
                'name'      => 'choice1',
                'value'     => __( 'Choice 1', 'river' ),
                'args'      => '',
            ),
            'choice2' => array(
                'name'      => 'choice2',
                'value'     => __( 'Choice 2', 'river' ),
                'args'      => '',
            ),
            'choice3' => array(
                'name'      => 'choice3',
                'value'     => __( 'Choice 3', 'river' ),
                'args'      => '',
            ), 
        ),
    );    
    
    $config['default_settings']['example_select'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'example_select',
        // element's label
        'title'             => __( 'Example Select', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'This is a description for the drop-down.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'select',
        // section these are assigned to
        'section_id'        => 'general',
        'choices' => array(
            'choice1' => array(
                'name'      => 'choice1',
                'value'     => __( 'Other Choice 1', 'river' ),
                'args'      => '',
            ),
            'choice2' => array(
                'name'      => 'choice2',
                'value'     => __( 'Other Choice 2', 'river' ),
                'args'      => '',
            ),
            'choice3' => array(
                'name'      => 'choice3',
                'value'     => __( 'Other Choice 3', 'river' ),
                'args'      => '',
            ), 
        ),       
    ); 


    /** Appearance Section ****************************************************/
    
    $config['default_settings']['header_logo'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'header_logo',
        // element's label
        'title'             => __( 'Header Logo', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter the URL to your logo for the theme header.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'upload-image',
        // section these are assigned to
        'section_id'        => 'appearance',
    );   

    $config['default_settings']['favicon'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'favicon',
        // element's label
        'title'             => __( 'Favicon', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter the URL to your custom favicon. It should be 16x16 pixels in size.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'upload-image',
        // section these are assigned to
        'section_id'        => 'appearance',
    ); 


    $config['default_settings']['custom_css'] = array(
        // settings ID for settings array & HTML element
        'id'                => 'custom_css',
        // element's label
        'title'             => __( 'Custom Styles', 'river' ),
        // (opt) description displayed under the element
        'desc'              => __( 'Enter any custom CSS here to apply it to your theme.', 'river' ),
        // element's default value
        'default'           => '',
        // HTML element type
        'type'              => 'textarea',
        // section these are assigned to
        'section_id'        => 'appearance',
        'class'             => 'code',
        'sanitizer_filter'  => 'no_html',
    ); 

    return $config;
}