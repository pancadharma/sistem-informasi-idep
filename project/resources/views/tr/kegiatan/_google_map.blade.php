<div id="googleMap" style="height: 400px; width: 100%;"></div>

@push('js')
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script>
    // Use a global object to store map state
    window.mapState = {
        map: null,
        markers: {}, // Use an object to store markers by unique ID
        markerClusterer: null,
        bounds: null,
        infoWindows: {} // Store infoWindows by unique ID
    };

    function initMap() {
        // Initialize map and bounds
        window.mapState.map = new google.maps.Map(document.getElementById("googleMap"), {
            zoom: 5,
            center: { lat: -2.548926, lng: 118.0148634 }, // Center of Indonesia
        });
        window.mapState.bounds = new google.maps.LatLngBounds();

        // Initialize the clusterer right after the map
        window.mapState.markerClusterer = new markerClusterer.MarkerClusterer({ map: window.mapState.map, markers: [] });

        // Initial marker setup from loaded data
        const initialLocations = @json($kegiatan->lokasi);
        if (initialLocations.length > 0) {
            initialLocations.forEach(function(location, index) {
                const row = $(`.list-lokasi-kegiatan .lokasi-kegiatan`).eq(index);
                if (row.length) {
                    const uniqueId = row.data('unique-id');
                    if (location.lat && location.long) {
                        addOrUpdateMarker(uniqueId, location.lat, location.long, location.lokasi, location.desa);
                    }
                }
            });

            // Fit map to bounds if there are markers
            if (Object.keys(window.mapState.markers).length > 0) {
                window.mapState.map.fitBounds(window.mapState.bounds);
            }
        }

        // --- DYNAMIC EVENT LISTENERS ---
        $('.list-lokasi-kegiatan').on('change', '.lat-input, .lang-input', function() {
            const row = $(this).closest('.lokasi-kegiatan');
            const uniqueId = row.data('unique-id');
            const lat = row.find('.lat-input').val();
            const lng = row.find('.lang-input').val();
            const lokasi = row.find('.lokasi-input').val();
            const desaSelect = row.find('.kelurahan-select');
            const desa = {
                nama: desaSelect.find('option:selected').text(),
                kecamatan: {
                    nama: row.find('.kecamatan-select option:selected').text()
                }
            };

            if (lat && lng) {
                addOrUpdateMarker(uniqueId, lat, lng, lokasi, desa);
            }
        });

        $('.list-lokasi-kegiatan').on('click', '.remove-lokasi-row', function() {
            const row = $(this).closest('.lokasi-kegiatan');
            const uniqueId = row.data('unique-id');
            removeMarker(uniqueId);
        });
    }

    function addOrUpdateMarker(uniqueId, lat, lng, lokasi, desa) {
        const latLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));

        if (window.mapState.markers[uniqueId]) {
            const marker = window.mapState.markers[uniqueId];
            marker.setPosition(latLng);

            const infoWindow = window.mapState.infoWindows[uniqueId];
            const newInfoWindowContent = `
                <div class="info-window-content">
                    <strong>Lokasi:</strong> ${lokasi || '-'}
                    <br><strong>Desa:</strong> ${desa && desa.nama ? desa.nama : '-'}
                    <br><strong>Kecamatan:</strong> ${desa && desa.kecamatan && desa.kecamatan.nama ? desa.kecamatan.nama : '-'}
                </div>`;
            infoWindow.setContent(newInfoWindowContent);

        } else {
            const marker = new google.maps.Marker({
                position: latLng,
                title: lokasi,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 7,
                    fillColor: "red",
                    fillOpacity: 1,
                    strokeWeight: 1,
                    strokeColor: "white"
                }
            });

            const infoWindowContent = `
                <div class="info-window-content">
                    <strong>Lokasi:</strong> ${lokasi || '-'}
                    <br><strong>Desa:</strong> ${desa && desa.nama ? desa.nama : '-'}
                    <br><strong>Kecamatan:</strong> ${desa && desa.kecamatan && desa.kecamatan.nama ? desa.kecamatan.nama : '-'}
                </div>`;

            const infoWindow = new google.maps.InfoWindow({
                content: infoWindowContent
            });

            marker.addListener('click', () => {
                infoWindow.open(window.mapState.map, marker);
            });

            window.mapState.markers[uniqueId] = marker;
            window.mapState.infoWindows[uniqueId] = infoWindow;

            window.mapState.markerClusterer.addMarker(marker);
        }

        updateMapBounds();
    }

    function removeMarker(uniqueId) {
        if (window.mapState.markers[uniqueId]) {
            const marker = window.mapState.markers[uniqueId];

            window.mapState.markerClusterer.removeMarker(marker);

            delete window.mapState.markers[uniqueId];
            delete window.mapState.infoWindows[uniqueId];

            updateMapBounds();
        }
    }

    function updateMapBounds() {
        window.mapState.bounds = new google.maps.LatLngBounds();
        const currentMarkers = Object.values(window.mapState.markers);

        if (currentMarkers.length > 0) {
            currentMarkers.forEach(marker => {
                window.mapState.bounds.extend(marker.getPosition());
            });
            window.mapState.map.fitBounds(window.mapState.bounds);
        } else {
            window.mapState.map.setCenter({ lat: -2.548926, lng: 118.0148634 });
            window.mapState.map.setZoom(5);
        }
    }

</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ&callback=initMap&v=beta&libraries=maps">
</script>
@endpush

