
function carouselButton(maxCarousel, linkArray) {
	
	
    window.carousel_Value = 0;
    window.currentPostLink = linkArray[carousel_Value];
    window.carousel_Num = maxCarousel - 1;

    console.log(carousel_Value);
    
    jQuery('.view-post-btn').click(function () {
    	
        window.location.href = currentPostLink;
        
    });
	 
    //
    // work out index in carousel after user input 
    // then set post link for button based on that index
    //
    // uses max carousel -1 cause it works
    //
    jQuery('#blog_posts').on('slide.bs.carousel', function (event) {
        
        direction = event.direction;
        
        function setPostLink(carouselPos){
        	currentPostLink = linkArray[carousel_Value];
        	return;
        }
        
        if (direction == "left" && carousel_Value < carousel_Num) {
            carousel_Value++;
            setPostLink(carousel_Value);
        } else if (direction == "right" && carousel_Value >= carousel_Num) {
            carousel_Value--;
            setPostLink(carousel_Value);
        } else if (direction == "right" && carousel_Value == carousel_Num){
            carousel_Value = carousel_Num;
            setPostLink(carousel_Value);
        } else if (direction == "right" && carousel_Value == 0){
            carousel_Value = carousel_Num;
            setPostLink(carousel_Value);
        } else {
            carousel_Value = 0;
            setPostLink(carousel_Value);
        }
        
        return currentPostLink;
        
    });
    
     
}






