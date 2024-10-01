<div id="add_kecamatan" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.kecamatan.title')}}
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
                <label class="required" for="provinsi_id">{{ trans('cruds.provinsi.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                <select class="form-control select2" name="provinsi_id" id="provinsi_id" required style="width: 100%">
                    @foreach($provinsi as $data => $entry)
                        <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data }} - {{ $entry }}</option>
                    @endforeach
                </select>
                <div id="provinsi_id-error" class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="kabupaten_id">{{ trans('cruds.kabupaten.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
                <div class="form-group">
                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" required style="width: 100%">
                        <option>{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}</option>
                    </select>
                    <div id="kabupaten_id-error" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="kode">{{ trans('cruds.kecamatan.kode') }} {{ trans('cruds.kecamatan.title') }}</label>
                <input placeholder="{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}" type="text" id="kode" name="kode" required class="form-control" data-placement="left" data-toggle="tooltip" data-placement="top" maxlength="8">
                <div id="kode-error" class="invalid-feedback"></div>
                <span id="kode_error" class="invalid-feedback">{{ trans('cruds.kecamatan.kode_validation') }}</span>
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.kecamatan.nama') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">     
                <div id="nama-error" class="invalid-feedback"></div>
                <span id="nama_error" class="invalid-feedback">{{ trans('cruds.kecamatan.nama_validation') }}</span>
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kecamatan.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                </div>
            </div>
            <button class="btn btn-success float-right btn-add-kecamatan" data-toggle="tooltip" data-placement="top" type="submit" title="{{ trans('global.save') }}"><i class="fas fa-save"></i> {{ trans('global.save') }}</button>
        </form>
    </div>
</div>