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
        <form id="AddUserForm" novalidate method="POST" class="resettable-form" data-toggle="validator" autocomplete="off">
            @csrf
            @method('POST')
            {{-- Name field --}}
            <div class="form-group">
                <label for="nama">{{ __('cruds.user.fields.nama') }}</label>
                <div class="input-group">
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="{{ __('adminlte.full_name') }}" autofocus required maxlength="200" minlength="3" id="nama" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            {{-- Username --}}
            <div class="form-group">
                <label for="username">{{ __('cruds.user.fields.username') }}</label>
                <div class="input-group">
                    <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}" placeholder="{{ __('cruds.user.fields.username') }}" autofocus required maxlength="100" minlength="5" autocomplete="off" remote="email">
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
            </div>

            {{-- Email field --}}
            <div class="form-group">
                <label for="email">{{ __('cruds.user.fields.email') }}</label>
                <div class="input-group">
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
            </div>

            {{-- Password field --}}
            <div class="form-group">
                <label for="password">{{ __('cruds.user.fields.password') }}</label>
                <div class="input-group">
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
            </div>
            {{-- Confirm password field --}}
            <div class="form-group">
                <label for="password_confirmation">{{ __('adminlte.retype_password') }}</label>
                <div class="input-group">
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
            </div>

            {{-- User Role --}}

            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div class="input-group select2-purple">
                    <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                        @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                    {{-- <div class="input-group-append"> --}}
                        <div class="input-group-text">
                            <span class="fas fa-user-tie {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    {{-- </div> --}}
                    {{-- @if($errors->has('roles'))
                     <span class="text-danger">{{ $errors->first('roles') }}</span>
                    @endif --}}
                    @error('roles')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
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


@push('js')

@endpush
