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
{{-- </div> --}}
<!-- jenis kegiatan-->
{{-- <div class="form-group row"> --}}
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="jenis_kegiatan" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control" name="jenis_kegiatan" id="jenis_kegiatan">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
    </div>
{{-- </div> --}}



<!-- durasi kegiatan-->
{{-- <div class="form-group row"> --}}
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
        <select class="form-control select2" data-api-url="{{ route('api.kegiatan.mitra') }}" id="nama_mitra" placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.nama_mitra') }}" name="nama_mitra">
        </select>
    </div>
<!-- status kegiatan-->
    <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
        <label for="status" class="input-group col-form-label">
            <strong>{{ __('cruds.status.title') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control" name="status" id="status" required>
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
                <label {{-- for="lokasi" --}} class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}
                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.lokasi') }}"></i>
                </label>
                {{-- <input type="text" class="form-control" id="lokasi" placeholder="{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}" name="lokasi[]"> --}}
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 self-center order-2 order-md-2">
                <label {{-- for="lat" --}} class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.lat') }}
                    <i class="bi bi-pin-map-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                </label>
                {{-- <input type="text" class="form-control" id="lat" placeholder="{{ __('cruds.kegiatan.basic.lat') }}" name="lat[]"> --}}
            </div>
            <div class="col-sm-10 col-md-3 col-lg-3 self-center order-3 order-md-3">
                <label {{-- for="long" --}} class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.long') }}
                    <i class="bi bi-geo" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                </label>
                {{-- <input type="text" class="form-control" id="long" placeholder="{{ __('cruds.kegiatan.basic.long') }}" name="long[]"> --}}
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-4 self-center order-1 order-md-1">
        <label {{-- for="maps"  --}}class="input-group col-form-label">
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
        var map, markers = []; // Array to hold all markers

        // Initialize the map
        initMap();

        // Event listener for longitude and latitude changes
        $(document).on('input', 'input[name="long[]"]', function() {
            var container = $(this).closest('.lokasi-kegiatan');
            var long = parseFloat($(this).val());
            var lat = parseFloat(container.find('input[name="lat[]"]').val());
            var index = $('.lokasi-kegiatan').index(container);

            if (!isNaN(lat) && !isNaN(long)) {
                updateMarkerAtIndex(lat, long, index);
            }
        });

        $(document).on('input', 'input[name="lat[]"]', function() {
            var container = $(this).closest('.lokasi-kegiatan');
            var lat = parseFloat($(this).val());
            var long = parseFloat(container.find('input[name="long[]"]').val());
            var index = $('.lokasi-kegiatan').index(container);

            if (!isNaN(lat) && !isNaN(long)) {
                updateMarkerAtIndex(lat, long, index);
            }
        });

        // Add event listener for location name changes
        $(document).on('input', 'input[name="lokasi[]"]', function() {
            var container = $(this).closest('.lokasi-kegiatan');
            var index = $('.lokasi-kegiatan').index(container);

            // Update popup content if marker exists
            if (markers[index]) {
                markers[index].setPopupContent(generatePopupContent(index));
            }
        });

        // Add new location and marker on button click
        $('#btn-lokasi-kegiatan').on('click', function() {
            addNewLocationInputs();
        });

        // Listen for changes in kode_kegiatan, nama_kegiatan, and nama_desa
        $('#kode_kegiatan, #nama_kegiatan, #nama_desa').on('input change', function() {
            updateAllMarkers();
        });

        // Event listener for deleting a location
        $(document).on('click', '.remove-staff-row', function() {
            var row = $(this).closest('.lokasi-kegiatan');
            var index = $('.lokasi-kegiatan').index(row);

            if (markers[index]) {
                map.removeLayer(markers[index]);
                markers.splice(index, 1);
            }

            row.remove();

            // Update remaining markers' popup content
            updateAllMarkers();
        });

        function initMap() {
            map = L.map('map').setView([-8.62194696592589, 115.20178628198094], 9);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="/">RGBDev</a>'
            }).addTo(map);

            // Add click event to map
            map.on('click', function(e) {
                // Get the last added location inputs
                var lastLocationInputs = $('.lokasi-kegiatan').last();
                var index = $('.lokasi-kegiatan').index(lastLocationInputs);

                // Set the latitude and longitude values
                lastLocationInputs.find('input[name="lat[]"]').val(e.latlng.lat.toFixed(8));
                lastLocationInputs.find('input[name="long[]"]').val(e.latlng.lng.toFixed(8));

                // Update marker for this location
                updateMarkerAtIndex(e.latlng.lat, e.latlng.lng, index);
            });
        }

        function updateMarkerAtIndex(lat, long, index) {
            // Remove existing marker at this index if it exists
            if (markers[index]) {
                map.removeLayer(markers[index]);
            }

            // Create new marker
            var marker = L.marker([lat, long]).addTo(map);
            markers[index] = marker;

            // Update popup content
            marker.bindPopup(generatePopupContent(index)).openPopup();

            // Center map on the marker
            map.setView([lat, long], 16);
        }

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

        function generatePopupContent(index) {
            var locationRow = $('.lokasi-kegiatan').eq(index);
            var kode_kegiatan = $('#kode_kegiatan').val();
            var nama_kegiatan = $('#nama_kegiatan').val();
            var nama_desa = $('#nama_desa').val();
            var lokasi = locationRow.find('input[name="lokasi[]"]').val() || '';

            return `
                <strong>Kode Kegiatan:</strong> ${kode_kegiatan}<br>
                <strong>Nama Kegiatan:</strong> ${nama_kegiatan}<br>
                <strong>Nama Desa:</strong> ${nama_desa}<br>
                <strong>Lokasi:</strong> ${lokasi}
            `;
        }

        function updateAllMarkers() {
            $('.lokasi-kegiatan').each(function(index) {
                var marker = markers[index];
                if (marker) {
                    marker.setPopupContent(generatePopupContent(index));
                }
            });
        }

        // Event listener for selecting an activity from the modal
        $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
            e.preventDefault();
            var activity_Id = $(this).closest('tr').data('id');
            var activity_Desk = $(this).closest('tr').data('deskripsi');
            var activity_Ind = $(this).closest('tr').data('indikator');
            var activity_Tar = $(this).closest('tr').data('target');

            let activityKode = $(this).closest('tr').data('kode');
            let activityNama = $(this).closest('tr').data('nama');


            // console.log(`Selected Activity ID: ${activity_Id}, Kode: ${activityKode}`, `Nama: ${activityNama}`, `Desc: ${activity_Desk}`);

            $('#id_programoutcomeoutputactivity').val(activity_Id);
            $('#kode_kegiatan').val(activityKode);
            $('#nama_kegiatan').val(activityNama).prop('disabled', true);

            $('#kode_program').prop('disabled', true);
            updateAllMarkers()
            $('#ModalDaftarProgramActivity').modal('hide');
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
    });
</script>

@endpush
