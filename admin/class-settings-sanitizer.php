<?php

/**
 * Validate and Sanitize Settings Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Validate Sanitize
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Settings_Sanitizer' ) ) :
/**
 * Class for validating and sanitizing both settings for the Admin Settings
 * Pages.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 * 
 * @link    http://wordpress.stackexchange.com/questions/13539/what-are-security-best-practices-for-wordpress-plugins-and-themes
 */
abstract class River_Settings_Sanitizer extends River_Admin {
    
    /** Class Parameters ******************************************************/    
    
    /**
     * Available Sanitizer Filter Types and their default HTML element types
     * 
     * @since 0.0.0
     * @var array
     */
    protected $sanitizer_filters;      
    
    /**
     * Available Sanitizer Filter Types and their default HTML element types
     * 
     * @since 0.0.0
     * @var array
     */
    protected $validator_filters;
    
    /** Class Methods *********************************************************/
    
    /**
     * Setup both the sanitizer and validator filters to their default values.
     * Arrays for each filter is provided to identify the HMTL element that is
     * assigned to this filter, i.e. when no filter is provided for the
     * individual setting.
     *
     * Both filter arrays can be filtered via 'river_default_sanitizer_filters' 
     * and 'river_default_validator_filters' to let child themes and plugins add
     * their own filters.
     *
     * @since 0.0.0
     *
     */
    protected function setup_filter_defaults() {
        
        $this->sanitizer_filters = apply_filters(
                'river_default_sanitizer_filters',             
                array( 
                    'zero_one'          => array( 'checkbox', 'button' ), 
                    'no_html'           => array(
                        'heading', 'text', 'upload', 'textarea', 'select', 'radio', 
                        'multicheck', 'upload-image',
                    ),
                    'safe_html'         => array(), 
                    'unfiltered_html'   => array(),
                )
        );
        
        $this->validator_filters = apply_filters(
                'river_default_validator_filters', 
                array(
                    'absint'            => array(),        
                    'boolean'           => array(), 
                    'boolean_multi'     => array( 'imgselect', 'multicheck', 'radio', 'select'),
                    'date_format'       => array( 'datepicker' ),        
                    'descr_name'        => array( 'textarea' ),
                    'email'             => array(),        
                    'hex'               => array( 'colorpicker' ),
                    'integer'           => array(),
                    'numeric'           => array(),
                    'string'            => array( 'text' ),
                    'time_format'       => array( 'timepicker' ),
                    'url'               => array( 'upload-image', 'siteurl' ),
                    'zero_one'          => array( 'checkbox', 'button' ),
                )
        );        
    }
    
    /**
     * Add sanitization filter to options to 'sanitize_option_{$option}'
     *
     * @since 0.0.0
     *
     * @return boolean Returns true when complete
     */
    protected function add_sanitize_option_filter() {

        /**
         * In wp-admin/includes/plugin.php line 1633, we can either assign
         * a callback when we register the setting (register_setting) or
         * add to the filter "sanitize_option_{$option_name}.  Here we are
         * adding a filter and the sanitizer is in this class
         * 
         * @link http://wordpress.stackexchange.com/questions/61024/default-wordpress-settings-api-data-sanitization
         */
        add_filter( 'sanitize_option_' . $this->settings_group, array( $this, 'sanitizer' ), 10, 2 );

        return true;

    }
    
