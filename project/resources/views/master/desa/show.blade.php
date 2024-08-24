<x-adminlte-modal id="showDesaModal" title="{{ trans('global.details') }} {{ trans('cruds.desa.title') }}" size="lg" theme="info" icon="fas fa-folder-open" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>{{ __('cruds.desa.form.kode') }}</th>
                    <td id="show-kode"></td>
                </tr>
                <tr>
                    <th>{{ __('cruds.desa.form.nama') }}</th>
                    <td id="show-nama"> </td>
                </tr>
                <tr>
                    <th>{{ __('cruds.kecamatan.title') }}</th>
                    <td id="show-kecamatan"> </td>
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