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

<!-- Peserta Kegiatan-->
<div class="row tambah_peserta" id="tambah_peserta">
    <div class="col mb-1 mt-2">
        <button type="button" class="btn btn-warning" id="addDataBtn" data-toggle="modal" data-target="#ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
            {{ __('global.add') .' '. '' }}
            <i class="bi bi-person-plus"></i>
        </button>
        <button type="button" class="btn btn-danger" id="submitDataBtn">{{ __('global.save') }} <i class="bi bi-save"></i></button>
    </div>
</div>
<div class="row responsive list_peserta">
    <div class="col-12 table-responsive">
        <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display nowrap">
            <thead style="background-color: rgba(255, 255, 255, 0) !important">
                <tr class="align-middle text-center display nowrap">
                    <th rowspan="2" class="text-center align-middle d-none">#</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.nama") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.gender") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.disability") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.marjinal") }}</th>
                    <th colspan="4" class="text-center align-middle">{{ __("cruds.meals.penerima.address") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.no_telp") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.jenis_kelompok") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.meals.penerima.age") }}</th>
                    <th colspan="4" class="text-center align-middle">{{ __("cruds.meals.penerima.age_group") }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __("global.actions") }}</th>
                </tr>
                <tr>
                    <th class="align-middle text-center">{{ __("cruds.meals.penerima.rt") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.meals.penerima.banjar") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.meals.penerima.dusun") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.meals.penerima.desa") }}</th>
                    <th class="align-middle text-center">0-17</th>
                    <th class="align-middle text-center">18-24</th>
                    <th class="align-middle text-center">25-59</th>
                    <th class="align-middle text-center"> > 60 </th>
                </tr>
            </thead>
            <tbody id="tableBody" class="display nowrap">
            </tbody>
        </table>
    </div>

</div>

@include('tr.meals.tabs.bene-modal')

@push('basic_tab_js')


@endpush

