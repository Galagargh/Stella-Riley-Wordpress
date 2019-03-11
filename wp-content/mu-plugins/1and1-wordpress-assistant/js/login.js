var jq = jQuery.noConflict();

(function( $ ) {

    /*
     * Input styling
     */
    $( 'input' ).each( function() {

        var placeholder = '';
        var id = $( this ).attr( 'id' );
        var name = $( this ).attr( 'name' );

        switch ( $( this ).attr( 'type' )) {

            /*
             * Input text HTML example:
             *
             * <div class="form-input">
             *     <input type="text" placeholder="[label]">
             *     <div class="underline"></div>
             * </div>
             */
            case 'text':
            case 'email':
            case 'password':

                // Question: does the label CONTAINS the input?
                if ( $( this ).parent().is( 'label' ) ) {

                    // Save the label text for the placeholder
                    placeholder = $.trim( $( this ).parent().text() );

                    // Remove the label text
                    $( this ).parent().contents().filter( function() {
                        return ( ! $( this ).is( 'input' ) );
                    } ).remove();

                    // Remove the label container
                    $( this ).unwrap();

                // Question: does the label PRECEDES or FOLLOWS the input?
                } else {
                    var label;

                    // Retrieve the label element (is it before or after?)
                    if ( id ) {
                        if ( $( this ).prev().is( 'label[for=' + id + ']' ) ) {
                            label = $( this ).prev();
                        } else if ( $( this ).next().is( 'label[for=' + id + ']' ) ) {
                            label = $( this ).next();
                        }
                    }
                    if ( label ) {
                        // Save the label text for the placeholder
                        placeholder = label.text();

                        // Remove the label element
                        label.remove();
                    }
                }

                // Wrap the result in our custom HTML + add placeholder
                $( this ).wrap( '<div class="form-input' + ( name ? ' ' + name : '' ) + '"></div>' )
                    .after( '<div class="underline"></div>' )
                    .attr( 'placeholder', placeholder )
                    .val( '' );
                break;

            /*
             * Input checkbox HTML example:
             *
             * <div class="checkboxes">
             *     <div class="item">
             *         <input type="checkbox" />
             *         <label class="label">[label]</label>
             *     </div>
             *     <div class="item">
             *         <label>
             *             <input type="checkbox" />
             *             <span class="label">[label]</span>
             *         </label>
             *     </div>
             * </div>
             */
            case 'checkbox':

                // Question: does the label CONTAINS the input?
                if ( $( this ).parent().is( 'label' ) ) {

                    // Wrap the label text in a span with the "label" class
                    $( this ).parent().contents().filter( function() {
                        return ( this.nodeType === 3 && $.trim( this.nodeValue ) );
                    } ).wrap( '<span class="label"></span>' );

                    // Wrap label + input in the extra containers
                    $( this ).parent()
                        .wrap( '<div class="item"></div>' ).parent()
                        .wrap( '<div class="checkboxes"></div>' );
                }

                // Question: does the label FOLLOWS the input?
                if ( $( this ).next().is( 'label' ) ) {

                    // Add the "label" class to the label
                    $( this ).next().addClass( 'label' );

                    // Wrap label + input in the extra containers
                    $( this ).add( $( this ).next() )
                        .wrapAll( '<div class="item"></div>' ).parent()
                        .wrap( '<div class="checkboxes"></div>' );
                }
                break;
        }
    });

    /*
     * Card animation on loading
     */
    var card = $( '.assistant-card' );

    card.css( {
        transform: 'rotateX(5deg) rotateY(5deg) rotateZ(0deg) scale(.91)'
    } ).addClass( 'morphing-first' );

    setTimeout( function() {
        card.removeClass('morphing-first').css( {
            transform: 'rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1)'
        } );
    }, 400 );

} )( jq );