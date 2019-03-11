/**
 * navigation.js
 *
 * My custom javascript that makes the navbar collapse 
 * still need to change pathname so navbar is solid on blog entries page
 *
 */
    
function navbarScroll(pathname){
	
    if (pathname == "Blog") {
        
        jQuery('.navbar').css('background-color', 'rgba(127, 105, 84, 1)');
    
    }else {
        
    	jQuery(window).on('scroll', function() {
            if (Math.round(jQuery(window).scrollTop()) > 100) {
            	jQuery('.navbar').addClass('scrolled');
            	jQuery('.navbar').css('background-color', 'rgba(127, 105, 84, 1)');
            } else {
            	jQuery('.navbar').removeClass('scrolled');
            	jQuery('.navbar').css('background-color', 'rgba(127, 105, 84, 0)');
            }
          });

    	jQuery('#StellaNav').on('show.bs.collapse', function () {
    		jQuery('.navbar').css('background-color', 'rgba(127, 105, 84, 1)');

        });

    	jQuery('#StellaNav').on('hide.bs.collapse', function () {
            if (jQuery('.navbar').hasClass('scrolled')) {
            	jQuery('.navbar').css('background-color', 'rgba(127, 105, 84, 1)');
            } else{
            	jQuery('.navbar').css('background-color', 'rgba(127, 105, 84, 0)');    
            }
        });
        
    }
    
}
    
/*
jQuery(document).ready(function() {
	navbarScroll();
});
*/
