<div id="add_partner" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.partner.title')}}
        <div class="card-tools">
            {{-- limit which user can view this button to open the form create partner --}}
            @can('partner_create')
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        {{-- Add Role Form --}}
        <form id="AddPartnerForm" method="POST" class="resettable-form" data-toggle="validator" autocomplete="off"  @submit.prevent="handleSubmit" novalidate>
            @csrf
            @method('POST')
            {{-- Partner Name --}}
            <div class="form-group">
                <label for="nama">{{ __('cruds.partner.fields.nama_partner') }}</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="{{ __('cruds.partner.fields.nama_partner') }}" autofocus required maxlength="200" minlength="3" id="nama" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-handshake {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <x-adminlte-textarea id="keterangan" name="keterangan" label="{{ __(('cruds.partner.fields.ket')) }}" rows=5
                placeholder="{{ __(('cruds.partner.fields.ket')) }}" maxlength="200" label-class="">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-white">
                        <i class="fas fa-lg fa-file-alt text-warning"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea>

            {{-- Status Partner --}}
            <div class="form-group">
                <strong> {{ __('cruds.status.title')  }}  </strong>
                <input type="hidden" name="aktif" value="0"> {{-- add to add default value --}}
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                </div>
                
            </div>

            <button type="submit" class="btn float-right {{ config('adminlte.classes_auth_btn', 'btn-primary') }} btn-add-role">
                <span class="fas fa-user-secret"></span>
                {{ __('global.add') }}
                {{ __('cruds.partner.title_singular') }}
            </button> --}}
            <button type="submit" class="btn btn-success float-right btn-add-role" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
                
        </form>
    </div>
</div>
