<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script>
    // Use a global object to store map state
    window.mapState = {
        map: null,
        markers: {}, // Use an object to store markers by unique ID
        markerClusterer: null,
        bounds: null,
        infoWindows: {}, // Store infoWindows by unique ID
        provinsiLayer: null,
        kabupatenLayer: null
    };

    const MapHandler = {
        formatCoordinate: function(coord) {
            if (Array.isArray(coord) && coord.length === 2) {
                return [parseFloat(coord[1]), parseFloat(coord[0])];
            }
            return null;
        },
        normalizeCoordinates: function(pathData) {
            try {
                if (!Array.isArray(pathData)) { return null; }
                if (pathData.length === 2 && typeof pathData[0] === 'number') {
                    return [[this.formatCoordinate(pathData)]];
                }
                if (pathData.length > 0 && Array.isArray(pathData[0]) && pathData[0].length === 2 && typeof pathData[0][0] === 'number') {
                    const formattedCoords = pathData.map(coord => this.formatCoordinate(coord));
                    if (JSON.stringify(formattedCoords[0]) !== JSON.stringify(formattedCoords[formattedCoords.length - 1])) {
                        formattedCoords.push(formattedCoords[0]);
                    }
                    return [formattedCoords];
                }
                return pathData.map(polygon => {
                    if (!Array.isArray(polygon)) return null;
                    const formattedPolygon = polygon.map(coord => this.formatCoordinate(coord));
                    if (JSON.stringify(formattedPolygon[0]) !== JSON.stringify(formattedPolygon[formattedPolygon.length - 1])) {
                        formattedPolygon.push(formattedPolygon[0]);
                    }
                    return formattedPolygon;
                }).filter(Boolean);
            } catch (e) { return null; }
        },
        convertToGeoJSON: function(pathData) {
            try {
                if (!pathData) return null;
                const normalizedCoords = this.normalizeCoordinates(pathData);
                if (!normalizedCoords) return null;
                return {
                    type: "Feature",
                    geometry: { type: "MultiPolygon", coordinates: [normalizedCoords] },
                    properties: {}
                };
            } catch (e) { return null; }
        },
        clearLayer: function(layer) {
            if (layer) {
                if (Array.isArray(layer)) {
                    layer.forEach(polygon => polygon.setMap(null));
                } else {
                    layer.setMap(null);
                }
            }
            return null;
        },
        displayGeoJSON: function(geojson, color) {
            if (!geojson || !geojson.geometry) return null;
            const displayPolygon = (paths) => new google.maps.Polygon({
                paths: paths,
                strokeColor: color,
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: color,
                fillOpacity: 0.3,
                map: window.mapState.map,
                clickable: false
            });
            const geometry = geojson.geometry;
            if (geometry.type === "MultiPolygon") {
                return geometry.coordinates.map(polygon => {
                    const paths = polygon.map(ring => ring.map(coord => ({ lat: coord[1], lng: coord[0] })));
                    return displayPolygon(paths);
                });
            } else if (geometry.type === "Polygon") {
                const paths = geometry.coordinates.map(ring => ring.map(coord => ({ lat: coord[1], lng: coord[0] })));
                return displayPolygon(paths);
            }
            return null;
        },
        updateMapBounds: function(layer) {
            if (layer) {
                let bounds = new google.maps.LatLngBounds();
                const extendBounds = (polygon) => {
                    polygon.getPaths().forEach(path => path.forEach(point => bounds.extend(point)));
                };
                if (Array.isArray(layer)) {
                    layer.forEach(extendBounds);
                } else {
                    extendBounds(layer);
                }
                window.mapState.map.fitBounds(bounds);
            }
        }
    };

    function fetchAndDisplayGeoJSON(id, type, color) {
        if (!id) {
            if (type === 'provinsi') window.mapState.provinsiLayer = MapHandler.clearLayer(window.mapState.provinsiLayer);
            if (type === 'kabupaten') window.mapState.kabupatenLayer = MapHandler.clearLayer(window.mapState.kabupatenLayer);
            return;
        }
        fetch(`/api/geojson/${type}/${id}`)
            .then(response => response.ok ? response.json() : Promise.reject(response.status))
            .then(data => {
                if (type === 'provinsi') window.mapState.provinsiLayer = MapHandler.clearLayer(window.mapState.provinsiLayer);
                if (type === 'kabupaten') window.mapState.kabupatenLayer = MapHandler.clearLayer(window.mapState.kabupatenLayer);
                const geojson = MapHandler.convertToGeoJSON(data.path);
                if (geojson) {
                    const newLayer = MapHandler.displayGeoJSON(geojson, color);
                    if (type === 'provinsi') window.mapState.provinsiLayer = newLayer;
                    if (type === 'kabupaten') window.mapState.kabupatenLayer = newLayer;
                    MapHandler.updateMapBounds(newLayer);
                }
            })
            .catch(error => console.error(`Error loading ${type} data:`, error));
    }

    function updateMapGeoJSON() {
        const provinsiId = $('#provinsi_id').val();
        const kabupatenId = $('#kabupaten_id').val();
        fetchAndDisplayGeoJSON(provinsiId, 'provinsi', '#2563eb');
        if (provinsiId && kabupatenId) {
            fetchAndDisplayGeoJSON(kabupatenId, 'kabupaten', '#dc2626');
        }
    }

    function addOrUpdateMarker(uniqueId, lat, lng, lokasi, desa) {
        const latLng = { lat: parseFloat(lat), lng: parseFloat(lng) };
        const infoWindowContent = `
            <div class="info-window-content">
                <strong>Lokasi:</strong> ${lokasi || '-'}<br>
                <strong>Desa:</strong> ${desa.nama || '-'}<br>
                <strong>Kecamatan:</strong> ${desa.kecamatan.nama || '-'}
            </div>`;

        if (window.mapState.markers[uniqueId]) {
            window.mapState.markers[uniqueId].position = latLng;
            window.mapState.infoWindows[uniqueId].setContent(infoWindowContent);
        } else {
            const pin = new google.maps.marker.PinElement({ scale: 1.0, background: 'red', borderColor: 'white', glyph: '' });
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map: window.mapState.map,
                position: latLng,
                title: lokasi,
                content: pin.element,
            });
            const infoWindow = new google.maps.InfoWindow({ content: infoWindowContent });
            marker.addListener('click', () => infoWindow.open(window.mapState.map, marker));
            window.mapState.markers[uniqueId] = marker;
            window.mapState.infoWindows[uniqueId] = infoWindow;
            window.mapState.markerClusterer.addMarker(marker);
        }
        updateMapBoundsWithMarkers();
    }

    function removeMarker(uniqueId) {
        if (window.mapState.markers[uniqueId]) {
            window.mapState.markerClusterer.removeMarker(window.mapState.markers[uniqueId]);
            delete window.mapState.markers[uniqueId];
            delete window.mapState.infoWindows[uniqueId];
            updateMapBoundsWithMarkers();
        }
    }

    function updateMapBoundsWithMarkers() {
        window.mapState.bounds = new google.maps.LatLngBounds();
        const currentMarkers = Object.values(window.mapState.markers);
        if (currentMarkers.length > 0) {
            currentMarkers.forEach(marker => window.mapState.bounds.extend(marker.position));
            window.mapState.map.fitBounds(window.mapState.bounds);
        } else {
            window.mapState.map.setCenter({ lat: -2.548926, lng: 118.0148634 });
            window.mapState.map.setZoom(5);
        }
    }

    const setupSelect2 = (selector, placeholder, url, dataCallback) => {
        $(selector).select2({
            placeholder: placeholder,
            allowClear: true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: dataCallback,
                processResults: (data, params) => ({
                    results: data.results,
                    pagination: { more: (params.page || 1) < data.last_page }
                }),
                cache: true
            }
        });
    };

    function addNewLocationInputs(uniqueId) {
        if (!uniqueId) uniqueId = Date.now();
        var newLocationField = `
        <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select" id="kecamatan-${uniqueId}" data-placeholder="Pilih Kecamatan"></select>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select" id="kelurahan-${uniqueId}" data-placeholder="Pilih Desa"></select>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                <input type="text" class="form-control lokasi-input" id="lokasi-${uniqueId}" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                <input type="text" class="form-control lat-input" id="lat-${uniqueId}" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                <input type="text" class="form-control lang-input flex-grow-1" id="long-${uniqueId}" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                <button type="button" class="btn btn-danger remove-lokasi-row btn-sm ml-1"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
        $('.list-lokasi-kegiatan').append(newLocationField);
        setupSelect2(`#kecamatan-${uniqueId}`, '{{ __('cruds.kegiatan.basic.select_kecamatan') }}', "{{ route('api.kegiatan.kecamatan') }}", params => ({ search: params.term, kabupaten_id: $('#kabupaten_id').val(), page: params.page || 1 }));
        setupSelect2(`#kelurahan-${uniqueId}`, '{{ __('cruds.kegiatan.basic.select_desa') }}', "{{ route('api.kegiatan.kelurahan') }}", params => ({ search: params.term, kecamatan_id: $(`#kecamatan-${uniqueId}`).val(), page: params.page || 1 }));
        $('#kabupaten_id').on('change', () => $(`#kecamatan-${uniqueId}`).val(null).trigger('change'));
        $(`#kecamatan-${uniqueId}`).on('change', () => $(`#kelurahan-${uniqueId}`).val(null).trigger('change'));
    }

    function initMap() {
        window.mapState.map = new google.maps.Map(document.getElementById("googleMap"), {
            zoom: 5,
            center: { lat: -2.548926, lng: 118.0148634 },
            mapId: "7e7fb1bfd929ec61"
        });
        window.mapState.bounds = new google.maps.LatLngBounds();
        window.mapState.markerClusterer = new markerClusterer.MarkerClusterer({ map: window.mapState.map, markers: [] });

        // Event listeners for dynamic markers
        $('.list-lokasi-kegiatan').on('change', '.lat-input, .lang-input', function() {
            const row = $(this).closest('.lokasi-kegiatan');
            const uniqueId = row.data('unique-id');
            const lat = row.find('.lat-input').val();
            const lng = row.find('.lang-input').val();
            const lokasi = row.find('.lokasi-input').val();
            const desa = {
                nama: row.find('.kelurahan-select option:selected').text(),
                kecamatan: { nama: row.find('.kecamatan-select option:selected').text() }
            };
            if (lat && lng) addOrUpdateMarker(uniqueId, lat, lng, lokasi, desa);
        });

        $('.list-lokasi-kegiatan').on('click', '.remove-lokasi-row', function() {
            const uniqueId = $(this).closest('.lokasi-kegiatan').data('unique-id');
            removeMarker(uniqueId);
        });

        // Attach event listeners for GeoJSON after map initialization
        $('#provinsi_id, #kabupaten_id').on('change', function() {
            console.log('GeoJSON update triggered by change event on:', this.id);
            updateMapGeoJSON();
        });
    }

    $(document).ready(function() {
        // Setup Select2 elements
        setupSelect2('#provinsi_id', '{{ __('cruds.kegiatan.basic.select_provinsi') }}', "{{ route('api.kegiatan.provinsi') }}", params => ({ search: params.term, page: params.page || 1 }));
        setupSelect2('#kabupaten_id', '{{ __('cruds.kegiatan.basic.select_kabupaten') }}', "{{ route('api.kegiatan.kabupaten') }}", params => ({ search: params.term, provinsi_id: $('#provinsi_id').val(), page: params.page || 1 }));

        // Setup button to add new location rows
        $('#btn-lokasi-kegiatan').on('click', function() {
            if (!$('#kabupaten_id').val()) {
                Swal.fire({ icon: 'warning', text: 'Please select a province and kabupaten first.', timer: 2000 });
                return false;
            }
            addNewLocationInputs();
        });
    });
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ&callback=initMap&v=beta&libraries=maps,marker"></script>
