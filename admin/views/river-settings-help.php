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

$general = __( 'This is the help tab for the General Section.', 'river');


$screen->add_help_tab( array(
    'id'         => 'river_settings_general_help_tab',
    'title'     => 'General Tab Help',
    'content'   => "<p>$general</p>"
));


/**
 * Help Tab for the Appearance Section
 */
$appearance = __( 'This is the help tab for the Appearance Section.', 'river');

$screen->add_help_tab( array(
    'id'         => 'river_settings_appearance_help_tab',
    'title'     => 'Appearance Tab Help',
    'content'   => "<p>$appearance</p>"
));