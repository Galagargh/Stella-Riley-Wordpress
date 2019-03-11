function hideBoxes() {
    jQuery("#TB_window").remove();
    jQuery("#TB_overlay").remove();
}

function showBox( id, modal, width, height ) {
    if ( typeof modal === 'undefined' ) {
        modal = false;
    }
    hideBoxes();
    tb_show( '', '#TB_inline?height=' + height + '&width=' + width + '&inlineId=' + id + '&modal=' + modal, null );
}

function startInstall( site_type, theme_id ) {
    showBox( 'oneandone-setup-install', true, 550, 150 );

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

( function( $ ) {

    $( 'ul.site-type-selector a' ).on( 'click', function() {
        var type = $( this ).attr( 'id' ).replace( 'oneandone-site-type-', '' );
        var url = ajax_assistant_object.ajaxurl;

        $( 'ul.site-type-selector li' ).removeClass( 'active' );
        $( this ).parent( 'li' ).addClass( 'active' );

        $( '.site-type-themes-box' ).removeClass( 'active' );
        $( '#oneandone-themes-' + type ).addClass( 'active' );

        $.ajax( {
            type: 'POST',
            dataType: 'html',
            url: url,
            data: 'site_type=' + type + '&action=ajaxload',

            success: function( response ) {
                var themes_container = $( '#oneandone-themes-' + type + ' .site-type-themes-list' );

                if ( ! themes_container.hasClass( 'loaded' ) ) {
                    themes_container.addClass( 'loaded' ).html( response );
                }
            }
        } );
    } );

    $( 'ul.site-type-selector a:first' ).trigger( 'click' );

} )( jQuery );