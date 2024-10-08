<div id="add_desa" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.desa.title')}}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
                {{-- <i class="fas fa-minus"></i> --}}
            </button>
        </div>
    </div>
    <div class="card-body">
        {{-- form add desa --}}
        <form id="submit_desa" action="{{ route('desa.store')}}" method="POST" class="resettable-form" data-toggle="validator" id="desaForm" autocomplete="off" novalidate>
            @csrf
            @method('POST')
            {{-- Select Provinsi --}}
            <div class="form-group">
                <label class="required" for="provinsi_id">{{ trans('cruds.desa.form.prov') }}</label>
                <select class="form-control select2" name="provinsi_id" id="provinsi_id" required style="width: 100%">
                    @foreach($provinsi as $data => $entry)
                        <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data .' - '. $entry }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Select kabupaten --}}
            <div class="form-group">
                <label for="kabupaten_id">{{ trans('cruds.desa.form.kab') }}</label>
                <div class="form-group">
                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" required style="width: 100%">
                        <option>{{ trans('global.pleaseSelect') .' '. trans('cruds.kabupaten.title')}}</option>
                    </select>
                    <div id="kabupaten_id-error" class="invalid-feedback">{{ trans('cruds.desa.validation.kab') }}</div>
                </div>
            </div>
            {{-- Select Kecamatan --}}
            <div class="form-group">
                <label for="kecamatan_id">{{ trans('cruds.desa.form.kec') }}</label>
                <div class="form-group">
                    <select id="kecamatan_id" name="kecamatan_id" class="form-control select2 kecamatan-data" required style="width: 100%">
                        <option>{{ trans('global.pleaseSelect') .' '. trans('cruds.kecamatan.title')}}</option>
                    </select>
                    <span id="kecamatan_id_error" class="invalid-feedback">{{ trans('cruds.desa.validation.kec') }}</span>

                </div>
            </div>
            {{-- Input Kode Desa --}}
            <div class="form-group">
                <label for="kode">{{ trans('cruds.desa.form.kode') }}</label>
                <input type="text" id="kode" name="kode" required class="form-control" data-placement="left" data-toggle="tooltip" data-placement="top" maxlength="13" minlength="13" pattern="\d{2}\.\d{2}\.\d{2}\.\d{4}">
                <span id="kode_error" class="invalid-feedback">{{ trans('cruds.desa.validation.kode') }}</span>
                <span id="kode-error" class="invalid-feedback"></span>
            </div>
            {{-- Input Nama Kecamatan --}}
            <div class="form-group">
                <label for="nama">{{ trans('cruds.desa.form.nama') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                <span id="nama_error" class="invalid-feedback">{{ trans('cruds.desa.validation.nama') }}</span>
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                </div>
            </div>
            <button class="btn btn-success float-right btn-add-desa" data-toggle="tooltip" data-placement="top" type="submit" title="{{ trans('global.save') }}"><i class="fas fa-save"></i> {{ trans('global.save') }}</button>
        </form>
    </div>
</div>

@push('js')

@endpush
