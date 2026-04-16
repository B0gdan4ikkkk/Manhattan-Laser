/**
 * Google Maps — тёмная тема сайта, один адрес, без InfoWindow.
 */
window.mlInitContactGoogleMap = function () {
	'use strict';

	var cfg = window.mlContactMapConfig || {};
	var el = document.getElementById( 'contact-google-map' );
	if ( ! el || typeof google === 'undefined' || ! google.maps ) {
		return;
	}

	var address = cfg.address || '';
	if ( ! address ) {
		return;
	}

	var ML_MAP_STYLES = [
		{ elementType: 'geometry', stylers: [ { color: '#18100d' } ] },
		{ elementType: 'labels.icon', stylers: [ { visibility: 'off' } ] },
		{ elementType: 'labels.text.fill', stylers: [ { color: '#6b645c' } ] },
		{ elementType: 'labels.text.stroke', stylers: [ { color: '#18100d' }, { weight: 3 } ] },
		{ featureType: 'administrative', elementType: 'geometry', stylers: [ { color: '#1c1714' } ] },
		{ featureType: 'administrative.land_parcel', stylers: [ { visibility: 'off' } ] },
		{ featureType: 'landscape.man_made', elementType: 'geometry', stylers: [ { color: '#1e1a17' } ] },
		{ featureType: 'poi', stylers: [ { visibility: 'off' } ] },
		{ featureType: 'poi.business', stylers: [ { visibility: 'off' } ] },
		{ featureType: 'poi.park', stylers: [ { visibility: 'off' } ] },
		{ featureType: 'road', elementType: 'geometry.fill', stylers: [ { color: '#2c2620' } ] },
		{ featureType: 'road', elementType: 'geometry.stroke', stylers: [ { color: '#18100d' }, { weight: 0.6 } ] },
		{ featureType: 'road.highway', elementType: 'geometry.fill', stylers: [ { color: '#383028' } ] },
		{ featureType: 'road.highway', elementType: 'geometry.stroke', stylers: [ { color: '#18100d' }, { weight: 0.8 } ] },
		{ featureType: 'road.local', elementType: 'geometry.fill', stylers: [ { color: '#26211c' } ] },
		{ featureType: 'transit', stylers: [ { visibility: 'off' } ] },
		{ featureType: 'water', elementType: 'geometry', stylers: [ { color: '#161210' } ] },
	];

	var pinSvg =
		'<svg xmlns="http://www.w3.org/2000/svg" width="40" height="52" viewBox="0 0 36 46">' +
		'<path d="M18 44C18 44 34 28.5 34 18C34 9.71573 26.8366 3 18 3C9.16344 3 2 9.71573 2 18C2 28.5 18 44 18 44Z" fill="#F4EFE8"/>' +
		'<circle cx="18" cy="18" r="5" fill="#18100D"/>' +
		'</svg>';
	var pinUrl = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent( pinSvg );

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( { address: address }, function ( results, status ) {
		if ( status !== 'OK' || ! results || ! results[ 0 ] ) {
			return;
		}

		var loc = results[ 0 ].geometry.location;
		var map = new google.maps.Map( el, {
			center: loc,
			zoom: cfg.zoom != null ? Number( cfg.zoom ) : 16,
			mapTypeControl: false,
			streetViewControl: false,
			fullscreenControl: true,
			backgroundColor: '#18100d',
			styles: ML_MAP_STYLES,
		} );

		new google.maps.Marker( {
			map: map,
			position: loc,
			icon: {
				url: pinUrl,
				scaledSize: new google.maps.Size( 40, 52 ),
				anchor: new google.maps.Point( 20, 52 ),
			},
		} );
	} );
};
