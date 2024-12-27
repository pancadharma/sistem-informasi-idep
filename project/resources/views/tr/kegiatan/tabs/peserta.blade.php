<!-- Peserta Kegiatan-->
<div class="form-group row tambah_peserta" id="tambah_peserta">
    <div class="col-12 pl-0 pr-0">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
            <i class="bi bi-person-plus"></i>
            {{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}
        </button>
    </div>
</div>
<div class="form-group row list_peserta">
    <div class="table-responsive">
        <table id="list_peserta_kegiatan" class="table table-sm table-borderless table-hover mb-0 datatable-kegiatan" style="width:100%">
            <thead style="background-color: #149387 !important">
                <tr class="align-middle text-center">
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 rounded-start-3 border-secondary">{{ __('No.') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.disabilitas') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.hamil') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.status_kawin') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.no_kk') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.jenis_peserta') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.nama_kk') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary rounded-end-3">{{ __('global.action') }}</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
    </div>
</div>

@include('tr.kegiatan.tabs.peserta-modal')

@push('basic_tab_js')
@endpush
