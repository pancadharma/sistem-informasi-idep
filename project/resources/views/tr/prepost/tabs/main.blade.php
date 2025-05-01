<!-- Program and Activity Select -->
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
        <input type="hidden" class="form-control" id="programoutcomeoutputactivity_id" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="programoutcomeoutputactivity_id">
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
{{-- Nama Pelatihan --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">{{ __('cruds.prepost.nama_pelatihan') }}</label>
        <input type="text" class="form-control" id="nama_pelatihan" placeholder="{{ __('cruds.prepost.nama_pelatihan') }}" name="nama_pelatihan">
    </div>
</div>

{{-- Tanggal Pelatihan --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">{{ __('cruds.prepost.start') }}</label>
        <input type="date" class="form-control" id="start_date" placeholder="{{ __('cruds.prepost.start') }}" name="start_date">
    </div>
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">{{ __('cruds.prepost.end') }}</label>
        <input type="date" class="form-control" id="end_date" placeholder="{{ __('cruds.prepost.end') }}" name="end_date">
    </div>
</div>

<!-- List -->
<div class="row tambah_komodel" id="tambah_komodel">
    <div class="col mb-1 mt-2">
        <button type="button" class="btn btn-warning" id="addDataBtn" data-toggle="modal" data-target="#ModalTambah" title="{{ __('global.add') }}">
            {{ __('global.add') .' '. '' }}
            {{-- <i class="bi bi-person-plus"></i> --}}
        </button>
        <button type="button" class="btn btn-success" id="submitDataBtn">{{ __('global.save') }} <i class="bi bi-save"></i></button>
    </div>
</div>
<div class="row responsive list_peserta">
    <div class="col-12 table-responsive">
        <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display text-nowrap">
            <thead style="background-color: rgba(255, 255, 255, 0) !important" class="text-sm">
                <tr class="align-middle text-center display nowrap">
                    <th rowspan="2" class="text-center align-middle d-none">#</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.nama_peserta') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.jenis_kelamin') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.kontak') }}</th>
                    <th colspan="5" class="text-center align-middle">{{ __("cruds.prepost.alamat") }}</th>
                    {{-- <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.start') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.end') }}</th> --}}
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.pre_score') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.filledby') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.post_score') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.filledby') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.perubahan') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.keterangan') }}</th> 
                    <th rowspan="2" class="text-center align-middle">{{ __("global.actions") }}</th>
                </tr>
                <tr id="activityHeaders" class="text-sm">
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.provinsi") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kabupaten") }} </th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kecamatan") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.desa") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.dusun") }}</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="display nowrap">
            </tbody>
        </table>
    </div>

</div>

{{-- @include('tr.komponenmodel.tabs.komodel-modal') --}}

@push('basic_tab_js')
{{-- <script>
    $(document).ready(function() {
    $('#sektor_id').select2({
        allowClear: true,
        placeholder: "Pilih Sektor",
        ajax: {
            url: "{{ route('api.komodel.sektor') }}",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term, // kirim parameter pencarian
                    page: params.page || 1 // pagination
                };
            },
            processResults: function(response) {
                // console.log("Data dari API:", response); // Debugging

                return {
                    results: response.results.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nama
                        };
                    }),
                    pagination: {
                        more: response.pagination.more
                    }
                };
            },
            cache: true
        }
    });
});
$(document).ready(function() {
    $('#model_id').select2({
        allowClear: true,
        placeholder: "Pilih Model",
        ajax: {
            url: "{{ route('api.komodel.model') }}", // Ganti dengan route API yang benar
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term, // kirim parameter pencarian
                    page: params.page || 1 // pagination
                };
            },
            processResults: function(response) {
                // console.log("Data Model dari API:", response); // Debugging

                return {
                    results: response.results.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nama // Sesuaikan dengan field yang benar
                        };
                    }),
                    pagination: {
                        more: response.pagination.more
                    }
                };
            },
            cache: true
        }
    });
});
</script> --}}

@endpush

