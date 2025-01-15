// Map Management Module
const MapManager = {
    map: null,
    markers: [],
    bataskec: null,
    currentKecamatan: '',
    currentKabupaten: '',

    initMap: function() {
        try {
            // Map Initialization
            this.map = L.map('map').setView([-8.38054848, 115.16239243], 9);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© <a href="/">RGBDev</a>'
            }).addTo(this.map);

            this.initGeoJSON();
            this.setupMapEvents();
        } catch (error) {
            ErrorHandler.handleMapError(error);
        }
    },

    initGeoJSON: function() {
        this.bataskec = L.geoJson(null, {
            style: this.getGeoJSONStyle.bind(this),
            onEachFeature: this.setupGeoJSONInteractions.bind(this)
        });

        $.getJSON("/data/batas_kecamatan1.geojson")
            .done((data) => {
                this.bataskec.addData(data);
                this.map.addLayer(this.bataskec);
                this.updateAllMarkers();
            })
            .fail((xhr, status, error) => {
                ErrorHandler.handleGeojsonError(error);
            });
    },

    getGeoJSONStyle: function() {
        return {
            fillColor: "white",
            fillOpacity: 0,
            color: "black",
            weight: 1,
            opacity: 0.6
        };
    },

    setupGeoJSONInteractions: function(feature, layer) {
        layer.on({
            click: () => this.handleGeoJSONClick(feature),
            mouseover: this.handleMouseOver,
            mouseout: this.handleMouseOut
        });
    },

    handleGeoJSONClick: function(feature) {
        this.currentKecamatan = feature.properties.KECAMATAN;
        this.currentKabupaten = feature.properties.KABUPATEN;

        const index = $('.lokasi-kegiatan').index($('.lokasi-kegiatan').last());
        if (this.markers[index]) {
            this.markers[index].setPopupContent(
                this.generatePopupContent(index, this.currentKecamatan, this.currentKabupaten)
            );
        }
    },

    handleMouseOver: function(e) {
        const layer = e.target;
        layer.setStyle({
            weight: 3,
            color: "#00FFFF",
            opacity: 1
        });
        layer.bringToFront();
    },

    handleMouseOut: function(e) {
        const layer = e.target;
        layer.setStyle({
            fillColor: "white",
            fillOpacity: 0,
            color: "black",
            weight: 1,
            opacity: 0.6
        });
    },

    setupMapEvents: function() {
        this.map.on('click', this.handleMapClick.bind(this));
    },

    handleMapClick: function(e) {

        const lastLocationInputs = $('.lokasi-kegiatan').last();
        const index = $('.lokasi-kegiatan').index(lastLocationInputs);

        lastLocationInputs.find('input[name="lat[]"]').val(e.latlng.lat.toFixed(8));
        lastLocationInputs.find('input[name="long[]"]').val(e.latlng.lng.toFixed(8));

        this.updateLocationDetails(e.latlng, index);
    },

    updateLocationDetails: function(latlng, index) {
        let foundLocation = false;
        this.bataskec.eachLayer((layer) => {
            if (!foundLocation && layer.getBounds().contains(latlng)) {
                this.currentKecamatan = layer.feature.properties.KECAMATAN;
                this.currentKabupaten = layer.feature.properties.KABUPATEN;
                foundLocation = true;
            }
        });

        this.updateMarkerAtIndex(latlng.lat, latlng.lng, index);
    },

    updateMarkerAtIndex: function(lat, long, index) {
        // Remove existing marker if exists
        if (this.markers[index]) {
            this.map.removeLayer(this.markers[index]);
        }

        // Create new marker
        var marker = L.marker([lat, long]).addTo(this.map);
        this.markers[index] = marker;

        // Bind popup
        marker.bindPopup(this.generatePopupContent(index, this.currentKecamatan, this.currentKabupaten)).openPopup();

        // Center map
        this.map.setView([lat, long], 14);
    },

    // Modify generatePopupContent to ensure all fields are updated
    generatePopupContent: function(index, kecamatan, kabupaten) {
        var locationRow = $('.lokasi-kegiatan').eq(index);
        var kode_kegiatan = $('#kode_kegiatan').val() || '';
        var nama_kegiatan = $('#nama_kegiatan').val() || '';
        var lokasi = locationRow.find('input[name="lokasi[]"]').val() || '';
        var lat = locationRow.find('input[name="lat[]"]').val() || '';
        var long = locationRow.find('input[name="long[]"]').val() || '';

        return `
            <strong>{{ __('cruds.kegiatan.basic.kode') }} :</strong> ${kode_kegiatan}<br>
            <strong>{{ __('cruds.kegiatan.basic.nama') }} :</strong> ${nama_kegiatan}<br>
            <strong>{{ __('cruds.kecamatan.title') }} :</strong> ${kecamatan || ''}<br>
            <strong>{{ __('cruds.kabupaten.title') }} :</strong> ${kabupaten || ''}<br><br>
            <strong>{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}:</strong> ${lokasi}<br>
            <strong>Latitude:</strong> ${lat}<br>
            <strong>Longitude:</strong> ${long}<br>
        `;
    },

    // Update updateAllMarkers to refresh popup content
    updateAllMarkers: function() {
        const self = this;
        $('.lokasi-kegiatan').each(function(index) {
            var marker = self.markers[index];
            if (marker) {
                // Get coordinates from inputs
                var lat = parseFloat($(this).find('input[name="lat[]"]').val());
                var long = parseFloat($(this).find('input[name="long[]"]').val());

                // Check if lat and long are valid numbers before proceeding
                if (!isNaN(lat) && !isNaN(long)) {
                    // Find kecamatan and kabupaten for these coordinates
                    var foundLocation = false;
                    self.bataskec.eachLayer(function(layer) {
                        if (!foundLocation && layer.getBounds().contains([lat, long])) {
                            self.currentKecamatan = layer.feature.properties.KECAMATAN;
                            self.currentKabupaten = layer.feature.properties.KABUPATEN;
                            foundLocation = true;
                        }
                    });

                    // Update popup content
                    marker.setPopupContent(
                        self.generatePopupContent(
                            index,
                            self.currentKecamatan,
                            self.currentKabupaten
                        )
                    );


                }
            }
        });
    },
};

