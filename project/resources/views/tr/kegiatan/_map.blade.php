@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

<div id="mapid" style="height: 400px;"></div>

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script>
        // Use a global object to store map state
        window.mapStateLeaflet = {
            map: null,
            markers: {}, // Use an object to store markers by unique ID
            markerClusterGroup: null,
            bounds: null,
            infoWindows: {} // Store infoWindows by unique ID (though Leaflet popups are tied to markers)
        };

        $(document).ready(function() {
            window.mapStateLeaflet.map = L.map('mapid').setView([-2.548926, 118.0148634], 5);

            // Using CartoDB Positron for a light, subdued map style
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }).addTo(window.mapStateLeaflet.map);

            window.mapStateLeaflet.markerClusterGroup = L.markerClusterGroup();
            window.mapStateLeaflet.map.addLayer(window.mapStateLeaflet.markerClusterGroup);

            // Initial marker setup from loaded data
            const initialLocations = @json($kegiatan->lokasi);
            if (initialLocations.length > 0) {
                initialLocations.forEach(function(location, index) {
                    const row = $(`.list-lokasi-kegiatan .lokasi-kegiatan`).eq(index);
                    if (row.length) {
                        const uniqueId = row.data('unique-id');
                        if (location.lat && location.long) {
                            addOrUpdateLeafletMarker(uniqueId, location.lat, location.long, location.lokasi, location.desa);
                        }
                    }
                });
                updateLeafletMapBounds();
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
                    addOrUpdateLeafletMarker(uniqueId, lat, lng, lokasi, desa);
                }
            });

            $('.list-lokasi-kegiatan').on('click', '.remove-lokasi-row', function() {
                const row = $(this).closest('.lokasi-kegiatan');
                const uniqueId = row.data('unique-id');
                removeLeafletMarker(uniqueId);
            });
        });

        function addOrUpdateLeafletMarker(uniqueId, lat, lng, lokasi, desa) {
            const latLng = [parseFloat(lat), parseFloat(lng)];
            const popupContent = `
                <b>Lokasi:</b> ${lokasi || '-'}<br>
                <b>Desa:</b> ${desa && desa.nama ? desa.nama : '-'}<br>
                <b>Kecamatan:</b> ${desa && desa.kecamatan && desa.kecamatan.nama ? desa.kecamatan.nama : '-'}
            `;

            if (window.mapStateLeaflet.markers[uniqueId]) {
                const marker = window.mapStateLeaflet.markers[uniqueId];
                marker.setLatLng(latLng);
                marker.setPopupContent(popupContent);
            } else {
                const marker = L.circleMarker(latLng, {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 8
                });
                marker.bindPopup(popupContent);
                window.mapStateLeaflet.markers[uniqueId] = marker;
                window.mapStateLeaflet.markerClusterGroup.addLayer(marker);
            }
            updateLeafletMapBounds();
        }

        function removeLeafletMarker(uniqueId) {
            if (window.mapStateLeaflet.markers[uniqueId]) {
                const marker = window.mapStateLeaflet.markers[uniqueId];
                window.mapStateLeaflet.markerClusterGroup.removeLayer(marker);
                delete window.mapStateLeaflet.markers[uniqueId];
                updateLeafletMapBounds();
            }
        }

        function updateLeafletMapBounds() {
            const currentMarkers = Object.values(window.mapStateLeaflet.markers);
            if (currentMarkers.length > 0) {
                const group = new L.featureGroup(currentMarkers);
                window.mapStateLeaflet.map.fitBounds(group.getBounds().pad(0.5));
            } else {
                window.mapStateLeaflet.map.setView([-2.548926, 118.0148634], 5);
            }
        }
    </script>
@endpush