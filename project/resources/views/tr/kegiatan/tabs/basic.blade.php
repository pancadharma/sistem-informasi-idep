{{-- Basic Information --}}
<div class="form-group row">
    <label for="pilih_program" class="col-sm-12 col-md-12 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-2 order-md-2 self-center">
        <!-- kode program -->
        <input type="hidden" name="program_id" id="program_id">
        <!-- kode program -->
        <input type="text" class="form-control" id="kode_program" placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program"
        data-toggle="modal" data-target="#ModalDaftarProgram">
    </div>
    <!-- nama program-->
    <label for="nama_program" class="col-sm-12 col-md-12 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.program_nama') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-6 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="nama_program" placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program">
    </div>
</div>
<div class="form-group row">
     <!-- kode kegiatan-->
    <label for="kode_kegiatan" class="col-sm-12 col-md-12 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.kode') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-2 order-md-2 self-center">
        <input type="hidden" class="form-control" id="id_programoutcomeoutputactivity" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="id_programoutcomeoutputactivity">
        <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan"
        data-toggle="modal" data-target="#ModalDaftarProgramActivity">
    </div>
    <!-- nama kegiatan-->
    <label for="nama_kegiatan" class="col-sm-12 col-md-12 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.nama') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-6 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="nama_kegiatan" placeholder=" {{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan" required>
    </div>
</div>


<div class="form-group row">
    <!-- dusun
        <label for="nama_desa" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.desa') }}</label>
    -->
    <label for="nama_desa" class="col-sm-12 col-md-12 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.desa') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-2 order-md-2 self-center">
        <select name="nama_desa" id="nama_desa" class="form-control select2" data-api-url="{{ route('api.kegiatan.desa') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.desa') }}">
        </select>
    </div>
    <!-- lokasi-->
    <label for="lokasi" class="col-sm-12 col-md-12 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.lokasi') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-6 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="lokasi" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}" name="lokasi">
    </div>
</div>
<div class="form-group row">
    <!-- latitude-->
    <label for="lat" class="col-sm-12 col-md-12 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.lat') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="lat" placeholder="{{ __('cruds.kegiatan.lat') }}" name="lat">
    </div>
    <!-- longitude-->
    <label for="long" class="col-sm-12 col-md-12 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.long') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="long" placeholder="{{ __('cruds.kegiatan.long') }}" name="long">
    </div>
</div>
<div class="form-group row">
    <!-- tgl mulai-->
    <label for="tanggalmulai" class="col-sm-12 col-md-12 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.tanggalmulai') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-2 order-md-2 self-center">
        <input type="date" class="form-control" id="tanggalmulai" placeholder="" name="tanggalmulai">
    </div>
    <!-- tgl selesai-->
    <label for="tanggalselesai" class="col-sm-12 col-md-12 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.tanggalselesai') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-4 order-md-4 self-center">
        <input type="date" class="form-control" id="tanggalselesai" placeholder="" name="tanggalselesai">
    </div>
</div>
<!-- nama mitra-->
<div class="form-group row">
    <label for="nama_mitra" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.nama_mitra') }}</label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <select class="form-control select2" data-api-url="{{ route('api.kegiatan.mitra') }}" id="nama_mitra" placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.nama_mitra') }}" name="nama_mitra">
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="maps" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('Maps Location') }}</label>
    <div class="col-sm col-md col-lg-4 order-2 order-md-2 self-center">
        <div id="map"></div>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script defer>
    $(document).ready(function() {
        var map, marker;

        // Inisialisasi peta
        initMap();

        $('#long').on('input', function() {
            var long = parseFloat($('#long').val());
            var lat = parseFloat($('#lat').val());

            if (!isNaN(lat) && !isNaN(long)) {
                updateMarker(lat, long);
            }
        });

        $('#lat').on('input', function() {
            var lat = parseFloat($('#lat').val());
            var long = parseFloat($('#long').val());

            if (!isNaN(lat) && !isNaN(long)) {
                updateMarker(lat, long);
            }
        });

        function initMap() {
            map = L.map('map').setView([-8.62194696592589, 115.20178628198094], 8);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="/">RGBDev</a>'
            }).addTo(map);

            marker = L.marker([-8.62194696592589, 115.20178628198094]).addTo(map);
            marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();

        }

        function updateMarker(lat, long) {
            marker.setLatLng([lat, long]);
            map.setView([lat, long], 16);
        }
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

    // Event listener for selecting an activity from the modal
    $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
        e.preventDefault();
        var activity_Id = $(this).closest('tr').data('id');
        var activity_Desk = $(this).closest('tr').data('deskripsi');
        var activity_Ind = $(this).closest('tr').data('indikator');
        var activity_Tar = $(this).closest('tr').data('target');

        let activityKode = $(this).closest('tr').data('kode');
        let activityNama = $(this).closest('tr').data('nama');


        console.log(`Selected Activity ID: ${activity_Id}, Kode: ${activityKode}`, `Nama: ${activityNama}`, `Desc: ${activity_Desk}`);

        $('#id_programoutcomeoutputactivity').val(activity_Id);
        $('#kode_kegiatan').val(activityKode);
        $('#nama_kegiatan').val(activityNama).prop('disabled', true);

        $('#kode_program').prop('disabled', true);

        $('#ModalDaftarProgramActivity').modal('hide');
    });

});
</script>
@endpush
