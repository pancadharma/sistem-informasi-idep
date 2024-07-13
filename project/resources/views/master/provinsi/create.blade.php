<x-adminlte-modal id="addProvinsi" title="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}"
    size="lg" theme="success" icon="fa fa-plus" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
        <form @submit.prevent="handleSubmit" id="provinsiForm" action="{{ route('provinsi.store')}}" method="POST" class="resettable-form">
          @csrf
            @method('POST')
            <div class="form-group">
              <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="2">
            </div>
            <div class="form-group">
              <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="text" id="nama" name="nama" class="form-control" pattern="^[A-Za-z][A-Za-z0-9]{1,}$" required maxlength="200">
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</strong>
				<div class="icheck-primary">
					<input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
					<label for="aktif"></label>
              </div>
              {{-- <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', 0) == 1 || old('aktif') === null ? 'checked' : '' }} value="1"> --}}
              {{-- <input type="checkbox" name="aktif" id="aktif" value="1" > --}}
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
