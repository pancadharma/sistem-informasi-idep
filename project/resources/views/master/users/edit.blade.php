<x-adminlte-modal id="EditUsersModal" title="" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>
    <form id="EditUserForm" novalidate method="PUT" class="resettable-form" data-toggle="validator" autocomplete="off">
        @csrf
        @method('PUT')
        {{-- Name field --}}
        <div class="form-group">
            <label for="edit_nama">{{ __('cruds.user.fields.nama') }}</label>
            <div class="input-group">
                <input type="hidden" name="id" id="id_user">
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama') }}" placeholder="{{ __('adminlte.full_name') }}"
                autofocus required maxlength="200"
                minlength="3" id="edit_nama" autocomplete="off">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Username --}}
        <div class="form-group">
            <label for="edit_username">{{ __('cruds.user.fields.username') }}</label>
            <div class="input-group">
                <input type="text" id="edit_username" name="username" class="form-control @error('username') is-invalid @enderror"
                value="{{ old('username') }}" placeholder="{{ __('cruds.user.fields.username') }}" autofocus required maxlength="100" minlength="5" autocomplete="off" remote="username">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Email field --}}
        <div class="form-group">
            <label for="edit_email">{{ __('cruds.user.fields.email') }}</label>
            <div class="input-group">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="{{ __('adminlte.email') }}" required maxlength="200" id="edit_email" autocomplete="off">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Password field --}}
        <div class="form-group">
            <label for="edit_password">{{ __('cruds.user.fields.password') }}</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="{{ __('adminlte.password') }}" maxlength="100" id="edit_password" autocomplete="new-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Confirm password field --}}
        <div class="form-group">
            <label for="edit_password_confirmation">{{ __('adminlte.retype_password') }}</label>
            <div class="input-group">
                <input type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="{{ __('adminlte.retype_password') }}" maxlength="100" id="edit_password_confirmation" autocomplete="new-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Jabatan Input --}}

        <div class="form-group">
            <label for="jabatan">{{ __('cruds.user.fields.jabatan') }}</label>
            <div class="input-group">
                <input type="hidden" id="jabatan_id" name="jabatan_id">
                <input type="text" name="jabatan"
                    class="form-control @error('jabatan') is-invalid @enderror"
                    placeholder="{{ __('adminlte.retype_password') }}" required maxlength="100" id="edit_jabatan" autocomplete="off">
                <select name="jabatan" id="jabatan">
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>


        {{-- User Role --}}
        <div class="form-group">
            <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
            <div class="input-group select2-purple">
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="edit_roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user-tie {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Status user --}}
        <div class="form-group">
            <strong>{{  __('cruds.status.title')  }}</strong>
            <input type="hidden" name="aktif" value="0"> {{-- when modal edit fields aktif is unchecked --}}
            <div class="icheck-primary">
                <input type="checkbox" name="aktif" id="edit_aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                <label for="edit_aktif"><span id="status"></span></label>
            </div>
        </div>
        <button type="submit" id="UpdateUserData" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }} EDIT_USERS">
            <span class="fas fa-user-plus"></span>
            {{ __('global.update') }}
        </button>
    </form>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>
