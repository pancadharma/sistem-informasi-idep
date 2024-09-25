<x-adminlte-modal id="showPartnerModal" title="" theme="info" icon="fas fa-folder-open" size='lg'>
    <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover dtr-inline">
        <tbody>
            <tr>
                <th>{{ __('cruds.partner.fields.nama_partner') }}</th>
                <td id="view_nama"></td>
            </tr>
            <tr>
                <th>{{ __('cruds.partner.fields.ket') }}</th>
                <td id="view_ket"></td>
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
</div>
</x-adminlte-modal>

