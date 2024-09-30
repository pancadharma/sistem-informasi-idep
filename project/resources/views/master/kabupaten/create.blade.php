<x-adminlte-modal id="addKabupaten" title="{{ __('global.add') .' '. __('cruds.kabupaten.title_singular') }}"
    size="lg" theme="success" icon="fa fa-plus" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
        <form @submit.prevent="handleSubmit" id="kabupatenForm" action="{{ route('kabupaten.store')}}" method="POST" class="resettable-form" autocomplete="off">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="provinsi_nama">{{ __('cruds.provinsi.nama') .' '. __('cruds.provinsi.title') }}</label>
                <div class="form-group">
                    <select id="provinsi_add" name="provinsi_id" class="form-control select2 provinsi-data" style="width: 100%"><option></option></select>
                </div>
            </div>
            <div class="form-group">
              <label for="kode">{{ __('cruds.form.kode') .' '. __('cruds.kabupaten.title') }}</label>
              <input placeholder="" type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required data-toggle="tooltip" data-placement="top" maxlength="5">
            </div>
            <div class="form-group">
              <label for="nama">{{ __('cruds.form.nama') .' '. __('cruds.kabupaten.title') }}</label>
              <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
            </div>
            <div class="form-group">
                <label for="type">{{ __('cruds.kabupaten.title') .' / '. __('cruds.kabupaten.kota') }}</label>
                <select id="type" name="type" class="form-control select2 type" style="width: 100%">
                    <option></option>
                    <option value="kabupaten"> {{ __('cruds.kabupaten.title') }} </option>
                    <option value="kota"> {{ __('cruds.kabupaten.kota') }} </option>
                </select>
            </div>
            <div class="form-group">
            <strong>{{ __('cruds.kabupaten.title') }}</strong>
				<div class="icheck-primary">
					<input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
					<label for="aktif">{{ __('cruds.status.aktif') }}</label>
            	</div>
            </div>
            <button type="submit" class="btn btn-success float-right btn-add-kabupaten" data-toggle="tooltip" data-placement="top" title="{{ __('global.submit') }}"><i class="fas fa-save"></i> {{ __('global.submit') }}</button>
        </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        {{-- <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>  --}}
    </x-slot>
</x-adminlte-modal>
