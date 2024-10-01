<x-adminlte-modal id="addkelompokmarjinal" title="{{ trans('global.add') }} {{ trans('cruds.kelompokmarjinal.title_singular') }}"
    size="lg" theme="success" icon="fa fa-plus" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
        <form @submit.prevent="handleSubmit" id="kelompokmarjinalForm" action="{{ route('kelompokmarjinal.store')}}" method="POST" class="resettable-form" autocomplete="off">
            @csrf
            @method('POST')
            
            {{-- nama jenis jabatan --}}
            <div class="form-group"> 
              <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.kelompokmarjinal.title') }}</label>
              <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
            </div>
            
            <button type="submit" class="btn btn-success float-right btn-add-kelompokmarjinal" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        {{-- <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>  --}}
    </x-slot>
</x-adminlte-modal>
