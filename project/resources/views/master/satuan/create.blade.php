<div id="add_satuan" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.satuan.title')}}
        <div class="card-tools">
            {{-- limit which user can view this button to open the form create satuan --}}
            @can('satuan_create')
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body responsive">
        {{-- Add Satuan Form --}}
        <form id="AddSatuanForm" method="POST" class="resettable-form" data-toggle="validator" autocomplete="off"  @submit.prevent="handleSubmit" novalidate>
            @csrf
            @method('POST')
            {{-- Satuan Name --}}
            <div class="form-group">
                <label for="nama">{{ __('cruds.satuan.fields.nama_satuan') }}</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="{{ __('cruds.satuan.fields.nama_satuan') }}" autofocus required maxlength="200" minlength="3" id="nama" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-ruler {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            {{-- Satuan Name --}}
            <div class="form-group">
                <strong> {{ trans('cruds.satuan.title_singular') .' '. trans('cruds.status.title')  }}  </strong>
                <input type="hidden" name="aktif" value="0"> {{-- add to add default value --}}
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif"></label>
                </div>
            </div>

            <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }} btn-add-role">
                <span class="fas fa-ruler"></span>
                {{ __('global.add') }}
                {{ __('cruds.satuan.title_singular') }}
            </button>
        </form>
    </div>
</div>
