<x-adminlte-modal id="showProvinceModal" title="{{ trans('global.show') }} {{ trans('cruds.provinsi.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>{{ __('cruds.provinsi.kode') }}</th>
                    <td id="show-kode"></td>
                </tr>
                <tr>
                    <th>{{ __('cruds.provinsi.nama') }}</th>
                    <td id="show-nama"> </td>
                </tr>
                <tr>
                    <th>{{ __('cruds.status.title') }}</th>
                    <td><div class="icheck-primary d-inline"><input type="checkbox" id="show-status"><label for="show-status"></label></div></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>