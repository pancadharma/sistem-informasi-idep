{{-- <!-- Hasil Kegiatan -->
<div class="form-group row">
    <label for="rekomendasi" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.hasil.rekomendasi') }}</label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <textarea name="rekomendasi" id="rekomendasi" class="form-control summernote" rows="2"
        placeholder=" {{ __('cruds.kegiatan.hasil.rekomendasi') }}"></textarea>
   </div>
</div>
<!-- Rencana Tindak Lanjut -->
<div class="form-group row">
    <label for="tindak_lanjut" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.hasil.tindak_lanjut') }}</label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <textarea name="tindak_lanjut" id="tindak_lanjut" class="form-control summernote" rows="2"
        placeholder=" {{ __('cruds.kegiatan.hasil.tindak_lanjut') }}"></textarea>
   </div>
</div>
<!-- Rencana Tindak Lanjut -->
<div class="form-group row">
    <label for="tantangan" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.hasil.tantangan') }}</label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <textarea name="tantangan" id="tantangan" class="form-control summernote" rows="2"
        placeholder=" {{ __('cruds.kegiatan.hasil.tantangan') }}"></textarea>
   </div>
</div> --}}

<div id="dynamic-form-container"></div>

<div class="form-group row">
    <label for="catatan_penulis" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">
        {{ __('cruds.kegiatan.hasil.catatan_penulis') }}
        <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.hasil.catatan_penulis_helper') }}"></i>
    </label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <textarea name="catatan_penulis" id="catatan_penulis" placeholder=" {{ __('cruds.kegiatan.hasil.catatan_penulis_helper') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<div class="form-group row">
    <label for="indikasi_perubahan" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">
        {{ __('cruds.kegiatan.hasil.indikasi_perubahan') }}
        <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.hasil.indikasi_perubahan_helper') }}"></i>
    </label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <textarea name="indikasi_perubahan" id="indikasi_perubahan" placeholder=" {{ __('cruds.kegiatan.hasil.indikasi_perubahan_helper') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>