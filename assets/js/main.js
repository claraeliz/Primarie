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

    // Banner slider
    const sliderEl = document.getElementById( 'bannerSlider' );

    if ( sliderEl ) {
        const track    = sliderEl.querySelector( '.banner-slider__track' );
        const viewport = sliderEl.querySelector( '.banner-slider__viewport' );
        const slides   = Array.from( track.children );
        const GAP      = 16; // 1rem gap
        const INTERVAL = 5000;

        let current  = 0;
        let timer    = null;

        function visibleCount() {
            const slideW = slides[ 0 ].offsetWidth + GAP;
            return Math.max( 1, Math.floor( viewport.offsetWidth / slideW ) );
        }

        function maxIndex() {
            return Math.max( 0, slides.length - visibleCount() );
        }

        function go( index ) {
            current = Math.max( 0, Math.min( index, maxIndex() ) );
            const slideW = slides[ 0 ].offsetWidth + GAP;
            track.style.transform = 'translateX(-' + ( current * slideW ) + 'px)';
        }

        function next() { go( current >= maxIndex() ? 0 : current + 1 ); }
        function prev() { go( current <= 0 ? maxIndex() : current - 1 ); }

        function startAutoplay() { timer = setInterval( next, INTERVAL ); }
        function stopAutoplay()  { clearInterval( timer ); }

        sliderEl.querySelector( '.banner-slider__prev' ).addEventListener( 'click', function () { stopAutoplay(); prev(); startAutoplay(); } );
        sliderEl.querySelector( '.banner-slider__next' ).addEventListener( 'click', function () { stopAutoplay(); next(); startAutoplay(); } );

        sliderEl.addEventListener( 'mouseenter', stopAutoplay );
        sliderEl.addEventListener( 'mouseleave', startAutoplay );

        startAutoplay();
    }

} )();
