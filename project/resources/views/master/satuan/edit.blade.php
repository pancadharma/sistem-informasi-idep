<x-adminlte-modal id="EditSatuanModal" title="" theme="info" icon="fas fa-handshake" size='lg'>
    <div class="card-body">
        {{-- Edit Role Form --}}
        <form id="EditSatuanForm" method="PATCH" class="resettable-form" data-toggle="validator" autocomplete="off"  @submit.prevent="handleSubmit" novalidate>
            @csrf
            @method('PUT')
            {{-- satuan Name --}}
            <input type="hidden" id="satuan_id" nama="id">
            <x-adminlte-input  id="edit_nama" name="nama" label="{{ __(('cruds.satuan.fields.nama_satuan')) }}" rows=5
                placeholder="{{ __(('cruds.satuan.fields.nama_satuan')) }}" maxlength="150" minlength="3" label-class="">
                <x-slot name="appendSlot" class="input-group-append">
                    <div class="input-group-text">
                        <i class="fas fa-ruler text-success"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            {{-- Status satuan --}}
            <div class="form-group">
                <strong> {{ trans('cruds.satuan.title_singular') .' '. trans('cruds.status.title')  }}  </strong>
                <input type="hidden" name="aktif" value="0"> {{-- add to add default value --}}
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="edit_aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="edit_aktif"></label>
                </div>
            </div>

            <button type="submit" class="btn btn-block btn-info btn-update-role" id="UpdateSatuanButton">
                <span class="fas fa-ruler"></span>
                {{ __('global.update') }}
                {{ __('cruds.satuan.title_singular') }}
            </button>
        </form>
    </div>

<x-slot name="footerSlot">
</x-slot>
</x-adminlte-modal>
