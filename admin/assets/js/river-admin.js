/**
 * riverAdmin JavaScript|jQuery functions
 * 
 * Load into riverAdmin namespace
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  River Admin JS
 * @since       0.0.3
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */
(function($){
    
    riverAdmin = {
        
        name: 'riverAdmin',

	/**
	 * Tasks on page load
	 *
	 * @since 0.0.4
	 *
	 * @function
	 */
        pageLoad: function() {
            
            // Turn off autocomplete for all browsers
            $("form").attr("autocomplete", "off");
            $("html, body").animate({ scrollTop: 0 }, "slow");
        },
        
	/**
	 * nav Section Handler
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */
        navSectionHandler: function() {           

            // Populate the sections[]
            riverAdmin.nav.find('ul li').each(function(){
                // Get the menu title and slug
                var title = $(this).children('a').text();
                var slug = $(this).attr('id');

                // If not already defined, add the title into the array
                if( "undefined" == typeof riverAdmin.sections[title] ) {
                    //riverAdmin.sections.push(title);
                    riverAdmin.sections[title] = [];
                }

                // If not already defined, add the menu_slug into the array
                if( "undefined" == typeof riverAdmin.sections[title][slug] ) {
                    riverAdmin.sections[title].push(slug);
                } 

            });

            // Add div wrap around each section
            var section = riverAdmin.content.find('h3').wrap("<div class=\"section\">");

            section.each( function () {
               $(this).parent().append($(this).parent().nextUntil("div.section")); 
            });    

           // Add 'id' to the section div wrap 	
           $("div.section").each(function(index) {
                $(this).attr("id", riverAdmin.sections[$(this).children("h3").text()]);
                if (index == 0)
                    $(this).addClass("current");
                else
                    $(this).addClass("hide");
            });   

            // listen for the click event & then scroll to the section
            riverAdmin.navA.on( 'click', function() {
                
                // This this section to current
                riverAdmin.setCurrent( this, this.hash.substring(1) );

            });        

        },

        /**
         * Sets the active section hash, by adding 'curent' class
         * & removes 'current' from all the other nav links
         * 
         * It also sets the 'current' and 'hide' for the corresponding
         * content section.
         * 
         * @since 0.0.4
         * 
         * @function
         * @param obj       $this
         * @param string    hash url
         */
        setCurrent: function($this, hash) {

            if (hash) {

                riverAdmin.nav
                    .find('.current').removeClass('current').end()
                    .find('a[href=#' + hash + ']').parent().addClass('current');

                riverAdmin.content
                    .find('.current')
                        .removeClass('current')
                        .addClass('hide')
                        .end()
                    .find('div#' + hash )
                        .removeClass('hide')
                        .addClass('current')
                        .end();
                        
                
                // Allow a little time for the new section tab to load up
                setTimeout( function() {

                    // Scroll to the top of the screen if it's not there already
                    //if( $("html, body").offset().top != 0 ) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    //}

                    // Remove the hash from the URL
                    riverAdmin.removeURLHash();
                    
                }, 200);                        
                        
            } 
        },
       
	/**
	 * Helper function for confirming a user action.
	 *
	 * @since 0.0.0
	 *
	 * @function
	 *
	 * @param {String} text The text to display.
	 * @returns {Boolean}
	 */
	confirm: function (text) {
		return confirm(text) ? true : false;
	},       

	/**
	 * imgselect handler
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */
        imgselectHandler: function() {

            /**
             * When the imgselect is clicked, remove 'selected' from all the
             * <label> elements and then add the 'selected' class to the
             * clicked img
             */
            $('label.imgselect input').on( 'click', function() { 
                // Walk up the DOM to the imgselect <div> for this imgselect group
                // Then remove 'selected' class from each of the <label> elements
                $(this).parent('label').parent('div').find('label').removeClass('selected');
                // Now assign 'selected' to the clicked image
                $(this).parent('label').addClass('selected');
            });         

        },
        
        
	/**
	 * upload-image handler
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */        
        uploadImageHandler: function() {
            
            $('.upload-button').on( 'click', function() {

                // grab the title tag, which we'll use in the header of the thickbox
                var title = $(this).attr( 'title' );
                // grab the targetfield to post the url back to
                targetfield = $(this).prev('.upload-url');

                // show Thickbox
                tb_show('Upload ' + title, 'media-upload.php?referer=wp-settings&type=image&TB_iframe=true&post_id=0', false);
                return false;
            });
            
            /**
             * Post the image's URL back to the targetfield, i.e. text field, and
             * then change the <img> src to the new URL
             */
            window.send_to_editor = function( html ) {

                imgurl = $('img', html).attr('src');
                $(targetfield)
                    .val(imgurl)
                    .parent('td')
                        .children('div#image-preview')
                            .css('display', 'block')
                            .children('img')
                                .attr('src', imgurl)
                                .end();

                tb_remove(); // close thickbox
            } 
            /**
             * Delete the image
             * 
             * Here we'll set the <img> src to '', hide the container for
             * image previewer, and clear out the upload-url text field val
             */
            $('div#image-preview a.delete-image').on('click', function(e) {
                e.preventDefault();
               
                $(this)
                    .prev('img')
                        .attr('src', '')
                        .end()
                    .parent('div')
                        .css('display', 'none')
                        .parent('td').children('input.upload-url')
                            .val('')
                            .end();

                    
            });
        },
        
	/**
	 * Removes the hash from the URL
	 *
	 * @since 0.0.4
	 *
	 * @function
	 */           
        removeURLHash: function () { 
            
            var loc = window.location.search.split('&');               

            //if url has '&reset=true or &error=true', remove it
            riverAdmin.pushState( loc[0] );
        },
        
	/**
	 * For HTML5 browsers, we use pushState; others (IE) use loc.hash.
	 *
	 * @since 0.0.4
	 *
	 * @function
	 */           
        pushState: function( finalLocSearch ) {          
            
            var loc = window.location;
            
            // Need to check if supports HTML5
            if( ! $.browser.msie ) {
                
                history.pushState("", document.title, loc.pathname + finalLocSearch );
                
            } else {
                
                var scrollV, scrollH ;
                
                if ("pushState" in history) {
                    history.pushState("", document.title, loc.pathname + finalLocSearch );
                } else {                  
                    // Prevent scrolling by storing the page's current scroll offset
                    scrollV = document.body.scrollTop;
                    scrollH = document.body.scrollLeft;

                    loc.hash = "";

                    // Restore the scroll offset, should be flicker free
                    document.body.scrollTop = scrollV;
                    document.body.scrollLeft = scrollH;
                }                
            }            
            
        },
        
	/**
	 * Time to initialize the river methods
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */
        ready: function() {
            
            /**
             * Cache global variabes
             */
            riverAdmin.container = $('div#river-container');
            riverAdmin.mainSection = riverAdmin.container.find('section#main');
            riverAdmin.content = riverAdmin.mainSection.find('.content');
            riverAdmin.nav = riverAdmin.mainSection.find('nav#river-sections');
            riverAdmin.navA = riverAdmin.nav.find('a');
            riverAdmin.sections = [];            
            
            riverAdmin.pageLoad();
            riverAdmin.navSectionHandler();
            riverAdmin.imgselectHandler(); 
            riverAdmin.uploadImageHandler();
        }

    };
   
        
    
    /**
     * Launch river
     *
     * @since 0.0.0
     * 
     * @river
     */
    $(document).ready(function () {
        riverAdmin.ready();
    });
    
})(jQuery);

/**
 * Helper function for confirming a user action.
 *
 * This function is deprecated in favor of riverAdmin.confirm(text) which provides
 * the same functionality.
 *
 * @since 0.0.0
 */
function river_confirm(text) {
        return riverAdmin.confirm(text);
} 

