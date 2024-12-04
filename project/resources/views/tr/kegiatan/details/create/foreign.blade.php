{{-- <div class="form-group row">
    <!-- nama kegiatan-->
    <label for="program_output_activity" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 control-label small mb-0">
        {{ __('cruds.activity.select') }}
    </label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
        <select name="program_output_activity" id="program_output_activity" class="form-control select2" placeholder=" {{ __('cruds.activity.select') }}" >
            <option value="">{{ __('cruds.activity.select') }}</option>
        </select>
    </div>
</div>
 --}}

<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="program_output_activity" class="control-label small mb-0">{{ __('cruds.kegiatan.program') }}</label>
            <select name="program_output_activity" id="program_output_activity" class="form-control select2">
                <option value="">{{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.program') }}</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="bentuk_kegiatan" class="control-label small mb-0">{{ __('cruds.kegiatan.bentuk_kegiatan') }}</label>
            <select name="bentuk_kegiatan" id="bentuk_kegiatan" class="form-control select2">
                <option value="">{{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.bentuk_kegiatan') }}</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="kategori_lokasi_kegiatan" class="control-label small mb-0">{{ __('cruds.kegiatan.kategori_lokasi_kegiatan') }}</label>
            <select name="kategori_lokasi_kegiatan" id="kategori_lokasi_kegiatan" class="form-control select2">
                <option value="">{{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.kategori_lokasi_kegiatan') }}</option>
            </select>
        </div>
    </div>
</div>
