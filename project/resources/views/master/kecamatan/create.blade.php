<div id="add_kecamatan" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ __('global.create')}} {{__('cruds.kecamatan.title')}}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        {{-- form add kecamatan --}}
        <form action="{{ route('kecamatan.store')}}" method="POST" class="resettable-form" novalidate data-toggle="validator" id="kecamatanForm" autocomplete="off">
            @csrf
            @method('POST')
            <div class="form-group">
                <label class="required" for="provinsi_id">{{ __('cruds.provinsi.nama') }} {{ __('cruds.provinsi.title') }}</label>
                <select class="form-control select2" name="provinsi_id" id="provinsi_id" required style="width: 100%">
                    @foreach($provinsi as $data => $entry)
                        <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data }} - {{ $entry }}</option>
                    @endforeach
                </select>
                <div id="provinsi_id-error" class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="kabupaten_id">{{ __('cruds.kabupaten.nama') }} {{ __('cruds.kabupaten.title') }}</label>
                <div class="form-group">
                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" required style="width: 100%">
                        <option>{{ __('global.pleaseSelect') }} {{ __('cruds.provinsi.title')}}</option>
                    </select>
                    <div id="kabupaten_id-error" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="kode">{{ __('cruds.kecamatan.kode') }} {{ __('cruds.kecamatan.title') }}</label>
                <input placeholder="{{ __('global.pleaseSelect') }} {{ __('cruds.kabupaten.title')}}" type="text" id="kode" name="kode" required class="form-control" data-placement="left" data-toggle="tooltip" data-placement="top" maxlength="8">
                <div id="kode-error" class="invalid-feedback"></div>
                <span id="kode_error" class="invalid-feedback">{{ __('cruds.kecamatan.kode_validation') }}</span>
            </div>
            <div class="form-group">
                <label for="nama">{{ __('cruds.kecamatan.nama') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                <div id="nama-error" class="invalid-feedback"></div>
                <span id="nama_error" class="invalid-feedback">{{ __('cruds.kecamatan.nama_validation') }}</span>
            </div>
            <div class="form-group">
            <strong>{{ __('cruds.status.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                </div>
            </div>
            <button class="btn btn-success float-right btn-add-kecamatan" data-toggle="tooltip" data-placement="top" type="submit" title="{{ trans('global.save') }}"><i class="fas fa-save"></i> {{ trans('global.save') }}</button>
        </form>
    </div>
</div>
