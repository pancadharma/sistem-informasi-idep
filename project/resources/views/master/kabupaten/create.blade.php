<x-adminlte-modal id="addKabupaten" title="{{ trans('global.add') }} {{ trans('cruds.kabupaten.title_singular') }}"
    size="lg" theme="success" icon="fa fa-plus" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
        <form @submit.prevent="handleSubmit" id="kabupatenForm" action="{{ route('kabupaten.store')}}" method="POST" class="resettable-form">
          @csrf
            @method('POST')
            <div class="form-group">
              <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.kabupaten.title') }}</label>
              <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="\d{2}\.\d{2}" maxlength="4">
            </div>
            <div class="form-group">
              <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
              <input type="text" id="nama" name="nama" class="form-control" pattern="^[A-Za-z][A-Za-z0-9]{1,}$" required maxlength="200">
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kabupaten.title') }}</strong>
				<div class="icheck-primary">
					<input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
					<label for="aktif"></label>
              </div>
            </div>
            <button type="submit" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        {{-- <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>  --}}
    </x-slot>
</x-adminlte-modal>