    /**
     * Sanitize a value, via the sanitization filter type associated with an
     * option.
     *
     * @since 0.0.0
     *
     * @param mixed $new_value New value
     * @param string $option Name of the option
     * @return mixed Filtered, or unfiltered value
     */
    public function sanitizer( $new_value, $option ) {

        // Oops this $option does not belong to this object
        if ( $option != $this->settings_group )
           return; 

        // defaults is a single option value
        if ( is_string( $this->defaults ) ) {
            // get the old values from the options database
            $old_value = get_option( $option );            
            return $this->do_sanitizer_filter( 
                    $this->default_settings['sanitizer_filter'], 
                    $new_value, 
                    $old_value );
        
        // defaults is an array
        } elseif ( is_array( $this->defaults ) ) {
            // get the old values from the options database
            $old_value = get_option( $option );
            
            foreach ( $this->default_settings as $key => $setting ) {
                
                $old_value[$key] = isset( $old_value[$key] ) ? $old_value[$key] : '';
                $new_value[$key] = isset( $new_value[$key] ) ? $new_value[$key] : '';
                
                if ( is_array( $new_value[$key] ) ) {
                    
                    if ( isset( $new_value[$key] ) ) {
                    
                        $temp_new_value = array();

                        foreach( $new_value[$key] as $sub_key => $sub_value) {

                            $temp_new_value[$sub_key] = $this->do_validator_filter( 
                                    $setting['validator_filter'], 
                                    $sub_value, $old_value[$key], $key );

                            if( $temp_new_value == $old_value[$key] )
                                break;

                            // Pass through the sanitizer filter and store updated value
                            $temp_new_value[$sub_key] = $this->do_sanitizer_filter( 
                                    $setting['sanitizer_filter'], 
                                    $sub_value, $old_value[$key] ); 

                            if( $temp_new_value == $old_value[$key] )
                                break;
                        }

                        $new_value[$key] = $temp_new_value;
                    }
                    
                } else {
                    
                    // if the new value = old value, then no need to validate
                    if( ( $new_value[$key] != $old_value[$key] ) && 
                            ( gettype( $new_value[$key] ) == gettype( $old_value[$key] ) ) )
                        // Pass through the validator filter first and store updated value
                        $new_value[$key] = $this->do_validator_filter( 
                                $setting['validator_filter'], 
                                $new_value[$key], $old_value[$key], $key );
                    
                    // Pass through the sanitizer filter and store updated value
                    $new_value[$key] = $this->do_sanitizer_filter( 
                            $setting['sanitizer_filter'], 
                            $new_value[$key], $old_value[$key] );
                }
            }
            return $new_value;
        }
        
        // We should never hit this, but just to be safe....
        return $new_value;

    }    

    /** Sanitizer Filter ******************************************************/  
    
    /**
     * Checks sanitization filter exists, and if so, passes the value through it.
     *
     * @since 0.0.0
     *
     * @param string $filter Sanitization filter type
     * @param string $new_value New value
     * @param string $old_value Previous value
     * @return mixed Returns filtered value, or submitted value if value is
     * unfiltered.
     */
    protected function do_sanitizer_filter( $filter, $new_value, $old_value ) {

        $available_filters = $this->get_available_sanitizer_filters();

        if ( ! in_array( $filter, array_keys( $available_filters ) ) )
            return $new_value;
        /**
         * call_user_func call the callback given by the first parameter
         * call_user_func( callable $callback, $param [, $param ] )
         * @link http://php.net/manual/en/function.call-user-func.php
         */
        return call_user_func( $available_filters[$filter], $new_value, $old_value );

    }

    /**
     * Return array of known sanitization filter types.
     *
     * Array can be filtered via 'river_available_sanitizer_filters' to let
     * child themes and plugins add their own sanitization filters.
     *
     * @since 0.0.0
     *
     * @return array Array with keys of sanitization types, and values of the
     * filter function name as a callback
     */
    function get_available_sanitizer_filters() {

        $default_filters = array(
                'zero_one'                 => array( $this, 'zero_one'                  ),
                'no_html'                  => array( $this, 'no_html'                   ),
                'safe_html'                => array( $this, 'safe_html'                 ),
                'requires_unfiltered_html' => array( $this, 'requires_unfiltered_html'  ),
        );

        return apply_filters( 'river_available_sanitizer_filters', $default_filters );

    }
    
    /** Validator Filter ******************************************************/  
    
    /**
     * Checks validatr filter exists, and if so, passes the value through it.
     *
     * @since 0.0.0
     *
     * @param string $filter Sanitization filter type
     * @param string $new_value New value
     * @param string $old_value Previous value
     * @return mixed Returns filtered value, or submitted value if value is
     * unfiltered.
     */
    protected function do_validator_filter( $filter, $new_value, $old_value, $key ) {

        $available_filters = $this->get_available_validator_filters();

        if ( ! in_array( $filter, array_keys( $available_filters ) ) )
            return $new_value;
        /**
         * call_user_func call the callback given by the first parameter
         * call_user_func( callable $callback, $param [, $param ] )
         * @link http://php.net/manual/en/function.call-user-func.php
         */
        return call_user_func( $available_filters[$filter], $new_value, $old_value, $key );

    }

    /**
     * Return array of known sanitization filter types.
     *
     * Array can be filtered via 'river_available_sanitizer_filters' to let
     * child themes and plugins add their own sanitization filters.
     *
     * @since 0.0.0
     *
     * @return array Array with keys of sanitization types, and values of the
     * filter function name as a callback
     */
    function get_available_validator_filters() {

        $default_filters = array(
                'absint'            => array( $this, 'v_absint'         ),
                'boolean'           => array( $this, 'v_boolean'        ),
                'boolean_multi'     => array( $this, 'v_boolean_multi'  ),
                'date_format'       => array( $this, 'v_date_format'    ),        
                'descr_name'        => array( $this, 'v_descr_name'     ),
                'email'             => array( $this, 'v_email'          ),        
                'hex'               => array( $this, 'v_hex'            ),
                'integer'           => array( $this, 'v_integer'        ),
                'numeric'           => array( $this, 'v_numeric'        ),
                'string'            => array( $this, 'v_string'         ),
                'time_format'       => array( $this, 'v_time_format'    ),
                'url'               => array( $this, 'v_url'            ),
                'zero_one'          => array( $this, 'v_zero_one'       ),            
        );

        return apply_filters( 'river_available_validator_filters', $default_filters );
                
    } 
    

