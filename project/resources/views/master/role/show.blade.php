<x-adminlte-modal id="showRoleModal" title="" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>
    <table class="table table-bordered table-striped table-hover dtr-inline">
        <tbody>
            <tr>
                <th>{{ __('cruds.role.fields.nama') }}</th>
                <td id="view_nama"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.role.fields.permissions') }}</th>
                <td id="view_permissions"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.user.title_singular') }}</th>
                <td id="view_users"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.status.title') }}</th>
                <td>
                    <div class="icheck-primary">
                        <input type="checkbox" id="aktif_show">
                        <label for="aktif_show" id="status"></label>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</x-adminlte-modal>

