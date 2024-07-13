<x-adminlte-modal id="editProvinceModal" title="{{ trans('global.edit') }} {{ trans('cruds.provinsi.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        {{-- <div class="modal-body"> --}}
            <form @submit.prevent="handleSubmit" id="editProvinceForm" method="PATCH">
                @method('PATCH')
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="text" id="editkode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="15">
                </div>
                <div class="form-group">
                    <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="text" id="editnama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
                </div>
                <div class="form-group">
                    <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="aktif" id="edit-aktif" value="0">
                    <label for="editaktif"></label>
                </div>
                </div>
                <button type="submit" id="editProvinsi" class="btn btn-success float-right"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
            </form>
        {{-- </div> --}}
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>

