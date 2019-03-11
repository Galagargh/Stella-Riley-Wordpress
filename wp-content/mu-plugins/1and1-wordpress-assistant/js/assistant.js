var tb_position;
var cardClasses = 'assistant-card animate';
var cardSelector = '.assistant-card.animate';

jQuery( document ).ready( function( $ ) {

    /**
     * WP Thickbox tb_position() is being overriden by media-upload.js (known bug: https://core.trac.wordpress.org/ticket/39267)
     * we fix this by writing our own tb_position() and take the occasion to customize some stuff
     */
    tb_position = function() {
        var tb_window = $( '#TB_window' );
        var tb_inner = $( '#TB_ajaxContent' );
        var custom_tb_width = 700;
        var custom_tb_height = tb_inner.children( ':first' ).outerHeight(true);
        
        tb_window
            .addClass(
                'card-lightbox'
            ).css( {
                marginLeft: '-' + parseInt((custom_tb_width / 2),10) + 'px',
                marginTop: '-' + parseInt((custom_tb_height / 2),10) + 'px',
                width: custom_tb_width + 'px'
            } );
        
        tb_inner
            .css( {
                width: custom_tb_width + 'px',
                height: 'auto'
            } );
    };

    /**
     * Animation opening the card
     *
     * @param {jQuery} firstStep
     */
    function cardFadeIn( firstStep ) {
        var card = $( cardSelector );
        var firstStepId = firstStep.attr( 'id' ).replace( 'card-', '' );

        card.attr( 'class', cardClasses + ' card-' + firstStepId )
            .css( { transform: 'rotateX(5deg) rotateY(5deg) rotateZ(0deg) scale(.91)' } )
            .addClass( 'morphing-first' );

        setTimeout( function() {
            $( cardSelector )
                .removeClass( 'morphing-first' )
                .css( { transform: 'rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1)' } );
        }, 400 );

        firstStep.show();
    }

    /**
     * Animation between each card
     *
     * @param {string} stepId
     */
    function cardSwitch( stepId ) {
        var card = $( cardSelector );
        var nextStep = $( '#card-' + stepId );

        card.find( '.active' ).removeClass( 'active' ).hide();

        card.attr( 'class', cardClasses + ' card-' + stepId )
            .css( { transform: 'rotateX(-5deg) rotateY(5deg) rotateZ(0deg) scale(.91)' } ).
        addClass( 'morphing' );

        setTimeout( function() {
            card.removeClass( 'morphing' )
                .css( { transform: 'rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1)' } );
        }, 200 );

        nextStep.addClass( 'active' ).show();
    }

    /**
     * Installation of the chosen theme
     * and the recommended plugins with the site type
     *
     * @param {string} site_type
     * @param {string} theme_id
     */
    function startInstall( site_type, theme_id ) {
        cardSwitch( 'install' );

        if ( typeof theme_id === 'undefined' ) {
            theme_id = '';
        }

        if ( typeof site_type !== 'undefined' ) {

            var form = jQuery( 'form#oneandone-install-form-' + site_type );
            var url = ajax_assistant_object.ajaxurl;
            var data = form.serialize() + '&site_type=' + site_type + '&theme=' + theme_id + '&action=ajaxinstall';

            jQuery.ajax( {
                type: 'POST',
                dataType: 'json',
                url: url,
                data: data,

                success: function( response ) {
                    window.location = response.data.referer;
                }
            } );
        }
    }

    // Open the site type menu (mobile)
    $( '.diys-sidebar-menu-btn' ).on( 'click', function( event ) {
        event.preventDefault();

        $( '.diys-sidebar-wrapper' ).toggleClass( 'open' );
    } );

    // Configure the loading of themes for each site type
    $( '.diys-sidebar-tabs a' ).on( 'click', function( event ) {
        event.preventDefault();

        $( '.diys-sidebar-wrapper' ).removeClass( 'open' );
        $( '.current-site-type' ).text( $( this ).text() );

        var type = $( this ).attr( 'id' ).replace( 'site-type-', '' );
        var url = ajax_assistant_object.ajaxurl;

        $( '.diys-sidebar-tabs li' ).removeClass( 'active' );
        $( this ).parent( 'li' ).addClass( 'active' );

        $( '.theme-list' ).removeClass( 'active' );
        $( '#themes-' + type ).addClass( 'active' );

        $.ajax( {
            type: 'POST',
            dataType: 'html',
            url: url,
            data: 'site_type=' + type + '&action=ajaxload',

            success: function( response ) {
                var themes_container = $( '#themes-' + type + ' .theme-list-inner' );

                if ( ! themes_container.hasClass( 'loaded' ) ) {
                    themes_container.addClass( 'loaded' ).html( response );
                }

                themes_container.find( '.theme' ).click( function() {
                    startInstall(
                        $( this ).data( 'site-type' ),
                        $( this ).data( 'theme' )
                    )
                } );
            }
        } );
    } );

    // Pop open the card and show the first content node (with the "active" class)
    var firstStep = $( cardSelector + ' .card-step.active' );
    if ( firstStep.length > 0 ) {
        cardFadeIn( firstStep );
    }
    
    // Pop open the card (using WP thickbox) in the Customizer
    $( window ).on( 'load', function() {
        var customizerCard = $( '#card-congrats-lightbox' );

        if ( customizerCard.length > 0 && typeof tb_show === 'function' ) {
            $( '#TB_window' ).remove();
            $(' #TB_overlay' ).remove();

            tb_show( '', '#TB_inline?inlineId=card-congrats-lightbox&modal=true', null );
        }
    } );

    // Animate the card and show next content node
    $( '[id^=goto-]' ).click( function ( event ) {
        event.preventDefault();

        var nextStepId = $( this ).attr( 'id' ).replace( 'goto-', '' );
        cardSwitch( nextStepId );

        // Show the list of themes of the first site type
        if ( nextStepId === 'design' ) {
            $( '.diys-sidebar-wrapper a:first' ).trigger( 'click' );
        }
    } );

    // Show the list of themes of the first site type if we are in the "design" step
    var firstUseCase = $( '.diys-sidebar-wrapper a:first' );
    if ( firstUseCase.is( ':visible' ) ) {
        firstUseCase.trigger( 'click' );
    }

} );