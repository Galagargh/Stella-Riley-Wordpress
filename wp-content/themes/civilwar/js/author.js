jQuery(document).ready(function() {

	jQuery('.sidebar-link').on('click', function() {
        
        var articleNum = jQuery(".sidebar-link").index(this) + 1;
        var article = jQuery('#article' + articleNum)[0];
        
        article.scrollIntoView();
        window.scrollBy(0, -110);
        
    });
    

	jQuery(window).on('scroll', function() {
        
        
        
        var wi = jQuery(window).width();
        var scrollmath = Math.round(jQuery(window).scrollTop());
        
        
        
        // 410 is pixel height at which navbar sticks which changes for different screen sizes
        
        // xl
        if (scrollmath > 410 && scrollmath < 2280 && wi >= 1182) {
            jQuery('.sidebar-nav').addClass('fixed-sidebar-nav');
            jQuery('.sidebar-nav').removeClass('fixed-sidebar-bottom');
        } 
         // lg
        else if (scrollmath > 480 && scrollmath < 2710 && wi >= 975 && wi < 1182) {
            jQuery('.sidebar-nav').addClass('fixed-sidebar-nav');
            jQuery('.sidebar-nav').removeClass('fixed-sidebar-bottom');
        } 
         // xl stop before footer
        else if (scrollmath > 2280 && wi >= 1182) {
            jQuery('.sidebar-nav').addClass('fixed-sidebar-bottom');
            jQuery('.sidebar-nav').removeClass('fixed-sidebar-nav');
        } 
         // lg stop before footer
        else if (scrollmath >= 2710 && wi >= 975 && wi < 1182) {
            jQuery('.sidebar-nav').addClass('fixed-sidebar-bottom');
            jQuery('.sidebar-nav').removeClass('fixed-sidebar-nav');
        } 
        // md down, and if returns to top unaffix
        else {
            jQuery('.sidebar-nav').removeClass('fixed-sidebar-nav');
        }

      });
    
});