<div id="add_reinstra" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.reinstra.title')}}
        <div class="card-tools">
            {{-- limit which user can view this button to open the form create reinstra --}}
            @can('target_reinstra_create')
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body responsive">
        {{-- Add Target Reinstra Form --}}
        <form id="AddTargetReinstraForm" method="POST" class="resettable-form" data-toggle="validator" autocomplete="off"  @submit.prevent="handleSubmit" novalidate>
            @csrf
            @method('POST')
            {{-- Target Reinstra Name --}}
            <div class="form-group">
                <label for="nama">{{ __('cruds.reinstra.fields.nama_reinstra') }}</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="{{ __('cruds.reinstra.fields.nama_reinstra') }}" autofocus required maxlength="200" minlength="3" id="nama" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-window-restore {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            {{-- Target Reinstra Name --}}
            <div class="form-group">
                <strong> {{ trans('cruds.reinstra.title_singular') .' '. trans('cruds.status.title')  }}  </strong>
                <input type="hidden" name="aktif" value="0"> {{-- add to add default value --}}
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif"></label>
                </div>
            </div>

            <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }} btn-add-role">
                <span class="fas fa-window-restore"></span>
                {{ __('global.add') }}
                {{ __('cruds.reinstra.title_singular') }}
            </button>
        </form>
    </div>
</div>
