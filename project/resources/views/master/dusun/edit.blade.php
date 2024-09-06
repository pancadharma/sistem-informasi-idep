<x-adminlte-modal id="editDesaModal" title=" {{ trans('global.update') .' '.trans('cruds.desa.title')}}" size="lg" theme="info" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="card-body">
            <form id="update_dusun" action="#" method="PUT" class="resettable-form" data-toggle="validator" id="EditDusunForm" autocomplete="off" novalidate>
                @csrf
                @method('PATCH')
                {{-- Select Provinsi --}}
                <div class="form-group">
                    <input type="hidden" name="id" id="id_dusun">
                    <label for="provinsi">{{ trans('cruds.dusun.form.prov') }}</label>
                    <select class="form-control select2" name="provinsi" id="provinsi" required style="width: 100%">
                        @foreach($provinsi as $data => $entry)
                            <option value="{{ $data }}" {{ old('provinsi') == $data ? 'selected' : '' }}>{{ $data .' - '. $entry }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Select kabupaten --}}
                <div class="form-group">
                    <label for="kabupaten">{{ trans('cruds.dusun.form.kab') }}</label>
                    <div class="form-group">
                        <select id="kabupaten" name="kabupaten" class="form-control select2 kabupaten-data" required style="width: 100%">
                            <option value="0" selected>{{ trans('global.pleaseSelect') .' '. trans('cruds.kabupaten.title')}}</option>
                        </select>
                    </div>
                </div>
                {{-- Select Kecamatan --}}
                <div class="form-group">
                    <label for="kecamatan">{{ trans('cruds.dusun.form.kec') }}</label>
                    <div class="form-group">
                        <select id="kecamatan" name="kecamatan" class="form-control select2 kecamatan-data" required style="width: 100%">
                            <option value="0" selected>{{ trans('global.pleaseSelect') .' '. trans('cruds.kecamatan.title')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desa">{{ trans('cruds.dusun.form.des') }}</label>
                    <div class="form-group">
                        <select id="desa" name="desa_id" class="form-control select2 desa-data" required style="width: 100%">
                            <option value="0" selected>{{ trans('global.pleaseSelect') .' '. trans('cruds.desa.title')}}</option>
                        </select>
                    </div>
                </div>
                {{-- Input Kode Dusun --}}
                <div class="form-group">
                    <label for="kode_dusun">{{ trans('cruds.dusun.form.kode') }}</label>
                    <input type="text" id="kode_dusun" name="kode" required class="form-control" maxlength="16" minlength="16">
                    {{--  pattern="\d{2}\.\d{2}\.\d{2}\.\d{4}.\d{2}" --}}
                </div>
                {{-- Input Nama Dusun --}}
                <div class="form-group">
                    <label for="nama_dusun">{{ trans('cruds.dusun.form.nama') }}</label>
                    <input type="text" id="nama_dusun" name="nama" class="form-control" required maxlength="200" aria-describedby="nama_dusun-error" aria-invalid="true" pattern="^[A-Za-z][A-Za-z0-9 .]*$" title="Must start with a letter. Repeated character like space, dot or else are not allowed 😊">
                    {{-- ^[A-Za-z][A-Za-z0-9 .]*$ --}}
                </div>
                {{-- Kode Pos --}}
                <div class="form-group">
                    <label for="kode_pos">{{ trans('cruds.dusun.form.kode_pos') }}</label>
                    <input type="text" id="postcode" name="kode_pos" class="form-control" minlength="5" maxlength="5" aria-describedby="kode_pos-error" aria-invalid="true" pattern="\d*" title="Only number input allowed">
                    <span class="invalid-feedback" id="kodepos_kurang"></span>
                </div>
                {{-- Active --}}
                <div class="form-group">
                    <strong>{{ trans('cruds.status.title') .' '. trans('cruds.dusun.title') }}</strong>
                    <div class="icheck-primary">
                        <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                        <input type="hidden" name="aktif" id="edit-aktif" value="0">
                        <label for="editaktif">{{ trans('cruds.status.aktif') ?: trans('cruds.status.tidak_aktif') }}</label>
                    </div>
                </div>
            
                <button class="btn btn-info float-right btn-update-dusun" data-toggle="tooltip" data-placement="top" type="submit" title="{{ trans('global.update') }}"><i class="fas fa-save"></i> {{ trans('global.update') }}</button>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>


