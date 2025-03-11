<!-- Program and Activity Select -->
<div class="row">
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

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_komponenmodel" class="input-group col-form-label">{{ __('cruds.komponenmodel.label_komponen') }}</label>
        {{-- <!-- id komponenmodel -->
        <input type="hidden" name="komponenmodel_id" id="komponenmodel_id">
        <!-- nama komponen model -->
        <input type="text" class="form-control" id="kode_komponenmodel" placeholder="{{ __('cruds.komponenmodel.label_komponen') }}" name="kode_komponenmodel"
        data-toggle="modal" data-target="#ModalKomponenModel"> --}}
        
        <div class="select2-purple">
            <select class="form-control select2" name="model_id[]" id="model_id" multiple data-api-url="{{ route('api.komodel.model') }}">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label class="invisible">Tombol</label> <!-- Label tetap ada tetapi disembunyikan -->
        <div class="input-group-append">
            <button type="button" class="btn btn-primary" id="addKomponenBtn" data-toggle="modal" data-target="#ModalTambahKomponen">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_sektor" class="input-group col-form-label">{{ __('cruds.komponenmodel.label_sektor') }}</label>
        {{-- <!-- id sektor -->
        <input type="hidden" name="sektor_id" id="sektor_id">
        <!-- nama sektor -->
        <input type="text" class="form-control" id="kode_sektor" placeholder="{{ __('cruds.komponenmodel.label_sektor') }}" name="kode_sektor"
        data-toggle="modal" data-target="#ModalDaftarSektor"> --}}

        <div class="select2-purple">
            <select class="form-control select2" name="sektor_id[]" id="sektor_id" multiple data-api-url="{{ route('api.komodel.sektor') }}">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
        
    </div>
</div>

<!-- List -->
<div class="row tambah_komodel" id="tambah_komodel">
    <div class="col mb-1 mt-2">
        <button type="button" class="btn btn-warning" id="addDataBtn" data-toggle="modal" data-target="#ModalTambah" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
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
                    <th colspan="7" class="text-center align-middle">{{ __("cruds.komponenmodel.label_lokasi") }}</th>
                    <th rowspan="2" class="align-middle">Jumlah</th>
                    <th rowspan="2" class="align-middle">Satuan</th>
                    <th rowspan="2" class="text-center align-middle">{{ __("global.actions") }}</th>
                </tr>
                <tr id="activityHeaders" class="text-sm">
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.provinsi") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kabupaten") }} </th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kecamatan") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.desa") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.dusun") }}</th>
                    <th class="align-middle text-center">Long</th>
                    <th class="align-middle text-center">Lat</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="display nowrap">
            </tbody>
        </table>
    </div>

</div>

@include('tr.komponenmodel.tabs.komodel-modal')

@push('basic_tab_js')
<script>
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
                console.log("Data dari API:", response); // Debugging

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
                console.log("Data Model dari API:", response); // Debugging

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
</script>

@endpush

