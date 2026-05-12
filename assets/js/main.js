( function () {
    'use strict';

    // Mobile menu toggle
    const toggle = document.querySelector( '.menu-toggle' );
    const nav    = document.querySelector( '.main-navigation' );

    if ( toggle && nav ) {
        toggle.addEventListener( 'click', function () {
            const expanded = this.getAttribute( 'aria-expanded' ) === 'true';
            this.setAttribute( 'aria-expanded', String( ! expanded ) );
            nav.classList.toggle( 'toggled' );
        } );
    }
} )();
