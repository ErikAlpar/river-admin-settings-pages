<?php

/**
 * Display settings callback to render the settings for the selected page
 * 
 * The file is held here for easier editing and viewing.
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Display Settings View
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

/**
 * Credits:  Let's give some love to those who have inspired this code, as most
 *           of it was adapted from these wonderful sites:
 * 1.   Aliso the Geek's tutorial, which you can find here at 
 *      http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 * 2.   Justin W. Hall's addition to Aliso the Geek's tutorial, here at
 *      http://www.justinwhall.com/multiple-upload-inputs-in-a-wordpress-theme-options-page/
 * 3.   Custom Metaboxes and Fields for WordPress by Andrew Norcross, Jared
 *      Atchison, and Bill Erickson
 *      https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 * 
 */


function river_admin_display_settings( $settings_group, $setting ) {
    
    $id = $setting['id'];
    $type = $setting['type'];

    $option_value = river_get_option( $settings_group, $id);

    $options = get_option( $settings_group  );

    // If 'class' isset, then add a space to the front of it
    if ( ! empty( $setting['class'] ) )
        $setting['class'] = ' ' . $setting['class'];

    /**
     * Render the HTML based on the type of element the setting is,
     * as defined in $setting['type']
     */
    switch ( $type ) {

        case 'colorpicker':

            printf( '<input id="%1$s" class="color%2$s" name="%3$s[%1$s]" ' .
                    'value="%4$s" data-default="%5$s" />', 
                    esc_attr( $id ),
                    esc_attr( $setting['class'] ),
                    esc_attr( $settings_group ),
                    esc_attr( $option_value ),
                    esc_attr( $setting['default'] ) );

            break;        


        case 'checkbox':

            printf( '<input id="%1$s" class="checkbox%2$s" type="checkbox" ' .
                    'name="%3$s[%1$s]" value="1" %4$s data-default="%5$s"/>',
                    esc_attr( $id ),
                    esc_attr( $setting['class'] ),
                    esc_attr( $settings_group ),
                    checked( $option_value, 1, false ),
                    esc_attr( $setting['default'] ) );

            printf( '<label for="%1$s">%2$s</label>',
                    esc_attr( $id ),
                    esc_html( $setting['desc'] ) );     

            break;

        case 'datepicker':

            break;

        // adds a heading to the page.
        // NOTE:  Don't use <h3> as the javascript will set it to 'hide', since
        //        sections are automatically placed in <h3> tags
        case 'heading':

            printf ( '<h4 class="heading">%s</h4>',
                    esc_html( $setting['desc'] ) );
            break;    

        // imgselect is for displaying an image over a radio input, e.g. page layout selector
        case 'imgselect':

            printf( '<div id="%s" class="imgselect">', esc_attr( $setting['id'] ) );

            foreach ( $setting['choices'] as $key => $choice ) {

                printf( '<label class="imgselect %1$s" title="%2$s">',
                        $key == $option_value ? 'selected' : '',
                        esc_attr( $choice['args']['title'] ) );

                printf( '<input id="%4$s" class="radio%2$s" type="radio" ' .
                        'name="%3$s[%1$s]" value="%4$s" %5$s data-default="%6$s"/><br>',
                        esc_attr( $id ),
                        esc_attr( $setting['class'] ),
                        esc_attr( $settings_group ),
                        esc_attr ( $key ),
                        checked( $option_value, $key, false ),
                        esc_attr( $setting['default'] ) );

                printf( '<img src="%1$s" title="%2$s" alt="%3$s" style="%4$s"/>',
                        esc_html( $choice['args']['value'] ),
                        esc_attr( $choice['args']['title'] ),
                        esc_attr( $choice['args']['alt'] ),
                        isset( $setting['style'] ) ? 
                            esc_attr( $setting['style'] ) : ''
                        );

                echo '</label>';
            }
            echo '</div>';

            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            break;

        case 'multicheck':

            $checked = isset( $option_value ) ? $option_value : '0';

            echo '<ul class="multicheck">';   
            $i = 0;
            foreach ( $setting['choices'] as $key => $choice ) {

                if( is_array ( $checked ) ) {
                    $is_checked = array_key_exists($key, $checked) ? 'checked="checked"' : null;
                } else {
                    $is_checked = $key == $checked ? 'checked="checked"' : null;
                }
                echo '<li>';

                printf( '<input id="%2$s%6$s" class="multicheck%1$s" type="checkbox" ' .
                        'name="%3$s[%2$s][%4$s]" value="%4$s" %5$s data-default="%6$s"/>', 
                        esc_attr( $setting['class'] ),
                        esc_attr( $id ),
                        esc_attr( $settings_group ),
                        esc_attr ( $key ),
                        isset( $is_checked ) ? 'checked="checked"' : '',
                        $i,
                        esc_attr( $setting['default'] ) );

                printf( '<label for="%1$s%2$s">%3$s</label>',
                        esc_attr( $id ),
                        $i,
                         esc_html( $choice['value'] ) );

                echo '</li>';
                $i++;
            }
            echo '</ul>';

            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            break;        

        case 'radio':      

            $i = 0;
            echo '<ul class="radio">'; 

            foreach ( $setting['choices'] as $key => $choice ) {
                echo '<li>';

                printf( '<input id="%1$s%4$s" class="radio%2$s" type="radio" ' .
                        'name="%3$s[%1$s]" value="%5$s" %6$s data-default="%7$s"/>',
                        esc_attr( $id ),
                        esc_attr( $setting['class'] ),
                        esc_attr( $settings_group ),
                        $i,
                        esc_attr ( $key ),
                        checked( $option_value, $key, false ),
                        esc_attr( $setting['default'] ) );

                printf( '<label for="%1$s%2$s">%3$s</label>',
                        esc_attr( $id ),
                        $i,
                         esc_html( $choice['value'] ) );

                $i++;
                echo '</li>';
            }
            echo '</ul>';

            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            break;

        case 'select':       

            printf ( '<select class="select%1$s" name="%3$s[%2$s]">',
                    esc_attr( $setting['class'] ),
                    esc_attr( $id ),
                    esc_attr( $settings_group ) );

            // If there is no default, then the first <option> is nothing
            if( empty( $setting['default'] ) )
                printf( '<option value="" %1$s data-default="%2$s"></option>',
                        selected( $option_value, '', false ),
                        esc_attr( $setting['default'] ) );

            // Now load up each of the other <options> in 'choices'
            foreach ( $setting['choices'] as $key => $choice ) {         

                printf( '<option value="%1$s" %2$s>%3$s</option>',
                        esc_attr ($key ), 
                        selected( $option_value, $key, false ),
                        esc_html( $choice['value'] )
                        );
            }
            echo '</select>';


            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            break; 

        case 'text':

            printf( '<input id="%1$s" class="regular-text%2$s" type="text" ' .
                    'name="%3$s[%1$s]" value="%4$s" placeholder="%5$s" data-default="%6$s"/>',
                    esc_attr( $id ),
                    esc_attr( $setting['class'] ),
                    esc_attr( $settings_group ),                       
                    esc_attr( $option_value ),                        
                    esc_attr( $setting['placeholder'] ),
                    esc_attr( $setting['default'] ) );

            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            break;        

        case 'textarea':

            printf( '<textarea id="%1$s" class="textarea%2$s" name="%3$s[%1$s]" ' .
                    'placeholder="%4$s" data-default="%6$s" rows="5" cols="30">' .
                    '%5$s</textarea>',
                    esc_attr( $id ),
                    esc_attr( $setting['class'] ),
                    esc_attr( $settings_group ),
                    esc_attr( $setting['placeholder'] ),                    
                    esc_textarea( $option_value ),
                    esc_attr( $setting['default'] ) );
            
            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            break;

        case 'timepicker':


            break;        

        case 'upload-image':

            printf( '<input id="%1$s" class="upload-url%2$s" type="text" ' .
                    'name="%3$s[%1$s]" value="%4$s" data-default="%5$s" />',
                    esc_attr( $id ),
                    esc_attr( $setting['class'] ),
                    esc_attr( $settings_group ),                       
                    esc_attr( $option_value ), 
                    esc_attr( $setting['default'] ) );                        

            printf( '<input id="%1$s" class="upload-button button" type="button" ' .
                    'name="upload_button" value="%2$s" title="%3$s" />',
                    esc_attr( $id ),
                    esc_attr( __( 'Upload', 'river' ) ), 
                    esc_attr( $setting['title'] ) );         

            if ( ! empty( $setting['desc'] ) )
                printf( '<br /><span class="description">%s</span>',
                    esc_html( $setting['desc'] ) );

            printf ( '<div id="image-preview" style="%s">' ,
                    empty( $option_value ) ? 'display: none;' : 'display:block' );

                // Display the image tag
                printf( '<img id="%1$s" class="upload-url%2$s" src="%3$s" />',
                        esc_attr( $id ),
                        esc_attr( $setting['class'] ),
                        esc_attr( $option_value ) );
                // Delete image
                echo '<a class="delete-image button" href="#">Remove Image</a>';

            echo '</div>';

            break;

        default:

            // Oops the type is not valid
    }
}
