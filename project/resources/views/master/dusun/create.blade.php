<div id="add_dusun" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.dusun.title')}}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
                {{-- <i class="fas fa-minus"></i> --}}
            </button>
        </div>
    </div>
    <div class="card-body">
        {{-- form add dusun --}}
        <form id="submit_dusun" action="{{ route('dusun.store')}}" method="POST" class="resettable-form" data-toggle="validator" id="dusunForm" autocomplete="off" novalidate>
            @csrf
            @method('POST')
            {{-- Select Provinsi --}}
            <div class="form-group">
                <label for="provinsi_id">{{ trans('cruds.dusun.form.prov') }}</label>
                <select class="form-control select2" name="provinsi_id" id="provinsi_id" required style="width: 100%">
                    @foreach($provinsi as $data => $entry)
                        <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data .' - '. $entry }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Select kabupaten --}}
            <div class="form-group">
                <label for="kabupaten_id">{{ trans('cruds.dusun.form.kab') }}</label>
                <div class="form-group">
                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" required style="width: 100%">
                        <option value="" selected>{{ trans('global.pleaseSelect') .' '. trans('cruds.kabupaten.title')}}</option>
                    </select>
                </div>
            </div>
            {{-- Select Kecamatan --}}
            <div class="form-group">
                <label for="kecamatan_id">{{ trans('cruds.dusun.form.kec') }}</label>
                <div class="form-group">
                    <select id="kecamatan_id" name="kecamatan_id" class="form-control select2 kecamatan-data" required style="width: 100%">
                        <option value="" selected>{{ trans('global.pleaseSelect') .' '. trans('cruds.kecamatan.title')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="desa_id">{{ trans('cruds.dusun.form.des') }}</label>
                <div class="form-group">
                    <select id="desa_id" name="desa_id" class="form-control select2 desa-data" required style="width: 100%">
                        <option value="" selected>{{ trans('global.pleaseSelect') .' '. trans('cruds.desa.title')}}</option>
                    </select>
                </div>
            </div>
            {{-- Input Kode Dusun --}}
            <div class="form-group">
                <label for="kode">{{ trans('cruds.dusun.form.kode') }}</label>
                <input type="text" id="kode" name="kode" required class="form-control" maxlength="16" minlength="16">
                {{--  pattern="\d{2}\.\d{2}\.\d{2}\.\d{4}.\d{2}" --}}
            </div>
            {{-- Input Nama Kecamatan --}}
            <div class="form-group">
                <label for="nama">{{ trans('cruds.dusun.form.nama') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200" aria-describedby="nama-error" aria-invalid="true" pattern="^[A-Za-z][A-Za-z0-9 .]*$" title="Must start with a letter. Repeated character like space, dot or else are not allowed 😊">
                {{-- ^[A-Za-z][A-Za-z0-9 .]*$ --}}
            </div>
            {{-- Kode Pos --}}
            <div class="form-group">
                <label for="kode_pos">{{ trans('cruds.dusun.form.kode_pos') }}</label>
                <input type="text" id="kode_pos" name="kode_pos" class="form-control" val="" maxlength="5" aria-describedby="kode_pos-error" aria-invalid="true" pattern="\d*" title="Only number input allowed">
                <span class="invalid-feedback" id="kodepos_kurang"></span>
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') .' '. trans('cruds.dusun.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif"></label>
                </div>
            </div>
            <button class="btn btn-success float-right btn-add-dusun" data-toggle="tooltip" data-placement="top" type="submit" title="{{ trans('global.save') }}"><i class="fas fa-save"></i> {{ trans('global.save') }}</button>
        </form>
    </div>
</div>

@push('js')

@endpush