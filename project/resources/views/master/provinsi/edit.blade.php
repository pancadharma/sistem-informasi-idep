<x-adminlte-modal id="editProvinceModal" title="{{ trans('global.edit') }} {{ trans('cruds.provinsi.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <form @submit.prevent="handleSubmit" id="editProvinceForm" action="{{ route('provinsi.update', 'ID_PLACEHOLDER')}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="text" id="editkode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="text" id="editnama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
                </div>
                <div class="form-group">
                    <label for="aktif">{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="checkbox" name="aktif" id="editaktif" checked value="1">
                </div>
                <button type="submit" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">
{{--        <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>--}}
{{--        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>--}}
    </x-slot>
</x-adminlte-modal>