// Document Ready Initialization
$(document).ready(function() {
    // Initialize Map
    MapManager.initMap();

    // Event Bindings
    $('#btn-lokasi-kegiatan').on('click', function() {
        addNewLocationInputs();
    });

    // Update markers when input fields change
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////

    // Add event listener for location input changes
    $(document).on('input', 'input[name="lokasi[]"]', function() {
        const container = $(this).closest('.lokasi-kegiatan');
        const index = $('.lokasi-kegiatan').index(container);

        // Update marker popup if marker exists
        if (MapManager.markers[index]) {
            MapManager.markers[index].setPopupContent(
                MapManager.generatePopupContent(
                    index,
                    MapManager.currentKecamatan,
                    MapManager.currentKabupaten
                )
            );
        }
    });

    // Error Handling for Coordinate Inputs
    $(document).on('input', 'input[name="long[]"], input[name="lat[]"]', function() {
        const container = $(this).closest('.lokasi-kegiatan');
        const long = parseFloat(container.find('input[name="long[]"]').val());
        const lat = parseFloat(container.find('input[name="lat[]"]').val());
        const index = $('.lokasi-kegiatan').index(container);

        if (!isNaN(lat) && !isNaN(long)) {
            MapManager.updateMarkerAtIndex(lat, long, index);
        } else {
            ErrorHandler.handleCoordinateError('Invalid coordinate format');
        }
    });
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////

    $('#kode_kegiatan, #nama_kegiatan, #nama_desa , #lokasi').on('input change', function() {
        MapManager.updateAllMarkers();
    });

    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
        e.preventDefault();
        // Fetch the selected program details
        var activity_Id = $(this).closest('tr').data('id');
        var activityKode = $(this).closest('tr').data('kode');
        var activityNama = $(this).closest('tr').data('nama');


        // Update the hidden input and display fields
        // $('#id_programoutcomeoutputactivity').val(activityId).trigger('change');
        // $('#kode_kegiatan').val(activityKode);
        // $('#nama_kegiatan').val(activityNama).prop('disabled', true);


        $('#programoutcomeoutputactivity_id').val(activity_Id).trigger('change');
        $('#kode_kegiatan').val(activityKode);
        $('#nama_kegiatan').val(activityNama).prop('disabled', true);
        $('#kode_program').prop('disabled', true);

        MapManager.updateAllMarkers();
        $('#nama_kegiatan').focus();
        setTimeout(function() {
            $('#ModalDaftarProgramActivity').modal('hide');
        }, 200);

    });

    // Add the remove location handler
    $(document).on('click', '.remove-staff-row', function() {
        var row = $(this).closest('.lokasi-kegiatan');
        var index = $('.lokasi-kegiatan').index(row);

        if (MapManager.markers[index]) {
            MapManager.map.removeLayer(MapManager.markers[index]);
            MapManager.markers.splice(index, 1);
        }

        row.remove();
        MapManager.updateAllMarkers();
    });

    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
});



