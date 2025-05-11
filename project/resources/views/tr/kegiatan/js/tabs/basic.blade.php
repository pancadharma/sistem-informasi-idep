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
                    return [
                        [this.formatCoordinate(pathData)]
                    ];
                }

                // Handle single polygon
                if (pathData.length > 0 && Array.isArray(pathData[0]) && pathData[0].length === 2 &&
                    typeof pathData[0][0] === 'number') {
                    const formattedCoords = pathData.map(coord => this.formatCoordinate(coord));
                    // Ensure polygon is closed
                    if (JSON.stringify(formattedCoords[0]) !== JSON.stringify(formattedCoords[formattedCoords
                            .length - 1])) {
                        formattedCoords.push(formattedCoords[0]);
                    }
                    return [formattedCoords];
                }

                // Handle multi-polygon structure
                return pathData.map(polygon => {
                    if (!Array.isArray(polygon)) return null;

                    const formattedPolygon = polygon.map(coord => this.formatCoordinate(coord));
                    // Ensure polygon is closed
                    if (JSON.stringify(formattedPolygon[0]) !== JSON.stringify(formattedPolygon[
                            formattedPolygon.length - 1])) {
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
            if (layer) {
                if (Array.isArray(layer)) {
                    layer.forEach(polygon => polygon.setMap(null));
                } else {
                    layer.setMap(null); // Remove the layer from the map
                }
            }
            return null;
        },

        displayGeoJSON: function(geojson, color) {
            if (!geojson) return null;

            const geometry = geojson.geometry;
            if (!geometry) {
                console.error("No geometry found in GeoJSON.");
                return null;
            }

            if (geometry.type === "MultiPolygon") {
                const coordinates = geometry.coordinates;
                if (!coordinates || coordinates.length === 0) {
                    console.error("No coordinates found in MultiPolygon GeoJSON.");
                    return null;
                }

                // Create an array to hold all the polygon instances
                const polygons = [];

                // Iterate through each polygon in the MultiPolygon
                coordinates.forEach(polygon => {
                    // Each polygon is an array of rings (exterior and interior)
                    const paths = polygon.map(ring => {
                        // Each ring is an array of coordinates
                        return ring.map(coord => {
                            return {
                                lat: coord[1],
                                lng: coord[0]
                            }; // Google Maps uses {lat, lng}
                        });
                    });

                    // Create a new polygon for each set of paths
                    const googlePolygon = new google.maps.Polygon({
                        paths: paths,
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color,
                        fillOpacity: 0.3,
                        map: map, // Set the map here
                        clickable: false // Add this line
                    });

                    polygons.push(googlePolygon); // Store the polygon instance
                });

                return polygons; // Return the array of polygons
            } else if (geometry.type === "Polygon") {
                // Handle a single Polygon
                const coordinates = geometry.coordinates;
                if (!coordinates || coordinates.length === 0) {
                    console.error("No coordinates found in Polygon GeoJSON.");
                    return null;
                }

                const paths = coordinates.map(ring => {
                    return ring.map(coord => {
                        return {
                            lat: coord[1],
                            lng: coord[0]
                        };
                    });
                });

                const googlePolygon = new google.maps.Polygon({
                    paths: paths,
                    strokeColor: color,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: color,
                    fillOpacity: 0.3,
                    map: map,
                    clickable: false // Add this line
                });

                return googlePolygon;
            } else {
                console.warn("Unsupported GeoJSON geometry type:", geometry.type);
                return null;
            }
        },

        updateMapBounds: function(layer) {
            if (layer) {
                let bounds = new google.maps.LatLngBounds();

                if (Array.isArray(layer)) {
                    // If layer is an array of polygons (from MultiPolygon)
                    layer.forEach(polygon => {
                        polygon.getPaths().forEach(path => {
                            path.forEach(point => {
                                bounds.extend(point);
                            });
                        });
                    });
                } else {
                    // If layer is a single polygon
                    layer.getPaths().forEach(path => {
                        path.forEach(point => {
                            bounds.extend(point);
                        });
                    });
                }

                map.fitBounds(bounds); // Adjust the map to fit the bounds
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
        },
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
                MapHandler.clearLayer(provinsiLayer);
                provinsiLayer = null;
            } else if (type === 'kabupaten') {
                MapHandler.clearLayer(kabupatenLayer);
                kabupatenLayer = null;
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
                    MapHandler.clearLayer(provinsiLayer);
                    provinsiLayer = null;

                } else if (type === 'kabupaten') {
                    MapHandler.clearLayer(kabupatenLayer);
                    kabupatenLayer = null;
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
                MapHandler.updateMapBounds(newLayer);
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

    // Function to generate a random hex color
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }


    // function initMap() {
    //     map = new google.maps.Map(document.getElementById('map'), {
    //         center: {lat: -2.5489, lng: 118.0149},
    //         zoom: 5,
    //         scrollwheel: true,
    //         mapID: 'DEMO_MAP_ID'
    //     });

    //     // Add a click listener to the map
    //     map.addListener('click', function(mapsMouseEvent) {
    //         // alert("Map clicked!");
    //         // Close any open infowindows
    //         //if (map.infowindow) {
    //         //    map.infowindow.close();
    //         //}

    //         // Get the clicked location
    //         const lat = mapsMouseEvent.latLng.lat();
    //         const lng = mapsMouseEvent.latLng.lng();

    //         // Get a random color
    //         const markerColor = getRandomColor();

    //         const marker = new google.maps.Marker({
    //             map: map,
    //             position: { lat: lat, lng: lng },
    //             title: 'Clicked Location',
    //             // Styling options for the marker (optional)
    //             // appearance: 'ICON', // or 'OUTLINE'
    //             // glyph: '📍', // Or a custom SVG path
    //             // icon: {
    //             //     path: google.maps.SymbolPath.CIRCLE,
    //             //     scale: 7,
    //             //     fillColor: markerColor,
    //             //     fillOpacity: 0.8,
    //             //     strokeWeight: 1,
    //             //     strokeColor: markerColor
    //             // }
    //         });

    //         // Create an infowindow
    //         const infowindow = new google.maps.InfoWindow({
    //             content: `
    //                 <div>
    //                     <strong>Location Details</strong><br>
    //                     Latitude: ${lat}<br>
    //                     Longitude: ${lng}<br>
    //                 </div>
    //             `
    //         });

    //         // Open the infowindow on the marker
    //         infowindow.open(map, marker);

    //         // Optional: Close the infowindow when the marker is clicked again
    //         marker.addListener('click', function() {
    //             infowindow.open(map, marker);
    //         });

    //         //Reverse Geocoding
    //         const geocoder = new google.maps.Geocoder();
    //         geocoder.geocode({ location: { lat: lat, lng: lng } }, (results, status) => {
    //             if (status === "OK") {
    //                 if (results[0]) {
    //                     infowindow.setContent(`
    //                         <div>
    //                             <strong>Location Details</strong><br>
    //                             Address: ${results[0].formatted_address}<br>
    //                             Latitude: ${lat}<br>
    //                             Longitude: ${lng}<br>
    //                         </div>
    //                     `);
    //                 } else {
    //                     infowindow.setContent(`
    //                         <div>
    //                             <strong>Location Details</strong><br>
    //                             No address found<br>
    //                             Latitude: ${lat}<br>
    //                             Longitude: ${lng}<br>
    //                         </div>
    //                     `);
    //                 }
    //             } else {
    //                 console.error("Geocoder failed due to: " + status);
    //             }
    //         });
    //     });
    // }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -2.5489,
                lng: 118.0149
            },
            zoom: 5,
            scrollwheel: true,
            gestureHandling: 'auto',
            mapId: 'YOUR_MAP_ID' // Add your Map ID here
        });

        // Add a click listener to the map
        map.addListener('click', async function(mapsMouseEvent) {
            // Get the clicked location
            const lat = mapsMouseEvent.latLng.lat();
            const lng = mapsMouseEvent.latLng.lng();

            // Get a random color
            const markerColor = getRandomColor();

            // Create an AdvancedMarkerElement
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: {
                    lat: lat,
                    lng: lng
                },
                title: 'Clicked Location',
            });

            // Create an infowindow
            const infowindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <strong>Location Details</strong><br>
                        Latitude: ${lat}<br>
                        Longitude: ${lng}<br>
                    </div>
                `
            });

            // Open the infowindow on the marker
            infowindow.open(map, marker);

            // Optional: Close the infowindow when the marker is clicked again
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            //Reverse Geocoding
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                location: {
                    lat: lat,
                    lng: lng
                }
            }, (results, status) => {
                if (status === "OK") {
                    if (results[0]) {
                        infowindow.setContent(`
                            <div>
                                <strong>Location Details</strong><br>
                                Address: ${results[0].formatted_address}<br>
                                Latitude: ${lat}<br>
                                Longitude: ${lng}<br>
                            </div>
                        `);
                    } else {
                        infowindow.setContent(`
                            <div>
                                <strong>Location Details</strong><br>
                                No address found<br>
                                Latitude: ${lat}<br>
                                Longitude: ${lng}<br>
                            </div>
                        `);
                    }
                } else {
                    console.error("Geocoder failed due to: " + status);
                }
            });
        });
    }

    function loadGoogleMapsAPI(apiKey) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src =
                `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places,geometry,marker`; // Add libraries if needed
            script.async = true;
            script.defer = true;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    $(document).ready(function() {
        // Initialize Google Maps
        // loadGoogleMapsAPI('AIzaSyDyl1M3DUZB5CZ3LARYxNqKiUQ8COBrI0Y') //dev api
        loadGoogleMapsAPI('AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ') //live api
            .then(() => {
                initMap(); // Call initMap after the API is loaded
            })
            .catch(error => {
                console.error("Error loading Google Maps API:", error);
            });



        $('#provinsi_id').on('change', function() {
            $('#kabupaten_id').val(null).trigger('change');
            updateMap();
        });

        $('#kabupaten_id').on('change', function() {
            updateMap();
        });

        $(`#provinsi_id`).select2({
            placeholder: '{{ __('cruds.kegiatan.basic.select_provinsi') }}',
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
        }).on('select2:open', function(e) {
            $('.select2-container').css('z-index', 1035);
        }).on('select2:close', function(e) {
            $('.select2-container').css('z-index', 999);
        });

        $(`#kabupaten_id`).select2({
            placeholder: '{{ __('cruds.kegiatan.basic.select_kabupaten') }}',
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
                                errorMessage = response
                                    .message; // Use message from the server if available
                            }
                        } catch (e) {
                            console.warn("Could not parse JSON response:", jqXHR.responseText);
                        }
                    }
                    if (provinsiId === null || provinsiId === undefined || provinsiId === '') {
                        Swal.fire({
                            icon: 'warning' ?? textStatus,
                            title: 'Failed to load Kabupaten data',
                            text: '{{ __('global.pleaseSelect') }} {{ __('cruds.provinsi.title') }}',
                            timer: 1500,
                        })
                        setTimeout(() => {
                            $(`#provinsi_id`).focus();
                        }, 1000);
                        return;
                    } else {
                        // Handle other AJAX errors
                        let errorMessage =
                            '{{ __('Failed to fetch kabupaten data.  Please check your internet connection or try again later.') }}'; // Default, localized message
                        Swal.fire({
                            icon: 'error', // Always use 'error' for AJAX failures
                            title: errorThrown ||
                                'Error', // Use errorThrown if available, otherwise generic 'Error'
                            text: errorMessage,
                            timer: 2500, // Slightly longer timer for general errors
                            showConfirmButton: false // Hide confirm button
                        });

                    }
                }
            }
        }).on('select2:open', function(e) {
            $('.select2-container').css('z-index', 1035);
        }).on('select2:close', function(e) {
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
                    <button type="button" class="btn btn-danger remove-lokasi-row btn-sm ml-1">
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
                placeholder: '{{ __('cruds.kegiatan.basic.select_kecamatan') }}',
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
            }).on('select2:open', function(e) {
                $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function(e) {
                $('.select2-container').css('z-index', 999);
            });

            $(`#kelurahan-${uniqueId}`).select2({
                placeholder: '{{ __('cruds.kegiatan.basic.select_desa') }}',
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
            }).on('select2:open', function(e) {
                $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function(e) {
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

            $(`#provinsi_id, #kabupaten_id, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`)
                .on('change', function() {

                });

            $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click',
                '.remove-lokasi-row',
                function() {
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
                    icon: 'warning',
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
        $(document).on('click', '.remove-lokasi-row', function() {
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
            }).filter(polygon => polygon !== null && polygon.length > 0); // Remove invalid polygons

            if (convertedCoordinates.length === 0) {
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
</script>
