<x-adminlte-modal id="showmpendonorModal" title="{{ trans('global.details') }} {{ trans('cruds.mpendonor.title_singular') }}" size="lg" theme="info" icon="fas fa-folder-open" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <tbody>
                {{-- <tr>
                    <th>{{ __('cruds.kelompokmarjinal.no') }}</th>
                    <td id="show-id"></td>
                </tr> --}}
                <tr>
                    <th>{{ __('cruds.kategoripendonor.title') }}</th>
                    <th>{{ __('cruds.mpendonor.title') }}</th>
                    <th>{{ __('cruds.mpendonor.pic') }}</th>
                    <th>{{ __('cruds.mpendonor.email') }}</th>
                    <th>{{ __('cruds.mpendonor.phone') }}</th>
                    <th>{{ __('cruds.status.title') }}</th>
                    
                </tr>
                <tr>
                    <td id="show-mpendonnorkategori"> </td>
                    <td id="show-nama"> </td>
                    <td id="show-pic"> </td>
                    <td id="show-email"> </td>
                    <td id="show-phone"> </td>
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