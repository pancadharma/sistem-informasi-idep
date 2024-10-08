<x-adminlte-modal id="editkelompokmarjinalModal" title="{{ trans('global.edit') }} {{ trans('cruds.kelompokmarjinal.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <form @submit.prevent="handleSubmit" id="editkelompokmarjinalForm" method="PATCH">
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" id="id_edit">
            
            <div class="form-group">
                <label for="nama">{{ trans('cruds.kelompokmarjinal.nama') }} {{ trans('cruds.kelompokmarjinal.title') }}</label>
                <input type="text" id="editnama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
            </div>
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="aktif" id="edit-aktif" value="0">
                    <label for="editaktif"></label>
                </div>
            </div>
            <button type="submit" id="editkelompokmarjinal" class="btn btn-success float-right"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>

