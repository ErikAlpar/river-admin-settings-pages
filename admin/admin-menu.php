<?php

/**
 * River Admin Menu - Adds the River Admin menu and submenus to the 
 * WordPress Admin Menu backend.
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Menu
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

add_action( 'after_setup_theme', 'river_add_admin_menu' );
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
function river_add_admin_menu() {

    // Oops not viewing Admin. Just return
    if ( ! is_admin() )
        return;
  
    /**
     * Let's do some checks to ensure we should proceed
     */
    if ( ! current_theme_supports( 'river-admin-menu' ) )
        return; // Programmatically disabled  
    
    $current_user = wp_get_current_user();
//    if ( ! get_the_author_meta( 'river_admin_menu', $user->ID ) )
//        return; // Disabled for this user
      
    $page = new River_Admin_Settings_Page( river_settings_menu_page() );

    // Let's do the submenus now
    do_action( 'river_admin_menu' );

}

add_action( 'river_admin_menu', 'river_add_admin_submenus' );
/**
 * Adds submenu items under River item in admin menu.
 *
 * @since 0.0.0
 * 
 * @global string $_river_admin_seo_settings
 * @global string $_river_admin_import_export
 * @global string $_river_admin_readme
 * @return null Returns null if River menu is disabled
 */
function river_add_admin_submenus() {

    /** Do nothing, if not viewing the admin */
    if ( ! is_admin() )
        return;

    /** Don't add submenu items if River menu is disabled */
    if( ! current_theme_supports( 'river-admin-menu' ) )
        return;

    $user = wp_get_current_user();

    // Add "SEO" submenu item
    //if ( current_theme_supports( 'river-seo-settings-menu' ) && get_the_author_meta( 'river_seo_settings_menu', $user->ID ) )
    if ( current_theme_supports( 'river-seo-menu' ) )
        $page = new River_Admin_Settings_Page( river_seo_submenu() );
    
    if ( current_theme_supports( 'river-theme-options-menu' ) )
        $page = new River_Admin_Settings_Page( river_theme_options() );
	
}

