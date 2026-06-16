/**
 * Sesizare Modal — logică multi-step + hartă Leaflet + trimitere AJAX.
 *
 * Expune:
 *   - window.openSesizareModal()  → deschide modalul
 *   - window.closeSesizareModal() → închide modalul
 *   - orice element cu id="open-sesizare-modal" sau atributul
 *     data-open-sesizare-modal declanșează automat deschiderea.
 *
 * Depinde de Leaflet (handle 'leaflet-js') și de obiectul localizat
 * `sesizareModalData` (vezi sesizare-modal-loader.php).
 */
( function () {
    'use strict';

    var TOTAL_STEPS = 3;

    var state = {
        step: 1,
        map: null,
        marker: null,
        lat: null,
        lng: null,
        boundary: null,      // GeoJSON brut, folosit pentru restricția de click
        boundaryLoaded: false,
    };

    var el = {}; // populat la DOMContentLoaded

    function cacheEls() {
        el.overlay        = document.getElementById( 'sesizare-modal-overlay' );
        el.close          = document.getElementById( 'sesizare-close' );
        el.content         = document.getElementById( 'sesizare-step-content' );
        el.panels          = el.overlay ? el.overlay.querySelectorAll( '[data-step-panel]' ) : [];
        el.btnPrev         = document.getElementById( 'sesizare-btn-prev' );
        el.btnNext         = document.getElementById( 'sesizare-btn-next' );
        el.stepperItems     = el.overlay ? el.overlay.querySelectorAll( '[data-stepper-item]' ) : [];
        el.stepperFill     = document.getElementById( 'sesizare-stepper-fill' );

        el.lat             = document.getElementById( 'sesizare-lat' );
        el.lng             = document.getElementById( 'sesizare-lng' );
        el.mapContainer    = document.getElementById( 'sesizare-map' );
        el.mapError        = document.getElementById( 'sesizare-map-error' );

        el.tip             = document.getElementById( 'sesizare-tip' );
        el.imagine         = document.getElementById( 'sesizare-imagine' );
        el.imagineError    = document.getElementById( 'sesizare-imagine-error' );
        el.descriere       = document.getElementById( 'sesizare-descriere' );
        el.descriereError  = document.getElementById( 'sesizare-descriere-error' );

        el.nume            = document.getElementById( 'sesizare-nume' );
        el.email           = document.getElementById( 'sesizare-email' );
        el.telefon         = document.getElementById( 'sesizare-telefon' );
        el.consimtamant    = document.getElementById( 'sesizare-consimtamant' );
        el.submit          = document.getElementById( 'sesizare-submit' );
        el.submitOriginalHtml = el.submit ? el.submit.innerHTML : '';
        el.formMessage     = document.getElementById( 'sesizare-form-message' );
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    function isEmailValid( value ) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( value );
    }

    function isPhoneValid( value ) {
        return /^[+]?[0-9\s\-()]{7,15}$/.test( value );
    }

    function showFieldError( errorEl, message ) {
        if ( ! errorEl ) return;
        if ( message ) {
            errorEl.textContent = message;
            errorEl.classList.remove( 'hidden' );
        } else {
            errorEl.textContent = '';
            errorEl.classList.add( 'hidden' );
        }
    }

    function hasUnsavedData() {
        return Boolean(
            state.lat !== null ||
            ( el.tip && el.tip.value ) ||
            ( el.descriere && el.descriere.value.trim() ) ||
            ( el.imagine && el.imagine.files && el.imagine.files.length ) ||
            ( el.nume && el.nume.value.trim() ) ||
            ( el.email && el.email.value.trim() ) ||
            ( el.telefon && el.telefon.value.trim() )
        );
    }

    function i18n( key, fallback ) {
        if ( typeof sesizareModalData !== 'undefined' && sesizareModalData.i18n && sesizareModalData.i18n[ key ] ) {
            return sesizareModalData.i18n[ key ];
        }
        return fallback;
    }

    // ─── Leaflet ─────────────────────────────────────────────────────────────

    function initMapIfNeeded() {
        if ( state.map || ! el.mapContainer || typeof L === 'undefined' ) {
            return;
        }

        state.map = L.map( el.mapContainer, {
            zoomControl: true,
        } ).setView( [ 45.9432, 24.9668 ], 7 ); // centrul României, până se încarcă conturul localității

        L.tileLayer( 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 18,
        } ).addTo( state.map );

        state.map.on( 'click', function ( e ) {
            handleMapClick( e.latlng.lat, e.latlng.lng );
        } );

        loadBoundary();
    }

    function loadBoundary() {
        var url = typeof sesizareModalData !== 'undefined' ? sesizareModalData.geojsonUrl : '';
        if ( ! url ) {
            return; // niciun contur configurat → click permis oriunde pe hartă
        }

        fetch( url )
            .then( function ( r ) { return r.json(); } )
            .then( function ( data ) {
                state.boundary = data;
                state.boundaryLoaded = true;

                var layer = L.geoJSON( data, {
                    // nimigea.json poate conține și feature-uri de tip Point (ex. sediul
                    // primăriei) — randăm aici DOAR conturul (Polygon/MultiPolygon), ca să
                    // nu apară automat un marker care s-ar confunda cu pin-ul de selecție.
                    filter: function ( feature ) {
                        return feature.geometry && (
                            feature.geometry.type === 'Polygon' ||
                            feature.geometry.type === 'MultiPolygon'
                        );
                    },
                    style: {
                        color: '#a52121',
                        weight: 3,
                        dashArray: '10, 8',
                        fillColor: '#a52121',
                        fillOpacity: 0.08,
                    },
                } ).addTo( state.map );

                state.map.fitBounds( layer.getBounds(), { padding: [ 20, 20 ] } );
            } )
            .catch( function ( err ) {
                console.error( 'Eroare la încărcarea conturului localității:', err );
            } );
    }

    function handleMapClick( lat, lng ) {
        if ( state.boundaryLoaded && ! isPointInBoundary( lat, lng, state.boundary ) ) {
            showFieldError( el.mapError, i18n( 'outsideBoundary', 'Locația selectată trebuie să fie în interiorul localității.' ) );
            return;
        }

        showFieldError( el.mapError, '' );
        setLocation( lat, lng );
    }

    function setLocation( lat, lng ) {
        state.lat = lat;
        state.lng = lng;
        el.lat.value = lat;
        el.lng.value = lng;

        if ( state.marker ) {
            state.marker.setLatLng( [ lat, lng ] );
        } else {
            state.marker = L.marker( [ lat, lng ] ).addTo( state.map );
        }

        updateNavButtons();
    }

    // ─── Point-in-polygon (ray casting), suportă Polygon / MultiPolygon /
    // Feature / FeatureCollection — fără dependențe externe (fără turf.js) ───

    function isPointInBoundary( lat, lng, geojson ) {
        var pt = [ lng, lat ]; // ordinea GeoJSON: [lng, lat]
        var features;

        if ( geojson.type === 'FeatureCollection' ) {
            features = geojson.features;
        } else if ( geojson.type === 'Feature' ) {
            features = [ geojson ];
        } else {
            features = [ { geometry: geojson } ];
        }

        return features.some( function ( feature ) {
            var geometry = feature.geometry;
            if ( ! geometry ) return false;

            if ( geometry.type === 'Polygon' ) {
                return polygonContainsPoint( geometry.coordinates, pt );
            }

            if ( geometry.type === 'MultiPolygon' ) {
                return geometry.coordinates.some( function ( polygon ) {
                    return polygonContainsPoint( polygon, pt );
                } );
            }

            return false;
        } );
    }

    function polygonContainsPoint( rings, pt ) {
        if ( ! rings.length || ! rayCast( pt, rings[ 0 ] ) ) {
            return false; // în afara conturului exterior
        }
        for ( var i = 1; i < rings.length; i++ ) {
            if ( rayCast( pt, rings[ i ] ) ) {
                return false; // cade într-o "gaură" a poligonului
            }
        }
        return true;
    }

    function rayCast( point, vertices ) {
        var x = point[ 0 ], y = point[ 1 ];
        var inside = false;

        for ( var i = 0, j = vertices.length - 1; i < vertices.length; j = i++ ) {
            var xi = vertices[ i ][ 0 ], yi = vertices[ i ][ 1 ];
            var xj = vertices[ j ][ 0 ], yj = vertices[ j ][ 1 ];
            var intersect = ( ( yi > y ) !== ( yj > y ) ) && ( x < ( xj - xi ) * ( y - yi ) / ( yj - yi ) + xi );
            if ( intersect ) inside = ! inside;
        }

        return inside;
    }

    // ─── Navigare / stepper ─────────────────────────────────────────────────

    function goToStep( step ) {
        state.step = step;

        el.panels.forEach( function ( panel ) {
            var isActive = parseInt( panel.getAttribute( 'data-step-panel' ), 10 ) === step;
            panel.classList.toggle( 'hidden', ! isActive );
        } );

        el.stepperItems.forEach( function ( item ) {
            var n      = parseInt( item.getAttribute( 'data-stepper-item' ), 10 );
            var circle = item.querySelector( '[data-stepper-circle]' );
            if ( ! circle ) return;
            circle.classList.toggle( 'is-complete', n < step );
            circle.classList.toggle( 'is-current', n === step );
        } );

        if ( el.stepperFill ) {
            var pct = ( ( step - 1 ) / ( TOTAL_STEPS - 1 ) ) * 100;
            el.stepperFill.style.width = pct + '%';
        }

        el.btnPrev.disabled = step === 1;

        if ( step === TOTAL_STEPS ) {
            // Pe ultimul pas trimiterea se face din butonul dedicat din conținut.
            el.btnNext.disabled = true;
        }

        el.content.scrollTop = 0;

        if ( step === 1 ) {
            initMapIfNeeded();
            setTimeout( function () {
                if ( state.map ) state.map.invalidateSize();
            }, 50 );
        }

        updateNavButtons();
    }

    function updateNavButtons() {
        if ( state.step === 1 ) {
            el.btnNext.disabled = state.lat === null;
        } else if ( state.step === 2 ) {
            el.btnNext.disabled = ! isStep2Valid( false );
        } else {
            el.btnNext.disabled = true;
        }

        if ( state.step === 3 ) {
            updateSubmitState();
        }
    }

    function isStep2Valid( showErrors ) {
        var tipOk       = Boolean( el.tip && el.tip.value );
        var descriere   = el.descriere ? el.descriere.value.trim() : '';
        var descOk      = descriere.length >= 20;
        var fileOk       = true;

        if ( el.imagine && el.imagine.files && el.imagine.files.length ) {
            fileOk = validateFile( el.imagine.files[ 0 ], showErrors );
        } else if ( showErrors ) {
            showFieldError( el.imagineError, '' );
        }

        if ( showErrors ) {
            showFieldError( el.descriereError, descOk ? '' : i18n( 'descTooShort', 'Descrierea trebuie să aibă minimum 20 de caractere.' ) );
        }

        return tipOk && descOk && fileOk;
    }

    function validateFile( file, showErrors ) {
        var allowedTypes = ( typeof sesizareModalData !== 'undefined' && sesizareModalData.allowedMimeTypes ) || [ 'image/jpeg', 'image/png', 'image/gif', 'image/webp' ];
        var maxSize      = ( typeof sesizareModalData !== 'undefined' && sesizareModalData.maxFileSize ) || ( 5 * 1024 * 1024 );

        if ( allowedTypes.indexOf( file.type ) === -1 ) {
            if ( showErrors ) showFieldError( el.imagineError, i18n( 'fileInvalidType', 'Tipul fișierului nu este permis. Sunt acceptate doar imagini.' ) );
            return false;
        }

        if ( file.size > maxSize ) {
            if ( showErrors ) showFieldError( el.imagineError, i18n( 'fileTooLarge', 'Imaginea depășește dimensiunea maximă admisă (5MB).' ) );
            return false;
        }

        if ( showErrors ) showFieldError( el.imagineError, '' );
        return true;
    }

    function updateSubmitState() {
        var nume    = el.nume ? el.nume.value.trim() : '';
        var email   = el.email ? el.email.value.trim() : '';
        var telefon = el.telefon ? el.telefon.value.trim() : '';
        var agreed  = Boolean( el.consimtamant && el.consimtamant.checked );

        var valid = Boolean( nume ) && isEmailValid( email ) && isPhoneValid( telefon ) && agreed;
        el.submit.disabled = ! valid;
    }

    // ─── Trimitere AJAX ──────────────────────────────────────────────────────

    function submitForm() {
        if ( el.submit.disabled ) return;

        el.submit.disabled = true;
        var originalHtml = el.submit.innerHTML;
        el.submit.innerHTML = i18n( 'sending', 'Se trimite…' );

        var formData = new FormData();
        formData.append( 'action', sesizareModalData.action );
        formData.append( 'nonce', sesizareModalData.nonce );
        formData.append( 'sesizare_lat', state.lat );
        formData.append( 'sesizare_lng', state.lng );
        formData.append( 'sesizare_tip', el.tip.value );
        formData.append( 'sesizare_descriere', el.descriere.value.trim() );
        formData.append( 'sesizare_nume', el.nume.value.trim() );
        formData.append( 'sesizare_email', el.email.value.trim() );
        formData.append( 'sesizare_telefon', el.telefon.value.trim() );
        formData.append( 'sesizare_consimtamant', el.consimtamant.checked ? '1' : '0' );

        if ( el.imagine.files && el.imagine.files.length ) {
            formData.append( 'sesizare_imagine', el.imagine.files[ 0 ] );
        }

        fetch( sesizareModalData.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData,
        } )
            .then( function ( res ) { return res.json(); } )
            .then( function ( json ) {
                if ( json && json.success ) {
                    showFormMessage( json.data && json.data.message, true );
                    setTimeout( function () {
                        resetForm();
                        closeSesizareModal( true );
                    }, 1800 );
                } else {
                    showFormMessage( ( json && json.data && json.data.message ) || i18n( 'genericError', 'A apărut o eroare. Încearcă din nou.' ), false );
                    el.submit.disabled = false;
                    el.submit.innerHTML = originalHtml;
                }
            } )
            .catch( function () {
                showFormMessage( i18n( 'genericError', 'A apărut o eroare. Încearcă din nou.' ), false );
                el.submit.disabled = false;
                el.submit.innerHTML = originalHtml;
            } );
    }

    function showFormMessage( message, isSuccess ) {
        if ( ! el.formMessage ) return;
        el.formMessage.textContent = message || '';
        el.formMessage.classList.remove( 'hidden', 'text-red-300', 'text-green-300' );
        el.formMessage.classList.add( isSuccess ? 'text-green-300' : 'text-red-300' );
    }

    // ─── Reset / open / close ───────────────────────────────────────────────

    function resetForm() {
        state.lat = null;
        state.lng = null;

        if ( state.marker && state.map ) {
            state.map.removeLayer( state.marker );
            state.marker = null;
        }

        el.lat.value = '';
        el.lng.value = '';
        el.tip.value = '';
        el.imagine.value = '';
        el.descriere.value = '';
        el.nume.value = '';
        el.email.value = '';
        el.telefon.value = '';
        el.consimtamant.checked = false;

        showFieldError( el.imagineError, '' );
        showFieldError( el.descriereError, '' );
        showFieldError( el.mapError, '' );
        showFormMessage( '', true );
        el.formMessage.classList.add( 'hidden' );

        el.submit.disabled = true;
        el.submit.innerHTML = el.submitOriginalHtml;

        goToStep( 1 );
    }

    function openSesizareModal() {
        if ( ! el.overlay ) return;

        el.overlay.classList.remove( 'hidden' );
        document.body.classList.add( 'sesizare-modal-locked' );

        // forțează reflow ca tranziția CSS să se declanșeze
        requestAnimationFrame( function () {
            el.overlay.classList.add( 'is-open' );
        } );

        goToStep( state.step || 1 );
        initMapIfNeeded();
    }

    function closeSesizareModal( skipConfirm ) {
        if ( ! el.overlay ) return;

        if ( ! skipConfirm && hasUnsavedData() ) {
            var confirmed = window.confirm( i18n( 'confirmClose', 'Ai date necompletate în formular. Sigur vrei să închizi?' ) );
            if ( ! confirmed ) return;
        }

        el.overlay.classList.remove( 'is-open' );
        document.body.classList.remove( 'sesizare-modal-locked' );

        setTimeout( function () {
            el.overlay.classList.add( 'hidden' );
        }, 200 );
    }

    // ─── Event wiring ────────────────────────────────────────────────────────

    function bindEvents() {
        document.addEventListener( 'click', function ( e ) {
            var trigger = e.target.closest( '#open-sesizare-modal, [data-open-sesizare-modal]' );
            if ( trigger ) {
                e.preventDefault();
                openSesizareModal();
            }
        } );

        if ( el.close ) {
            el.close.addEventListener( 'click', function () { closeSesizareModal(); } );
        }

        if ( el.overlay ) {
            el.overlay.addEventListener( 'click', function ( e ) {
                if ( e.target === el.overlay ) closeSesizareModal();
            } );
        }

        document.addEventListener( 'keydown', function ( e ) {
            if ( e.key === 'Escape' && el.overlay && ! el.overlay.classList.contains( 'hidden' ) ) {
                closeSesizareModal();
            }
        } );

        el.btnPrev.addEventListener( 'click', function () {
            if ( state.step > 1 ) goToStep( state.step - 1 );
        } );

        el.btnNext.addEventListener( 'click', function () {
            if ( state.step === 1 && state.lat !== null ) {
                goToStep( 2 );
            } else if ( state.step === 2 && isStep2Valid( true ) ) {
                goToStep( 3 );
            }
        } );

        if ( el.tip ) el.tip.addEventListener( 'change', function () { updateNavButtons(); } );
        if ( el.descriere ) el.descriere.addEventListener( 'input', function () { updateNavButtons(); } );
        if ( el.imagine ) el.imagine.addEventListener( 'change', function () {
            if ( el.imagine.files && el.imagine.files.length ) {
                validateFile( el.imagine.files[ 0 ], true );
            }
            updateNavButtons();
        } );

        [ el.nume, el.email, el.telefon ].forEach( function ( field ) {
            if ( field ) field.addEventListener( 'input', updateSubmitState );
        } );
        if ( el.consimtamant ) el.consimtamant.addEventListener( 'change', updateSubmitState );

        if ( el.submit ) el.submit.addEventListener( 'click', submitForm );
    }

    document.addEventListener( 'DOMContentLoaded', function () {
        cacheEls();
        if ( ! el.overlay ) return; // markup-ul nu e prezent pe această pagină
        bindEvents();
    } );

    window.openSesizareModal  = openSesizareModal;
    window.closeSesizareModal = closeSesizareModal;

} )();
