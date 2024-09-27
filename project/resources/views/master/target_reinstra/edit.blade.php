<x-adminlte-modal id="EditTargetReinstraModal" title="" theme="purple" icon="fas fa-handshake" size='lg'>
    <div class="card-body">
        {{-- Edit Role Form --}}
        <form id="EditTargetReinstraForm" method="PATCH" class="resettable-form" data-toggle="validator" autocomplete="off"  @submit.prevent="handleSubmit" novalidate>
            @csrf
            @method('PUT')
            {{-- reinstra Name --}}
            <input type="hidden" id="reinstra_id" nama="id">
            <x-adminlte-input  id="edit_nama" name="nama" label="{{ __(('cruds.reinstra.fields.nama_reinstra')) }}" rows=5
                placeholder="{{ __(('cruds.reinstra.fields.nama_reinstra')) }}" maxlength="150" minlength="3" label-class="">
                <x-slot name="appendSlot" class="input-group-append">
                    <div class="input-group-text">
                        <i class="fas fa-window-restore text-success"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            {{-- <x-adminlte-textarea id="edit_keterangan" name="keterangan" label="{{ __(('cruds.reinstra.fields.ket')) }}" rows=5
                placeholder="{{ __(('cruds.reinstra.fields.ket')) }}" maxlength="200" label-class="">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-white">
                        <i class="fas fa-lg fa-file-alt text-warning"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea> --}}

            {{-- Status Reinstra --}}
            <div class="form-group">
                <strong> {{ trans('cruds.reinstra.title_singular') .' '. trans('cruds.status.title')  }}  </strong>
                <input type="hidden" name="aktif" value="0"> {{-- add to add default value --}}
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="edit_aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="edit_aktif"></label>
                </div>
            </div>

            <button type="submit" class="btn btn-block btn-info btn-update-role" id="UpdateParterButton">
                <span class="fas fa-window-restore"></span>
                {{ __('global.update') }}
                {{ __('cruds.reinstra.title_singular') }}
            </button>
        </form>
    </div>

<x-slot name="footerSlot">
</x-slot>
</x-adminlte-modal>
