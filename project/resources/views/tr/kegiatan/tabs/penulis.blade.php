<!-- Penulis Laporan Kegiatan-->
<div class="form-group row tambah_penulis col">
    <button type="button" class="btn btn-success float-right" title="{{ __('global.add') .' '. __('cruds.kegiatan.penulis.label') }}">
        <i class="bi bi-folder-plus"></i> {{ __('global.add') .' '. __('cruds.kegiatan.penulis.label') }}
    </button>
</div>
<div class="form-group row list_penulis">
    <div class="col-sm col-md col-lg self-center">
        <label for="penulis" class="input-group">{{ __('cruds.kegiatan.penulis.label') }}</label>
        <input type="text" class="form-control select2" id="penulis" placeholder=" {{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.penulis.label') }}" name="penulis[]">
    </div>
    <div class="col-sm col-md col-lg self-center">
        <label for="jabatan" class="input-group ">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
        <input type="text" class="form-control select2" id="jabatan" placeholder=" {{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.penulis.jabatan') }}" name="jabatan[]">
    </div>
</div>