////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
//



// off since today 2025/01/13
// Map Management Module
// const MapManager = {
//     map: null,
//     markers: [],
//     bataskec: null,
//     currentKecamatan: '',
//     currentKabupaten: '',

//     initMap: function() {
//         try {
//             // Map Initialization
//             this.map = L.map('map').setView([-8.38054848, 115.16239243], 9);

//             L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//                 maxZoom: 18,
//                 attribution: '© <a href="/">RGBDev</a>'
//             }).addTo(this.map);

//             this.initGeoJSON();
//             this.setupMapEvents();
//         } catch (error) {
//             ErrorHandler.handleMapError(error);
//         }
//     },

//     initGeoJSON: function() {
//         this.bataskec = L.geoJson(null, {
//             style: this.getGeoJSONStyle.bind(this),
//             onEachFeature: this.setupGeoJSONInteractions.bind(this)
//         });

//         $.getJSON("/data/batas_kecamatan1.geojson")
//             .done((data) => {
//                 this.bataskec.addData(data);
//                 this.map.addLayer(this.bataskec);
//                 this.updateAllMarkers();
//             })
//             .fail((xhr, status, error) => {
//                 ErrorHandler.handleGeojsonError(error);
//             });
//     },

//     getGeoJSONStyle: function() {
//         return {
//             fillColor: "white",
//             fillOpacity: 0,
//             color: "black",
//             weight: 1,
//             opacity: 0.6
//         };
//     },

//     setupGeoJSONInteractions: function(feature, layer) {
//         layer.on({
//             click: () => this.handleGeoJSONClick(feature),
//             mouseover: this.handleMouseOver,
//             mouseout: this.handleMouseOut
//         });
//     },

//     handleGeoJSONClick: function(feature) {
//         this.currentKecamatan = feature.properties.KECAMATAN;
//         this.currentKabupaten = feature.properties.KABUPATEN;
//         const uniqueId = Date.now();

//         const index = $('.lokasi-kegiatan').index($('.lokasi-kegiatan').last());
//         if (this.markers[index]) {
//             this.markers[index].setPopupContent(
//                 this.generatePopupContent(index, this.currentKecamatan, this.currentKabupaten)
//                 );
//                 addNewLocationInputs();
//                 saveLocationToLocalStorage(uniqueId);
//         }
//     },

//     handleMouseOver: function(e) {
//         const layer = e.target;
//         layer.setStyle({
//             weight: 3,
//             color: "#00FFFF",
//             opacity: 1
//         });
//         layer.bringToFront();
//     },

//     handleMouseOut: function(e) {
//         const layer = e.target;
//         layer.setStyle({
//             fillColor: "white",
//             fillOpacity: 0,
//             color: "black",
//             weight: 1,
//             opacity: 0.6
//         });
//     },

//     setupMapEvents: function() {
//         this.map.on('click', this.handleMapClick.bind(this));
//     },

//     handleMapClick: function(e) {

