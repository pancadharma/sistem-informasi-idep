<div class="card card-primary collapsed-card">
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
        <form @submit.prevent="handleSubmit" method="PATCH" class="resettable-form" id="editKecamatanForm" autocomplete="off">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="id">
            {{-- <div class="form-group">
                <label class="required" for="provinsi_id">{{ trans('cruds.provinsi.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                <select class="form-control select2 {{ $errors->has('provinsi') ? 'is-invalid' : '' }}" name="provinsi_id" id="provinsi_id" required style="width: 100%">
                    @foreach($provinsi as $data => $entry)
                        <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data }} - {{ $entry }}</option>
                    @endforeach
                </select>
            </div> --}}

            <div class="form-group">
                <select id="provinsi_id" name="provinsi_id" class="form-control select2 provinsi-data" style="width: 100%"></select>
            </div>


            <div class="form-group">
                <label for="kabupaten_nama">{{ trans('cruds.kabupaten.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
                <div class="form-group">
                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" style="width: 100%" required>
                        <option>{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="kode">{{ trans('cruds.kecamatan.kode') .' '. trans('cruds.kecamatan.title') }}</label>
                <input placeholder="" type="text" id="editkode" name="kode" class="form-control" required data-placement="left" title="Update {{ trans('cruds.kecamatan.kode') .' '. trans('cruds.kecamatan.title') }}" data-toggle="tooltip" data-placement="top" maxlength="8">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.kecamatan.nama') }}</label>
                <input type="text" id="editnama" name="nama" class="form-control" required maxlength="200">
            </div>

            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kecamatan.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="aktif" id="edit-aktif" value="0">
                    <label for="editaktif">{{ trans('cruds.status.aktif', [], 'en') ?: trans('cruds.status.tidak_aktif', [], 'en') }}</label>
                </div>
            </div>
            {{-- <button class="btn btn-success float-right btn-update-kecamatan" data-toggle="tooltip" data-placement="top" title="{{ trans('global.update') }}"><i class="fas fa-update"></i> {{ trans('global.update') }}</button> --}}

            <button type="submit" id="editKecamatan" class="btn btn-success float-right" ><i class="fas fa-update"></i> {{ trans('global.update') }}</button>
        </form>
    </div>
</div>