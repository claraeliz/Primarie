( function() {
    'use strict';

    document.addEventListener( 'DOMContentLoaded', function() {

        if ( typeof hartaComunaData === 'undefined' ) {
            return;
        }

        var mapEl = document.getElementById( 'harta-comuna' );
        if ( ! mapEl ) {
            return;
        }

        var map = L.map( 'harta-comuna', {
            zoomControl: false,   // adăugat manual jos-dreapta, să nu acopere textul
            scrollWheelZoom: false
        } ).setView(
            [ hartaComunaData.centerLat, hartaComunaData.centerLng ],
            hartaComunaData.zoom
        );

        L.control.zoom( { position: 'bottomright' } ).addTo( map );

        setTimeout( function() { map.invalidateSize(); }, 200 );

        L.tileLayer( 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 11,
            
        } ).addTo( map );

        // Conturul comunei
        fetch( hartaComunaData.geojsonUrl )
            .then( function( r ) { return r.json(); } )
            .then( function( data ) {
                var layer = L.geoJSON( data, {
                    style: {
                        color: '#a52121',
                        weight: 3,
                        dashArray: '10, 8',
                        fillColor: '#a52121',
                        fillOpacity: 0.08
                    }
                } ).addTo( map );

                map.fitBounds( layer.getBounds(), { padding: [ 20, 20 ], animate: false } );
                map.panBy( [ -320, 0 ], { animate: false } );
            } )
            .catch( function( err ) {
                console.error( 'Eroare la încărcarea GeoJSON:', err );
            } );

        // Marker primărie
        L.marker( [ hartaComunaData.primarieLat, hartaComunaData.primarieLng ] )
            .addTo( map )
            .bindPopup( '<b>' + hartaComunaData.primarieLabel + '</b>' )
            .openPopup();

    } );

} )();