//         const lastLocationInputs = $('.lokasi-kegiatan').last();
//         const index = $('.lokasi-kegiatan').index(lastLocationInputs);

//         lastLocationInputs.find('input[name="lat[]"]').val(e.latlng.lat.toFixed(8));
//         lastLocationInputs.find('input[name="long[]"]').val(e.latlng.lng.toFixed(8));

//         this.updateLocationDetails(e.latlng, index);
//     },

//     updateLocationDetails: function(latlng, index) {
//         let foundLocation = false;
//         this.bataskec.eachLayer((layer) => {
//             if (!foundLocation && layer.getBounds().contains(latlng)) {
//                 this.currentKecamatan = layer.feature.properties.KECAMATAN;
//                 this.currentKabupaten = layer.feature.properties.KABUPATEN;
//                 foundLocation = true;
//             }
//         });

//         this.updateMarkerAtIndex(latlng.lat, latlng.lng, index);
//     },

//     updateMarkerAtIndex: function(lat, long, index) {
//         // Remove existing marker if exists
//         if (this.markers[index]) {
//             this.map.removeLayer(this.markers[index]);
//         }

//         // Create new marker
//         var marker = L.marker([lat, long]).addTo(this.map);
//         this.markers[index] = marker;

//         // Bind popup
//         marker.bindPopup(this.generatePopupContent(index, this.currentKecamatan, this.currentKabupaten)).openPopup();

//         // Center map
//         this.map.setView([lat, long], 14);
//     },

//     // // Modify generatePopupContent to ensure all fields are updated
//     // generatePopupContent: function(index, kecamatan, kabupaten) {
//     //     var locationRow = $('.lokasi-kegiatan').eq(index);
//     //     var kode_kegiatan = $('#kode_kegiatan').val() || '';
//     //     var nama_kegiatan = $('#nama_kegiatan').val() || '';
//     //     var lokasi = locationRow.find('input[name="lokasi[]"]').val() || '';
//     //     var lat = locationRow.find('input[name="lat[]"]').val() || '';
//     //     var long = locationRow.find('input[name="long[]"]').val() || '';

//     //     return `
//     //         <strong>{{ __('cruds.kegiatan.basic.kode') }} :</strong> ${kode_kegiatan}<br>
//     //         <strong>{{ __('cruds.kegiatan.basic.nama') }} :</strong> ${nama_kegiatan}<br>
//     //         <strong>{{ __('cruds.kecamatan.title') }} :</strong> ${kecamatan || ''}<br>
//     //         <strong>{{ __('cruds.kabupaten.title') }} :</strong> ${kabupaten || ''}<br><br>
//     //         <strong>{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}:</strong> ${lokasi}<br>
//     //         <strong>Latitude:</strong> ${lat}<br>
//     //         <strong>Longitude:</strong> ${long}<br>
//     //     `;
//     // },

//     // // Update updateAllMarkers to refresh popup content
//     // updateAllMarkers: function() {
//     //     const self = this;
//     //     $('.lokasi-kegiatan').each(function(index) {
//     //         var marker = self.markers[index];
//     //         if (marker) {
//     //             // Get coordinates from inputs
//     //             var lat = parseFloat($(this).find('input[name="lat[]"]').val());
//     //             var long = parseFloat($(this).find('input[name="long[]"]').val());

//     //             // Check if lat and long are valid numbers before proceeding
//     //             if (!isNaN(lat) && !isNaN(long)) {
//     //                 // Find kecamatan and kabupaten for these coordinates
//     //                 var foundLocation = false;
//     //                 self.bataskec.eachLayer(function(layer) {
//     //                     if (!foundLocation && layer.getBounds().contains([lat, long])) {
//     //                         self.currentKecamatan = layer.feature.properties.KECAMATAN;
//     //                         self.currentKabupaten = layer.feature.properties.KABUPATEN;
//     //                         foundLocation = true;
//     //                     }
//     //                 });

//     //                 // Update popup content
//     //                 marker.setPopupContent(
//     //                     self.generatePopupContent(
//     //                         index,
//     //                         self.currentKecamatan,
//     //                         self.currentKabupaten
//     //                     )
//     //                 );


