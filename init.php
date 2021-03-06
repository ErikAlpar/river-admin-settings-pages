<?php

/**
 * Admin Page Class
 *
 * @category    River 
 * @package     Framework
 * @subpackage  Init
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

function river_define_constants() {
    
    /** Define Parent Theme ***************************************************/    

    define( 'RIVER_NAME', 'River' );
    define( 'RIVER_VERSION', '0.0.0' );
    define( 'RIVER_DB_VERSION', '000' );
    define( 'RIVER_RELEASE_DATE', date_i18n( 'F j, Y', '1340211600' ) );
    
    /** Define Paths **********************************************************/     
    define( 'RIVER_DIR', get_template_directory() );
    define( 'RIVER_URL', get_template_directory_uri() );
    define( 'CHILD_DIR', get_stylesheet_directory() );
    define( 'CHILD_URL', get_stylesheet_directory_uri() );
    
    // Lib
    define( 'RIVER_LIB_DIR', RIVER_DIR . '/lib' );    
    define( 'RIVER_LIB_URL', RIVER_URL . '/lib' );
    
    // Framework
    define( 'RIVER_ADMIN_DIR', RIVER_LIB_DIR . '/framework/admin' );    
    define( 'RIVER_ADMIN_URL', RIVER_LIB_URL . '/framework/admin' );
    define( 'RIVER_CORE_DIR', RIVER_LIB_DIR . '/framework/core' );    
    define( 'RIVER_CORE_URL', RIVER_LIB_URL . '/framework/core' );
    
    /** Define Database Constants *********************************************/
    define( 'RIVER_SETTINGS', 'river_settings' );
    define( 'RIVER_SEO_SETTINGS', 'river_seo_settings' );
    define( 'RIVER_SANITIZER_ERROR', 'RIVER SANITIZER ERROR');
    define( 'RIVER_FIELD_TYPE_ERROR', 'RIVER FIELD TYPE ERROR');
    
}
add_action( 'river_init', 'river_define_constants' );

/**
 * 
 * 
 * @since 0.0.0
 */
function river_load_includes() {
    
    // Run the river_pre_framework hook, which is called from the Child theme
    do_action( 'river_pre_framework' );
    
    /** Core ******************************************************************/     
    require_once( RIVER_CORE_DIR . '/core-helpers.php' );    
    
    /** Admin *****************************************************************/
    if ( is_admin() ) {
        require_once( RIVER_ADMIN_DIR . '/class-admin.php' );
        require_once( RIVER_ADMIN_DIR . '/class-admin-sanitizer.php' ); 
        require_once( RIVER_ADMIN_DIR . '/class-admin-fields.php' );      
        require_once( RIVER_ADMIN_DIR . '/class-admin-settings-page.php' );
        require_once( RIVER_ADMIN_DIR . '/admin-helpers.php'); 
        
        require_once( RIVER_ADMIN_DIR . '/class-river-settings-page.php' );
        require_once( RIVER_ADMIN_DIR . '/class-seo-settings-page.php' );
    }
  
    
}
add_action( 'river_init', 'river_load_includes' );

function river_add_theme_supports() {

    add_theme_support( 'river-admin-menu' );
    add_theme_support( 'river-seo-menu' );
    add_theme_support( 'river-theme-options-menu' );

    
}
add_action( 'river_init', 'river_add_theme_supports' );


/**
 * Everything is loaded.  Time to run River.
 */
do_action( 'river_init' );