<x-adminlte-modal id="showTargetReinstraModal" title="" theme="info" icon="fas fa-folder-open" size='lg'>
    <div class="table-responsive">
        <div class="container">
            <table class="table table-bordered table-striped table-hover dtr-inline">
                <tbody>
                    <tr>
                        <th widht="40%">{{ __('cruds.reinstra.fields.nama_reinstra') }}</th>
                        <td id="view_nama"></td>
                    </tr>
                    <tr>
                        <th widht="60%">{{ __('cruds.status.title') }}</th>
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
    </div>
</x-adminlte-modal>

