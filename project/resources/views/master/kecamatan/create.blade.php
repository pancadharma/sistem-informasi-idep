<div class="card card-primary">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.kecamatan.title')}}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        {{-- form add kecamatan --}}
        <form action="{{ route('kecamatan.store')}}" method="POST" class="resettable-form" id="kecamatanForm" autocomplete="off">
            @csrf
            @method('POST')
            {{-- <div class="form-group">
                <label for="provinsi_nama">{{ trans('cruds.provinsi.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                <div class="form-group">
                    <select id="provinsi_add" name="provinsi_id" class="form-control select2 provinsi-data" style="width: 100%">
                        <option></option>
                    </select>
                </div>
            </div> --}}
            <div class="form-group">
                <label class="required" for="provinsi_id">{{ trans('cruds.provinsi.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                <select class="form-control select2 {{ $errors->has('provinsi') ? 'is-invalid' : '' }}" name="provinsi_id" id="provinsi_id" required>
                    @foreach($provinsi as $data => $entry)
                        <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data }} - {{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="kabupaten_nama">{{ trans('cruds.kabupaten.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
                <div class="form-group">
                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" style="width: 100%">
                        <option>{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="kode">{{ trans('cruds.kecamatan.kode') }} {{ trans('cruds.kecamatan.title') }}</label>
                <input placeholder="" type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required data-toggle="tooltip" data-placement="top" maxlength="5">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.kecamatan.nama') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kecamatan.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                </div>
            </div>
            <button class="btn btn-success float-right btn-add-kecamatan" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
    </div>
</div>