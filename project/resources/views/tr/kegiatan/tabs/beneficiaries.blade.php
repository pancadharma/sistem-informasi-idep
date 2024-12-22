<!-- Peserta Kegiatan -->
<!--
    <div class="form-group row tambah_peserta" id="tambah_peserta">
    <div class="col-12 pl-0 pr-0">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
            <i class="bi bi-person-plus"></i>
            {{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}
        </button>
    </div>
</div>
-->
<div class="form-group row list_peserta">
    <label for="peserta" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.hasil.tindak_lanjut') }}</label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <textarea name="peserta" id="peserta" class="form-control summernote" rows="2"
        placeholder=" {{ __('cruds.kegiatan.hasil.tindak_lanjut') }}"></textarea>
   </div>
</div>

@push('basic_tab_js')
@endpush
