<x-adminlte-modal id="showKecamatanModal" title="{{ trans('global.details') }} {{ trans('cruds.kecamatan.title_singular') }}" size="lg" theme="info" icon="fas fa-folder-open" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>{{ __('cruds.kecamatan.kode') }}</th>
                    <td id="show-kode"></td>
                </tr>
                <tr>
                    <th>{{ __('cruds.kecamatan.nama') }}</th>
                    <td id="show-nama"> </td>
                </tr>
                <tr>
                    <th>{{ __('cruds.kabupaten.title') }}</th>
                    <td id="show-kabupaten"> </td>
                </tr>
                <tr>
                    <th>{{ __('cruds.status.title') }}</th>
                    <td>
                        <div class="icheck-primary">
                            <input type="checkbox" id="show-aktif">
                            <label for="show-aktif"></label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>