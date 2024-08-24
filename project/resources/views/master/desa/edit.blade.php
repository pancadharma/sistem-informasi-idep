<x-adminlte-modal id="editDesaModal" title=" {{ trans('global.update') .' '.trans('cruds.desa.title')}}" size="lg" theme="info" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="card-body">
            <form action="#" @submit.prevent="handleSubmit" method="PATCH" class="resettable-form" id="editDesaForm" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="hidden" name="id" id="id">
                    <label class="required" for="edit_provinsi_id">{{ trans('cruds.provinsi.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                    <select class="form-control select2" name="provinsi_id" id="edit_provinsi_id" required style="width: 100%">
                        @foreach($provinsi as $data => $entry)
                            <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data }} - {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_kabupaten_id">{{ trans('cruds.desa.form.kab') }}</label>
                    <div class="form-group">
                        <select id="edit_kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" style="width: 100%" required>
                            <option>{{ trans('global.pleaseSelect') .' '. trans('cruds.provinsi.title')}}</option>
                        </select>
                        <span id="edit_kabupaten_id_error" class="invalid-feedback">{{ trans('cruds.desa.validation.kab') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_kecamatan_id">{{ trans('cruds.desa.form.kec') }}</label>
                    <div class="form-group">
                        <select id="edit_kecamatan_id" name="kecamatan_id" class="form-control select2 kecamatan-data" style="width: 100%" required>
                            <option>{{ trans('global.pleaseSelect') .' '. trans('cruds.desa.form.kec')}}</option>
                        </select>
                        @error('kecamatan_id')
                            <span id="edit_kecamatan_id" class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="editkode">{{ trans('cruds.desa.form.kode') }}</label>
                    <input placeholder="Please enter in the format xx.xx.xx.xxxx" type="text" id="editkode" name="kode" class="form-control" required title="Update {{ trans('cruds.desa.form.kode') .' '. trans('cruds.desa.title') }}" maxlength="13" minlength="13" pattern="^\d{2}\.\d{2}\.\d{2}\.\d{4}$">
                    @error('kode')
                        <span id="editkode" class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="editnama">{{ trans('cruds.desa.form.nama') }}</label>
                    <input type="text" id="editnama" name="nama" class="form-control" required minlength="3" maxlength="200">
                    @error('nama')
                        <span id="editnama" class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <strong>{{ trans('cruds.status.title') .' '. trans('cruds.desa.title') }}</strong>
                    <div class="icheck-primary">
                        <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                        <input type="hidden" name="aktif" id="edit-aktif" value="0">
                        <label for="editaktif">{{ trans('cruds.status.aktif') ?: trans('cruds.status.tidak_aktif') }}</label>
                    </div>
                </div>
                <button type="submit" id="editDesa" class="btn btn-success float-right btnUpdateDesa" ><i class="fas fa-update"></i> {{ trans('global.update') }}</button>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>
