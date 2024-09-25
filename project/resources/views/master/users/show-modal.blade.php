<x-adminlte-modal id="showUsersModal" title="" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>
    <table class="table table-bordered table-striped table-hover dtr-inline">
        <tbody>
            <tr>
                <th>{{ __('cruds.user.fields.name') }}</th>
                <td id="view_nama"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.user.fields.username') }}</th>
                <td id="view_username"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.user.fields.email') }}</th>
                <td id="view_email"></td>
            </tr>
            <tr>
            <tr>
                <th>{{ __('cruds.user.fields.jabatan') }}</th>
                <td id="view_jabatan"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.user.fields.role') }}</th>
                <td id="view_roles"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.status.title') }}</th>
                <td>
                    <div class="icheck-primary">
                        <input type="checkbox" id="aktif_show">
                        <label for="aktif_show" id="status"></label>
                    </div>

                    {{-- <div class="icheck-primary"> --}}
                       {{--  <input type="checkbox" name="aktif" id="aktif" {{ $user->aktif == 1 ? 'checked' : '' }} value="{{ $user->aktif}}">
                        <label for="show-aktif">{{ $user->aktif == 1 ? 'Active' : 'Not Active' }}</label> --}}
                    {{-- </div> --}}
                </td>
            </tr>
        </tbody>
    </table>

</x-adminlte-modal>
{{-- Example button to open modal --}}

