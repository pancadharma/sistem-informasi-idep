<x-adminlte-modal id="editKabupatenModal" title="{{ trans('global.edit') }} {{ trans('cruds.kabupaten.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <form @submit.prevent="handleSubmit" id="editKabupatenForm" method="PATCH">
            @method('PATCH')
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <select id="provinsi_id" name="provinsi_id" class="form-control select2 provinsi-data" style="width: 100%"></select>
            </div>
            <div class="form-group">
                <label for="kode">{{ trans('cruds.kabupaten.kode') }} {{ trans('cruds.kabupaten.title') }}</label>
                <input type="text" id="editkode" name="kode" class="form-control" v-model="form.kode" required maxlength="5">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.kabupaten.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
                <input type="text" id="editnama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
            </div>
            <div class="form-group">
                <label for="type">{{ trans('cruds.kabupaten.title') }} / {{ trans('cruds.kabupaten.kota') }}</label>
                <select id="type_edit" name="type" class="form-control select2 type" style="width: 100%">
                    <option value="0" selected></option>
                    <option value="kabupaten"> {{ trans('cruds.kabupaten.title') }} </option>
                    <option value="kota"> {{ trans('cruds.kabupaten.kota') }} </option>
                </select>
            </div>
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }}</strong>
            <div class="icheck-primary">
                <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                <input type="hidden" name="aktif" id="edit-aktif" value="0">
                <label for="editaktif"></label>
            </div>
            </div>
            <button type="submit" id="editKabupaten" class="btn btn-success float-right"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>

