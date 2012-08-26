/**
 * River Admin Interface JavaScript
 *
 * All JavaScript logic for the theme options admin interface.
 * @since 4.8.0
 *
 */
(function($){

    /**
     * Cache global variabes
     */
    var container = $('div#river-container');
    var mainSection = container.find('section#main');
    var content = mainSection.find('.content');
    var nav = mainSection.find('nav#river-sections');
    var navA = nav.find('a');
    var sections = [];

    window['river'] = {

	/**
	 * Tasks on page load
	 *
	 * @since 0.0.0
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
            nav.find('ul li').each(function(){
                // Get the menu title and slug
                var title = $(this).children('a').text();
                var slug = $(this).attr('id');

                // If not already defined, add the title into the array
                if( "undefined" == typeof sections[title] ) {
                    //sections.push(title);
                    sections[title] = [];
                }

                // If not already defined, add the menu_slug into the array
                if( "undefined" == typeof sections[title][slug] ) {
                    sections[title].push(slug);
                } 

            });

            // Add div wrap around each section
            var section = content.find('h3').wrap("<div class=\"section\">");

            section.each( function () {
               $(this).parent().append($(this).parent().nextUntil("div.section")); 
            });    

           // Add 'id' to the section div wrap 	
           $("div.section").each(function(index) {
                $(this).attr("id", sections[$(this).children("h3").text()]);
                if (index == 0)
                    $(this).addClass("current");
                else
                    $(this).addClass("hide");
            });    

            // listen for the click event & then scroll to the section
            navA.on( 'click', function() {
                // This this section to current
                $.when(river.setCurrent( this, this.hash.substring(1) )).then(river.removeURLHash() );
// TO DO:  THIS IS JERKY RIGHT NOW
                $("html, body").animate({ scrollTop: 0 }, "slow");

            }); 

        },

        /**
         * Sets the active section hash, by adding 'curent' class
         * & removes 'current' from all the other nav links
         * 
         * It also sets the 'current' and 'hide' for the corresponding
         * content section.
         * 
         * @since 0.0.0
         * 
         * @function
         * @param obj       $this
         * @param string    hash url
         */
        setCurrent: function($this, hash) {

            if (hash) {

                nav
                    .find('.current').removeClass('current').end()
                    .find('a[href=#' + hash + ']').parent().addClass('current');

                content
                    .find('.current')
                        .removeClass('current')
                        .addClass('hide')
                        .end()
                    .find('div#' + hash )
                        .removeClass('hide')
                        .addClass('current')
                        .end();
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
        
        removeURLHash: function () { 
            var loc = window.location;
console.log(loc);            
            
            // Need to check if supports HTML5
            // http://stackoverflow.com/questions/1397329/how-to-remove-the-hash-from-window-location-with-javascript-without-page-refresh
            if( true ) {
                
                history.pushState("", document.title, loc.pathname + loc.search);
                
            } else {
                
                var scrollV, scrollH ;
                
                if ("pushState" in history) {
                    history.pushState("", document.title, loc.pathname + loc.search);
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
console.log('removeURLHash after ' + loc);
        },
        
        pushState: function( finalLocSearch ) {
            
            var loc = window.location;
            
            // Need to check if supports HTML5
            // http://stackoverflow.com/questions/1397329/how-to-remove-the-hash-from-window-location-with-javascript-without-page-refresh
            if( '' ) {
                
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
            
            river.pageLoad();
            river.navSectionHandler();
            river.imgselectHandler(); 
            river.uploadImageHandler();
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
        river.ready();
    });
    
})(jQuery);

/**
 * Helper function for confirming a user action.
 *
 * This function is deprecated in favor of river.confirm(text) which provides
 * the same functionality.
 *
 * @since 1.0.0
 */
function river_confirm(text) {
        return river.confirm(text);
} 

