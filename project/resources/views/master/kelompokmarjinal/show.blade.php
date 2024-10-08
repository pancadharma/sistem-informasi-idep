<x-adminlte-modal id="showkelompokmarjinalModal" title="{{ trans('global.details') }} {{ trans('cruds.kelompokmarjinal.title_singular') }}" size="lg" theme="info" icon="fas fa-folder-open" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <tbody>
                {{-- <tr>
                    <th>{{ __('cruds.kelompokmarjinal.no') }}</th>
                    <td id="show-id"></td>
                </tr> --}}
                <tr>
                    <th>{{ __('cruds.kelompokmarjinal.nama') }}</th>
                    <th>{{ __('cruds.status.title') }}</th>
                    
                </tr>
                <tr>
                    <td id="show-nama"> </td>
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