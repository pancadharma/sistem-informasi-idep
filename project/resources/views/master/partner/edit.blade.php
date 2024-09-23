<x-adminlte-modal id="EditPartnerModal" title="" theme="purple" icon="fas fa-handshake" size='lg'>
    <div class="card-body">
        {{-- Edit Role Form --}}
        <form id="EditPartnerForm" method="PATCH" class="resettable-form" data-toggle="validator" autocomplete="off"  @submit.prevent="handleSubmit" novalidate>
            @csrf
            @method('PUT')
            {{-- Partner Name --}}
            <input type="hidden" id="partner_id" nama="id">
            <x-adminlte-input  id="edit_nama" name="nama" label="{{ __(('cruds.partner.fields.nama_partner')) }}" rows=5
                placeholder="{{ __(('cruds.partner.fields.nama_partner')) }}" maxlength="150" minlength="3" label-class="">
                <x-slot name="appendSlot" class="input-group-append">
                    <div class="input-group-text">
                        <i class="fas fa-handshake text-success"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            {{-- Keterangan Partner --}}
            <x-adminlte-textarea id="edit_keterangan" name="keterangan" label="{{ __(('cruds.partner.fields.ket')) }}" rows=5
                placeholder="{{ __(('cruds.partner.fields.ket')) }}" maxlength="200" label-class="">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-white">
                        <i class="fas fa-lg fa-file-alt text-warning"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea>

            {{-- Status Partner --}}
            <div class="form-group">
                <strong> {{ trans('cruds.partner.title') .' '. trans('cruds.status.title')  }}  </strong>
                <div class="icheck-primary">
                    <input type="hidden" name="aktif" value="0"> {{-- add to add default value --}}
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif"></label>
                </div>
            </div>

            <button type="submit" class="btn btn-block btn-info btn-update-role" id="UpdateParterButton">
                <span class="fas fa-handshake"></span>
                {{ __('global.update') }}
                {{ __('cruds.partner.title_singular') }}
            </button>
        </form>
    </div>

<x-slot name="footerSlot">
</x-slot>
</x-adminlte-modal>
