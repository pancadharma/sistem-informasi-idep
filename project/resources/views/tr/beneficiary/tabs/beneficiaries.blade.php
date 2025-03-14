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
        <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display text-nowrap">
            <thead style="background-color: rgba(255, 255, 255, 0) !important" class="text-sm">
                <tr class="align-middle text-center display nowrap">
                    <th rowspan="2" class="text-center align-middle d-none">#</th>
                    <th rowspan="2" class="align-middle text-nowrap">{{ __("cruds.beneficiary.penerima.nama") }}</th>
                    <th rowspan="2" class="align-middle text-wrap">{{ __("cruds.beneficiary.penerima.gender") }}</th>
                    <th rowspan="2" class="align-middle text-wrap d-none">{{ __("cruds.beneficiary.penerima.disability") }}</th>
                    <th rowspan="2" class="align-middle text-wrap">{{ __("cruds.beneficiary.penerima.marjinal") }}</th>
                    <th colspan="4" class="text-center align-middle">{{ __("cruds.beneficiary.penerima.address") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.beneficiary.penerima.no_telp") }}</th>
                    <th rowspan="2" class="align-middle text-wrap">{{ __("cruds.beneficiary.penerima.jenis_kelompok") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.beneficiary.penerima.age") }}</th>
                    <th colspan="4" class="text-center align-middle">{{ __("cruds.beneficiary.penerima.age_group") }}</th>
                    <th rowspan="2" class="text-center align-middle text-nowrap" id="headerActivityProgram">Activity</th>
                    <th rowspan="2" class="text-center align-middle text-nowrap" id="header_is_non_activity">Non-AC</th>
                    <th rowspan="2" class="text-center align-middle">{{ __("global.actions") }}</th>
                </tr>
                <tr id="activityHeaders" class="text-sm">
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }} </th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }} <small><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></small></th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                    <th class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
                    <th class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
                    <th class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
                    <th class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
                </tr>
            </thead>
            <tbody id="tableBody" class="display nowrap">
            </tbody>
        </table>
    </div>

</div>

@include('tr.beneficiary.tabs.bene-modal')

@push('basic_tab_js')


@endpush

