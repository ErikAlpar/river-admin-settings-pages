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



if ( isset( $this->form['site_url'] ) ) {
    $site_url = $this->form['site_url'];
} else {
    $site_url = '';
}

?>
<div id="river-container" class="wrap">
    
    <div id="river-popup-save" class="river-save-popup">
        <div class="river-save-save">
            <?php esc_html( _e( 'Options Updated', 'river' ) ); ?>
        </div>
    </div>
    <div id="river-popup-reset" class="river-save-popup">
        <div class="river-save-reset">
            <?php esc_html( _e( 'Options Reset', 'river' ) ); ?>
        </div>
    </div>
    <div id="river-popup-error" class="river-save-popup">
        <div class="river-save-error">
            <?php esc_html( _e( 'Error Occurred', 'river' ) ); ?>
        </div>
    </div>    
    
    
    
    <?php
    // Start the form
    printf( '<form id="%s" action="/" method="post" enctype="multipart/form-data">',
            esc_attr( $this->form['id'] ) );

        /**
         * Outputs nonce, action, and option_page fields
         * @link http://codex.wordpress.org/Function_Reference/settings_fields
         */
        settings_fields( $this->settings_group ); 
        ?>

        <header id="header">
            <div class="logo"></div>
            <div class="info">
                <span class="page-title">
                    <?php echo esc_html( get_admin_page_title() ); ?>
                </span>
                <span class="page-version">
                    <?php printf( __('Version %s', 'river' ), 
                            esc_html( $this->form['version'] ) ); ?>
                </span>
            </div>
            <div class="clearfix"></div>
            <nav id="support">
                <ul>
                    <li class="changelog">
                        <?php 

                        printf( '<a href="%s" title="Changelog" target="_blank">%s</a>',
                                esc_url( $site_url . $this->form['changelog_url'] ),
                                esc_html( __( 'View Changelog', 'river' ) ) );
                        ?>
                    </li>

                    <li class="docs">
                        <?php 

                        printf( '<a href="%s" title="Documentation" target="_blank">%s</a>',
                                esc_url( $site_url . $this->form['docs_url'] ),
                                esc_html( __( 'View Docs', 'river' ) ) );
                        ?>
                    </li>
                    <li class="forum">
                        <?php 

                        printf( '<a href="%s" title="Forums" target="_blank">%s</a>',
                                esc_url( $site_url . $this->form['forum_url'] ),
                                esc_html( __( 'Visit Forum', 'river' ) ) );
                        ?>
                    </li>
                    <li class="right">
                        <?php 

                        printf( '<img style="display:none" src="%s" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />', 
                                RIVER_ADMIN_URL . '/assets/images/loading-top.gif' );                        
                        printf( '<input class="button submit-button" type="submit" value="%s">',
                                esc_html( __( 'Save All Changes', 'river' ) ) );
                        ?>

                    </li>
                </ul>                    
            </nav>
        </header>
        <div class="clearfix"></div>
        <section id="main">

            <?php if( $this->sections ) : ?>

                <nav id="river-sections">
                    <div class="shadow"></div>
                    <ul>
                        <?php 
                        $first = true;
                        // Render the nav for each section
                        foreach ( $this->sections as $slug => $this->section ) {
                            // Note: We are not using esc_html for the <a>
                            // href tag because this is a hash (in page)
                            // link.
                            printf( '<li id="%1$s" class="%1$s %2$s">' .
                                    '<div class="river-menu-arrow"><div></div></div>' .
                                    '<a href="#%1$s"' .
                                    'title="%3$s">%4$s</a></li>',
                                    esc_attr( $slug ),
                                    $first ? 'current' : '',
                                    esc_attr( $this->section ),
                                    esc_html( $this->section )
                                    );
                            $first = false;
                        }
                        ?>
                    </ul>
                </nav>

            <?php endif; ?>

            <div class="content">
                <?php do_settings_sections( $_GET['page'] ); ?>
            </div>

        </section>

        <footer id="save-reset" class="clearfix">
            
            <?php

            printf( '<img style="display:none" src="%s" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />', 
                    RIVER_ADMIN_URL . '/assets/images/loading-bottom.gif' );
            // @link http://codex.wordpress.org/Function_Reference/submit_button            
            submit_button( 
                    // $text The text of the button (default 'Save Changes')
                    $this->form['button_save_text'],
                    // $type Type of button: primary, secondary, delete
                    'primary', 
                    // $name HTML name of the submit button
                    'submit',
                    // $wrap True to wrap in <p> tag
                    false
                    // $other_attributes (opt) array|string
                    );


            printf( '<input id="%1$s" class="button reset-button" type="button" value="%2$s" onclick="%3$s">', 
                    sprintf( '%s[%s]', 
                            $this->settings_group, 
                            $this->default_settings['reset']['id'] ),
                    $this->default_settings['reset']['title'],
                    'return river_confirm(\'' . 
                        esc_js( __( 'Are you sure you want to reset? ' ) )
                        . '\n' .
                        esc_js( __( 'All settings for this page will be lost & reset back to the default settings.', 'river' ) ) 
                        . '\');'                     
                    );             

            ?>

        </footer>

    </form> 
    
</div><!-- /#river-container -->   

