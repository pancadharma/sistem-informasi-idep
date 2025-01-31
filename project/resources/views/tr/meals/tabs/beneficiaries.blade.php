<!-- Peserta Kegiatan-->
<div class="row tambah_peserta" id="tambah_peserta">
    <div class="col-12 pl-0 pr-0">
        <button type="button" class="btn btn-warning mt-3" id="addDataBtn" data-toggle="modal" data-target="#ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
            {{ __('global.add') .' '. '' }}
            <i class="bi bi-person-plus"></i>
        </button>
    </div>
</div>
<div class="row responsive list_peserta">
    <div class="table-responsive">
        <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display nowrap">
            <thead style="background-color: rgb(64, 206, 8) !important">
                <tr class="align-middle text-center display nowrap">
                    <th rowspan="2" class="text-center align-middle">#</th>
                    <th rowspan="2" class="align-middle">Nama</th>
                    <th rowspan="2" class="align-middle">Gender</th>
                    <th rowspan="2" class="align-middle">Disabilitas</th>
                    <th rowspan="2" class="align-middle">Kelompok Rentan</th>
                    <th colspan="4" class="text-center align-middle">Alamat</th>
                    <th rowspan="2" class="align-middle">No. Telp</th>
                    <th rowspan="2" class="align-middle">Jenis Kelompok/Instansi</th>
                    <th rowspan="2" class="align-middle">Usia</th>
                    <th colspan="4" class="text-center align-middle">Kelompok Usia</th>
                    <th rowspan="2" class="text-center align-middle">Action</th>
                </tr>
                <tr>
                    <th class="align-middle text-center">RT</th>
                    <th class="align-middle text-center">RW / Banjar</th>
                    <th class="align-middle text-center">Dusun</th>
                    <th class="align-middle text-center">Desa</th>
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
    <button type="button" class="btn btn-danger" id="submitDataBtn">{{ __('global.save') }}</button>
</div>

@include('tr.meals.tabs.bene-modal')

@push('basic_tab_js')


@endpush