    /** Sanitizer Filter Methods **********************************************/    

    // Now, our filter methods

    /**
     * Returns a 1 or 0, for all truthy / falsy values.
     *
     * Uses double casting. First, we cast to bool, then to integer.
     *
     * @since 1.7.0
     *
     * @param mixed $new_value Should ideally be a 1 or 0 integer passed in
     * @return integer 1 or 0.
     */
    function zero_one( $new_value ) {        

        return (int) (bool) $new_value;

    }

    /**
     * Removes HTML tags from string.
     *
     * @since 1.7.0
     *
     * @param string $new_value String, possibly with HTML in it
     * @return string String without HTML in it.
     */
    function no_html( $new_value ) {

        return strip_tags( $new_value );

    }

    /**
     * Removes unsafe HTML tags, via wp_kses_post().
     *
     * @since 1.7.0
     *
     * @param string $new_value String with potentially unsafe HTML in it
     * @return string String with only safe HTML in it
     */
    function safe_html( $new_value ) {

        return wp_kses_post( $new_value );

    }

    /**
     * Keeps the option from being updated if the user lacks unfiltered_html
     * capability.
     *
     * @since 1.7.0
     *
     * @param string $new_value New value
     * @param string $old_value Previous value
     * @return string New or previous value, depending if user has correct
     * capability or not.
     */
    function requires_unfiltered_html( $new_value, $old_value ) {

        if ( current_user_can( 'unfiltered_html' ) ) {
            return $new_value;
        } else {
            return $old_value;
        }

    }
    

    /** Validator Filter Methods **********************************************/
    
    function v_absint( $new_value, $old_value ) {
        
    }
    
    function v_boolean( $new_value, $old_value ) {
        
        $new_value = is_string( $new_value ) ? (bool) trim( $new_value ) : '';
        
        return is_bool( $new_value ) ? $new_value : $old_value;
        
    }
    
    function v_boolean_multi( $new_value, $old_value, $id ) {
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return array_key_exists( $new_value, $this->default_settings[$id]['choices'] ) ? $new_value : $old_value;
        
    } 
    
    function v_date_format( $new_value, $old_value ) {
        
        return $new_value;
    }
    
    function v_descr_name( $new_value, $old_value ) {
        
        if( ! isset( $new_value ) )
            return $new_value;         
        
        return is_string( $new_value ) ? trim( $new_value ) : $old_value;        
        
    }
    
    function v_email( $new_value, $old_value ) {
        
        if( ! isset( $new_value ) )
            return $new_value;
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return is_email( $new_value ) ? sanitize_email( $new_value ) : $old_value;
        
    } 
    function v_hex( $new_value, $old_value ) {
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return is_integer( hexdec( $new_value ) ) ? $new_value : $old_value;
    }
    
    function v_integer( $new_value, $old_value ) {
        
        $new_value = is_string( $new_value ) ? (int) trim( $new_value ) : '';
        
        return is_integer( $new_value ) ? (int) $new_value : $old_value;
        
    }
    
    function v_numeric( $new_value, $old_value ) {
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return is_numeric( $new_value ) ? $new_value : $old_value;
        
    } 
    function v_string( $new_value, $old_value ) {
        
        return is_string( $new_value ) ? trim( $new_value ) : $old_value;
        
    }
    
    function v_time_format( $new_value, $old_value ) {
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        $regex = "/^([1-9]|1[0-2]|0[1-9]){1}(:[0-5][0-9][aApP][mM]){1}$/";
                
        return preg_match($regex, $new_value ) ? $new_value : $old_value;
        
    }
    
    function v_url( $new_value, $old_value ) {
        
        if( empty( $new_value ) )
            return $new_value;        
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return preg_match( '#http(s?)://(.+)#i', $new_value ) ? $new_value : $old_value;
    } 
    
    function v_zero_one( $new_value, $old_value ) {
        
        return 0 == (int) $new_value || 1 == (int) $new_value ? (int) $new_value : $old_value;
        
    }      

} // end of class
endif; // end of class exist check

