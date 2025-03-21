
<script>
    // Variables to store current GeoJSON layers
    var provinsiLayer = null;
    var kabupatenLayer = null;
    var map;
    let uniqueId = Date.now();

    const MapHandler = {
        // Function to ensure coordinates are in proper format [longitude, latitude]
        formatCoordinate: function(coord) {
            // If coordinates are in [lat, lng] format, swap them to [lng, lat]
            if (Array.isArray(coord) && coord.length === 2) {
                return [parseFloat(coord[1]), parseFloat(coord[0])];
            }
            return null;
        },

        // Normalize the coordinate array structure
        normalizeCoordinates: function(pathData) {
            try {
                if (!Array.isArray(pathData)) {
                    console.error("Invalid path data:", pathData);
                    return null;
                }

                // Handle case where data is direct array of coordinate pairs
                if (pathData.length === 2 && typeof pathData[0] === 'number') {
                    return [[this.formatCoordinate(pathData)]];
                }

                // Handle single polygon
                if (pathData.length > 0 && Array.isArray(pathData[0]) && pathData[0].length === 2 && typeof pathData[0][0] === 'number') {
                    const formattedCoords = pathData.map(coord => this.formatCoordinate(coord));
                    // Ensure polygon is closed
                    if (JSON.stringify(formattedCoords[0]) !== JSON.stringify(formattedCoords[formattedCoords.length - 1])) {
                        formattedCoords.push(formattedCoords[0]);
                    }
                    return [formattedCoords];
                }

                // Handle multi-polygon structure
                return pathData.map(polygon => {
                    if (!Array.isArray(polygon)) return null;

                    const formattedPolygon = polygon.map(coord => this.formatCoordinate(coord));
                    // Ensure polygon is closed
                    if (JSON.stringify(formattedPolygon[0]) !== JSON.stringify(formattedPolygon[formattedPolygon.length - 1])) {
                        formattedPolygon.push(formattedPolygon[0]);
                    }
                    return formattedPolygon;
                }).filter(Boolean);

            } catch (e) {
                console.error("Error normalizing coordinates:", e);
                return null;
            }
        },

        convertToGeoJSON: function(pathData) {
            try {
                if (!pathData) return null;

                const normalizedCoords = this.normalizeCoordinates(pathData);
                if (!normalizedCoords) return null;

                return {
                    type: "Feature",
                    geometry: {
                        type: "MultiPolygon",
                        coordinates: [normalizedCoords] // Wrap in array for MultiPolygon
                    },
                    properties: {}
                };
            } catch (e) {
                console.error("Error converting to GeoJSON:", e);
                return null;
            }
        },

        clearLayer: function(layer) {
            if (layer && map.hasLayer(layer)) {
                map.removeLayer(layer);
                return null;
            }
            return layer;
        },

        displayGeoJSON: function(geojson, color) {
            if (!geojson) return null;

            return L.geoJSON(geojson, {
                style: {
                    color: color,
                    weight: 2,
                    fillOpacity: 0.3
                }
            }).addTo(map);
        },

        updateMapBounds: function(layer, parentLayer) {
            if (parentLayer && map.hasLayer(parentLayer)) {
                map.fitBounds(parentLayer.getBounds());
            } else if (layer && map.hasLayer(layer)) {
                map.fitBounds(layer.getBounds());
            }
        }
    };


    const ErrorHandler = {
        handleMapError: function(error) {
            console.error('Map Initialization Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Map Error',
                text: 'Unable to load map. Please try again later.',
                footer: `Error Details: ${error.message}`
            });
        },

        handleGeojsonError: function(error) {
            console.error('GeoJSON Loading Error:', error);
            Swal.fire({
                icon: 'warning',
                title: 'Geographic Data Error',
                text: 'Could not load geographic boundaries. Some features may be limited.',
                confirmButtonText: 'Retry',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },

        handleCoordinateError: function(message) {
            Toast.fire({
                icon: 'warning',
                title: 'Invalid Coordinates',
                text: message || 'The provided coordinates are invalid.',
                timer: 300,
                position: 'top-end',
            });
        }
    };

    function validateCoordinate(value, type) {
        let isValid = false;
        let validatedValue = null;

        if (value === null || value === undefined || value === '') {
            return {
                valid: false,
                value: null
            };
        }

        // Validate Latitude
        if (type === 'latitude') {
            const lat = parseFloat(value);
            if (!isNaN(lat) && lat >= -90 && lat <= 90) {
                isValid = true;
                validatedValue = lat.toFixed(6); // Formatting to 6 decimal places
            }
        }
        // Validate Longitude
         else if (type === 'longitude') {
            const long = parseFloat(value);
            if (!isNaN(long) && long >= -180 && long <= 180) {
                isValid = true;
                validatedValue = long.toFixed(6); // Formatting to 6 decimal places
            }
        }

        return {
             valid: isValid,
             value: validatedValue
        };

    }

    function fetchAndDisplayGeoJSON(id, type, color, parentLayer) {
    if (!id) {
        if (type === 'provinsi') {
            provinsiLayer = MapHandler.clearLayer(provinsiLayer);
        } else if (type === 'kabupaten') {
            kabupatenLayer = MapHandler.clearLayer(kabupatenLayer);
        }
        return;
    }

    fetch(`/api/geojson/${type}/${id}`)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            // Clear existing layer
            if (type === 'provinsi') {
                provinsiLayer = MapHandler.clearLayer(provinsiLayer);
            } else if (type === 'kabupaten') {
                kabupatenLayer = MapHandler.clearLayer(kabupatenLayer);
            }

            // Convert and display new data
            const geojson = MapHandler.convertToGeoJSON(data.path);
            if (!geojson) {
                console.error(`Invalid GeoJSON data for ${type}:`, data.path);
                return;
            }

            const newLayer = MapHandler.displayGeoJSON(geojson, color);

            // Store new layer reference
            if (type === 'provinsi') {
                provinsiLayer = newLayer;
            } else if (type === 'kabupaten') {
                kabupatenLayer = newLayer;
            }

            // Update map bounds
            MapHandler.updateMapBounds(newLayer, parentLayer);
        })
        .catch(error => {
            console.error(`Error loading ${type} data:`, error);
            ErrorHandler.handleGeojsonError(error);
        });
    }

    function updateMap() {
        const provinsiId = $('#provinsi_id').val();
        const kabupatenId = $('#kabupaten_id').val();

        // Update provinsi first
        fetchAndDisplayGeoJSON(provinsiId, 'provinsi', '#2563eb');

        // Then update kabupaten if we have both IDs
        if (provinsiId && kabupatenId) {
            fetchAndDisplayGeoJSON(kabupatenId, 'kabupaten', '#dc2626', provinsiLayer);
        }
    }

    $(document).ready(function() {

        map = L.map('map').setView([ -2.5489, 118.0149 ], 5);

        $('#provinsi_id').on('change', function() {
            $('#kabupaten_id').val(null).trigger('change');
            kabupatenLayer = MapHandler.clearLayer(kabupatenLayer);
            updateMap();
        });

        $('#kabupaten_id').on('change', function() {
            updateMap();
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);

        $(`#provinsi_id`).select2({
            placeholder: '{{ __("cruds.kegiatan.basic.select_provinsi") }}',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.provinsi') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        }).on('select2:open', function (e) {
            $('.select2-container').css('z-index', 1051);
        }).on('select2:close', function (e) {
            $('.select2-container').css('z-index', 999);
        });

        $(`#kabupaten_id`).select2({
            placeholder: '{{ __("cruds.kegiatan.basic.select_kabupaten") }}',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.kabupaten') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    const provinsiId = $(`#provinsi_id`).val();
                    return {
                        search: params.term,
                        provinsi_id: provinsiId,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true,
                error: function(jqXHR, textStatus, errorThrown) {
                    const provinsiId = $(`#provinsi_id`).val();
                    let errorMessage = "";
                    if (jqXHR.responseText) {
                        try {
                            const response = JSON.parse(jqXHR.responseText);
                            if (response.message) {
                                errorMessage = response.message; // Use message from the server if available
                            }
                        } catch (e) {
                            console.warn("Could not parse JSON response:", jqXHR.responseText);
                        }
                    }
                    if(provinsiId === null || provinsiId === undefined || provinsiId === ''){
                        Swal.fire({
                            icon: 'warning' ?? textStatus,
                            title: 'Failed to load Kabupaten data',
                            text: '{{ __('global.pleaseSelect') }} {{ __('cruds.provinsi.title') }}',
                            timer: 1500,
                            showConfirmButton: false, // Hide confirm button
                            timerProgressBar: true,// Show progress bar
                        })
                        setTimeout(() => {
                            $(`#provinsi_id`).focus();
                        }, 1000);
                    }
                    else{
                        // Handle other AJAX errors
                        let errorMessage = '{{ __("Failed to fetch kabupaten data.  Please check your internet connection or try again later.") }}'; // Default, localized message
                        Swal.fire({
                            icon: 'error', // Always use 'error' for AJAX failures
                            title: errorThrown || 'Error', // Use errorThrown if available, otherwise generic 'Error'
                            text: errorMessage,
                            timer: 2500, // Slightly longer timer for general errors
                            showConfirmButton: false // Hide confirm button
                        });

                    }
                }
            }
        }).on('select2:open', function (e) {
            $('.select2-container').css('z-index', 1051);
        }).on('select2:close', function (e) {
            $('.select2-container').css('z-index', 999);
        });

        function addNewLocationInputs(uniqueId) {
            if (!uniqueId) {
                uniqueId = Date.now();
            }
            var newLocationField = `
            <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                    <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select" id="kecamatan-${uniqueId}" data-placeholder="Pilih Kecamatan">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                    <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select" id="kelurahan-${uniqueId}" data-placeholder="Pilih Desa">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                    <input type="text" class="form-control lokasi-input" id="lokasi-${uniqueId}" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                    <input type="text" class="form-control lat-input" id="lat-${uniqueId}" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                    <input type="text" class="form-control lang-input flex-grow-1" id="long-${uniqueId}" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                    <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
            $('.list-lokasi-kegiatan').append(newLocationField);

            $(document).on('blur', '.lat-input, .lang-input', function() {
                const $input = $(this);
                const value = $input.val();
                const type = $input.hasClass('lat-input') ? 'latitude' : 'longitude';

                const validationResult = validateCoordinate(value, type);

                if (validationResult.valid) {
                    $input.val(validationResult.value);
                    $input.removeClass('is-invalid').addClass('is-valid');
                } else {
                    $input.val('');
                    $input.removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Initialize kecamatan select2
            $(`#kecamatan-${uniqueId}`).select2({
                placeholder: '{{ __("cruds.kegiatan.basic.select_kecamatan") }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kecamatan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kabupaten_id: $(`#kabupaten_id`).val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, textStatus, errorThrown) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error fetching Kecamatan data. Please try again later.',
                            position: 'center',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        $('#kecamatan-' + uniqueId).focus();
                    }
                }
            }).on('select2:open', function (e) {
                $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function (e) {
                $('.select2-container').css('z-index', 999);
            });

            $(`#kelurahan-${uniqueId}`).select2({
                placeholder: '{{ __("cruds.kegiatan.basic.select_desa") }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kelurahan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                    params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                            more: data.pagination.more
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error fetching Kelurahan data:", textStatus, errorThrown);
                        Toast.fire({
                            icon: 'error', // Use 'error' icon for errors
                            title: 'Please Select Kecamatan First!',
                            text: 'Kelurahan data depends on Kecamatan data. Please select a Kecamatan first.',
                            position: 'center',
                            timer: 2000, // Increased timer to 3 seconds for better visibility
                            timerProgressBar: true
                        });
                        $('#kecamatan-' + uniqueId).focus();
                    }
                }
            }).on('select2:open', function (e) {
                $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function (e) {
                $('.select2-container').css('z-index', 999);
            });

            // Handle dependencies
            $(`#provinsi_id`).on('change', function() {
                $(`#kabupaten_id`).val(null).trigger('change');
                $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#kabupaten_id`).on('change', function() {
                $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#kecamatan-${uniqueId}`).on('change', function() {
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#provinsi_id, #kabupaten_id, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`).on('change', function() {

            });

            $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click', '.remove-staff-row', function() {
                $(this).closest('.lokasi-kegiatan').remove();
            });
            return uniqueId
        }

        $('#btn-lokasi-kegiatan').on('click', function() {
            let idProvinsi = $('#provinsi_id').val();
            let idKabupaten = $('#kabupaten_id').val();
            if (!idProvinsi) {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: 'Please select a province first.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });
                $('#provinsi_id').focus();
                return false;
            }
            if (!idKabupaten) {
                Swal.fire({
                    icon: 'waring',
                    title: '',
                    text: 'Please select a kabupaten after selecting a province.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });

                $('#kabupaten_id').focus();
                return false;
            }

            addNewLocationInputs();
        });

        $(document).on('input', 'input[name="long[]"], input[name="lat[]"]', function() {
            const uniqueId = $(this).closest('.lokasi-kegiatan').data('unique-id');
            const container = $(this).closest('.lokasi-kegiatan');
            const long = parseFloat(container.find('input[name="long[]"]').val());
            const lat = parseFloat(container.find('input[name="lat[]"]').val());
            const index = $('.lokasi-kegiatan').index(container);

            if (!isNaN(lat) && !isNaN(long)) {
                // saveLocationToLocalStorage(uniqueId);
                validateCoordinate(lat, long);
            } else {
                ErrorHandler.handleCoordinateError('Invalid coordinate format');
            }
        });
        $(document).on('click', '.remove-staff-row', function() {
            var row = $(this).closest('.lokasi-kegiatan');
            var index = $('.lokasi-kegiatan').index(row);
            row.remove();
        });
        updateMap();
    });

    function convertPathToGeoJSON(pathData) {
        try {
            if (!Array.isArray(pathData)) {
                console.error("pathData is not an array:", pathData);
                return null;
            }

            const convertedCoordinates = pathData.map(polygon => {
                if (!Array.isArray(polygon)) {
                    console.error("Polygon is not an array:", polygon);
                    return null;
                }
                return polygon.map(ring => {
                    if (!Array.isArray(ring)) {
                        console.error("Ring is not an array:", ring);
                        return null;
                    }
                    const convertedRing = ring.map(coord => {
                        if (!Array.isArray(coord)) {
                            console.error("Coordinate is not an array:", coord);
                            return null;
                        }

                        if (coord.length < 2) {
                            console.error("Coordinate is missing values:", coord);
                            return null;
                        }

                        let lng = parseFloat(coord[1]);
                        let lat = parseFloat(coord[0]);

                        if (isNaN(lng)) {
                            console.error("Invalid lng", coord[1]);
                            lng = 0;
                        }
                        if (isNaN(lat)) {
                            console.error("Invalid lat", coord[0]);
                            lat = 0;
                        }

                        return [lng, lat];
                    }).filter(coord => coord !== null); // Remove invalid coords

                    // ensure first and last point is same
                    if (convertedRing.length > 0) {
                        const firstCoord = convertedRing[0];
                        const lastCoord = convertedRing[convertedRing.length - 1];
                        if (firstCoord[0] !== lastCoord[0] || firstCoord[1] !== lastCoord[1]) {
                            convertedRing.push(firstCoord);
                        }
                    }
                    return convertedRing;
                }).filter(ring => ring !== null && ring.length > 0); // Remove invalid rings
            }).filter(polygon => polygon !== null && polygon.length > 0);  // Remove invalid polygons

            if(convertedCoordinates.length === 0){
                console.error("No valid convertedCoordinates:", convertedCoordinates)
                return null;
            }
            return {
                type: "Feature",
                geometry: {
                    type: "MultiPolygon",
                    coordinates: convertedCoordinates,
                }
            };
        } catch (e) {
            console.error("Error parsing or converting path to geojson:", e);
            return null;
        }
    }

    // additional to add click on maps
    $(document).ready(function() {
        var clickMarker = null;
        var reverseGeocodeMarker = null;

        function reverseGeocode(lat, lng) {
            return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10`)
                .then(response => response.json())
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                    return null;
                });
        }


        map.on('click', function(e) {
            if (clickMarker) {
                map.removeLayer(clickMarker);
            }
            if (reverseGeocodeMarker) {
                map.removeLayer(reverseGeocodeMarker);
            }

            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            // Create marker
            clickMarker = L.marker(e.latlng).addTo(map);

            // Perform reverse geocoding
            reverseGeocode(lat, lng)
                .then(data => {
                    let locationName = 'Unknown Location';
                    let address = {};

                    if (data && data.display_name) {
                        locationName = data.display_name || data.name;

                        // Extract address components
                        address = {
                            road: data.address.road || '',
                            suburb: data.address.suburb || data.address.city_district || '',
                            city: data.address.city || data.address.town || data.address.village || '',
                            county: data.address.county || '',
                            state: data.address.state || '',
                            country: data.address.country || ''
                        };
                    }

                    // Create popup content
                    const popupContent = `
                        <div>
                            <strong>Location Details</strong><br>
                            <b>Name:</b> ${locationName}<br>
                            <b>Coordinates:</b><br>
                            <li>Latitude: ${lat}</li>
                            <li>Longitude: ${lng}</li>
                            <br>
                            <strong>Address Components:</strong><br>
                            Road: ${address.road}<br>
                            Suburb: ${address.suburb}<br>
                            City: ${address.city}<br>
                            County: ${address.county}<br>
                            State: ${address.state}<br>
                            Country: ${address.country}<br>
                            <br>
                        </div>
                    `;
                    // <button type="button" class="btn btn-primary btn-sm mt-2 use-coordinates"
                    //                 data-lat="${lat}"
                    //                 data-lng="${lng}">
                    //             Use These Coordinates
                    // </button>

                    // Bind popup
                    clickMarker.bindPopup(popupContent).openPopup();
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);

                    // Fallback popup if geocoding fails
                    const popupContent = `
                        <div>
                            <strong>Coordinates:</strong><br>
                            Latitude: ${lat}<br>
                            Longitude: ${lng}<br>
                            <br>
                            <button type="button" class="btn btn-primary btn-sm mt-2 use-coordinates"
                                    data-lat="${lat}"
                                    data-lng="${lng}">
                                Use These Coordinates
                            </button>
                        </div>
                    `;

                    clickMarker.bindPopup(popupContent).openPopup();
                });
        });

        $(document).on('click', '.leaflet-popup .use-coordinates', function(e) {
            e.preventDefault(); // Prevent any default form submission

            const lat = $(this).data('lat');
            const lng = $(this).data('lng');

            // Debugging logs
            console.log('Coordinates selected:', lat, lng);

            // Find the last empty coordinate input pair
            const locationRows = $('.lokasi-kegiatan');
            let targetRow = null;

            locationRows.each(function() {
                // const latInput = $(this).find('.lat-input');
                // const longInput = $(this).find('.lang-input');
                const latInput = $(this).find(`#lat-${uniqueId}`);
                const longInput = $(this).find(`#long-${uniqueId}`);

                if (!latInput.val() && !longInput.val()) {
                    targetRow = $(this);
                    return false; // Break the loop
                }
            });

            // Add this block here
            if (!targetRow || targetRow.length === 0) {
                // Manually create a new location row
                const newUniqueId = addNewLocationInputs();
                targetRow = $(`.lokasi-kegiatan[data-unique-id="${uniqueId}"]`);
            }

            // Fill in the coordinates
            if (targetRow) {
                const latInput = targetRow.find(`#lat-${uniqueId}`);
                const longInput = targetRow.find(`#long-${uniqueId}`);
                // const latInput = targetRow.find('.lat-input');
                // const longInput = targetRow.find('.lang-input');

                // Debugging logs
                console.log('Target row found:', targetRow);
                console.log('Lat input:', latInput);
                console.log('Long input:', longInput);

                // Use .val() and trigger change event
                latInput.val(lat).trigger('change');
                longInput.val(lng).trigger('change');

                // Additional validation trigger
                latInput.trigger('blur');
                longInput.trigger('blur');
            }

            // Close the popup
            if (clickMarker) {
                clickMarker.closePopup();
            }
        });
    });

    // Function to use coordinates in the form
    function useCoordinates(lat, lng, e) {
        // Find the last empty coordinate input pair
        e.preventDefault();
        const locationRows = $('.lokasi-kegiatan');
        let targetRow = null;

        locationRows.each(function() {
            const latInput = $(this).find('.lat-input');
            const longInput = $(this).find('.lang-input');

            if (!latInput.val() && !longInput.val()) {
                targetRow = $(this);
                return false; // Break the loop
            }
        });

        // If no empty inputs found, create new location row
        if (!targetRow) {
            const uniqueId = addNewLocationInputs();
            targetRow = $(`.lokasi-kegiatan[data-unique-id="${uniqueId}"]`);
        }

        // Fill in the coordinates
        if (targetRow) {
            targetRow.find('.lat-input').val(lat).trigger('change');
            targetRow.find('.lang-input').val(lng).trigger('change');
        }
    }

    // Add this helper function to format coordinates
    function formatCoordinate(value, type) {
        const val = parseFloat(value);
        if (isNaN(val)) return '';

        // Validate range
        if (type === 'latitude' && (val < -90 || val > 90)) return '';
        if (type === 'longitude' && (val < -180 || val > 180)) return '';

        return val.toFixed(6);
    }

    // Enhance the existing validateCoordinate function
    function validateCoordinate(value, type) {
        const formattedValue = formatCoordinate(value, type);
        return {
            valid: formattedValue !== '',
            value: formattedValue
        };
    }
</script>
