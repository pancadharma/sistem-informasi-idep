<x-adminlte-modal id="EditRoleModal" title="" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>
    <div class="container">
        <form id="EditRoleForm" novalidate method="PUT" class="resettable-form" data-toggle="validator" autocomplete="off">
            @csrf
            @method('PUT')
            {{-- Name field --}}
            <div class="form-group col-12">
                <label for="edit_nama">{{ __('cruds.role.fields.nama') }}</label>
                <div class="input-group">
                    <input type="hidden" name="id" id="id_role">
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="{{ __('adminlte.full_name') }}"
                    autofocus required maxlength="200"
                    minlength="3" id="edit_nama" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user-secret  {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Permission in Role --}}
            <div class="form-group col-12">
                <label class="required control-label" for="edit_permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                <div class="input-group select2-purple">
                    <select class="form-control select2-edit {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="edit_permissions" multiple required>
                    </select>
                </div>
            </div>
            <div class="form-group col-12">
                <strong> {{ trans('cruds.role.title') .' '. trans('cruds.status.title')  }}  </strong>
                <div class="icheck-primary">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" name="aktif" id="edit_aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                    <label for="edit_aktif"><span id="status"></span></label>
                </div>
            </div>
            <div class="form-group col-12">
                <button type="submit" id="UpdateRoleData" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-user-secret"></span>
                    {{ __('global.update') }}
                </button>
            </div>
        </form>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>


{{-- <div class="form-group col-12">
    <x-adminlte-select class="form-control select2-edit" id="permissions" name="permissions[]" label="{{ __('cruds.role.fields.permissions') }}" required multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-shield-alt"></i>
            </div>
        </x-slot>
        @foreach($permissions as $id => $permission)
            <option value="{{ $id }}" {{ in_array($id, old('permission', [])) ? 'selected' : '' }}>{{ $permission }}</option>
        @endforeach
    </x-adminlte-select>
</div> --}}
{{-- Status Role --}}
