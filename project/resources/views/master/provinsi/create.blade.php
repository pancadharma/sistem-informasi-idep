<x-adminlte-modal id="addProvinsi" title="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}" 
    size="lg" theme="success" icon="fa fa-plus" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
        <form @submit.prevent="handleSubmit" id="provinsiForm" action="{{ route('provinsi.store')}}" method="POST">
            @method('POST')
            @csrf
            <div class="form-group">
              <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="10">
            </div>
            <div class="form-group">
              <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="text" id="nama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
            </div>
            <div class="form-group">
              <label for="aktif">{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="checkbox" name="aktif" id="aktif" checked value="1">
            </div>
            <button type="submit" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        {{-- <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/> --}}
    </x-slot>
</x-adminlte-modal>