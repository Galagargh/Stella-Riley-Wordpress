jQuery(document).ready(function() {
	

	var wi;

	var shareFlagTether = new Tether({
	  element: share,
	  target: navbar,
	  attachment: 'top left',
	  targetAttachment: 'bottom left',
	//  offset: '0 40px',
	//  targetOffset: '40px 0',
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
	
	
	function flagVisibility(){
		   
	    if(wi >=700){
	    shareFlagTether.enable();
	    jQuery('.share-flag').show();
	    } else {
	    shareFlagTether.disable();
	    jQuery('.share-flag').hide();
	    }
	    
	}
	
	
	jQuery(window).load(function () {
	    wi = jQuery(window).width();
	    flagVisibility();
	});
	    
	jQuery(window).resize(function() {
	    
	    var hidden;
	    wi = jQuery(window).width();
	    flagVisibility();
	    console.log('resize method run')
	        
	});

});