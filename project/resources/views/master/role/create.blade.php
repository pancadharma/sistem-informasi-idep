<div id="add_role" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.role.title')}}
        <div class="card-tools">
            {{-- limit which user can view this button to open the form create role --}}
            @can('role_create')
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        {{-- Add Role Form --}}
        <form id="AddRole" novalidate method="POST" class="resettable-form" data-toggle="validator" autocomplete="off">
            @csrf
            @method('POST')
            {{-- Role Name --}}
            <div class="form-group">
                <label for="nama">{{ __('cruds.role.fields.nama') }}</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="{{ __('adminlte.full_name') }}" autofocus required maxlength="200" minlength="3" id="nama" autocomplete="off">
                    {{-- <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user-secret {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div> --}}
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            {{-- Permission --}}
            <div class="form-group">
                <label class="required control-label" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                <div class="input-group select2-purple">
                    <select class="form-control {{ $errors->has('permissions') ? 'is-invalid' : '' }}"
                        name="permissions[]" id="permissions" multiple="multiple" required >
                        {{-- @foreach($permissions as $id => $permission) --}}
                            {{-- <option value="{{ $id }}" {{ in_array($id, old('permissions', [])) ? 'selected' : '' }}>{{ $permission }}</option> --}}
                        {{-- @endforeach --}}
                    </select>
                    {{-- <div class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="permissions">
                            <span class="fas fa-shield-alt {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </button>
                    </div> --}}
                    @error('permissions')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            {{-- Status Role --}}
            <div class="form-group">
                <strong> {{ __('cruds.status.title')  }}  </strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ __('cruds.status.aktif')  }}</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success float-right btn-add-role">
                <span class="fas fa-save"></span>
                {{ __('global.submit') }}
            </button>
        </form>
    </div>
</div>