//     //             }
//     //         }
//     //     });
//     // },


//     updateAllMarkers: function() {
//         const self = this;
//         let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

//         $('.lokasi-kegiatan').each(function(index) {
//             var $locationRow = $(this);
//             var lat = parseFloat($locationRow.find('input[name="lat[]"]').val());
//             var long = parseFloat($locationRow.find('input[name="long[]"]').val());
//             var uniqueId = $locationRow.data('unique-id'); // Assuming you have a unique ID for each location row

//             // Check if lat and long are valid numbers before proceeding
//             if (!isNaN(lat) && !isNaN(long)) {
//                 // Find kecamatan and kabupaten for these coordinates
//                 var foundLocation = false;
//                 self.bataskec.eachLayer(function(layer) {
//                     if (!foundLocation && layer.getBounds().contains([lat, long])) {
//                         self.currentKecamatan = layer.feature.properties.KECAMATAN;
//                         self.currentKabupaten = layer.feature.properties.KABUPATEN;
//                         foundLocation = true;
//                     }
//                 });

//                 // Create or update marker
//                 if (!self.markers[index]) {
//                     var marker = L.marker([lat, long]).addTo(self.map);
//                     self.markers[index] = marker;
//                 } else {
//                     var marker = self.markers[index];
//                     marker.setLatLng([lat, long]);
//                 }

//                 // Try to load additional details from localStorage
//                 var storedLocationData = kegiatanLokasi.find(item => item.uniqueId == uniqueId);

//                 // Update popup content
//                 marker.bindPopup(
//                     self.generatePopupContent(
//                         index,
//                         self.currentKecamatan,
//                         self.currentKabupaten,
//                         storedLocationData // Pass stored location data
//                     )
//                 );
//             }
//         });
//     },

//     // Modify generatePopupContent to handle optional stored location data
//     generatePopupContent: function(index, kecamatan, kabupaten, storedLocationData = null) {
//         var locationRow = $('.lokasi-kegiatan').eq(index);
//         var kode_kegiatan = $('#kode_kegiatan').val() || '';
//         var nama_kegiatan = $('#nama_kegiatan').val() || '';
//         var lokasi = locationRow.find('input[name="lokasi[]"]').val() || '';
//         var lat = locationRow.find('input[name="lat[]"]').val() || '';
//         var long = locationRow.find('input[name="long[]"]').val() || '';

//         // If stored location data exists, you can use additional details
//         if (storedLocationData) {
//             // Example of using additional data from localStorage
//             var provinsi = storedLocationData.provinsi || '';
//             var kabupaten = storedLocationData.kabupaten || kabupaten;
//             var kecamatan = storedLocationData.kecamatan || kecamatan;
//         }

//         return `
//             <strong>{{ __('cruds.kegiatan.basic.kode') }} :</strong> ${kode_kegiatan}<br>
//             <strong>{{ __('cruds.kegiatan.basic.nama') }} :</strong> ${nama_kegiatan}<br>
//             <strong>{{ __('cruds.kecamatan.title') }} :</strong> ${kecamatan || ''}<br>
//             <strong>{{ __('cruds.kabupaten.title') }} :</strong> ${kabupaten || ''}<br><br>
//             <strong>{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}:</strong> ${lokasi}<br>
//             <strong>Latitude:</strong> ${lat}<br>
//             <strong>Longitude:</strong> ${long}<br>
//         `;
//     }
// };


// Add event listener for location input changes
// off since today 2025/01/13
// $(document).on('input', 'input[name="lokasi[]"]', function() {
//     const container = $(this).closest('.lokasi-kegiatan');
//     const index = $('.lokasi-kegiatan').index(container);

//     // Update marker popup if marker exists
//     if (MapManager.markers[index]) {
//         MapManager.markers[index].setPopupContent(
//             MapManager.generatePopupContent(
//                 index,
//                 MapManager.currentKecamatan,
//                 MapManager.currentKabupaten
//             )
//         );
//     }
// });

// Error Handling for Coordinate Inputs
// off since today 2025/01/13
