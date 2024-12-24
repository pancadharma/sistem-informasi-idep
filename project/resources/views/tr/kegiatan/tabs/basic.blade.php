{{-- Basic Information --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
    <!-- id program -->
        <input type="hidden" name="program_id" id="program_id">
    <!-- kode program -->
        <input type="text" class="form-control" id="kode_program" placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program"
        data-toggle="modal" data-target="#ModalDaftarProgram">
    </div>
    <!-- nama program-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_program" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.program_nama') }}
        </label>
        <input type="text" class="form-control" id="nama_program" placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program">
    </div>
</div>
<div class="form-group row">
    <!-- kode kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.kode') }}
        </label>
        <input type="hidden" class="form-control" id="id_programoutcomeoutputactivity" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="id_programoutcomeoutputactivity">
        <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan"
        data-toggle="modal" data-target="#ModalDaftarProgramActivity">
    </div>
    <!-- nama kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.nama') }}
        </label>
        <input type="text" class="form-control" id="nama_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan">
    </div>
</div>

<!-- fase pelaporan-->
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="fase_pelaporan" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.fase_pelaporan') }} </strong>
            <i class="bi bi-question-circle" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.fase_pelaporan') }}"></i>
        </label>
        <div class="select2-purple">
            <select class="form-control" name="fase_pelaporan" id="fase_pelaporan">
                <option value="">{{ __('global.pleaseSelect') }}</option>
                @for ($i = 1; $i <= 99; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>
<!-- jenis kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="jenis_kegiatan" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control select2" name="jenis_kegiatan" id="jenis_kegiatan" data-api-url="{{ route('api.kegiatan.jenis_kegiatan') }}">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
    </div>
<!-- durasi kegiatan-->
    <!-- tgl mulai-->
    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-1 order-md-1">
        <label for="tanggalmulai" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.tanggalmulai') }}
        </label>
        <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai">
    </div>
    <!-- tgl selesai-->
    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-2 order-md-2">
        <label for="tanggalselesai" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.tanggalselesai') }}
        </label>
        <input type="date" class="form-control" id="tanggalselesai" name="tanggalselesai">
    </div>
</div>

<!-- nama mitra-->
<div class="form-group row">
    <div class="col-sm-12 col-md-9 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_mitra" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.nama_mitra') }}</label>
        <div class="select2-purple">
            <select class="form-control select2" data-api-url="{{ route('api.kegiatan.mitra') }}" id="nama_mitra" placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.nama_mitra') }}" name="nama_mitra" multiple>
            </select>
        </div>
    </div>
<!-- status kegiatan-->
    <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
        <label for="status" class="input-group col-form-label">
            <strong>{{ __('cruds.status.title') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control" name="status" id="status" required placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.status_kegiatan') }}">
                <optgroup label="{{ __('cruds.kegiatan.status') }}">
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label for="nama_desa" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.desa') }}</label>
        <select name="nama_desa" id="nama_desa" class="form-control select2" data-api-url="{{ route('api.kegiatan.desa') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.desa') }}">
        </select>
    </div>
</div>

<div class="card-info pt-2">
    <div class="card-header pl-1">
        <div class="col-sm-12 col-md-12 col-lg-4 self-center order-1 order-md-1">
            <button type="button" id="btn-lokasi-kegiatan" class="btn btn-warning">{{ __('cruds.kegiatan.basic.tambah_lokasi') }}</button>
        </div>
    </div>
    <div class="card-body pl-0 pt-1 pb-0 pr-1 list-lokasi-kegiatan">
        <div class="form-group row lokasi-kegiatan mb-0">
            <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
                <label class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}
                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.lokasi') }}"></i>
                </label>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 self-center order-2 order-md-2">
                <label class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.lat') }}
                    <i class="bi bi-pin-map-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                </label>
            </div>
            <div class="col-sm-10 col-md-3 col-lg-3 self-center order-3 order-md-3">
                <label class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.long') }}
                    <i class="bi bi-geo" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-4 self-center order-1 order-md-1">
        <label class="input-group col-form-label">
            {{ __('Maps Location') }}
            <i class="bi bi-map-fill"></i>
        </label>
        <div id="map" class="form-control col-form-label"></div>
    </div>
</div>


@include('tr.kegiatan.tabs.program')
@include('tr.kegiatan.tabs.program-act')

@push('next-button')
<div class="button" id="task_flyout">
    <button type="button" id="clearStorageButton" class="btn btn-warning float-left">Reset</button>
    <button type="button" id="next-button" class="btn btn-primary float-right">Next</button>
</div>
@endpush

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 280px; }
</style>
@endpush
@push('basic_tab_js')
<!-- javascript to push javascript to stack('basic_tab_js') -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
$(document).ready(function() {
    var map, markers = [], currentKecamatan = '', currentKabupaten = '';

    function initMap() {
        map = L.map('map').setView([-8.38054848, 115.16239243], 9);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="/">RGBDev</a>'
        }).addTo(map);

        // Initialize GeoJSON layer with proper styling and interactions
        var bataskec = L.geoJson(null, {
            style: function(feature) {
                return {
                    fillColor: "white",
                    fillOpacity: 0,
                    color: "black",
                    weight: 1,
                    opacity: 0.6
                };
            },
            onEachFeature: function(feature, layer) {
                layer.on({
                    click: function(e) {
                        // Store current location data
                        currentKecamatan = feature.properties.KECAMATAN;
                        currentKabupaten = feature.properties.KABUPATEN;

                        // If there's an active marker, update its popup
                        var index = $('.lokasi-kegiatan').index($('.lokasi-kegiatan').last());
                        if (markers[index]) {
                            markers[index].setPopupContent(generatePopupContent(index, currentKecamatan, currentKabupaten));
                        }
                    },
                    mouseover: function(e) {
                        var layer = e.target;
                        layer.setStyle({
                            weight: 3,
                            color: "#00FFFF",
                            opacity: 1
                        });
                        layer.bringToFront();
                    },
                    mouseout: function(e) {
                        bataskec.resetStyle(e.target);
                    }
                });
            }
        });

        // Load GeoJSON data
        $.getJSON("/data/batas_kecamatan1.geojson", function(data) {
            bataskec.addData(data);
            map.addLayer(bataskec);
        });

        // Map click handler
        map.on('click', function(e) {
            var lastLocationInputs = $('.lokasi-kegiatan').last();
            var index = $('.lokasi-kegiatan').index(lastLocationInputs);

            lastLocationInputs.find('input[name="lat[]"]').val(e.latlng.lat.toFixed(8));
            lastLocationInputs.find('input[name="long[]"]').val(e.latlng.lng.toFixed(8));

            // Get kecamatan and kabupaten based on click location
            var clickedPoint = e.latlng;
            var foundLocation = false;

            bataskec.eachLayer(function(layer) {
                if (!foundLocation && layer.getBounds().contains(clickedPoint)) {
                    currentKecamatan = layer.feature.properties.KECAMATAN;
                    currentKabupaten = layer.feature.properties.KABUPATEN;
                    foundLocation = true;
                }
            });

            updateMarkerAtIndex(e.latlng.lat, e.latlng.lng, index);
        });
    }

    function updateMarkerAtIndex(lat, long, index) {
        if (markers[index]) {
            map.removeLayer(markers[index]);
        }

        var marker = L.marker([lat, long]).addTo(map);
        markers[index] = marker;

        marker.bindPopup(generatePopupContent(index, currentKecamatan, currentKabupaten)).openPopup();
        map.setView([lat, long], 12);
    }

    function generatePopupContent(index, kecamatan, kabupaten) {
        var locationRow = $('.lokasi-kegiatan').eq(index);
        var kode_kegiatan = $('#kode_kegiatan').val() || '';
        var nama_kegiatan = $('#nama_kegiatan').val() || '';
        var lokasi = locationRow.find('input[name="lokasi[]"]').val() || '';

        return `
            <strong>{{ __('cruds.kegiatan.basic.kode') }}:</strong> ${kode_kegiatan}<br>
            <strong>{{ __('cruds.kegiatan.basic.nama') }}:</strong> ${nama_kegiatan}<br>
            <strong>Kecamatan:</strong> ${kecamatan || ''}<br>
            <strong>Kabupaten:</strong> ${kabupaten || ''}<br><br>
            <strong>{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}:</strong> ${lokasi}<br>
        `;
    }

    function updateAllMarkers() {
        $('.lokasi-kegiatan').each(function(index) {
            var marker = markers[index];
            if (marker) {
                // Get coordinates from inputs
                var lat = parseFloat($(this).find('input[name="lat[]"]').val());
                var long = parseFloat($(this).find('input[name="long[]"]').val());

                // Find kecamatan and kabupaten for these coordinates
                var foundLocation = false;
                bataskec.eachLayer(function(layer) {
                    if (!foundLocation && layer.getBounds().contains([lat, long])) {
                        currentKecamatan = layer.feature.properties.KECAMATAN;
                        currentKabupaten = layer.feature.properties.KABUPATEN;
                        foundLocation = true;
                    }
                });

                marker.setPopupContent(generatePopupContent(index, currentKecamatan, currentKabupaten));
            }
        });
    }

    // Event handlers
    $(document).on('input', 'input[name="long[]"], input[name="lat[]"]', function() {
        var container = $(this).closest('.lokasi-kegiatan');
        var long = parseFloat(container.find('input[name="long[]"]').val());
        var lat = parseFloat(container.find('input[name="lat[]"]').val());
        var index = $('.lokasi-kegiatan').index(container);

        if (!isNaN(lat) && !isNaN(long)) {
            updateMarkerAtIndex(lat, long, index);
        }
    });

    // Retain your existing event handlers for other functionality
    $('#btn-lokasi-kegiatan').on('click', addNewLocationInputs);
    $('#kode_kegiatan, #nama_kegiatan, #nama_desa').on('input change', updateAllMarkers);

    // Initialize the map
    initMap();
    function addNewLocationInputs() {
        var newLocationField = `
            <div class="form-group row lokasi-kegiatan">
                <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
                    <input type="text" class="form-control" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}">
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 self-center order-2 order-md-2">
                    <input type="text" class="form-control" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
                </div>
                <div class="col-sm-10 col-md-3 col-lg-3 self-center order-3 order-md-3">
                    <input type="text" class="form-control" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 self-center order-4 order-md-4">
                    <button type="button" class="btn btn-danger remove-staff-row btn-flat">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;

        $('.list-lokasi-kegiatan').append(newLocationField);
    }
    // Add activity selection handler
    $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
        e.preventDefault();
        var activity_Id = $(this).closest('tr').data('id');
        var activityKode = $(this).closest('tr').data('kode');
        var activityNama = $(this).closest('tr').data('nama');

        $('#id_programoutcomeoutputactivity').val(activity_Id);
        $('#kode_kegiatan').val(activityKode);
        $('#nama_kegiatan').val(activityNama).prop('disabled', true);
        $('#kode_program').prop('disabled', true);

        updateAllMarkers();
        $('#ModalDaftarProgramActivity').modal('hide');
    });

    // Add the remove location handler
    $(document).on('click', '.remove-staff-row', function() {
        var row = $(this).closest('.lokasi-kegiatan');
        var index = $('.lokasi-kegiatan').index(row);
        if (markers[index]) {
            map.removeLayer(markers[index]);
            markers.splice(index, 1);
        }
        row.remove();
        updateAllMarkers();
    });

});

</script>

<!-- javascript to push javascript to stack('basic_tab_js') -->
<script>
    // Next button
    document.getElementById('next-button').addEventListener('click', function(e) {
        e.preventDefault();
        var tabs = document.querySelectorAll('#details-kegiatan-tab .nav-link');
        var activeTab = document.querySelector('#details-kegiatan-tab .nav-link.active');
        var nextTabIndex = Array.from(tabs).indexOf(activeTab) + 1;

        if (nextTabIndex < tabs.length) {
            tabs[nextTabIndex].click();
        }
    });
</script>

<!-- JS for Modal Program -->
<script>
    $(document).ready(function() {
        // Variable to hold the selected program ID
        let programId = null;

        // Event handler when a program is selected in the modal
        $('#list_program_kegiatan tbody').on('click', '.select-program', function(e) {
            e.preventDefault();

            // Fetch the selected program details
            programId = $(this).data('program-id');
            const programKode = $(this).data('program-kode');
            const programNama = $(this).data('program-nama');

            // Update the hidden input and display fields
            $('#program_id').val(programId).trigger('change');
            $('#kode_program').val(programKode);
            $('#nama_program').val(programNama).prop('disabled', true);

            $('#id_programoutcomeoutputactivitykode_kegiatan').val('').trigger('change');
            $('#kode_kegiatan').val('').trigger('change');
            $('#nama_kegiatan').val('').trigger('change');

            // Close the program selection modal
            $('#ModalDaftarProgram').modal('hide');
        });

        // Handle opening the activities modal
        $('#kode_kegiatan').click(function(e) {
            if (!programId) {
                e.preventDefault(); // Prevent modal from opening
                Toast.fire({
                    icon: "warning",
                    title: "Opssss...",
                    text: "Please select a program first.",
                    timer: 3000,
                    position: "top-end",
                    timerProgressBar: true,
                });

                $('#ModalDaftarProgram').modal('show');
                return false;
            }
            else{
                fetchProgramActivities(programId);
            }
        });


        // Function to fetch activities based on programId
        function fetchProgramActivities(programId) {
            const url = '{{ route('api.program.kegiatan', ':id') }}'.replace(':id', programId);

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "{{ __('cruds.activity.search') }}...",
                        timer: 2000,
                        position: "top-end",
                        timerProgressBar: true,
                    });
                },
                success: function(data) {
                    setTimeout(() => {
                        populateModalWithActivities(data);
                    }, 500);
                },
                error: function() {
                    Toast.fire({
                        icon: "error",
                        title: "Failed to fetch activities.",
                    });
                }
            });
        }
        function populateModalWithActivities(data) {
            const tbody = $('#list_program_out_activity tbody'); // Ensure tbody selector points to the correct table
            tbody.empty(); // Clear existing rows
            // Iterate over the activity data

            if(data.length > 0){
                data.forEach(activity => {
                    const row = `
                        <tr data-id="${activity.id}" data-kode="${activity.kode}" data-nama="${activity.nama}" data-deskripsi="${activity.deskripsi}" data-indikator="${activity.indikator}" data-target="${activity.target}">
                            <td>${activity.kode}</td>
                            <td>${activity.nama}</td>
                            <td>${activity.deskripsi}</td>
                            <td>${activity.indikator}</td>
                            <td>${activity.target}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info select-activity" data-id="${activity.id}">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row); // Append the row to the table body
                });
            }
            else{
                const row = `
                    <tr>
                        <td colspan="6" class="dt-empty text-center">
                            {{ __('global.no_results') }}
                        </td>
                    </tr>
                `;
                tbody.append(row); // Append the row to the table body
            }

            // Show the modal
            $('#ModalDaftarProgramActivity').modal('show');
        }
    });
</script>

<!-- JS for drop down jenis kegiatan -->
<script>
    $(document).ready(function() {
        $('#jenis_kegiatan').select2({
            placeholder: '{{ __('global.pleaseSelect').' '. __('cruds.kegiatan.basic.jenis_kegiatan') }}',
            ajax: {
                url: '{{ route('api.kegiatan.jenis_kegiatan') }}',
                dataType: 'json',
                processResults: function (data) {
                    var results = [];
                    $.each(data, function(group, options) {
                        var optgroup = {
                            text: group,
                            children: []
                        };
                        $.each(options, function(id, text) {
                            optgroup.children.push({
                                id: id,
                                text: text
                            });
                        });
                        results.push(optgroup);
                    });
                    return {
                        results: results
                    };
                }
            }
        });

        $('#fase_pelaporan').select2({
            placeholder: '{{ __('global.pleaseSelect') }}',
        });
        $('#status').select2({
            placeholder: '{{ __('global.pleaseSelect' ).' '.__('cruds.kegiatan.basic.status_kegiatan') }}',
        });
    });
</script>

@endpush
