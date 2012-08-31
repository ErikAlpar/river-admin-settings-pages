/**
 * riverSettings JavaScript|jQuery functions
 * 
 * Load into riverAdmin namespace
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  River Settings JS
 * @since       0.0.3
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */
(function($){
        
    riverSettings = {
        
        name: 'riverSettings',
        
        /**
         * Cache variables passed from wp_localize_script in WordPress
         */
        ajaxURL: "undefined" != typeof riverAdminAjax.ajaxurl && riverAdminAjax.ajaxurl !== null ? riverAdminAjax.ajaxurl : '',
        resetRequest: "undefined" != typeof riverAdminAjax.resetRequest && riverAdminAjax.resetRequest !== null ? riverAdminAjax.resetRequest : '',
        formID: "undefined" != typeof riverAdminAjax.formID && riverAdminAjax.formID !== null ? riverAdminAjax.formID : '',
        settingsGroup: "undefined" != typeof riverAdminAjax.settingsGroup && riverAdminAjax.settingsGroup !== null ? riverAdminAjax.settingsGroup : '',
        pageID: "undefined" != typeof riverAdminAjax.pageID && riverAdminAjax.pageID !== null ? riverAdminAjax.pageID : '',
        riverNonce: "undefined" != typeof riverAdminAjax.riverNonce && riverAdminAjax.riverNonce !== null ? riverAdminAjax.riverNonce : '',

	/**
	 * Scrolling and centering of the popup boxes
	 *
	 * @since 0.0.3
	 *
	 * @function
	 */        
        messageCenter: function() {

            //Update Message popup
            $.fn.center = function () {
                this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
                this.css( "left", 250 );
                return this;
            }

            riverSettings.savePopup.center();
            riverSettings.nosavePopup.center();
            riverSettings.errorPopup.center();
            riverSettings.resetPopup.center();
            $(window).scroll(function() {
                riverSettings.savePopup.center();
                riverSettings.nosavePopup.center();
                riverSettings.errorPopup.center();                   
                riverSettings.resetPopup.center();

            });            
            
        },
       
	/**
	 * Handles "reset all options" tasks
	 *
	 * @since 0.0.3
	 *
	 * @function
	 */         
        reset: function() {

            if( '' != riverAdminAjax.resetRequest )
                riverSettings.resetInit = true;
            

            if ( riverSettings.resetInit ) {

                riverSettings.resetPopup.fadeIn();

                window.setTimeout(function() {
                    riverSettings.resetPopup.fadeOut();
                    riverSettings.resetInit = false;                        
                }, 2000);                    
            } 
            
            // Reset clicked function
            riverSettings.form.children('footer').find('input.reset-button').on("click", function() {                 
                url = '?page=' + riverSettings.pageID + '&reset=true';
                window.location =  url; 
            });            

        },
    
	/**
	 * Handles the form submit function & AJAX request/handling
	 *
	 * @since 0.0.3
	 *
	 * @function
	 */         
        submit: function() {
            
            //Save everything else
            riverSettings.form.submit( function() {
                
                var unchecked = '';                   
                var loc = window.location.search.split('&'); 
                var total = 0;
                var totalUnchecked = 0;                

                //if url has '&reset=true or &error=true', remove it
                if ( $.isArray(loc) && loc.length >= 2  ) {
                    riverAdmin.pushState( loc[0] );
                }
                
                // Get the unchecked checkboxes and add to the unchecked variable.
                riverSettings.form.find( 'input[type=checkbox].checkbox' ).not( ':checked' ).each( function() {
                    unchecked += '&' + riverSettings.settingsGroup + '%5B' + $(this).attr('id') + '%5D=0';                  
                });
                
                // If all the multicheck checkboxes are unchecked, then add
                // the multicheck to the unchecked variable.
                riverSettings.form.find( 'ul.multicheck').each( function() {
                    var $this = $(this);
                    var multicheck = $this.find( 'input[type=checkbox]' );
                    // Get the total number of checkboxes in this multicheck
                    total = multicheck.size();
                    // Get the total number that are unchecked
                    totalUnchecked = multicheck.not( ':checked' ).size()

                    // If all of them are unchecked, then add this multicheck
                    if ( total == totalUnchecked ) {
                        multicheck.not( ':checked' ).each( function() {
                            unchecked += '&' + riverSettings.settingsGroup + '%5B' + $(this).data('key') + '%5D='; 
                            return false;
                        });
                    }  
                });  

                // data =  $(this).serialize();
                // Note: We add unchecked to data because .serialize() does not
                //       include it.
                ajaxSave( 'save', 'settings_group=' + riverSettings.settingsGroup + '&' + 
                    $(this).serialize() + unchecked);

                return false;
            });

            /**
             * ajaxSave Handler for sending the form data back to the Settings
             * Page object and options database
             *
             * @since 0.0.3
             *
             * @function
             */             
            ajaxSave = function( action, inputData ) {

                $( '.ajax-loading-img').fadeIn();                  
                   
                data = {
                    /**
                     * We are straying from the standard 'GET' or 'POST'
                     * settings here for 'type' to redefine it for passing
                     * this page's settings_group.
                     */
                    type: riverSettings.settingsGroup,
                    data: inputData,
                    action: 'river_' + riverSettings.settingsGroup,
                    _ajax_nonce: riverSettings.riverNonce,
                    timeout: 5000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if ( 'timeout' == textStatus ) {
                            alert( 'AJAX Save Timed Out!' );
                        } else if ( 'error' == textStatus ) {
                            alert( 'AJAX Save Error!' );
                        }
                    }
                }                    
                //riverSettings.ajaxURL
                $.post( riverSettings.ajaxURL, data, function(response) {
                    var loading = $( '.ajax-loading-img' );
                    var popup;

                    if( 'save' == response) {
                        popup = riverSettings.savePopup;
                    } else if ( 'nosave' == response ) {
                        popup = riverSettings.nosavePopup;  
                    } else if ( 'error' == response ) {
                        popup = riverSettings.errorPopup;
                    }

                    popup.fadeIn();

                    loading.fadeOut();

                    window.setTimeout(function() {
                       popup.fadeOut();
                    }, 2000);
                });

            }             
            
        },


        /**
         * ready handles checking if riverSettings is ready for use.
         *
         * @since 0.0.3
         *
         * @function
         */          
        ready: function() {

            // If the variables were not passed, pop an error
            if ( '' == riverSettings.ajaxURL && 
                '' == riverSettings.resetRequest && 
                '' == riverSettings.formID && 
                '' == riverSettings.settingsGroup && 
                '' == riverSettings.pageID && 
                '' == riverSettings.riverNounce ) {

                alert( 'Variables not passed from wp_localize_script to setup AJAX!' );

            // Variables were passed, time to fire this baby up
            } else {

                // Cache the variables
                riverSettings.form = $( 'form#' + riverSettings.formID );
                riverSettings.resetPopup = $( '#river-popup-reset' );
                riverSettings.savePopup = $('#river-popup-save');
                riverSettings.nosavePopup = $('#river-popup-nosave');
                riverSettings.errorPopup = $('#river-popup-error');  

                riverSettings.resetInit = false;
                riverSettings.data;                

                // Bind the functions
                riverSettings.messageCenter();        
                riverSettings.reset();
                riverSettings.submit();                        
            }            
        }
    };
    
    
    
    /**
     * Launch riverSettings
     *
     * @since 0.0.3
     * 
     * @river
     */
    $(document).ready(function () {
        riverSettings.ready();
    });        

    
})(jQuery);