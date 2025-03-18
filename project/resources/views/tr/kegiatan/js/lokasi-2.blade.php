// ErrorHandler Module
    const ErrorHandler = {
        handleMapError: function (error) {
            console.error('Map Initialization Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Map Error',
                text: 'Unable to load map. Please try again later.',
                footer: `Error Details: ${error.message}`
            });
        },

        handleGeojsonError: function (error) {
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

        handleCoordinateError: function (message) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Coordinates',
                text: message || 'The provided coordinates are invalid.'
            });
        }
    };

    // Map Management Module
    const MapManager = {
        map: null,
        markers: [],
        bataskec: null,
        currentKecamatan: '',
        currentKabupaten: '',

        initMap: function () {
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

        initGeoJSON: function () {
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

        getGeoJSONStyle: function () {
            return {
                fillColor: "white",
                fillOpacity: 0,
                color: "black",
                weight: 1,
                opacity: 0.6
            };
        },

        setupGeoJSONInteractions: function (feature, layer) {
            layer.on({
                click: () => this.handleGeoJSONClick(feature),
                mouseover: this.handleMouseOver,
                mouseout: this.handleMouseOut
            });
        },

        handleGeoJSONClick: function (feature) {
            this.currentKecamatan = feature.properties.KECAMATAN;
            this.currentKabupaten = feature.properties.KABUPATEN;

            const index = $('.lokasi-kegiatan').index($('.lokasi-kegiatan').last());
            if (this.markers[index]) {
                this.markers[index].setPopupContent(
                    this.generatePopupContent(index, this.currentKecamatan, this.currentKabupaten)
                );
            }
        },

        handleMouseOver: function (e) {
            const layer = e.target;
            layer.setStyle({
                weight: 3,
                color: "#00FFFF",
                opacity: 1
            });
            layer.bringToFront();
        },

        handleMouseOut: function (e) {
            const layer = e.target;
            layer.setStyle({
                fillColor: "white",
                fillOpacity: 0,
                color: "black",
                weight: 1,
                opacity: 0.6
            });
        },

        setupMapEvents: function () {
            this.map.on('click', this.handleMapClick.bind(this));
        },

        handleMapClick: function (e) {

            const lastLocationInputs = $('.lokasi-kegiatan').last();
            const index = $('.lokasi-kegiatan').index(lastLocationInputs);

            lastLocationInputs.find('input[name="lat[]"]').val(e.latlng.lat.toFixed(8));
            lastLocationInputs.find('input[name="long[]"]').val(e.latlng.lng.toFixed(8));

            this.updateLocationDetails(e.latlng, index);
        },

        updateLocationDetails: function (latlng, index) {
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

        updateMarkerAtIndex: function (lat, long, index) {
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
        generatePopupContent: function (index, kecamatan, kabupaten) {
            var locationRow = $('.lokasi-kegiatan').eq(index);
            var kode_kegiatan = $('#kode_kegiatan').val() || '';
            var nama_kegiatan = $('#nama_kegiatan').val() || '';
            var lokasi = locationRow.find('input[name="lokasi[]"]').val() || '';
            var lat = locationRow.find('input[name="lat[]"]').val() || '';
            var long = locationRow.find('input[name="long[]"]').val() || '';

            return `
            <strong>Kode Kegiatan :</strong> ${kode_kegiatan}<br>
            <strong>Nama Kegiatan :</strong> ${nama_kegiatan}<br>
            <strong>Kecamatan :</strong> ${kecamatan || ''}<br>
            <strong>Kabupaten :</strong> ${kabupaten || ''}<br><br>
            <strong>Lokasi Kegiatan:</strong> ${lokasi}<br>
            <strong>Latitude:</strong> ${lat}<br>
            <strong>Longitude:</strong> ${long}<br>
        `;
        },

        // Update updateAllMarkers to refresh popup content
        updateAllMarkers: function () {
            const self = this;
            $('.lokasi-kegiatan').each(function (index) {
                var marker = self.markers[index];
                if (marker) {
                    // Get coordinates from inputs
                    var lat = parseFloat($(this).find('input[name="lat[]"]').val());
                    var long = parseFloat($(this).find('input[name="long[]"]').val());

                    // Check if lat and long are valid numbers before proceeding
                    if (!isNaN(lat) && !isNaN(long)) {
                        // Find kecamatan and kabupaten for these coordinates
                        var foundLocation = false;
                        self.bataskec.eachLayer(function (layer) {
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
    $(document).ready(function () {
        // Initialize Map
        MapManager.initMap();

        // Event Bindings
        $('#btn-lokasi-kegiatan').on('click', function () {
            addNewLocationInputs();
        });

        // Update markers when input fields change
        // Add event listener for location input changes
        $(document).on('input', 'input[name="lokasi[]"]', function () {
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
        $(document).on('input', 'input[name="long[]"], input[name="lat[]"]', function () {
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

        $('#kode_kegiatan, #nama_kegiatan, #nama_desa , #lokasi').on('input change', function () {
            MapManager.updateAllMarkers();
        });

        $('#list_program_out_activity tbody').on('click', '.select-activity', function (e) {
            e.preventDefault();
            // Fetch the selected program details
            var activity_Id = $(this).closest('tr').data('id');
            var activityKode = $(this).closest('tr').data('kode');
            var activityNama = $(this).closest('tr').data('nama');

            $('#programoutcomeoutputactivity_id').val(activity_Id).trigger('change');
            $('#kode_kegiatan').val(activityKode);
            $('#nama_kegiatan').val(activityNama).prop('disabled', true);
            //$('#kode_program').prop('disabled', true);

            MapManager.updateAllMarkers();
            $('#nama_kegiatan').focus();
            setTimeout(function () {
                $('#ModalDaftarProgramActivity').modal('hide');
            }, 200);

        });

        $(document).on('click', '.remove-staff-row', function () {
            var row = $(this).closest('.lokasi-kegiatan');
            var index = $('.lokasi-kegiatan').index(row);

            if (MapManager.markers[index]) {
                MapManager.map.removeLayer(MapManager.markers[index]);
                MapManager.markers.splice(index, 1);
            }

            row.remove();
            MapManager.updateAllMarkers();
        });

            let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

            kegiatanLokasi.forEach(data => {
                let uniqueId = addNewLocationInputs(data.uniqueId);
                loadLocationFromLocalStorage(uniqueId);


            });
            kegiatanLokasi.forEach(data => {
                const lat = parseFloat(data.lat);
                const long = parseFloat(data.long);

                if (!isNaN(lat) && !isNaN(long)) {
                        MapManager.updateMarkerAtIndex(lat, long, $('.lokasi-kegiatan').index($(`.lokasi-kegiatan[data-unique-id="${data.uniqueId}"]`)));
                    }
            });
    });

    function addNewLocationInputs(uniqueId) {
        if (!uniqueId) {
            uniqueId = Date.now();
        }

        var newLocationField = `
            <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
                <div class="col-sm-12 col-md-12 col-lg-1 self-center order-1">
                    <select name="provinsi_id[]" class="form-control dynamic-select2 provinsi-select"
                        id="provinsi-${uniqueId}"
                        data-placeholder="Pilih Provinsi">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-1 self-center order-2">
                    <select name="kabupaten_id[]" class="form-control dynamic-select2 kabupaten-select"
                        id="kabupaten-${uniqueId}"
                        data-placeholder="Pilih Kabupaten">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                    <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select"
                        id="kecamatan-${uniqueId}"
                        data-placeholder="Pilih Kecamatan">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                    <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select"
                        id="kelurahan-${uniqueId}"
                        data-placeholder="Pilih Desa">
                    </select>
                </div>
                    <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                    <input type="text" class="form-control lokasi-input" id="lokasi-${uniqueId}" name="lokasi[]" placeholder="Lokasi Kegiatan">
                    </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                    <input type="text" class="form-control lat-input" id="lat-${uniqueId}" name="lat[]" placeholder="Latitude">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                    <input type="text" class="form-control lang-input flex-grow-1"  id="long-${uniqueId}" name="long[]" placeholder="Longitude">
                    <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;

        $('.list-lokasi-kegiatan').append(newLocationField);


        // Initialize provinsi select2
        $(`#provinsi-${uniqueId}`).select2({
            placeholder: 'Pilih Provinsi',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.provinsi') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
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
        });

        // Initialize kabupaten select2
        $(`#kabupaten-${uniqueId}`).select2({
            placeholder: 'Pilih Kabupaten',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.kabupaten') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        provinsi_id: $(`#provinsi-${uniqueId}`).val(), // Get the province ID
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
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
        });


        // Initialize kecamatan select2
        $(`#kecamatan-${uniqueId}`).select2({
            placeholder: 'Pilih Kecamatan',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.kecamatan') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        kabupaten_id: $(`#kabupaten-${uniqueId}`).val(),
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
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
        });


        // Initialize kelurahan select2
        $(`#kelurahan-${uniqueId}`).select2({
            placeholder: 'Pilih Desa',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.kelurahan') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
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
        });


        // Handle dependencies
        $(`#provinsi-${uniqueId}`).on('change', function () {
            $(`#kabupaten-${uniqueId}`).val(null).trigger('change');
            $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
            $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
        });

        $(`#kabupaten-${uniqueId}`).on('change', function () {
            $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
            $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
        });


        $(`#kecamatan-${uniqueId}`).on('change', function () {
            $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
        });


        // Add event listeners to save data to localStorage
        $(`#provinsi-${uniqueId}, #kabupaten-${uniqueId}, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`)
            .on('change', function () {
                saveLocationToLocalStorage(uniqueId);
            });

        $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click', '.remove-staff-row', function () {
            // Remove from localStorage when row is removed
            removeLocationFromLocalStorage(uniqueId);
            $(this).closest('.lokasi-kegiatan').remove();
        });
       return uniqueId;
    }

    function saveLocationToLocalStorage(uniqueId) {
        const locationRow = $(`.lokasi-kegiatan[data-unique-id="${uniqueId}"]`);

        // Get all location data
        var locationData = {
            uniqueId: uniqueId,
            provinsi_id: $(`#provinsi-${uniqueId}`).val(),
            kabupaten_id: $(`#kabupaten-${uniqueId}`).val(),
            kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
            kelurahan_id: $(`#kelurahan-${uniqueId}`).val(),
            lokasi: $(`#lokasi-${uniqueId}`).val(),
            lat: $(`#lat-${uniqueId}`).val(),
            long: $(`#long-${uniqueId}`).val(),
        };
        let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

        let existingIndex = kegiatanLokasi.findIndex(item => item.uniqueId === uniqueId);
        if (existingIndex !== -1) {

            kegiatanLokasi[existingIndex] = locationData;
        } else {
            // Add new entry
            kegiatanLokasi.push(locationData);
        }
        // Save back to localStorage
        localStorage.setItem('KegiatanLokasi', JSON.stringify(kegiatanLokasi));

    }

    function loadLocationFromLocalStorage(uniqueId) {
        let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];
        let locationData = kegiatanLokasi.find(item => item.uniqueId === uniqueId);

        if (locationData) {
            preselectSelect2($(`#provinsi-${uniqueId}`), locationData.provinsi_id, "{{ route('api.kegiatan.provinsi') }}")
                .then(() => {
                    return preselectSelect2($(`#kabupaten-${uniqueId}`), locationData.kabupaten_id, "{{ route('api.kegiatan.kabupaten') }}", { provinsi_id: locationData.provinsi_id });
                })
                .then(() => {
                    return preselectSelect2($(`#kecamatan-${uniqueId}`), locationData.kecamatan_id, "{{ route('api.kegiatan.kecamatan') }}", { kabupaten_id: locationData.kabupaten_id });
                })
                .then(() => {
                    return preselectSelect2($(`#kelurahan-${uniqueId}`), locationData.kelurahan_id, "{{ route('api.kegiatan.kelurahan') }}", { kecamatan_id: locationData.kecamatan_id });
                })
                .then(() => {
                    // After all select2s are populated, load the inputs
                    $(`#lokasi-${uniqueId}`).val(locationData.lokasi);
                    $(`#lat-${uniqueId}`).val(locationData.lat);
                    $(`#long-${uniqueId}`).val(locationData.long);
                });
        }

        return uniqueId;
    }


    function preselectSelect2(selectElement, id, url, additionalData = {}) {
            return new Promise(resolve => {
                if (!id) {
                    resolve();
                    return;
                }

                $.ajax({
                    url: url,
                    dataType: 'json',
                    data: { ...additionalData, id: [id] },
                    success: function (data) {
                        if (data.results && data.results.length > 0) {
                            const item = data.results[0];
                            const option = new Option(item.text, item.id, true, true);
                            selectElement.append(option).trigger('change');
                            selectElement.trigger({
                                type: 'select2:select',
                                params: {
                                    data: item
                                }
                            });
                        }
                        resolve();
                    },
                    error: function () {
                        resolve(); // Resolve even on error
                    }
                });
            });
    }

    function removeLocationFromLocalStorage(uniqueId) {
        let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];
        kegiatanLokasi = kegiatanLokasi.filter(item => item.uniqueId !== uniqueId);
        localStorage.setItem('KegiatanLokasi', JSON.stringify(kegiatanLokasi));
    }

    function clearKegiatanLokasiLocalStorage() {
        localStorage.removeItem('KegiatanLokasi');
    }




















    
    {{-- 
        // un used script function 
        $('#saveModalData').click(function() {
            var identitas = $('#identitas').val() || '';
            var nama = $('#nama').val() || '';

            var jenis_kelamin = $('#jenis_kelamin').val();
            var jenis_kelamin_label = {
                'pria': 'Laki-laki',
                'wanita': 'Perempuan'
            } [jenis_kelamin] || 'Tidak Diketahui';

            var tanggal_lahir = $('#tanggal_lahir').val() ? formatDate($('#tanggal_lahir').val()) : '';
            var disabilitasValue = $('#disabilitas').val();
            var disabilitasIcon = disabilitasValue === '1' ?
                '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

            var hamilValue = $('#hamil').val();
            var hamilIcon = hamilValue === '1' ?
                '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

            var status_kawin = $('#status_kawin').val();
            var status_kawin_label = {
                'belum_menikah': 'Belum Menikah',
                'menikah': 'Menikah',
                'cerai': 'Cerai',
                'cerai_mati': 'Cerai Mati'
            } [status_kawin] || 'Tidak Diketahui';

            var no_kk = $('#no_kk').val() || '';
            var jenis_peserta = $('#jenis_peserta').val() || '';
            var nama_kk = $('#nama_kk').val() || '';
            var rowCount = $('#list_peserta_kegiatan tbody tr').length + 1;
            var newRow = `
                <tr data-identitas="${identitas}" data-jenis-kelamin="${jenis_kelamin}" data-status-kawin="${status_kawin}" data-no-kk="${no_kk}" data-disabilitas="${disabilitasValue}" data-hamil="${hamilValue}">
                    <td class="text-center self-center text-nowrap py-2 rounded-start-3">${rowCount}</td>
                    <td class="text-nowrap py-2 border-start text-start">${identitas}</td>
                    <td class="text-nowrap py-2 border-start text-start">${nama}</td>
                    <td class="text-nowrap py-2 border-start text-start">${jenis_kelamin_label}</td>
                    <td class="text-nowrap py-2 border-start text-start">${tanggal_lahir}</td>
                    <td class="text-center self-center" data-disabilitas="${disabilitasValue}">
                        ${disabilitasIcon}
                    </td>
                    <td class="text-center self-center" data-hamil="${hamilValue}">
                        ${hamilIcon}
                    </td>
                    <td class="text-center text-nowrap py-2 border-start text-start">${status_kawin_label}</td>
                    <td class="bg-silver text-nowrap py-2 border-start text-start">${no_kk}</td>
                    <td class="text-center text-nowrap py-2 border-start text-start">${jenis_peserta}</td>
                    <td class="bg-silver text-nowrap py-2 px-4 border-start">${nama_kk}</td>
                    <td class="text-center text-nowrap py-0 border-start rounded-end-3">
                        <div class="button-container pt-1 self-center">
                            <button class="btn btn-sm btn-info edit-row"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-row"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>`;
            $('#tableBody').append(newRow);
            saveParticipantsToStorage();
            $('#identitas').val('');
            $('#nama').val('');
            $('#jenis_kelamin').val('pria');
            $('#tanggal_lahir').val('');
            $('#disabilitas').val('0');
            $('#hamil').val('0');
            $('#status_kawin').val('belum_menikah');
            $('#no_kk').val('');
            $('#jenis_peserta').val('');
            $('#nama_kk').val('');
            $('#ModalTambahPeserta').modal('hide');
            saveFormDataToStorage();
        });
        $('#clearStorageButton').on('click', function() {
            clearStoredFormData();
            $('#createKegiatan').each(function() {
                inputFields = $(this).find('input, select, textarea').removeAttr('disabled');
                inputFields.each(function() {
                    $(this).val('');
                });
            });
        });

        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
            $('#list_peserta_kegiatan tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            saveParticipantsToStorage();
        });

        $(document).on('click', '.edit-row', function() {
            var row = $(this).closest('tr');
            var formattedDate = row.find('td:eq(4)').text();
            var parsedDate = parseFormattedDate(formattedDate);

            $('#identitas').val(row.data('identitas'));
            $('#nama').val(row.find('td:eq(2)').text());
            $('#jenis_kelamin').val(row.data('jenis-kelamin'));
            $('#tanggal_lahir').val(parsedDate);

            $('#disabilitas').val(row.data('disabilitas'));
            $('#hamil').val(row.data('hamil'));

            $('#status_kawin').val(row.data('status-kawin'));
            $('#no_kk').val(row.data('no-kk'));
            $('#jenis_peserta').val(row.find('td:eq(9)').text());
            $('#nama_kk').val(row.find('td:eq(10)').text());

            $('#ModalTambahPeserta').modal('show');
            row.remove();
            $('#list_peserta_kegiatan tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            saveParticipantsToStorage();
        });

        function saveParticipantsToStorage() {
            var participants = [];
            $('#list_peserta_kegiatan tbody tr').each(function() {
                participants.push({
                    identitas: $(this).data('identitas'),
                    nama: $(this).find('td:eq(2)').text(),
                    jenis_kelamin: $(this).data('jenis-kelamin'),
                    tanggal_lahir: $(this).find('td:eq(4)').text(),
                    disabilitas: $(this).data('disabilitas'),
                    hamil: $(this).data('hamil'),
                    status_kawin: $(this).data('status-kawin'),
                    no_kk: $(this).data('no-kk'),
                    jenis_peserta: $(this).find('td:eq(9)').text(),
                    nama_kk: $(this).find('td:eq(10)').text()
                });
            });

            localStorage.setItem('participantsData', JSON.stringify(participants));
        }

        function loadParticipantsFromStorage() {
            var savedParticipants = localStorage.getItem('participantsData');
            if (savedParticipants) {
                var participants = JSON.parse(savedParticipants);
                $('#tableBody').empty();

                participants.forEach(function(participant, index) {
                    var jenis_kelamin_label = {
                        'pria': 'Laki-laki',
                        'wanita': 'Perempuan'
                    } [participant.jenis_kelamin] || 'Tidak Diketahui';

                    var disabilitasIcon = participant.disabilitas === '1' ?
                        '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                        '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

                    var hamilIcon = participant.hamil === '1' ?
                        '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                        '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

                    var status_kawin_label = {
                        'belum_menikah': 'Belum Menikah',
                        'menikah': 'Menikah',
                        'cerai': 'Cerai',
                        'cerai_mati': 'Cerai Mati'
                    } [participant.status_kawin] || 'Tidak Diketahui';

                    var newRow = `
                    <tr data-identitas="${participant.identitas}" data-jenis-kelamin="${participant.jenis_kelamin}" data-status-kawin="${participant.status_kawin}" data-no-kk="${participant.no_kk}" data-disabilitas="${participant.disabilitas}" data-hamil="${participant.hamil}">
                        <td class="text-center self-center text-nowrap py-2 rounded-start-3">${index + 1}</td>
                        <td class="text-nowrap py-2 border-start text-start">${participant.identitas}</td>
                        <td class="text-nowrap py-2 border-start text-start">${participant.nama}</td>
                        <td class="text-nowrap py-2 border-start text-start">${jenis_kelamin_label}</td>
                        <td class="text-nowrap py-2 border-start text-start">${participant.tanggal_lahir}</td>
                        <td class="text-center self-center" data-disabilitas="${participant.disabilitas}">
                            ${disabilitasIcon}
                        </td>
                        <td class="text-center self-center" data-hamil="${participant.hamil}">
                            ${hamilIcon}
                        </td>
                        <td class="text-center text-nowrap py-2 border-start text-start">${status_kawin_label}</td>
                        <td class="bg-silver text-nowrap py-2 border-start text-start">${participant.no_kk}</td>
                        <td class="text-center text-nowrap py-2 border-start text-start">${participant.jenis_peserta}</td>
                        <td class="bg-silver text-nowrap py-2 px-4 border-start">${participant.nama_kk}</td>
                        <td class="text-center text-nowrap py-0 border-start rounded-end-3">
                            <div class="button-container pt-1 self-center">
                                <button class="btn btn-sm btn-info edit-row"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-danger delete-row"><i class="bi bi-trash-fill"></i></button>
                            </div>
                        </td>
                    </tr>`;

                    $('#tableBody').append(newRow);
                });
            }
        }
        function clearParticipantsStorage() {
            localStorage.removeItem('participantsData');
            $('#tableBody').empty();
        }
        function parseFormattedDate(formattedDate) {
            const months = {
                'Januari': '01',
                'Februari': '02',
                'Maret': '03',
                'April': '04',
                'Mei': '05',
                'Juni': '06',
                'Juli': '07',
                'Agustus': '08',
                'September': '09',
                'Oktober': '10',
                'November': '11',
                'Desember': '12',
            };

            const parts = formattedDate.split(' ');
            const day = parts[0].padStart(2, '0');
            const month = months[parts[1]];
            const year = parts[2];

            return `${year}-${month}-${day}`;
        }

        function formatDate(dateString) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
            ];

            const date = new Date(dateString);
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();

            return `${day} ${month} ${year}`;
        }

        function clearStoredFormData() {
            localStorage.removeItem('pesertaFormData');
            localStorage.removeItem('participantsData');
            localStorage.removeItem('kegiatanFormData');
            localStorage.removeItem('KegiatanLokasi');
            $('#createKegiatan')[0].reset();
            $('#tableBody').empty();
            $('.summernote').each(function() {
                $(this).summernote('reset');
            });
            $('.select2').val(null).trigger('change');
        }
 --}}