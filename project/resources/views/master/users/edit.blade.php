<div id="add_user" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.user.title')}}
        <div class="card-tools">
            
            {{--   --}}
            @can(['user_create', 'user_edit'])
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        {{-- form add user --}}
        <form id="EditUserForm" action="{{ route('users.store')}}" method="POST" class="resettable-form" data-toggle="validator" autocomplete="off">
            @csrf
            @method('POST')
            
            {{-- Name field --}}
            <label for="nama">{{ __('cruds.user.fields.nama') }}</label>
            <div class="input-group mb-3">
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" placeholder="{{ __('adminlte.full_name') }}" autofocus required maxlength="200" minlength="3" id="nama" autocomplete="off">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- Username --}}
            <label for="username">{{ __('cruds.user.fields.username') }}</label>
            <div class="input-group mb-3">
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" placeholder="{{ __('cruds.user.fields.username') }}" autofocus required maxlength="100" minlength="3" id="username" autocomplete="off">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Email field --}}
            <label for="email">{{ __('cruds.user.fields.email') }}</label>
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="{{ __('adminlte.email') }}" required maxlength="200" id="email" autocomplete="off">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password field --}}
            <label for="password">{{ __('cruds.user.fields.password') }}</label>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="{{ __('adminlte.password') }}" required maxlength="100" id="password" autocomplete="new-password">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Confirm password field --}}
            <label for="password_confirmation">{{ __('adminlte.retype_password') }}</label>
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="{{ __('adminlte.retype_password') }}" required maxlength="100" id="password_confirmation" autocomplete="new-password">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- User Role --}}
            
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <span class="text-danger">{{ $errors->first('roles') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>

            {{-- Status user --}}
            <div class="form-group">
                <strong> {{ trans('cruds.user.title') .' '. trans('cruds.status.title')  }}  </strong>
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif"></label>
                </div>
            </div>
            {{-- <button class="btn btn-success float-right btn-add-user" data-toggle="tooltip" data-placement="top" type="submit" title="{{ trans('global.create') }}"><i class="fas fa-save"></i> {{ trans('global.save') }}</button> --}}
            <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }} btn-add-user">
                <span class="fas fa-user-plus"></span>
                {{ __('global.create') }}
            </button>
        </form>
    </div>
</div>


{{-- @if(auth()->user()->can('user_create') || Auth::user()->can('user_edit'))
      Using this for permission of user ability to do in the system      
@endif --}}

@push('js')

@endpush