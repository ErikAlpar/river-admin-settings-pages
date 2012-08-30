<?php

/**
 * Validate and Sanitize Settings Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Settings Sanitizer
 * @since       0.0.1
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

/**
 * Credits:     This code was inspired by the Genesis code and the work that
 *              Mark Jaquith did in Genesis_Settings_Sanitizer.
 */
if ( !class_exists( 'River_Settings_Sanitizer' ) ) :
/**
 * Class for validating and sanitizing both settings for the Admin Settings
 * Pages.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.1
 * 
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
    
    /**
     * Indicates if sanitizer is functioning on the default settings
     * 
     * @since 0.0.0
     * 
     * @var bool
     */
    protected $is_defaults_sanitizer = FALSE;
    
    /**
     * Indicates if the new sanitized and validated settings are identical
     * to the current options database
     * 
     * @since 0.0.2
     * 
     * @var bool
     */
    protected $is_settings_identical_to_db = FALSE;
    
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
     * @since 0.0.1
     *
     */
    protected function setup_filter_defaults() {
        
        $this->sanitizer_filters = apply_filters(
                'river_default_sanitizer_filters',             
                array( 
                    'zero_one'          => array( 'checkbox', 'button' ), 
                    'no_html'           => array(
                        'heading', 'text', 'upload', 'textarea', 'select', 'radio', 
                        'multicheck', 'upload-image', 'email', 'url', 'imgselect',
                        'colorpicker'
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
                    'boolean_multi'     => array(),
                    'datetime_format'   => array( 'datepicker', 'timepicker' ),        
                    'email'             => array( 'email' ),        
                    'hex'               => array( 'colorpicker' ),
                    'integer'           => array(),
                    'numeric'           => array(),
                    'string'            => array( 'text', 'textarea' ),
                    'string_choices'    => array( 'imgselect', 'multicheck', 'radio', 'select' ),
                    'url'               => array( 'upload-image', 'url' ),
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
         */
        add_filter( 'sanitize_option_' . $this->settings_group, 
                array( $this, 'do_validate_sanitize' ), 10, 2 );

        return true;

    }
    
    /**
     * Validate and sanitize a value, via the filter types associated with an
     * option.
     *
     * @since 0.0.2
     *
     * @param mixed     $new_value New value
     * @param string    $option Name of the option
     * @return mixed    Filtered, or unfiltered value
     */
    public function do_validate_sanitize( $new_value, $option ) {
        
        $this->is_settings_identical_to_db = false;

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
                
                // We don't store a heading type in the options database
                if ( 'heading' == $setting['type'] )
                    continue;
                
                $old_value[$key] = isset( $old_value[$key] ) ? $old_value[$key] : '';
                $new_value[$key] = isset( $new_value[$key] ) ? $new_value[$key] : '';
                
                // This option is an array
                if ( is_array( $new_value[$key] ) ) {
                    
                    if ( isset( $new_value[$key] ) ) {
                    
                        $temp_new_value = array();

                        foreach( $new_value[$key] as $sub_key => $sub_value) {
                            
                            if( $new_value[$key] !== $old_value[$key] ) {

                                $temp_new_value[$sub_key] = $this->do_validator_filter( 
                                        $setting['validator_filter'], 
                                        $sub_value, $old_value[$key], $sub_key, $key );

                                if ( $temp_new_value === $old_value[$key] )
                                    break;                                
                            }

                            // Pass through the sanitizer filter and store updated value
                            $temp_new_value[$sub_key] = $this->do_sanitizer_filter( 
                                    $setting['sanitizer_filter'], 
                                    $sub_value, $old_value[$key] );


                            if( $temp_new_value == $old_value[$key] )
                                break;
                        }

                        $new_value[$key] = $temp_new_value;
                    }
                    
                // This option is not an array    
                } else {
                    
                    // if the new value = old value, then no need to validate
                    if( ( $new_value[$key] !== $old_value[$key] ) ) {
                        // Pass through the validator filter first and store updated value
                        $new_value[$key] = $this->do_validator_filter( 
                                $setting['validator_filter'], 
                                $new_value[$key], $old_value[$key], $new_value[$key], $key );
                    }
                    
                    // Pass through the sanitizer filter and store updated value
                    $new_value[$key] = $this->do_sanitizer_filter( 
                            $setting['sanitizer_filter'], 
                            $new_value[$key], $old_value[$key] );

                }
            }
            
            // Tell the caller that the new_value is identical to the old_value
            $this->is_settings_identical_to_db = $new_value === $old_value ? TRUE : FALSE;
            $GLOBALS['river-is-seetings-identical-to-db'] = $this->is_settings_identical_to_db;
            
            return $new_value;
        }
        
        // We should never hit this, but just to be safe....
        return $new_value;

    }

    /** Sanitizer Filter ******************************************************/  
    
    /**
     * Checks sanitization filter exists, and if so, passes the value through it.
     * 
     * @uses call_user_func() to provide the callback to assigned filter
     *
     * @since 0.0.0
     *
     * @param string    $filter Sanitizer filter type
     * @param string    $new_value New value
     * @param string    $old_value Current value in the options database
     * @return mixed    Returns filtered value
     */
    protected function do_sanitizer_filter( $filter, $new_value, $old_value ) {

        $available_filters = $this->get_available_sanitizer_filters();

        if ( ! in_array( $filter, array_keys( $available_filters ) ) )
            return $new_value;

        // Callback to the assigned filter
        return call_user_func( $available_filters[$filter], $new_value, $old_value );

    }

    /**
     * Return array of known sanitizer filter types.
     *
     * Array can be filtered via 'river_available_sanitizer_filters' to let
     * child themes and plugins add their own sanitization filters.
     *
     * @since 0.0.0
     *
     * @return array    Associative array containing the sanitizer filter types
     *                  as the keys and filter method callback as the values
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
     * Checks validation filter exists, and if so, passes the value through it.
     * 
     * @uses call_user_func() to provide the callback to assigned filter
     *
     * @since 0.0.0
     *
     * @param string    $filter Validator filter type
     * @param string    $new_value New value
     * @param string    $old_value Current value in the options database
     * @return mixed    Returns filtered value
     */
    protected function do_validator_filter( $filter, $new_value, $old_value, $options_key, $key ) {

        $available_filters = $this->get_available_validator_filters();

        if ( ! in_array( $filter, array_keys( $available_filters ) ) )
            return $new_value;
        
        // Callback to the assigned filter
        return call_user_func( $available_filters[$filter], $new_value, $old_value, $options_key, $key );

    }

    /**
     * Return array of known validator filter types.
     *
     * Array can be filtered via 'river_available_validator_filters' to let
     * child themes and plugins add their own sanitization filters.
     *
     * @since 0.0.1
     *
     * @return array    Associative array containing the validator filter types
     *                  as the keys and filter method callback as the values
     */
    function get_available_validator_filters() {

        $default_filters = array(
                'absint'            => array( $this, 'v_absint'         ),
                'boolean'           => array( $this, 'v_boolean'        ),
                'boolean_multi'     => array( $this, 'v_boolean_multi'  ),
                'date_format'       => array( $this, 'v_datetime_format'),        
                'email'             => array( $this, 'v_email'          ),        
                'hex'               => array( $this, 'v_hex'            ),
                'integer'           => array( $this, 'v_integer'        ),
                'numeric'           => array( $this, 'v_numeric'        ),
                'string'            => array( $this, 'v_string'         ),
                'string_choices'    => array( $this, 'v_string_choices' ),            
                'url'               => array( $this, 'v_url'            ),
                'zero_one'          => array( $this, 'v_zero_one'       ),            
        );        

        return apply_filters( 'river_available_validator_filters', $default_filters );
                
    } 
    

    /** Sanitizer Filter Methods **********************************************/    

    /**
     * Returns a 0 or 1
     *
     * Uses double casting. First, we cast to boolean, then to integer.
     *
     * @since 0.0.0
     *
     * @param mixed     $new_value Should ideally be a 0 or 1 integer passed in
     * @return integer  1 or 0.
     */
    function zero_one( $new_value ) {        

        return (int) (bool) $new_value;

    }

    /**
     * Removes HTML tags from string.
     *
     * @since 0.0.0
     *
     * @param string    $new_value String, possibly with HTML in it
     * @return string   String without HTML in it.
     */
    function no_html( $new_value ) {

        return strip_tags( $new_value );

    }

    /**
     * Removes unsafe HTML tags, via wp_kses_post().
     *
     * @since 0.0.0
     *
     * @param string    $new_value String with potentially unsafe HTML in it
     * @return string   String with only safe HTML in it
     */
    function safe_html( $new_value ) {

        return wp_kses_post( $new_value );

    }

    /**
     * Keeps the option from being updated if the user lacks unfiltered_html
     * capability.
     * 
     * @uses current_user_can( $capability )
     * 
     * @since 0.0.0
     *
     * @param string    $new_value New value
     * @param string    $old_value Current value in the options database
     * @return string   Returns unfiltered new value if user has the
     *                  capability; else returns the old value.
     * @link http://codex.wordpress.org/Function_Reference/current_user_can
     */
    function requires_unfiltered_html( $new_value, $old_value ) {

        if ( current_user_can( 'unfiltered_html' ) ) {
            return $new_value;
        } else {
            return $old_value;
        }

    }
    

    /** Validator Filter Methods **********************************************/
    
    /**
     * Absolute integer value (non-negative integers) validation
     * 
     * @uses absint( $maybeint )
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param +integer  $old_value Current value in options database
     * @return +integer If new value is numeric, returns absolute integer value
     *                  of new value; else returns the old value 
     * @link http://codex.wordpress.org/Function_Reference/absint
     */
    function v_absint( $new_value, $old_value ) {
        
        if ( is_string( $new_value ) )
            $new_value = trim( $new_value );
        
        return is_numeric ( $new_value ) ? absint( $new_value ) : $old_value;       
        
    }
    
    /**
     * Boolean (TRUE, FALSE, 0, 1) validation
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param bool|int  $old_value Current value in options database
     * @return bool|int If new value is boolean or integer and equal to 0 or 1,
     *                  returns new value; else returns the old value 
     */    
    function v_boolean( $new_value, $old_value ) {
        
        if ( is_string( $new_value ) )
            $new_value = trim( $new_value );
        
        $new_value = is_numeric( $new_value ) ? (int) $new_value : $new_value;
        
        return is_bool( $new_value )|| 
            ( ( is_integer( $new_value ) && $new_value == 0 || $new_value == 1 ) ) ? 
            $new_value : $old_value;
        
    }
    
    /**
     * Boolean (TRUE, FALSE, 0, 1) validation for multi-boolean field types
     * 
     * First it checks that the option's key exists in the default setting's
     * choices array.  If yes, then it calls v_boolean to validate the new value.
     * Else, it returns the old value.
     * 
     * @uses v_boolean()
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param bool|int  $old_value Current value in options database
     * @param string    $option_key This option's key name (used to see if it
     *                  exists in the default setting's choices array.
     * @param string    $id Option's id in the default settings
     * @return bool|int If new value is boolean or integer and equal to 0 or 1,
     *                  returns new value; else returns the old value 
     */     
    function v_boolean_multi( $new_value, $old_value, $option_key, $id ) {
        
        return array_key_exists( $option_key, $this->default_settings[$id]['choices'] ) ? 
                $this->v_boolean($new_value, $old_value) : $old_value;
        
    }      
    
    /**
     * Date and/or time format validation
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @return string   If new value returns a timestamp, returns the new
     *                  value; else returns the old value 
     */     
    function v_datetime_format( $new_value, $old_value ) {
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return strtotime($new_value) ? $new_value : $old_value;
    }
    
    /**
     * Email validation
     * 
     * @uses is_email()
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @return string   If new value returns a timestamp, returns the new
     *                  value; else returns the old value
     * @link http://codex.wordpress.org/Function_Reference/is_email
     */     
    function v_email( $new_value, $old_value ) {
        
        if( ! isset( $new_value ) )
            return $new_value;
        
        $new_value = is_string( $new_value ) ? trim( $new_value ) : '';
        
        return is_email( $new_value ) ? sanitize_email( $new_value ) : $old_value;
        
    } 
    
    /**
     * Hexadecimal validation
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @return string   If new value is in hex, returns the new
     *                  value; else returns the old value 
     */     
    function v_hex( $new_value, $old_value) {

        if ( empty ($new_value) )
            return $new_value;
        
        return ctype_xdigit( $new_value ) ? $new_value : $old_value;
    } 
    
    /**
     * Integer validation
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @return string   If new value is an integer, returns the new
     *                  value; else returns the old value 
     */     
    function v_integer( $new_value, $old_value ) {
        
        if ( is_string( $new_value ) )
            $new_value = trim( $new_value );
        
        if( is_numeric( $new_value ) )
            $new_value = (int) $new_value;
        
        return is_integer( $new_value ) ? $new_value : $old_value;
        
    }
    
    /**
     * Numeric validation
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @return string   If new value is numeric, returns the new
     *                  value; else returns the old value 
     */     
    function v_numeric( $new_value, $old_value ) {
        
        if ( is_string( $new_value ) )
            $new_value = trim( $new_value );
        
        return is_numeric( $new_value ) ? $new_value : $old_value;
        
    } 
    
    /**
     * String validation
     * 
     * @since 0.0.0
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @return string   If new value is a string, returns the new
     *                  value; else returns the old value 
     */     
    function v_string( $new_value, $old_value ) {
        
        return is_string( $new_value ) ? trim( $new_value ) : $old_value;
        
    }
    
    
    /**
     * Choices validation - validating the new value is in the default settings'
     * choices for this option.
     * 
     * @since 0.0.3
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @param string    $option_key This option's key name (used to see if it
     *                  exists in the default setting's choices array.
     * @param string    $id Option's id in the default settings
     * @return string   If new value is a string and it's key is in the
     *                  default settings choices array, returns new value;
     *                  else returns the old value 
     */     
    function v_string_choices( $new_value, $old_value, $option_key, $id ) {
        
        if( ! is_string( $new_value) )
            return $old_value;
        
        if ( empty ( $new_value ) )
            return $new_value;
        
        return array_key_exists( $option_key, $this->default_settings[$id]['choices'] ) ? 
                $new_value : $old_value;
        
    }    
    
    /**
     * URL validation
     * 
     * Because of the complexities of an URL, we are only testing the
     * 
     * 
     * @since 0.0.2
     * 
     * @param string    $new_value New value to validate
     * @param string    $old_value Current value in options database
     * @param bool      $return_empty TRUE: return new_value if it's an empty string
     * @return string   If new value is an integer, returns the new
     *                  value; else returns the old value 
     */     
    function v_url( $new_value, $old_value ) {
        
        if ( is_string( $new_value ) ) {
            $new_value = trim( $new_value );
            
            if ( empty( $new_value) )
                return $new_value;
            
        } else {
            return $old_value;
        }
        
        return preg_match( '#http(s?)://(.+)#i', $new_value ) ? $new_value : $old_value;
    } 
    
    /**
     * Zero One (0, 1) validation
     * 
     * Use this one when you want just an integer back and not true or false bool.
     * 
     * @since 0.0.1
     * 
     * @param string    $new_value New value to validate
     * @param bool|int  $old_value Current value in options database
     * @return int      If new value is boolean or integer and equal to 0 or 1,
     *                  returns new value in int; else returns the old value 
     */     
    function v_zero_one( $new_value, $old_value ) {
        
        $new_value = $this->v_boolean( $new_value, $old_value );
        
        return is_bool($new_value) ? (int) $new_value : $new_value;
        
    }      

} // end of class
endif; // end of class exist check

