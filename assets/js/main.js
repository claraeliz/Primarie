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

    // Banner slider — infinite loop via clone technique
    const sliderEl = document.getElementById( 'bannerSlider' );

    if ( sliderEl ) {
        const track    = sliderEl.querySelector( '.banner-slider__track' );
        const viewport = sliderEl.querySelector( '.banner-slider__viewport' );
        const GAP      = 16;
        const INTERVAL = 5000;

        // Clone all original slides and append for seamless forward loop.
        // Also prepend clones for seamless backward loop, then start at N.
        const origSlides = Array.from( track.children );
        const N          = origSlides.length;

        origSlides.forEach( s => track.appendChild( s.cloneNode( true ) ) );  // append clones
        origSlides.forEach( s => track.insertBefore( s.cloneNode( true ), track.firstChild ) ); // prepend clones

        // Start at the first real slide (offset N to skip prepended clones).
        let current = N;
        let locked  = false;
        let timer   = null;

        function sw() { return track.children[ 0 ].offsetWidth + GAP; }

        function jumpTo( i ) {
            track.style.transition = 'none';
            current = i;
            track.style.transform  = 'translateX(-' + ( i * sw() ) + 'px)';
            track.offsetHeight; // force reflow so transition: none takes effect
            track.style.transition = '';
        }

        function slideTo( i ) {
            if ( locked ) return;
            locked  = true;
            current = i;
            track.style.transform = 'translateX(-' + ( i * sw() ) + 'px)';
        }

        track.addEventListener( 'transitionend', function () {
            if ( current >= N * 2 ) jumpTo( current - N ); // went past appended clones → jump to real
            else if ( current < N ) jumpTo( current + N ); // went before prepended clones → jump to real
            locked = false;
        } );

        function next() { slideTo( current + 1 ); }
        function prev() { slideTo( current - 1 ); }

        function startAutoplay() { timer = setInterval( next, INTERVAL ); }
        function stopAutoplay()  { clearInterval( timer ); }

        sliderEl.querySelector( '.banner-slider__prev' ).addEventListener( 'click', function () { stopAutoplay(); prev(); startAutoplay(); } );
        sliderEl.querySelector( '.banner-slider__next' ).addEventListener( 'click', function () { stopAutoplay(); next(); startAutoplay(); } );

        sliderEl.addEventListener( 'mouseenter', stopAutoplay );
        sliderEl.addEventListener( 'mouseleave', startAutoplay );

        jumpTo( N ); // poziție inițială fără animație
        startAutoplay();
    }

} )();
