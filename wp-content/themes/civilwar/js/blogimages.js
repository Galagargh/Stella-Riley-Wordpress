
jQuery(document).ready(function() {
    
    var wi;
    var pathname = window.location.pathname;
    
    var archivePos = new Tether({
    	  element: archive,
    	  target: navbar,
    	  attachment: 'top right',
    	  targetAttachment: 'bottom right',
    	  enabled: 'false',
    	  constraints: [
    	    {
    	      to: 'window',
    	      pin: true
    	    },
    	    {
    	      to: blog,
    	      pin: true
    	    }
    	  ]
    	});
    	        
    	function archivePosition(){
    	    
    	    if(wi < 1008){
    	        archivePos.disable();
    	        jQuery('#archive').css('position', 'relative');
    	        jQuery('#archive').css('top', '0px');  
    	    } else if (wi >=1008) {
    	        archivePos.enable();
    	        jQuery('#archive').css('position', 'fixed');
    	    }
    	    
    	}
    	   
    	      
    	/*	TAKEN OUT HERE */

    	        
    	jQuery(window).load(function () {
    	    wi = jQuery(window).width();
    	    archivePosition();
    	    blogImages();	    
    	});
    	    
    	jQuery(window).resize(function() {
    	    wi = jQuery(window).width();
    	    archivePosition(); 
    	    
    	    if(wi > 550){
    	        jQuery('.desktop').show();
    	    } else if (wi < 550){
    	        jQuery('.desktop').hide();
    	    }
    	});    
    	 
    	var scrollmath = Math.round(jQuery(window).scrollTop());
               
});


function blogImages(postAmount, postImages){
    
    //var latestBlogImages = ["images/AnthonyVanDyck_Large.jpg", "images/Charles_Landseer_Cromwell_Battle_of_Naseby(large).jpg", "images/The_Plunering_of_Basing_House.jpg"];  

    // finds number of blog posts
    //var blogPostImages = jQuery( ".blog-post-container" ).length;

	wi = jQuery(window).width();
	
    if (wi > 550) {
                
        for (i = 0; i < postAmount; i++) {

        jQuery('.blog-post-image'+i).parallax({
        parallax: scroll,
        imageSrc: postImages[i],
        position: 'center center',
        bleed: 50, 
        naturalHeight: 200
        });

        }

        jQuery('.desktop').show();
        
    } else if (wi < 550){

        jQuery('.desktop').hide();
        
    }
}
