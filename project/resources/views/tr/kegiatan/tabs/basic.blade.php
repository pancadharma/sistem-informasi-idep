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
        <input type="text" class="form-control" id="nama_kegiatan" placeholder=" {{ __('cruds.kegiatan.basic.nama') }}" name="nama" required>
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
    <label for="longitude" class="col-sm-12 col-md-12 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.long') }}</label>
    <div class="col-sm-12 col-md-12 col-lg-2 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="longitude" placeholder="{{ __('cruds.kegiatan.long') }}" name="longitude">
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

@include('tr.kegiatan.tabs.program')
@include('tr.kegiatan.tabs.program-act')

@push('next-button')
<div class="button" id="task_flyout">
    <button type="button" id="clearStorageButton" class="btn btn-warning float-left">Reset</button>
    <button type="button" id="next-button" class="btn btn-primary float-right">Next</button>
</div>
@endpush

@push('basic_tab_js')
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
                title: "Please select a program first.",
                timer: 2000,
                timerProgressBar: true,
            });
            return;
        }
        fetchProgramActivities(programId);
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
                    title: "Fetching activities...",
                    timer: 1000,
                    timerProgressBar: true,
                });
            },
            success: function(data) {
                setTimeout(() => {
                    populateModalWithActivities(data);
                }, 1000);
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
        data.forEach(activity => {
            const row = `
                <tr data-id="${activity.id}" data-deskripsi="${activity.deskripsi}" data-indikator="${activity.indikator}" data-target="${activity.target}">
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

        // Show the modal
        $('#ModalDaftarProgramActivity').modal('show');
    }

    // Event listener for selecting an activity from the modal
    $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
        e.preventDefault();
        let activityId = $(this).data('id');
        let activityDeskripsi = $(this).data('deskripsi');
        let activityIndikator = $(this).data('indikator');
        let activityTarget = $(this).data('target');

        var activity_Id = $(this).closest('tr').data('id');
        var activity_Desk = $(this).closest('tr').data('deskripsi');
        var activity_Ind = $(this).closest('tr').data('indikator');
        var activity_Tar = $(this).closest('tr').data('target');
        console.log(`Selected Activity ID: ${activity_Id}, Deskripsi: ${activity_Desk}`, `Indikator: ${activity_Ind}`, `Target: ${activity_Tar}`);


        $('#kode_program').prop('disabled', true);
        $('#ModalDaftarProgramActivity').modal('hide');
    });

});

</script>
@endpush
