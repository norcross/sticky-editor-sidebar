//*****************************************************************************************************
// handle sticky sidebar calculations
//*****************************************************************************************************
function SES_IsScrolledTo( elem ) {

    var doc_top     = jQuery( window ).scrollTop(); //num of pixels hidden above current screen
    var doc_bottom  = doc_top + jQuery( window ).height();
    var elem_top    = jQuery( elem ).offset().top; //num of pixels above the elem
    var elem_bottom = elem_top + jQuery( elem ).height();

    return ( (elem_top <= doc_top) );

}

//*****************************************************************************************************
// make the sticky editor sidebar
//*****************************************************************************************************
function SES_SideSticky( floatWrap, floatBox ) {

    // set the float box
    var pubBox  = jQuery( floatWrap ).find( floatBox );

    // get our sizes and offsets
    var pubWth  = ( pubBox ).width();
    var offTop  = jQuery( floatWrap ).offset().top - 50;
    var offRgt  = ( jQuery( window ).width() - ( pubBox.offset().left + pubBox.outerWidth() ) );

    // add our floater
    jQuery( pubBox ).before( '<span class="float-block"></span>' ).addClass( 'box-floater' );

    // set our two items to check and compare
    var catcher = jQuery( 'span.float-block' );
    var sticky  = jQuery( 'div.box-floater' );

    // check the position on load
    if( SES_IsScrolledTo( sticky ) ) {
        // add the actual sticky CSS
        sticky.css({
            'width' : pubWth + 'px',
            'position' : 'fixed',
            'top' : offTop + 'px',
            'right' : offRgt + 'px',
            'z-index' : '9999',
        });
        // set the opacity of all the other items
        jQuery( floatWrap ).find( 'div.postbox' ).not( 'div.box-floater' ).css( 'opacity', sesOptions.opacity );
    }

    // start checking on scroll
    jQuery( window ).scroll( function() {
        // if we hit our mark
        if( SES_IsScrolledTo( sticky ) ) {
            // add the actual sticky CSS
            sticky.css({
                'width' : pubWth + 'px',
                'position' : 'fixed',
                'top' : offTop + 'px',
                'right' : offRgt + 'px',
                'z-index' : '9999',
            });
            // set the opacity of all the other items
            jQuery( floatWrap ).find( 'div.postbox' ).not( 'div.box-floater' ).css( 'opacity', sesOptions.opacity );
        }
        // if we get back to the top, remove our stuff
        if ( ( offTop + 20 ) > sticky.offset().top ) {
            // remove the sticky CSS
            sticky.removeAttr( 'style' );
            // remove the opacity of all the other items
            jQuery( floatWrap ).find( 'div.postbox' ).not( 'div.box-floater' ).css( 'opacity', '' );
        }
    });

}

//*****************************************************************************************************
// start the engine
//*****************************************************************************************************
jQuery(document).ready( function($) {

//*****************************************************************************************************
// check our screen size on itital load
//*****************************************************************************************************

    var SES_ScreenSize = $( window ).width();

//*****************************************************************************************************
// make the publish box floatable for items we set with the class
//*****************************************************************************************************
    $( window ).load( function() {
        // bail on pages without body class
        if ( ! $( 'body' ).hasClass( 'sticky-editor-side' ) ) {
            return;
        }
        // bail if we dont have our wpcontent div ID
        if ( ! $( 'div#side-sortables' ).length ){
            return;
        }
        // check the width of our container (since we dont side by side on mobile)
        // and load if we are above the breakpoint
        if ( SES_ScreenSize > 850 ) {
            SES_SideSticky( 'div#side-sortables', 'div#submitdiv' );
        }
        // now do our check again on resize
        $( window ).resize(function() {
            // get our new width
            SES_ScreenSize   = $( window ).width();
            // apply if on tablet or above
            if ( SES_ScreenSize > 850 ) {
                SES_SideSticky( 'div#side-sortables', 'div#submitdiv' );
            } else {
                $( 'div.box-floater' ).removeAttr( 'style' );
            }

        });


    });
//*****************************************************************************************************
// we are done here. go home
//*****************************************************************************************************
});