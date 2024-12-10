{{-- Basic Information --}}
<div class="form-group row">
    <label for="pilih_program" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <!-- kode program -->
        <input type="hidden" name="program_id" id="program_id">
        <!-- kode program -->
        <input type="text" class="form-control" id="kode_program" placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program"
        data-toggle="modal" data-target="#ModalDaftarProgram">
    </div>
    <!-- nama program-->
    <label for="nama_program" class="col-sm-3 col-md-3 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.program_nama') }}</label>
    <div class="col-sm col-md col-lg-4 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="nama_program" placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program">
    </div>
</div>
<div class="form-group row">
     <!-- kode -->
    <label for="kode_kegiatan" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.kode') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <input type="hidden" class="form-control" id="id_programoutcomeoutputactivity" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="id_programoutcomeoutputactivity">
        <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan">
    </div>
</div>

<div class="form-group row">
    <!-- nama kegiatan-->
    <label for="nama_kegiatan" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.nama') }}</label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="nama_kegiatan" placeholder=" {{ __('cruds.kegiatan.basic.nama') }}" name="nama" required>
    </div>
</div>


<div class="form-group row">
    <!-- dusun-->
    <label for="nama_desa" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.desa') }}</label>
    <div class="col-sm col-md col-lg-4 order-2 order-md-2 self-center">
        <select name="nama_desa" id="nama_desa" class="form-control select2" data-api-url="{{ route('api.kegiatan.desa') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.desa') }}">
        </select>
    </div>
    <!-- lokasi-->
    <label for="lokasi" class="col-sm-3 col-md-3 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.lokasi') }}</label>
    <div class="col-sm col-md col-lg-4 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="lokasi" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}" name="lokasi">
    </div>
</div>
<div class="form-group row">
    <!-- latitude-->
    <label for="lat" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.lat') }}</label>
    <div class="col-sm col-md col-lg-4 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="lat" placeholder="{{ __('cruds.kegiatan.lat') }}" name="lat">
    </div>
    <!-- longitude-->
    <label for="longitude" class="col-sm-3 col-md-3 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.long') }}</label>
    <div class="col-sm col-md col-lg-4 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="longitude" placeholder="{{ __('cruds.kegiatan.long') }}" name="longitude">
    </div>
</div>
<div class="form-group row">
    <!-- tgl mulai-->
    <label for="tanggalmulai" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.basic.tanggalmulai') }}</label>
    <div class="col-sm-3 col-md-3 col-lg-4 order-2 order-md-2 self-center">
        <input type="date" class="form-control" id="tanggalmulai" placeholder="" name="tanggalmulai">
    </div>
    <!-- tgl selesai-->
    <label for="tanggalselesai" class="col-sm-3 col-md-3 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">{{ __('cruds.kegiatan.basic.tanggalselesai') }}</label>
    <div class="col-sm-3 col-md-3 col-lg-4 order-4 order-md-4 self-center">
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
        $('#list_program_kegiatan tbody').on('click', '.select-program', function(e) {
            e.preventDefault();
            var programId = $(this).closest('tr').data('program-id');
            var programKode = $(this).closest('tr').data('program-kode');
            var programName = $(this).closest('tr').data('program-nama');

            $('#program_id').val(programId).trigger('change');
            $('#kode_program').val(programKode).trigger('change').prop('disabled', true);
            $('#nama_program').val(programName).trigger('change').prop('disabled', true);

            setTimeout(function() {
                $('#kode_kegiatan').focus();
            }, 100);

            saveFormDataToStorage();
            $('#ModalDaftarProgram').modal('hide');
        });
    });

</script>
@endpush
