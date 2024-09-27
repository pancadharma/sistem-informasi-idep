<x-adminlte-modal id="editmpendonorModal" title="{{ trans('global.edit') }} {{ trans('cruds.mpendonor.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <form @submit.prevent="handleSubmit" id="editmpendonorForm" method="PATCH">
            @csrf
            @method('PATCH')
            {{-- <input type="hidden" name="id" id="id_edit"> --}}
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="mpendonorkategori_id">{{ trans('cruds.kategoripendonor.nama') }} {{ trans('cruds.kategoripendonor.title') }}</label>
                <select id="mpendonorkategori_id" name="mpendonorkategori_id" class="form-control select2 mpendonorkategori-data" style="width: 100%">
                </select>
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.mpendonor.nama') }} {{ trans('cruds.mpendonor.title') }}</label>
                <input type="text" id="editnama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.mpendonor.nama') }} {{ trans('cruds.mpendonor.title') }}</label>
                <input type="text" id="editpic" name="pic" class="form-control" v-model="form.nama" required maxlength="200">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.mpendonor.nama') }} {{ trans('cruds.mpendonor.title') }}</label>
                <input type="text" id="editemail" name="email" class="form-control" v-model="form.email" required maxlength="200">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.mpendonor.nama') }} {{ trans('cruds.mpendonor.title') }}</label>
                <input type="text" id="editphone" name="phone" class="form-control" v-model="form.phone" required maxlength="200">
            </div>
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }}</strong>
                <div class="icheck-primary">
                    {{-- <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="aktif" id="edit-aktif" value="0"> --}}
                    <input type="checkbox" id="editaktif" v-model="form.aktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="aktif" id="edit-aktif" :value="form.aktif">
                    <label for="editaktif"></label>
                </div>
            </div>
            <button type="submit" id="editmpendonor" class="btn btn-success float-right"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>

